<?php
/*
 * Plugin Name:       CB Portfolio
 * Description:       A portfolio plugin for showcasing work.
 * Version:           0.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            Chinmoy Biswas
 * Author URI:        https://chinmoybiswas.me
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       cb-portfolio
 * Domain Path:       /languages
 */

if (! defined('ABSPATH')) {
    exit;
}

define('CB_PORTFOLIO_VERSION', '0.1.0');
define('CB_PORTFOLIO_PLUGIN_FILE', __FILE__);
define('CB_PORTFOLIO_PLUGIN_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
define('CB_PORTFOLIO_PLUGIN_URL', untrailingslashit(plugin_dir_url(__FILE__)));
define('CB_PORTFOLIO_VITE_DEV_URL', 'http://localhost:5173');


if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

\ChinmoyBiswas\CBPortfolio\Bootstrap::init();
