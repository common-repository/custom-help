<?php

	class Custom_Help_Admin {

		private $name;
		private $slug;
		private $short_term;
		private $version;

		public function __construct($name, $slug, $short_term, $version) {
			$this->name = $name;
			$this->slug = $slug;
			$this->short_term = $short_term;
			$this->version = $version;
		}

		public function enqueue_scripts() {
			wp_register_script($this->name, plugin_dir_url(__FILE__) . '../assets/scripts/custom-help-admin.min.js', array(), $this->version, false);
			wp_localize_script($this->name, $this->short_term, array(
				'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
				'nonce' => wp_create_nonce('wp_rest')
			));
			wp_enqueue_script($this->name);
		}

		public function enqueue_styles() {
			wp_enqueue_style($this->name, plugin_dir_url(__FILE__) . '../assets/css/custom-help-admin.min.css', array(), $this->version, 'all');
		}

		public function add_help_tab($old_help, $screen_id, $screen) {
			$page = $this->get_page_data();
			$documentation = $this->get_documentation();
			if ($documentation) {
				$id = $documentation['id'];
				$is_markdown = $documentation['is_markdown'];
				$content = $documentation['content'];
				$content_parsed = $this->format_content($documentation['content'], $is_markdown);
				$date_updated = $documentation['date_updated'];
				$editor = get_user_by('id', $documentation['editor'])
					? get_user_by('id', $documentation['editor'])->display_name : '';
			}
			ob_start();
			$template_vars = array(
				'PLUGIN_NAME' => $this->name,
				'PLUGIN_LOGO' => plugin_dir_url(__FILE__) . '../assets/images/custom-help.png',
				'ID' => isset($id) ? $id : '',
				'FILENAME' => $page['filename'],
				'PAGE' => $page['page'] ? $page['page'] : '',
				'POST_TYPE' => $page['post_type'] ? $page['post_type'] : '',
				'IS_MARKDOWN' => isset($is_markdown) ? $is_markdown : true,
				'CONTENT' => isset($content) ? $content : '',
				'CONTENT_PARSED' => isset($content_parsed) ? $content_parsed : '',
				'LAST_EDITION' => sprintf(
					__('Last edition by <span>%s</span> at <span>%s</span>.', $this->slug),
					isset($editor) ? $editor : '',
					isset($date_updated) ? $date_updated : '',
				),
				'HIDE_LAST_EDITION' => !isset($id) || !$id ? 'true' : 'false',
				'DOCUMENTATION_NOT_FOUND' => __('No documentation found related to this page.', $this->slug),
				'DOCUMENTATION_EMPTY' => __('Documentation found is empty.', $this->slug),
				'EDIT_DOCUMENTATION' => __('Edit documentation', $this->slug),
				'CREATE_DOCUMENTATION' => __('Create documentation', $this->slug),
				'SHOW_DOCUMENTATION' => __('Show documentation', $this->slug),
				'SUBMIT_FORM' => __('Send documentation', $this->slug),
				'USE_MARKDOWN' => __('Use markdown', $this->slug),
				'DONT_USE_MARKDOWN' => __('Don\'t use markdown', $this->slug)
			);
			require_once plugin_dir_path(dirname(__FILE__)) . 'templates/help_tab_content.php';
			$output = ob_get_clean();
			ob_end_flush();
			$screen->add_help_tab(array(
				'id' => $this->slug,
				'title'=> $this->name,
				'content'=> $output
			));
    	return $old_help;
		}

		private function get_page_data() {
			$filename = basename($_SERVER['SCRIPT_FILENAME']);
			$page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : null;
			$post_type = get_post_type(get_the_ID());
			return array(
				'filename' => $filename ? $filename : null,
				'page' => $page ? $page : null,
				'post_type' => $post_type ? $post_type : null
			);
		}

		private function get_documentation() {
			global $wpdb;
			$page = $this->get_page_data();
			$table_name = $wpdb->prefix . $this->short_term;
			$page_fragment = $page['page']
				? "page = '{$page['page']}'"
				: "(page = '' OR page IS NULL)";
			$post_type_fragment = $page['post_type']
				? "post_type = '{$page['post_type']}'"
				: "(post_type = '' OR post_type IS NULL)";
			$db_results = $wpdb->get_results("SELECT * FROM {$table_name} 
				WHERE filename = '{$page['filename']}'
				AND {$page_fragment} AND {$post_type_fragment}
				ORDER BY date_updated DESC LIMIT 1");
			return $db_results ? array(
				'id' => $db_results[0]->id,
				'is_markdown' => $db_results[0]->is_markdown,
				'content' => $db_results[0]->content,
				'date_created' => $db_results[0]->date_created,
				'date_updated' => $db_results[0]->date_updated,
				'author' => $db_results[0]->author_id,
				'editor' => $db_results[0]->editor_id
			) : false;
		}

		private function format_content($content, $is_markdown = false) {
			$markdown = new Custom_Help_Markdown();
			return $is_markdown
				? $markdown->text($content)
				: str_replace("\n", '<br>', $content);
		}

		public function save_documentation() {
			global $wpdb;
			if (!is_admin()) {
				wp_send_json_error();
			}
			$id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : null;
			$is_markdown = isset($_POST['is_markdown']) && $_POST['is_markdown'] === 'true';
			$content = isset($_POST['content']) ? stripslashes($_POST['content']) : '';
			$params = array(
				'is_markdown' => $is_markdown,
				'content' => $content,
				'date_updated' => current_time('mysql'),
				'editor_id' => get_current_user_id()
			);
			$table_name = $wpdb->prefix . $this->short_term;
			if ($id) {
				$query_result = $wpdb->update($table_name, $params, array(
					'id' => $id
				));
			} else {
				$params['date_created'] = current_time('mysql');
				$params['author_id'] = get_current_user_id();
				$params['filename'] = isset($_POST['filename']) ? sanitize_text_field($_POST['filename']) : null;
				$params['page'] = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : null;
				$params['post_type'] = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : null;
				$query_result = $wpdb->insert($table_name, $params);
			}
			if ($query_result) {
				wp_send_json_success(array(
					'content' => $this->format_content($content, $is_markdown),
					'id' => $id ? $id : $wpdb->insert_id,
					'date_updated' => current_time('mysql'),
					'editor' => wp_get_current_user()->display_name
				));
			} else {
				wp_send_json_error();
			}
		}

	}