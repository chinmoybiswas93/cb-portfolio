<?php

namespace ChinmoyBiswasPortfolio\Http\Controllers;

if (! defined('ABSPATH')) {
    exit;
}

class PortfolioController
{
    public function register_routes()
    {
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

        register_rest_route('cb-portfolio/v1', '/experience/reorder', [
            'methods' => 'POST',
            'callback' => [$this, 'reorder_experience'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route('cb-portfolio/v1', '/projects/reorder', [
            'methods' => 'POST',
            'callback' => [$this, 'reorder_projects'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route('cb-portfolio/v1', '/export', [
            'methods' => 'GET',
            'callback' => [$this, 'export_all_data'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route('cb-portfolio/v1', '/import', [
            'methods' => 'POST',
            'callback' => [$this, 'import_all_data'],
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
            'about' => wp_kses($params['about'] ?? '', $this->getAllowedHtmlTags()),
            'email' => sanitize_email($params['email'] ?? ''),
            'phone' => sanitize_text_field($params['phone'] ?? ''),
            'location' => sanitize_text_field($params['location'] ?? ''),
            'github_url' => esc_url_raw($params['github_url'] ?? ''),
            'linkedin_url' => esc_url_raw($params['linkedin_url'] ?? ''),
            'twitter_url' => esc_url_raw($params['twitter_url'] ?? ''),
            'website_url' => esc_url_raw($params['website_url'] ?? ''),
            'resume_url' => esc_url_raw($params['resume_url'] ?? ''),
            'profile_image' => esc_url_raw($params['profile_image'] ?? ''),
            'footer_text' => wp_kses($params['footer_text'] ?? '', $this->getAllowedHtmlTags()),
        ];

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
            "SELECT * FROM {$wpdb->prefix}cb_portfolio_experience ORDER BY order_index ASC, created_at ASC"
        );

        foreach ($experience as $exp) {
            $exp->current = (int) $exp->current;
            $exp->id = (int) $exp->id;
            $exp->order_index = (int) ($exp->order_index ?? 0);
        }

        return new \WP_REST_Response($experience, 200);
    }

    public function save_experience(\WP_REST_Request $request)
    {
        global $wpdb;

        $params = $request->get_json_params();

        $data = [
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
            $max_order = $wpdb->get_var("SELECT MAX(order_index) FROM {$wpdb->prefix}cb_portfolio_experience");
            $data['order_index'] = ($max_order ?? 0) + 1;
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
            "SELECT * FROM {$wpdb->prefix}cb_portfolio_projects ORDER BY order_index ASC, created_at ASC"
        );

        foreach ($projects as $project) {
            $project->id = (int) $project->id;
            $project->featured = (int) $project->featured;
            $project->order_index = (int) ($project->order_index ?? 0);
        }

        return new \WP_REST_Response($projects, 200);
    }

    public function save_project(\WP_REST_Request $request)
    {
        global $wpdb;

        $params = $request->get_json_params();

        $data = [
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
            $max_order = $wpdb->get_var("SELECT MAX(order_index) FROM {$wpdb->prefix}cb_portfolio_projects");
            $data['order_index'] = ($max_order ?? 0) + 1;
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

    public function reorder_experience(\WP_REST_Request $request)
    {
        if (!current_user_can('manage_options')) {
            return new \WP_Error('forbidden', 'Access denied', array('status' => 403));
        }

        $items = $request->get_param('items');

        if (!is_array($items)) {
            return new \WP_Error('invalid_data', 'Invalid data format', array('status' => 400));
        }

        global $wpdb;
        $experience_table = $wpdb->prefix . 'cb_portfolio_experience';

        foreach ($items as $item) {
            if (!isset($item['id']) || !isset($item['order_index'])) {
                continue;
            }

            $wpdb->update(
                $experience_table,
                array('order_index' => intval($item['order_index'])),
                array('id' => intval($item['id'])),
                array('%d'),
                array('%d')
            );
        }

        return new \WP_REST_Response(array(
            'success' => true,
            'message' => 'Experience order updated successfully'
        ), 200);
    }

    public function reorder_projects(\WP_REST_Request $request)
    {
        if (!current_user_can('manage_options')) {
            return new \WP_Error('forbidden', 'Access denied', array('status' => 403));
        }

        $items = $request->get_param('items');

        if (!is_array($items)) {
            return new \WP_Error('invalid_data', 'Invalid data format', array('status' => 400));
        }

        global $wpdb;
        $projects_table = $wpdb->prefix . 'cb_portfolio_projects';

        $updated_items = array();

        foreach ($items as $item) {
            if (!isset($item['id']) || !isset($item['order_index'])) {
                continue;
            }

            $result = $wpdb->update(
                $projects_table,
                array('order_index' => intval($item['order_index'])),
                array('id' => intval($item['id'])),
                array('%d'),
                array('%d')
            );

            $updated_items[] = array(
                'id' => intval($item['id']),
                'order_index' => intval($item['order_index']),
                'result' => $result
            );
        }

        return new \WP_REST_Response(array(
            'success' => true,
            'message' => 'Projects order updated successfully',
            'updated_items' => $updated_items,
            'sql_last_error' => $wpdb->last_error
        ), 200);
    }

    public function export_all_data(\WP_REST_Request $request)
    {
        if (!current_user_can('manage_options')) {
            return new \WP_Error('forbidden', 'Access denied', array('status' => 403));
        }

        global $wpdb;

        $export_data = [
            'metadata' => [
                'exportDate' => current_time('c'),
                'version' => '1.0',
                'source' => 'CB Portfolio Plugin',
                'site_url' => get_site_url()
            ]
        ];

        try {
            // Get portfolio data
            $portfolio_table = $wpdb->prefix . 'cb_portfolio';
            $portfolio_data = $wpdb->get_row("SELECT * FROM $portfolio_table ORDER BY id DESC LIMIT 1", ARRAY_A);
            if ($portfolio_data) {
                unset($portfolio_data['id'], $portfolio_data['created_at'], $portfolio_data['updated_at']);
                $export_data['portfolio'] = $portfolio_data;
            }

            // Get experience data
            $experience_table = $wpdb->prefix . 'cb_portfolio_experience';
            $experience_data = $wpdb->get_results("SELECT * FROM $experience_table ORDER BY order_index ASC", ARRAY_A);
            if ($experience_data) {
                foreach ($experience_data as &$exp) {
                    unset($exp['id'], $exp['created_at'], $exp['updated_at']);
                }
                $export_data['experience'] = $experience_data;
            }

            // Get projects data
            $projects_table = $wpdb->prefix . 'cb_portfolio_projects';
            $projects_data = $wpdb->get_results("SELECT * FROM $projects_table ORDER BY order_index ASC", ARRAY_A);
            if ($projects_data) {
                foreach ($projects_data as &$project) {
                    unset($project['id'], $project['created_at'], $project['updated_at']);
                }
                $export_data['projects'] = $projects_data;
            }

            // Get settings
            $settings = [
                'enabled' => get_option('cb_portfolio_enabled', false)
            ];
            $export_data['settings'] = $settings;

            return new \WP_REST_Response($export_data, 200);
        } catch (\Exception $e) {
            return new \WP_Error('export_failed', 'Failed to export data: ' . $e->getMessage(), array('status' => 500));
        }
    }

    public function import_all_data(\WP_REST_Request $request)
    {
        if (!current_user_can('manage_options')) {
            return new \WP_Error('forbidden', 'Access denied', array('status' => 403));
        }

        $import_data = $request->get_json_params();

        if (empty($import_data)) {
            return new \WP_Error('invalid_data', 'No data provided', array('status' => 400));
        }

        global $wpdb;

        // Track import results for debugging
        $import_results = [];

        try {
            // Start transaction
            $wpdb->query('START TRANSACTION');

            // Import portfolio data
            if (isset($import_data['portfolio']) && !empty($import_data['portfolio'])) {
                $portfolio_table = $wpdb->prefix . 'cb_portfolio';

                // Clear existing portfolio data
                $deleted = $wpdb->query("DELETE FROM $portfolio_table");
                $import_results['portfolio_deleted'] = $deleted;

                $portfolio_data = $import_data['portfolio'];
                $portfolio_data['created_at'] = current_time('mysql');
                $portfolio_data['updated_at'] = current_time('mysql');

                $inserted = $wpdb->insert($portfolio_table, $portfolio_data);
                $import_results['portfolio_inserted'] = $inserted !== false ? 1 : 0;
                
                if ($inserted === false) {
                    $import_results['portfolio_error'] = $wpdb->last_error;
                }
            }

            // Import experience data
            if (isset($import_data['experience']) && !empty($import_data['experience'])) {
                $experience_table = $wpdb->prefix . 'cb_portfolio_experience';

                // Clear existing experience data
                $deleted = $wpdb->query("DELETE FROM $experience_table");
                $import_results['experience_deleted'] = $deleted;

                $inserted_count = 0;
                foreach ($import_data['experience'] as $index => $experience) {
                    $experience['order_index'] = $index + 1;
                    $experience['created_at'] = current_time('mysql');
                    $experience['updated_at'] = current_time('mysql');

                    $result = $wpdb->insert($experience_table, $experience);
                    if ($result !== false) {
                        $inserted_count++;
                    } else {
                        $import_results['experience_errors'][] = $wpdb->last_error;
                    }
                }
                $import_results['experience_inserted'] = $inserted_count;
            }

            // Import projects data
            if (isset($import_data['projects']) && !empty($import_data['projects'])) {
                $projects_table = $wpdb->prefix . 'cb_portfolio_projects';

                // Clear existing projects data
                $deleted = $wpdb->query("DELETE FROM $projects_table");
                $import_results['projects_deleted'] = $deleted;

                $inserted_count = 0;
                foreach ($import_data['projects'] as $index => $project) {
                    $project['order_index'] = $index + 1;
                    $project['created_at'] = current_time('mysql');
                    $project['updated_at'] = current_time('mysql');

                    $result = $wpdb->insert($projects_table, $project);
                    if ($result !== false) {
                        $inserted_count++;
                    } else {
                        $import_results['projects_errors'][] = $wpdb->last_error;
                    }
                }
                $import_results['projects_inserted'] = $inserted_count;
            }

            // Import settings
            if (isset($import_data['settings']) && !empty($import_data['settings'])) {
                $settings = $import_data['settings'];
                if (isset($settings['enabled'])) {
                    $updated = update_option('cb_portfolio_enabled', $settings['enabled']);
                    $import_results['settings_updated'] = $updated;
                }
            }

            // Commit transaction
            $wpdb->query('COMMIT');

            return new \WP_REST_Response([
                'success' => true,
                'message' => 'Data imported successfully',
                'import_results' => $import_results
            ], 200);
        } catch (\Exception $e) {
            // Rollback transaction on error
            $wpdb->query('ROLLBACK');

            return new \WP_Error('import_failed', 'Failed to import data: ' . $e->getMessage(), [
                'status' => 500,
                'import_results' => $import_results
            ]);
        }
    }

    private function getAllowedHtmlTags()
    {
        return [
            'p' => [],
            'br' => [],
            'b' => [],
            'strong' => [],
            'span' => [
                'class' => [],
                'style' => []
            ],
            'a' => [
                'href' => [],
                'target' => [],
                'rel' => [],
                'title' => []
            ]
        ];
    }
}
