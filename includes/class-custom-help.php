<?php

	class Custom_Help {

		private $loader;
		private $name;
		private $slug;
		private $short_term;
		private $version;

		public function __construct($name, $slug, $short_term, $version) {
			$this->name = $name;
			$this->slug = $slug;
			$this->short_term = $short_term;
			$this->version = $version;
			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
		}

		private function load_dependencies() {
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-custom-help-loader.php';
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-custom-help-i18n.php';
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-custom-help-admin.php';
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-custom-help-markdown.php';
			$this->loader = new Custom_Help_Loader();
		}

		private function set_locale() {
			$plugin_i18n = new Custom_Help_i18n($this->slug);
			$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
		}

		private function define_admin_hooks() {
			$plugin_admin = new Custom_Help_Admin($this->name, $this->slug, $this->short_term, $this->version);
			$this->loader->add_action('wp_ajax_' . $this->short_term . '_save', $plugin_admin, 'save_documentation');
			$this->loader->add_action('wp_ajax_nopriv_' . $this->short_term . '_save', $plugin_admin, 'save_documentation');
			$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
			$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
			$this->loader->add_filter('contextual_help', $plugin_admin, 'add_help_tab', 5, 3);
		}

		public function run() {
			$this->loader->run();
		}

	}
