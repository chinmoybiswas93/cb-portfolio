<?php
/**
 * Plugin Name: CB Portfolio
 * Plugin URI: https://github.com/chinmoybiswas93/cb-portfolio
 * Description: A modern portfolio plugin with Vue.js frontend and admin dashboard for showcasing experience and projects.
 * Version: 1.0.0
 * Author: Chinmoy Biswas
 * Author URI: https://github.com/chinmoybiswas93
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cb-portfolio
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

if (! defined('ABSPATH')) {
    exit;
}

define('CB_PORTFOLIO_VERSION', '1.0.0');
define('CB_PORTFOLIO_PLUGIN_FILE', __FILE__);
define('CB_PORTFOLIO_PLUGIN_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
define('CB_PORTFOLIO_PLUGIN_URL', untrailingslashit(plugin_dir_url(__FILE__)));
define('CB_PORTFOLIO_VITE_DEV_URL', 'http://localhost:5173');


if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

\ChinmoyBiswas\CBPortfolio\Bootstrap::init();
