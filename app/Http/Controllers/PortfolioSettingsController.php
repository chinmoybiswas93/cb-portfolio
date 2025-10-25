<?php

namespace ChinmoyBiswasPortfolio\Http\Controllers;

if (! defined('ABSPATH')) {
    exit;
}

class PortfolioSettingsController
{
    public function register_routes()
    {
        register_rest_route('cb-portfolio/v1', '/settings', [
            'methods' => 'GET',
            'callback' => [$this, 'get_settings'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route('cb-portfolio/v1', '/settings', [
            'methods' => 'POST',
            'callback' => [$this, 'save_settings'],
            'permission_callback' => [$this, 'check_permission'],
        ]);
    }

    public function check_permission()
    {
        return current_user_can('manage_options');
    }

    public function get_settings(\WP_REST_Request $request)
    {
        $enabled = get_option('cb_portfolio_enabled', false);
        
        return new \WP_REST_Response([
            'enabled' => (bool) $enabled,
        ], 200);
    }

    public function save_settings(\WP_REST_Request $request)
    {
        $params = $request->get_json_params();
        $enabled = isset($params['enabled']) ? (bool) $params['enabled'] : false;

        update_option('cb_portfolio_enabled', $enabled);

        return new \WP_REST_Response([
            'success' => true,
            'enabled' => $enabled,
        ], 200);
    }
}
