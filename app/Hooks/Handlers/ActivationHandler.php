<?php

namespace ChinmoyBiswasPortfolio\Hooks\Handlers;

if (! defined('ABSPATH')) {
    exit;
}
class ActivationHandler
{
    public function handle()
    {

        error_log('Plugin activated.');
        error_log('CB_PORTFOLIO_PLUGIN_FILE' . CB_PORTFOLIO_PLUGIN_FILE);
        error_log('CB_PORTFOLIO_PLUGIN_PATH' . CB_PORTFOLIO_PLUGIN_PATH);
        error_log('CB_PORTFOLIO_PLUGIN_URL' . CB_PORTFOLIO_PLUGIN_URL);

        // Create database tables if needed
        $this->createTables();
    }

    private function createTables()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'cb_portfolio_inquiries';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            message text,
            product_id bigint(20),
            status varchar(20) DEFAULT 'pending',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
