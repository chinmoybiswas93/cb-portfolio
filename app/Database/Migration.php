<?php

namespace ChinmoyBiswasPortfolio\Database;

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
            portfolio_id mediumint(9) NOT NULL,
            company varchar(200) NOT NULL,
            position varchar(200) NOT NULL,
            start_date varchar(50),
            end_date varchar(50),
            current tinyint(1) DEFAULT 0,
            description text,
            skills text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (portfolio_id) REFERENCES {$wpdb->prefix}cb_portfolio(id) ON DELETE CASCADE
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
            portfolio_id mediumint(9) NOT NULL,
            title varchar(200) NOT NULL,
            description text,
            image_url varchar(255),
            live_url varchar(255),
            github_url varchar(255),
            technologies text,
            featured tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (portfolio_id) REFERENCES {$wpdb->prefix}cb_portfolio(id) ON DELETE CASCADE
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
