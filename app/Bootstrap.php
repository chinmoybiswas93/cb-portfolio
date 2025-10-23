<?php

namespace ChinmoyBiswasPortfolio;

use ChinmoyBiswasPortfolio\Hooks\Handlers\ActivationHandler;
use ChinmoyBiswasPortfolio\Hooks\Handlers\DeactivationHandler;
use ChinmoyBiswasPortfolio\Hooks\Handlers\AdminMenuHandler;

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
        // Admin menus and admin-only features
        if (is_admin()) {
            (new AdminMenuHandler())->register();
        }
    }
}
