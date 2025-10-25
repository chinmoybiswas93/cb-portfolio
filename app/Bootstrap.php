<?php

namespace ChinmoyBiswasPortfolio;

use ChinmoyBiswasPortfolio\Hooks\Handlers\ActivationHandler;
use ChinmoyBiswasPortfolio\Hooks\Handlers\DeactivationHandler;
use ChinmoyBiswasPortfolio\Hooks\Handlers\AdminMenuHandler;
use ChinmoyBiswasPortfolio\Http\Controllers\PortfolioSettingsController;

if (! defined('ABSPATH')) {
    exit;
}

class Bootstrap
{
    private static ?self $instance = null;

    public static function init(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->registerHooks();
    }

    private function registerHooks(): void
    {
        // Activation
        register_activation_hook(CB_PORTFOLIO_PLUGIN_FILE, function () {
            (new ActivationHandler())->handle();
        });

        //Deactivation
        register_deactivation_hook(CB_PORTFOLIO_PLUGIN_FILE, function () {
            (new DeactivationHandler())->handle();
        });

        // Initialize plugin features
        add_action('init', [$this, 'initHooks']);
    }

    public function initHooks(): void
    {
        // Register REST API routes
        add_action('rest_api_init', [$this, 'registerApiRoutes']);
        
        // Admin menus and admin-only features
        if (is_admin()) {
            (new AdminMenuHandler())->register();
        }
        
        // Frontend hooks
        if (!is_admin()) {
            add_action('template_redirect', [$this, 'maybeLoadPortfolio']);
        }
    }
    
    public function registerApiRoutes(): void
    {
        $controller = new PortfolioSettingsController();
        $controller->register_routes();
    }
    
    public function maybeLoadPortfolio(): void
    {
        $enabled = get_option('cb_portfolio_enabled', false);
        
        if ($enabled && is_front_page()) {
            // Load portfolio on homepage
            add_filter('template_include', [$this, 'loadPortfolioTemplate']);
        }
    }
    
    public function loadPortfolioTemplate($template): string
    {
        return CB_PORTFOLIO_PLUGIN_PATH . '/templates/portfolio-template.php';
    }
}
