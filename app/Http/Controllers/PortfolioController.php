<?php

namespace ChinmoyBiswasPortfolio\Http\Controllers;

if (! defined('ABSPATH')) {
    exit;
}

class PortfolioController
{
    public function register_routes()
    {
        // Portfolio data routes
        register_rest_route('cb-portfolio/v1', '/portfolio', [
            'methods' => 'GET',
            'callback' => [$this, 'get_portfolio'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('cb-portfolio/v1', '/portfolio', [
            'methods' => 'POST',
            'callback' => [$this, 'save_portfolio'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        // Experience routes
        register_rest_route('cb-portfolio/v1', '/experience', [
            'methods' => 'GET',
            'callback' => [$this, 'get_experience'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('cb-portfolio/v1', '/experience', [
            'methods' => 'POST',
            'callback' => [$this, 'save_experience'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route('cb-portfolio/v1', '/experience/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete_experience'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        // Projects routes
        register_rest_route('cb-portfolio/v1', '/projects', [
            'methods' => 'GET',
            'callback' => [$this, 'get_projects'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('cb-portfolio/v1', '/projects', [
            'methods' => 'POST',
            'callback' => [$this, 'save_project'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route('cb-portfolio/v1', '/projects/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete_project'],
            'permission_callback' => [$this, 'check_permission'],
        ]);
    }

    public function check_permission()
    {
        return current_user_can('manage_options');
    }

    public function get_portfolio(\WP_REST_Request $request)
    {
        global $wpdb;
        
        $portfolio = $wpdb->get_row(
            "SELECT * FROM {$wpdb->prefix}cb_portfolio ORDER BY id DESC LIMIT 1"
        );
        
        return new \WP_REST_Response($portfolio ?: [], 200);
    }

    public function save_portfolio(\WP_REST_Request $request)
    {
        global $wpdb;
        
        $params = $request->get_json_params();
        
        $data = [
            'name' => sanitize_text_field($params['name'] ?? ''),
            'title' => sanitize_text_field($params['title'] ?? ''),
            'tagline' => sanitize_textarea_field($params['tagline'] ?? ''),
            'about' => sanitize_textarea_field($params['about'] ?? ''),
            'email' => sanitize_email($params['email'] ?? ''),
            'phone' => sanitize_text_field($params['phone'] ?? ''),
            'location' => sanitize_text_field($params['location'] ?? ''),
            'github_url' => esc_url_raw($params['github_url'] ?? ''),
            'linkedin_url' => esc_url_raw($params['linkedin_url'] ?? ''),
            'twitter_url' => esc_url_raw($params['twitter_url'] ?? ''),
            'website_url' => esc_url_raw($params['website_url'] ?? ''),
            'resume_url' => esc_url_raw($params['resume_url'] ?? ''),
            'profile_image' => esc_url_raw($params['profile_image'] ?? ''),
        ];
        
        // Check if portfolio exists
        $existing = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}cb_portfolio ORDER BY id DESC LIMIT 1");
        
        if ($existing) {
            $result = $wpdb->update(
                $wpdb->prefix . 'cb_portfolio',
                $data,
                ['id' => $existing->id]
            );
            $portfolio_id = $existing->id;
        } else {
            $result = $wpdb->insert($wpdb->prefix . 'cb_portfolio', $data);
            $portfolio_id = $wpdb->insert_id;
        }
        
        if ($result === false) {
            return new \WP_Error('save_failed', 'Failed to save portfolio data', ['status' => 500]);
        }
        
        return new \WP_REST_Response(['success' => true, 'id' => $portfolio_id], 200);
    }

    public function get_experience(\WP_REST_Request $request)
    {
        global $wpdb;
        
        $experience = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}cb_portfolio_experience ORDER BY id DESC"
        );
        
        // Convert database values to proper types
        foreach ($experience as $exp) {
            $exp->current = (int) $exp->current; // Convert string to integer
            $exp->id = (int) $exp->id;
            $exp->portfolio_id = (int) $exp->portfolio_id;
        }
        
        return new \WP_REST_Response($experience, 200);
    }

    public function save_experience(\WP_REST_Request $request)
    {
        global $wpdb;
        
        $params = $request->get_json_params();
        
        $data = [
            'portfolio_id' => 1, // For now, assume single portfolio
            'company' => sanitize_text_field($params['company'] ?? ''),
            'company_website' => esc_url_raw($params['company_website'] ?? ''),
            'position' => sanitize_text_field($params['position'] ?? ''),
            'start_date' => sanitize_text_field($params['start_date'] ?? ''),
            'end_date' => sanitize_text_field($params['end_date'] ?? ''),
            'current' => (int) ($params['current'] ?? 0),
            'description' => sanitize_textarea_field($params['description'] ?? ''),
            'skills' => sanitize_text_field($params['skills'] ?? ''),
        ];
        
        if (isset($params['id']) && $params['id']) {
            $result = $wpdb->update(
                $wpdb->prefix . 'cb_portfolio_experience',
                $data,
                ['id' => $params['id']]
            );
        } else {
            $result = $wpdb->insert($wpdb->prefix . 'cb_portfolio_experience', $data);
        }
        
        if ($result === false) {
            return new \WP_Error('save_failed', 'Failed to save experience', ['status' => 500]);
        }
        
        return new \WP_REST_Response(['success' => true], 200);
    }

    public function delete_experience(\WP_REST_Request $request)
    {
        global $wpdb;
        
        $id = $request['id'];
        
        $result = $wpdb->delete(
            $wpdb->prefix . 'cb_portfolio_experience',
            ['id' => $id]
        );
        
        if ($result === false) {
            return new \WP_Error('delete_failed', 'Failed to delete experience', ['status' => 500]);
        }
        
        return new \WP_REST_Response(['success' => true], 200);
    }

    public function get_projects(\WP_REST_Request $request)
    {
        global $wpdb;
        
        $projects = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}cb_portfolio_projects ORDER BY id DESC"
        );
        
        return new \WP_REST_Response($projects, 200);
    }

    public function save_project(\WP_REST_Request $request)
    {
        global $wpdb;
        
        $params = $request->get_json_params();
        
        $data = [
            'portfolio_id' => 1, // For now, assume single portfolio
            'title' => sanitize_text_field($params['title'] ?? ''),
            'description' => sanitize_textarea_field($params['description'] ?? ''),
            'image_url' => esc_url_raw($params['image_url'] ?? ''),
            'live_url' => esc_url_raw($params['live_url'] ?? ''),
            'github_url' => esc_url_raw($params['github_url'] ?? ''),
            'technologies' => sanitize_text_field($params['technologies'] ?? ''),
            'featured' => (int) ($params['featured'] ?? 0),
        ];
        
        if (isset($params['id']) && $params['id']) {
            $result = $wpdb->update(
                $wpdb->prefix . 'cb_portfolio_projects',
                $data,
                ['id' => $params['id']]
            );
        } else {
            $result = $wpdb->insert($wpdb->prefix . 'cb_portfolio_projects', $data);
        }
        
        if ($result === false) {
            return new \WP_Error('save_failed', 'Failed to save project', ['status' => 500]);
        }
        
        return new \WP_REST_Response(['success' => true], 200);
    }

    public function delete_project(\WP_REST_Request $request)
    {
        global $wpdb;
        
        $id = $request['id'];
        
        $result = $wpdb->delete(
            $wpdb->prefix . 'cb_portfolio_projects',
            ['id' => $id]
        );
        
        if ($result === false) {
            return new \WP_Error('delete_failed', 'Failed to delete project', ['status' => 500]);
        }
        
        return new \WP_REST_Response(['success' => true], 200);
    }
}
