<?php

	class Custom_Help_Activator {

		private $short_term;
		private $version;

		public function __construct($short_term, $version) {
			$this->short_term = $short_term;
			$this->version = $version;
		}

		public function activate() {
			$this->create_table_db();
			$this->create_options();
		}

		public function deactivate() {}

		private function create_options() {
			update_option($this->short_term . '_version', $this->version);
		}

		private function create_table_db() {
			global $wpdb;
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$charset_collate = $wpdb->get_charset_collate();
			$table_name = $wpdb->prefix . $this->short_term;
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				date_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				author_id mediumint(9) DEFAULT NULL,
				editor_id mediumint(9) DEFAULT NULL,
				filename varchar(50) NOT NULL,
				page varchar(50) DEFAULT NULL,
				post_type varchar(50) DEFAULT NULL,
				is_markdown tinyint(1) NOT NULL DEFAULT 0,
				content text DEFAULT NULL,
				UNIQUE KEY id (id)
			) {$charset_collate}";
			dbDelta($sql);
		}

	}
