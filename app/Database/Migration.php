<?php

declare(strict_types=1);

namespace ChinmoyBiswas\CBPortfolio\Database;

if (! defined('ABSPATH')) {
    exit;
}

class Migration
{
    public static function createPortfolioTable()
    {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'cb_portfolio';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            title varchar(200) NOT NULL,
            tagline text,
            about text,
            email varchar(100),
            phone varchar(20),
            location varchar(100),
            github_url varchar(255),
            linkedin_url varchar(255),
            twitter_url varchar(255),
            website_url varchar(255),
            resume_url varchar(255),
            profile_image varchar(255),
            footer_text text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public static function createExperienceTable()
    {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'cb_portfolio_experience';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            company varchar(200) NOT NULL,
            company_website varchar(255),
            position varchar(200) NOT NULL,
            start_date varchar(50),
            end_date varchar(50),
            current tinyint(1) DEFAULT 0,
            description text,
            skills text,
            order_index int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public static function createProjectsTable()
    {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'cb_portfolio_projects';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title varchar(200) NOT NULL,
            description text,
            image_url varchar(255),
            live_url varchar(255),
            github_url varchar(255),
            technologies text,
            year varchar(4),
            made_at varchar(200),
            featured tinyint(1) DEFAULT 0,
            order_index int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        self::addProjectsTableColumns();
    }
    
    private static function addProjectsTableColumns()
    {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'cb_portfolio_projects';
        
        $year_exists = $wpdb->get_results(
            $wpdb->prepare(
                "SHOW COLUMNS FROM {$table_name} LIKE %s",
                'year'
            )
        );
        
        if (empty($year_exists)) {
            $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN year varchar(4) AFTER technologies");
        }
        
        $made_at_exists = $wpdb->get_results(
            $wpdb->prepare(
                "SHOW COLUMNS FROM {$table_name} LIKE %s",
                'made_at'
            )
        );
        
        if (empty($made_at_exists)) {
            $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN made_at varchar(200) AFTER year");
        }
    }
}
