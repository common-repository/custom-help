<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Custom Help
 * Description:       Plugin to add custom documentation to all admin pages.
 * Version:           1.0.1
 * Author:            Codents
 * Author URI:        https://codents.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-help
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
	die;
}

define('CUSTOM_HELP_NAME', 'Custom Help');
define('CUSTOM_HELP_SLUG', 'custom-help');
define('CUSTOM_HELP_SHORT_TERM', 'customhelp');
define('CUSTOM_HELP_VERSION', '1.0.1');

function activate_custom_help() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-custom-help-activator.php';
	$activator = new Custom_Help_Activator(CUSTOM_HELP_SHORT_TERM, CUSTOM_HELP_VERSION);
	$activator->activate();
}

register_activation_hook(__FILE__, 'activate_custom_help');

function deactivate_custom_help() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-custom-help-activator.php';
	$activator = new Custom_Help_Activator(CUSTOM_HELP_SHORT_TERM, CUSTOM_HELP_VERSION);
	$activator->deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_custom_help');

function run_custom_help() {
	require plugin_dir_path(__FILE__) . 'includes/class-custom-help.php';
	$plugin = new Custom_Help(CUSTOM_HELP_NAME, CUSTOM_HELP_SLUG, CUSTOM_HELP_SHORT_TERM, CUSTOM_HELP_VERSION);
	$plugin->run();
}

run_custom_help();
