<?php

namespace ChinmoyBiswas\CBPortfolio;

use ChinmoyBiswas\CBPortfolio\Hooks\Handlers\ActivationHandler;
use ChinmoyBiswas\CBPortfolio\Hooks\Handlers\DeactivationHandler;
use ChinmoyBiswas\CBPortfolio\Hooks\Handlers\AdminMenuHandler;
use ChinmoyBiswas\CBPortfolio\Http\Controllers\PortfolioSettingsController;
use ChinmoyBiswas\CBPortfolio\Http\Controllers\PortfolioController;

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
        register_activation_hook(CB_PORTFOLIO_PLUGIN_FILE, function () {
            (new ActivationHandler())->handle();
        });

        register_deactivation_hook(CB_PORTFOLIO_PLUGIN_FILE, function () {
            (new DeactivationHandler())->handle();
        });

        add_action('init', [$this, 'initHooks']);
    }

    public function initHooks(): void
    {
        add_action('rest_api_init', [$this, 'registerApiRoutes']);

        if (is_admin()) {
            (new AdminMenuHandler())->register();
        }

        if (!is_admin()) {
            add_action('template_redirect', [$this, 'maybeLoadPortfolio']);
        }
    }

    public function registerApiRoutes(): void
    {
        $settingsController = new PortfolioSettingsController();
        $settingsController->register_routes();

        $portfolioController = new PortfolioController();
        $portfolioController->register_routes();
    }

    public function maybeLoadPortfolio(): void
    {
        $enabled = get_option('cb_portfolio_enabled', false);

        if ($enabled) {
            add_filter('template_include', [$this, 'loadPortfolioTemplate']);
        }
    }

    public function loadPortfolioTemplate($template): string
    {
        $enabled = get_option('cb_portfolio_enabled', false);
        
        if (!$enabled) {
            return $template;
        }

        // Check if we're on the projects page
        $request_uri = isset($_SERVER['REQUEST_URI']) ? esc_url_raw(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        $parsed_url = wp_parse_url($request_uri);
        $path = isset($parsed_url['path']) ? trim($parsed_url['path'], '/') : '';
        
        // Check if path is /projects
        if ($path === 'projects' || substr($path, -8) === '/projects') {
            return CB_PORTFOLIO_PLUGIN_PATH . '/templates/projects-template.php';
        }

        // Only load portfolio template on front page
        if (is_front_page()) {
            return CB_PORTFOLIO_PLUGIN_PATH . '/templates/portfolio-template.php';
        }

        return $template;
    }
}
