<?php
/**
 * Portfolio Template - Server-Side Rendered for SEO
 * 
 * This template renders the portfolio content server-side for better SEO,
 * then hydrates with Vue.js for interactivity.
 */

// Fetch portfolio data server-side (used for SEO/meta + JS hydration)
global $wpdb;
$portfolio = $wpdb->get_row(
    "SELECT * FROM {$wpdb->prefix}cb_portfolio ORDER BY id DESC LIMIT 1"
);

$experience = $wpdb->get_results(
    "SELECT * FROM {$wpdb->prefix}cb_portfolio_experience ORDER BY order_index ASC, created_at ASC"
);

$projects = $wpdb->get_results(
    "SELECT * FROM {$wpdb->prefix}cb_portfolio_projects ORDER BY order_index ASC, created_at ASC"
);

// Normalize data types
if ($portfolio) {
    $portfolio->id = (int) $portfolio->id;
}

foreach ($experience as $exp) {
    $exp->current = (int) $exp->current;
    $exp->id = (int) $exp->id;
    $exp->order_index = (int) ($exp->order_index ?? 0);
}

foreach ($projects as $project) {
    $project->id = (int) $project->id;
    $project->featured = (int) $project->featured;
    $project->order_index = (int) ($project->order_index ?? 0);
}

