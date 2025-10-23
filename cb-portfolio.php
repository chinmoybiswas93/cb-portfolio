<?php
/*
Plugin Name: Chinmoy Biswas - Portfolio
Description: A portfolio plugin for showcasing work.
Version: 0.1.0
Author: Chinmoy Biswas
License: GPLv2 or later
Text Domain: cb-portfolio
Requires PHP: 7.4
*/

if (! defined('ABSPATH')) {
    exit;
}

define('CB_PORTFOLIO_VERSION', '0.1.0');
define('CB_PORTFOLIO_PLUGIN_FILE', __FILE__);
define('CB_PORTFOLIO_PLUGIN_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
define('CB_PORTFOLIO_PLUGIN_URL', untrailingslashit(plugin_dir_url(__FILE__)));


if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

\ChinmoyBiswasPortfolio\Bootstrap::init();
