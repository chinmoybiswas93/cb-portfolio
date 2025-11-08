<?php

namespace ChinmoyBiswasPortfolio;

use ChinmoyBiswasPortfolio\Hooks\Handlers\ActivationHandler;
use ChinmoyBiswasPortfolio\Hooks\Handlers\DeactivationHandler;
use ChinmoyBiswasPortfolio\Hooks\Handlers\AdminMenuHandler;
use ChinmoyBiswasPortfolio\Http\Controllers\PortfolioSettingsController;
use ChinmoyBiswasPortfolio\Http\Controllers\PortfolioController;

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
        return CB_PORTFOLIO_PLUGIN_PATH . '/templates/portfolio-template.php';
    }
}