// Prepare data for JavaScript
$portfolio_data = $portfolio ?: null;
$experience_data = $experience ?: [];
$projects_data = $projects ?: [];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- WordPress title and meta -->
    <title>
        <?php
            $site_title = get_bloginfo('name');
            $site_tagline = get_bloginfo('description');
            if ($site_title && $site_tagline) {
                echo esc_html($site_title . ' - ' . $site_tagline);
            } elseif ($site_title) {
                echo esc_html($site_title);
            } else {
                echo 'Portfolio';
            }
            ?>
    </title>

    <!-- Site favicon -->
    <?php if (has_site_icon()): ?>
        <link rel="icon" href="<?php echo esc_url(get_site_icon_url(32)); ?>" sizes="32x32">
        <link rel="icon" href="<?php echo esc_url(get_site_icon_url(192)); ?>" sizes="192x192">
        <link rel="apple-touch-icon" href="<?php echo esc_url(get_site_icon_url(180)); ?>">
        <meta name="msapplication-TileImage" content="<?php echo esc_url(get_site_icon_url(270)); ?>">
    <?php endif; ?>

    <!-- Meta description -->
    <?php 
    $meta_description = '';
    if ($portfolio && !empty($portfolio->tagline)) {
        $meta_description = $portfolio->tagline;
    } elseif (get_bloginfo('description')) {
        $meta_description = get_bloginfo('description');
    }
    if ($meta_description): ?>
        <meta name="description" content="<?php echo esc_attr($meta_description); ?>">
    <?php endif; ?>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo esc_url(home_url($_SERVER['REQUEST_URI'])); ?>">
    <meta property="og:title" content="<?php echo esc_attr($portfolio && !empty($portfolio->name) ? $portfolio->name . ' - ' . get_bloginfo('name') : get_bloginfo('name')); ?>">
    <?php if ($meta_description): ?>
        <meta property="og:description" content="<?php echo esc_attr($meta_description); ?>">
    <?php endif; ?>
    <?php if ($portfolio && !empty($portfolio->profile_image)): ?>
        <meta property="og:image" content="<?php echo esc_url($portfolio->profile_image); ?>">
    <?php endif; ?>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo esc_url(home_url($_SERVER['REQUEST_URI'])); ?>">
    <meta property="twitter:title" content="<?php echo esc_attr($portfolio && !empty($portfolio->name) ? $portfolio->name . ' - ' . get_bloginfo('name') : get_bloginfo('name')); ?>">
    <?php if ($meta_description): ?>
        <meta property="twitter:description" content="<?php echo esc_attr($meta_description); ?>">
    <?php endif; ?>
    <?php if ($portfolio && !empty($portfolio->profile_image)): ?>
        <meta property="twitter:image" content="<?php echo esc_url($portfolio->profile_image); ?>">
    <?php endif; ?>

    <?php if (file_exists(CB_PORTFOLIO_PLUGIN_PATH . '/.hot')): ?>
        <!-- Development mode -->
        <script type="module" src="<?php echo CB_PORTFOLIO_VITE_DEV_URL; ?>/@vite/client"></script>
        <script type="module" src="<?php echo CB_PORTFOLIO_VITE_DEV_URL; ?>/frontend/app.js"></script>
    <?php else: ?>
        <!-- Production mode -->
        <?php
        $manifest = CB_PORTFOLIO_PLUGIN_PATH . '/assets/.vite/manifest.json';
        if (file_exists($manifest)) {
            $manifest_data = json_decode(file_get_contents($manifest), true);
            if (isset($manifest_data['frontend/app.js'])) {
                $entry = $manifest_data['frontend/app.js'];
                if (isset($entry['css'])) {
                    foreach ($entry['css'] as $css) {
                        echo '<link rel="stylesheet" href="' . esc_url(CB_PORTFOLIO_PLUGIN_URL . '/assets/' . $css) . '">';
                    }
                }
            }
        }
        ?>
        <script type="module" src="<?php echo esc_url(CB_PORTFOLIO_PLUGIN_URL . '/assets/frontend/app.js'); ?>"></script>
    <?php endif; ?>

    <style>
        :root {
            --cb-loader-duration: 2.4s;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #0f172a;
        }

        #cb-portfolio-frontend {
            min-height: 100vh;
        }

        #cb-portfolio-frontend.cb-portfolio-hidden {
            opacity: 0;
            visibility: hidden;
        }

        #cb-portfolio-frontend.cb-portfolio-ready {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.2s ease;
        }

        .cb-portfolio-loader {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            z-index: 9999;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .cb-portfolio-loader--hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .cb-portfolio-loader__icon {
            position: relative;
            width: 120px;
            height: 120px;
            animation: loaderScale var(--cb-loader-duration) ease-in-out infinite;
        }

        .cb-portfolio-loader__svg {
            width: 100%;
            height: 100%;
        }

        .cb-portfolio-loader__hex {
            fill: transparent;
            stroke: rgb(148, 163, 184);
            stroke-width: 6;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 420;
            stroke-dashoffset: 420;
            animation: hexStroke var(--cb-loader-duration) ease-in-out infinite;
        }

        .cb-portfolio-loader__letter {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
            font-size: 42px;
            font-weight: 500;
            color: rgb(148, 163, 184);
            -webkit-text-stroke: 1px rgb(148, 163, 184);
            letter-spacing: 2px;
            animation: letterReveal var(--cb-loader-duration) ease-in-out infinite;
        }

        @keyframes hexStroke {
            0% {
                stroke-dashoffset: 420;
                opacity: 0.2;
            }
            20% {
                stroke-dashoffset: 0;
                opacity: 1;
            }
            55% {
                stroke-dashoffset: 0;
                opacity: 1;
            }
            75% {
                stroke-dashoffset: 420;
                opacity: 0.3;
            }
            100% {
                stroke-dashoffset: 420;
                opacity: 0.2;
            }
        }

        @keyframes letterReveal {
            0% {
                opacity: 0;
                transform: scale(0.6);
            }
            35% {
                opacity: 0;
                transform: scale(0.6);
            }
            45% {
                opacity: 1;
                transform: scale(1);
            }
            65% {
                opacity: 1;
                transform: scale(1);
            }
            85% {
                opacity: 0;
                transform: scale(0.7);
            }
            100% {
                opacity: 0;
                transform: scale(0.5);
            }
        }

        @keyframes loaderScale {
            0% {
                transform: scale(1);
            }
            70% {
                transform: scale(1);
            }
            100% {
                transform: scale(0.3);
            }
        }
    </style>

</head>

<body>
    <div id="cb-portfolio-loader" class="cb-portfolio-loader" aria-hidden="true">
        <div class="cb-portfolio-loader__icon">
            <svg class="cb-portfolio-loader__svg" viewBox="0 0 120 120" role="presentation">
                <polygon class="cb-portfolio-loader__hex" points="60 8 108 36 108 84 60 112 12 84 12 36" />
                                        </svg>
            <span class="cb-portfolio-loader__letter">C</span>
                    </div>
                </div>
    <script>
        window.cbPortfolioLoaderStart = performance.now();
    </script>

    <div id="cb-portfolio-frontend" class="cb-portfolio-hidden" data-ssr="true"></div>

        <noscript>
            <style>
                .cb-portfolio-noscript {
                    color: #e2e8f0;
                    background: #0f172a;
                    padding: 40px 20px;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }
                .cb-portfolio-noscript a {
                    color: #93c5fd;
                }
            </style>
            <div class="cb-portfolio-noscript">
                <h1><?php esc_html_e('JavaScript Required', 'cb-portfolio'); ?></h1>
                <p><?php esc_html_e('To view the interactive portfolio, please enable JavaScript in your browser.', 'cb-portfolio'); ?></p>
                            <?php if ($portfolio && !empty($portfolio->about)): ?>
                    <h2><?php echo esc_html($portfolio->name ?: get_bloginfo('name')); ?></h2>
                    <p><?php echo wp_kses_post($portfolio->about); ?></p>
                            <?php endif; ?>
                        </div>
        </noscript>
    </div>

    <!-- Pass data to JavaScript for Vue hydration -->
    <script>
        var cbPortfolioData = <?php echo wp_json_encode($portfolio_data); ?>;
        var cbExperienceData = <?php echo wp_json_encode($experience_data); ?>;
        var cbProjectsData = <?php echo wp_json_encode($projects_data); ?>;
    </script>
</body>

</html>