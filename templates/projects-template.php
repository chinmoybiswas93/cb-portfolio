<?php

if (! defined('ABSPATH')) {
    exit;
}

global $wpdb;

$portfolio = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT id, name, profile_image FROM {$wpdb->prefix}cb_portfolio ORDER BY id DESC LIMIT %d",
        1
    ),
    OBJECT
);

$projects = $wpdb->get_results(
    "SELECT id, title, description, image_url, live_url, github_url, technologies, year, made_at, featured, order_index FROM {$wpdb->prefix}cb_portfolio_projects ORDER BY order_index ASC, created_at ASC",
    OBJECT
);

if ($portfolio) {
    $portfolio->id = (int) $portfolio->id;
}
if ($projects) {
    foreach ($projects as $project) {
        $project->id          = (int) $project->id;
        $project->featured    = (int) $project->featured;
        $project->order_index = (int) ($project->order_index ?? 0);
    }
}

$portfolio_data = $portfolio ?: null;
$projects_data  = $projects ?: [];

$site_title     = get_bloginfo('name');
$request_uri    = $_SERVER['REQUEST_URI'] ?? '';
$portfolio_name = $portfolio && !empty($portfolio->name) ? $portfolio->name : $site_title;

$page_title       = ($portfolio && !empty($portfolio->name) ? $portfolio->name . ' - ' : '') . 'All Projects' . ($site_title ? ' - ' . $site_title : '');
$og_title         = ($portfolio && !empty($portfolio->name) ? $portfolio->name . ' - ' : '') . 'All Projects';
$meta_description = 'View all projects by ' . $portfolio_name;

$app_root_id = 'cb-portfolio-projects';
$vite_entry  = 'frontend/projects.js';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html($page_title); ?></title>

    <?php include __DIR__ . '/partials/favicon.php'; ?>

    <meta name="description" content="<?php echo esc_attr($meta_description); ?>">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo esc_url(home_url($request_uri)); ?>">
    <meta property="og:title" content="<?php echo esc_attr($og_title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($meta_description); ?>">
    <?php if ($portfolio && !empty($portfolio->profile_image)): ?>
        <meta property="og:image" content="<?php echo esc_url($portfolio->profile_image); ?>">
    <?php endif; ?>

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo esc_url(home_url($request_uri)); ?>">
    <meta property="twitter:title" content="<?php echo esc_attr($og_title); ?>">
    <meta property="twitter:description" content="<?php echo esc_attr($meta_description); ?>">
    <?php if ($portfolio && !empty($portfolio->profile_image)): ?>
        <meta property="twitter:image" content="<?php echo esc_url($portfolio->profile_image); ?>">
    <?php endif; ?>

    <?php include __DIR__ . '/partials/vite-assets.php'; ?>
    <?php include __DIR__ . '/partials/loader-styles.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/partials/loader.php'; ?>

    <div id="<?php echo esc_attr($app_root_id); ?>" class="cb-portfolio-hidden" data-ssr="true"></div>

    <noscript>
        <style>
            .cb-portfolio-noscript { color: #e2e8f0; background: #0f172a; padding: 40px 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
            .cb-portfolio-noscript a { color: #93c5fd; }
        </style>
        <div class="cb-portfolio-noscript">
            <h1><?php esc_html_e('JavaScript Required', 'cb-portfolio'); ?></h1>
            <p><?php esc_html_e('To view the projects page, please enable JavaScript in your browser.', 'cb-portfolio'); ?></p>
        </div>
    </noscript>

    <script>
        var cbPortfolioData = <?php echo wp_json_encode($portfolio_data); ?>;
        var cbProjectsData = <?php echo wp_json_encode($projects_data); ?>;
    </script>
</body>
</html>
