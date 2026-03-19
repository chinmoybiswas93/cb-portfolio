<?php

declare(strict_types=1);

namespace ChinmoyBiswas\CBPortfolio\Http\Controllers;

use ChinmoyBiswas\CBPortfolio\Services\OrderedItemRepository;
use ChinmoyBiswas\CBPortfolio\Services\SanitizationService;

if (! defined('ABSPATH')) {
    exit;
}

class PortfolioController
{
    private OrderedItemRepository $experienceRepo;
    private OrderedItemRepository $projectsRepo;

    public function __construct()
    {
        $this->experienceRepo = new OrderedItemRepository(
            'cb_portfolio_experience',
            ['id', 'order_index', 'current']
        );
        $this->projectsRepo = new OrderedItemRepository(
            'cb_portfolio_projects',
            ['id', 'order_index', 'featured']
        );
    }

    public function register_routes(): void
    {
        $namespace = 'cb-portfolio/v1';

        register_rest_route($namespace, '/portfolio', [
            'methods'             => 'GET',
            'callback'            => [$this, 'get_portfolio'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($namespace, '/portfolio', [
            'methods'             => 'POST',
            'callback'            => [$this, 'save_portfolio'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route($namespace, '/experience', [
            'methods'             => 'GET',
            'callback'            => [$this, 'get_experience'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($namespace, '/experience', [
            'methods'             => 'POST',
            'callback'            => [$this, 'save_experience'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route($namespace, '/experience/(?P<id>\d+)', [
            'methods'             => 'DELETE',
            'callback'            => [$this, 'delete_experience'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route($namespace, '/projects', [
            'methods'             => 'GET',
            'callback'            => [$this, 'get_projects'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($namespace, '/projects', [
            'methods'             => 'POST',
            'callback'            => [$this, 'save_project'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route($namespace, '/projects/(?P<id>\d+)', [
            'methods'             => 'DELETE',
            'callback'            => [$this, 'delete_project'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route($namespace, '/experience/reorder', [
            'methods'             => 'POST',
            'callback'            => [$this, 'reorder_experience'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route($namespace, '/projects/reorder', [
            'methods'             => 'POST',
            'callback'            => [$this, 'reorder_projects'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route($namespace, '/export', [
            'methods'             => 'GET',
            'callback'            => [$this, 'export_all_data'],
            'permission_callback' => [$this, 'check_permission'],
        ]);

        register_rest_route($namespace, '/import', [
            'methods'             => 'POST',
            'callback'            => [$this, 'import_all_data'],
            'permission_callback' => [$this, 'check_permission'],
        ]);
    }

    public function check_permission(): bool
    {
        return current_user_can('manage_options');
    }

    // ── Portfolio ────────────────────────────────────────────────

    public function get_portfolio(\WP_REST_Request $request): \WP_REST_Response
    {
        global $wpdb;

        $portfolio = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT id, name, title, tagline, about, email, phone, location, github_url, linkedin_url, twitter_url, website_url, resume_url, profile_image, footer_text FROM {$wpdb->prefix}cb_portfolio ORDER BY id DESC LIMIT %d",
                1
            ),
            OBJECT
        );

        return new \WP_REST_Response($portfolio ?: [], 200);
    }

    public function save_portfolio(\WP_REST_Request $request)
    {
        global $wpdb;

        $data = SanitizationService::sanitizePortfolio($request->get_json_params());

        $existing = $wpdb->get_var(
            $wpdb->prepare("SELECT id FROM {$wpdb->prefix}cb_portfolio ORDER BY id DESC LIMIT %d", 1)
        );

        if ($existing) {
            $result = $wpdb->update(
                $wpdb->prefix . 'cb_portfolio',
                $data,
                ['id' => (int) $existing],
                array_fill(0, count($data), '%s'),
                ['%d']
            );
            $portfolio_id = (int) $existing;
        } else {
            $result = $wpdb->insert(
                $wpdb->prefix . 'cb_portfolio',
                $data,
                array_fill(0, count($data), '%s')
            );
            $portfolio_id = $wpdb->insert_id;
        }

        if ($result === false) {
            return new \WP_Error('save_failed', 'Failed to save portfolio data', ['status' => 500]);
        }

        return new \WP_REST_Response(['success' => true, 'id' => $portfolio_id], 200);
    }

    // ── Experience ───────────────────────────────────────────────

    public function get_experience(\WP_REST_Request $request): \WP_REST_Response
    {
        $columns = 'id, company, company_website, position, start_date, end_date, current, description, skills, order_index';
        return new \WP_REST_Response($this->experienceRepo->getAll($columns), 200);
    }

    public function save_experience(\WP_REST_Request $request)
    {
        $params = $request->get_json_params();
        $data = SanitizationService::sanitizeExperience($params);
        $id = !empty($params['id']) ? (int) $params['id'] : null;

        if (!$this->experienceRepo->save($data, $id)) {
            return new \WP_Error('save_failed', 'Failed to save experience', ['status' => 500]);
        }

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function delete_experience(\WP_REST_Request $request)
    {
        $id = (int) $request['id'];

        if (!$this->experienceRepo->delete($id)) {
            return new \WP_Error('delete_failed', 'Failed to delete experience', ['status' => 500]);
        }

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function reorder_experience(\WP_REST_Request $request)
    {
        $items = $request->get_param('items');

        if (!is_array($items)) {
            return new \WP_Error('invalid_data', 'Invalid data format', ['status' => 400]);
        }

        $this->experienceRepo->reorder($items);

        return new \WP_REST_Response(['success' => true, 'message' => 'Experience order updated successfully'], 200);
    }

    // ── Projects ─────────────────────────────────────────────────

    public function get_projects(\WP_REST_Request $request): \WP_REST_Response
    {
        $columns = 'id, title, description, image_url, live_url, github_url, technologies, year, made_at, featured, order_index';
        return new \WP_REST_Response($this->projectsRepo->getAll($columns), 200);
    }

    public function save_project(\WP_REST_Request $request)
    {
        $params = $request->get_json_params();
        $data = SanitizationService::sanitizeProject($params);
        $id = !empty($params['id']) ? (int) $params['id'] : null;

        if (!$this->projectsRepo->save($data, $id)) {
            return new \WP_Error('save_failed', 'Failed to save project', ['status' => 500]);
        }

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function delete_project(\WP_REST_Request $request)
    {
        $id = (int) $request['id'];

        if (!$this->projectsRepo->delete($id)) {
            return new \WP_Error('delete_failed', 'Failed to delete project', ['status' => 500]);
        }

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function reorder_projects(\WP_REST_Request $request)
    {
        $items = $request->get_param('items');

        if (!is_array($items)) {
            return new \WP_Error('invalid_data', 'Invalid data format', ['status' => 400]);
        }

        $this->projectsRepo->reorder($items);

        return new \WP_REST_Response(['success' => true, 'message' => 'Projects order updated successfully'], 200);
    }

    // ── Import / Export ──────────────────────────────────────────

    public function export_all_data(\WP_REST_Request $request)
    {
        global $wpdb;

        $export_data = [
            'metadata' => [
                'exportDate' => current_time('c'),
                'version'    => '1.0',
                'source'     => 'CB Portfolio Plugin',
                'site_url'   => get_site_url(),
            ],
        ];

        try {
            $portfolio = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM {$wpdb->prefix}cb_portfolio ORDER BY id DESC LIMIT %d", 1),
                ARRAY_A
            );
            if ($portfolio) {
                unset($portfolio['id'], $portfolio['created_at'], $portfolio['updated_at']);
                $export_data['portfolio'] = $portfolio;
            }

            $experience = $this->experienceRepo->getAllRaw();
            if ($experience) {
                foreach ($experience as &$exp) {
                    unset($exp['id'], $exp['created_at'], $exp['updated_at']);
                }
                $export_data['experience'] = $experience;
            }

            $projects = $this->projectsRepo->getAllRaw();
            if ($projects) {
                foreach ($projects as &$project) {
                    unset($project['id'], $project['created_at'], $project['updated_at']);
                }
                $export_data['projects'] = $projects;
            }

            $export_data['settings'] = [
                'enabled' => get_option('cb_portfolio_enabled', false),
            ];

            return new \WP_REST_Response($export_data, 200);
        } catch (\Exception $e) {
            return new \WP_Error('export_failed', 'Failed to export data: ' . $e->getMessage(), ['status' => 500]);
        }
    }

    public function import_all_data(\WP_REST_Request $request)
    {
        $import_data = $request->get_json_params();

        if (empty($import_data)) {
            return new \WP_Error('invalid_data', 'No data provided', ['status' => 400]);
        }

        global $wpdb;
        $results = [];

        try {
            $wpdb->query('START TRANSACTION');

            if (!empty($import_data['portfolio'])) {
                $portfolio_table = $wpdb->prefix . 'cb_portfolio';
                $wpdb->query($wpdb->prepare("DELETE FROM {$portfolio_table} WHERE %d = %d", 1, 1));

                $sanitized = SanitizationService::sanitizePortfolio($import_data['portfolio']);
                $sanitized['created_at'] = current_time('mysql');
                $sanitized['updated_at'] = current_time('mysql');

                $wpdb->insert($portfolio_table, $sanitized, $this->buildFormats($sanitized));
                $results['portfolio'] = true;
            }

            if (!empty($import_data['experience'])) {
                $this->experienceRepo->deleteAll();
                $count = 0;
                foreach ($import_data['experience'] as $index => $exp) {
                    $sanitized = SanitizationService::sanitizeExperience($exp);
                    $sanitized['order_index'] = $index + 1;
                    $sanitized['created_at'] = current_time('mysql');
                    $sanitized['updated_at'] = current_time('mysql');
                    if ($this->experienceRepo->insertRaw($sanitized)) {
                        $count++;
                    }
                }
                $results['experience'] = $count;
            }

            if (!empty($import_data['projects'])) {
                $this->projectsRepo->deleteAll();
                $count = 0;
                foreach ($import_data['projects'] as $index => $project) {
                    $sanitized = SanitizationService::sanitizeProject($project);
                    $sanitized['order_index'] = $index + 1;
                    $sanitized['created_at'] = current_time('mysql');
                    $sanitized['updated_at'] = current_time('mysql');
                    if ($this->projectsRepo->insertRaw($sanitized)) {
                        $count++;
                    }
                }
                $results['projects'] = $count;
            }

            if (!empty($import_data['settings']['enabled'])) {
                update_option('cb_portfolio_enabled', (bool) $import_data['settings']['enabled']);
                $results['settings'] = true;
            }

            $wpdb->query('COMMIT');

            return new \WP_REST_Response(['success' => true, 'message' => 'Data imported successfully', 'results' => $results], 200);
        } catch (\Exception $e) {
            $wpdb->query('ROLLBACK');
            return new \WP_Error('import_failed', 'Failed to import data: ' . $e->getMessage(), ['status' => 500]);
        }
    }

    private function buildFormats(array $data): array
    {
        $formats = [];
        foreach ($data as $value) {
            if (is_int($value)) {
                $formats[] = '%d';
            } elseif (is_float($value)) {
                $formats[] = '%f';
            } else {
                $formats[] = '%s';
            }
        }
        return $formats;
    }
}
