<?php

namespace ChinmoyBiswasPortfolio\Hooks\Handlers;

if (! defined('ABSPATH')) {
    exit;
}
class AdminMenuHandler
{
    public function register()
    {
        add_action('admin_menu', [$this, 'registerMenu']);
    }

    public function registerMenu()
    {
        add_menu_page(
            'Chinmoy Biswas Portfolio',
            'CB Portfolio',
            'manage_options',
            'cb-custom-portfolio',
            [$this, 'renderAdminPage'],
            'dashicons-format-gallery',
            6
        );

        add_submenu_page(
            'cb-custom-portfolio',
            'Portfolio Settings',
            'Settings',
            'manage_options',
            'cb-portfolio-settings',
            [$this, 'renderSettingsPage']
        );
    }

    public function renderAdminPage()
    {
        echo '<div id="cb-portfolio-admin"></div>';
    }
    public function renderSettingsPage()
    {
        echo '<div id="cb-portfolio-admin"></div>';
    }
}
