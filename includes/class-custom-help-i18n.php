<?php

	class Custom_Help_i18n {

		public function load_plugin_textdomain($slug) {
			load_plugin_textdomain(
				$slug,
				false,
				dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
			);
		}

	}
