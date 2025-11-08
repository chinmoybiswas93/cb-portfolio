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
    }

    public function renderAdminPage()
    {
        ?>
            <div id="cb-portfolio-admin">
                <p>Loading...</p>
            </div>
        <?php
    }

    public function enqueueAssets($hook)
    {
        if (!in_array($hook, ['toplevel_page_cb-custom-portfolio', 'cb-portfolio_page_cb-portfolio-settings'])) {
            return;
        }
        
        wp_enqueue_media();
        
        add_filter('script_loader_tag', function ($tag, $handle) {
            if (strpos($handle, 'cb-portfolio-') === 0 || $handle === 'vite-client') {
                return str_replace('<script ', '<script type="module" crossorigin ', $tag);
            }
            return $tag;
        }, 10, 2);
        
        if (file_exists(CB_PORTFOLIO_PLUGIN_PATH . '/.hot')) {
            $this->enqueueDevScripts();
        } else {
            $this->enqueueProductionScripts();
        }
        
        wp_localize_script('cb-portfolio-admin', 'cbPortfolioData', [
            'nonce' => wp_create_nonce('wp_rest'),
            'restUrl' => rest_url('cb-portfolio/v1/'),
        ]);
    }

    private function enqueueDevScripts()
    {
        wp_enqueue_script(
            'vite-client',
            CB_PORTFOLIO_VITE_DEV_URL . '/@vite/client',
            [],
            null,
            true
        );

        wp_enqueue_script(
            'cb-portfolio-admin',
            CB_PORTFOLIO_VITE_DEV_URL . '/admin/app.js',
            ['vite-client'],
            CB_PORTFOLIO_VERSION,
            true
        );
    }

    private function enqueueProductionScripts()
    {
        $manifest_path = CB_PORTFOLIO_PLUGIN_PATH . '/assets/.vite/manifest.json';
        
        if (!file_exists($manifest_path)) {
            return;
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);
        
        if (!isset($manifest['admin/app.js'])) {
            return;
        }

        $entry = $manifest['admin/app.js'];
        $plugin_url = CB_PORTFOLIO_PLUGIN_URL;
        $dependencies = [];
        
        // Load shared chunks first
        if (isset($entry['imports'])) {
            foreach ($entry['imports'] as $import) {
                if (isset($manifest[$import])) {
                    $chunk_handle = 'cb-portfolio-' . $manifest[$import]['name'];
                    $dependencies[] = $chunk_handle;
                    
                    wp_enqueue_script(
                        $chunk_handle,
                        $plugin_url . '/assets/' . $manifest[$import]['file'],
                        [],
                        CB_PORTFOLIO_VERSION,
                        true
                    );
                }
            }
        }
        
        // Load CSS
        if (isset($entry['css'])) {
            foreach ($entry['css'] as $css) {
                wp_enqueue_style(
                    'cb-portfolio-admin-style',
                    $plugin_url . '/assets/' . $css,
                    [],
                    CB_PORTFOLIO_VERSION
                );
            }
        }
        
        // Load main script with dependencies
        wp_enqueue_script(
            'cb-portfolio-admin',
            $plugin_url . '/assets/' . $entry['file'],
            $dependencies,
            CB_PORTFOLIO_VERSION,
            true
        );
    }
}
