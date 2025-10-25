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
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
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
            [$this, 'renderAdminPage']
        );
    }

    public function renderAdminPage()
    {
        ?>
            <div id="cb-portfolio-admin-page">
            </div>
        <?php
    }

    public function enqueueAssets($hook)
    {

        if ('toplevel_page_cb-custom-portfolio' === $hook) {
            $plugin_url = CB_PORTFOLIO_PLUGIN_URL;

            // Check if we're in dev mode
            if (file_exists(CB_PORTFOLIO_PLUGIN_PATH . '/.hot')) {
                add_filter('script_loader_tag', function ($tag, $handle) {
                    if (in_array($handle, ['vite-client', 'cb-portfolio-admin'])) {
                        // Add type="module" and allow inline/unsafe for localhost dev
                        return str_replace(
                            '<script ',
                            '<script type="module" crossorigin ',
                            $tag
                        );
                    }
                    return $tag;
                }, 10, 2);

                // Add Vite client FIRST (use http for localhost)
                wp_enqueue_script(
                    'vite-client',
                    'http://localhost:5173/@vite/client',
                    [],
                    null,
                    true
                );

                // Then add the app script
                wp_enqueue_script(
                    'cb-portfolio-admin',
                    'http://localhost:5173/admin/app.js',
                    ['vite-client'],
                    CB_PORTFOLIO_VERSION,
                    true
                );
            } else {
                // Production mode - use built assets
                wp_enqueue_script(
                    'cb-portfolio-admin',
                    $plugin_url . '/assets/admin/app.js',
                    [],
                    CB_PORTFOLIO_VERSION,
                    true
                );

                wp_enqueue_style(
                    'cb-portfolio-admin',
                    $plugin_url . '/assets/admin/style.css',
                    [],
                    CB_PORTFOLIO_VERSION
                );
            }
        }
    }
}
