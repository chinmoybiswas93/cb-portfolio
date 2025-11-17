<?php
/**
 * Portfolio Template - Server-Side Rendered for SEO
 * 
 * This template renders the portfolio content server-side for better SEO,
 * then hydrates with Vue.js for interactivity.
 */

// Fetch portfolio data server-side
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

// Filter featured projects (first 5)
$featured_projects = array_filter($projects_data, function($p) {
    return $p->featured === 1;
});
usort($featured_projects, function($a, $b) {
    return ($a->order_index ?? 999) - ($b->order_index ?? 999);
});
$featured_projects = array_slice($featured_projects, 0, 5);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- WordPress title and meta -->
    <title><?php
            $site_title = get_bloginfo('name');
            $site_tagline = get_bloginfo('description');
            if ($site_title && $site_tagline) {
                echo esc_html($site_title . ' - ' . $site_tagline);
            } elseif ($site_title) {
                echo esc_html($site_title);
            } else {
                echo 'Portfolio';
            }
            ?></title>

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
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
        }

        #cb-portfolio-frontend {
            min-height: 100vh;
        }
    </style>

</head>

<body>
    <div id="cb-portfolio-frontend" data-ssr="true">
        <div class="portfolio-app">
            <!-- Mouse spotlight effect -->
            <div class="spotlight-effect"></div>

            <!-- Left Sidebar -->
            <aside class="left-sidebar">
                <div class="sidebar-content">
                    <!-- Profile Section -->
                    <div class="profile-section">
                        <?php if ($portfolio): ?>
                            <div class="profile-info">
                                <?php if (!empty($portfolio->name)): ?>
                                    <h1 class="profile-name"><?php echo esc_html($portfolio->name); ?></h1>
                                <?php endif; ?>
                                <?php if (!empty($portfolio->title)): ?>
                                    <p class="profile-title"><?php echo esc_html($portfolio->title); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($portfolio->tagline)): ?>
                                    <p class="profile-tagline"><?php echo esc_html($portfolio->tagline); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="sidebar-nav">
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="#about" class="nav-link" data-section="about">ABOUT</a>
                            </li>
                            <li class="nav-item">
                                <a href="#experience" class="nav-link" data-section="experience">EXPERIENCE</a>
                            </li>
                            <li class="nav-item">
                                <a href="#projects" class="nav-link" data-section="projects">PROJECTS</a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Social Links -->
                    <div class="contact-section">
                        <?php if ($portfolio): ?>
                            <div class="social-links">
                                <?php if (!empty($portfolio->github_url)): ?>
                                    <a href="<?php echo esc_url($portfolio->github_url); ?>" target="_blank" class="social-link" aria-label="GitHub">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($portfolio->linkedin_url)): ?>
                                    <a href="<?php echo esc_url($portfolio->linkedin_url); ?>" target="_blank" class="social-link" aria-label="LinkedIn">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($portfolio->twitter_url)): ?>
                                    <a href="<?php echo esc_url($portfolio->twitter_url); ?>" target="_blank" class="social-link" aria-label="Twitter">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($portfolio->email)): ?>
                                    <a href="mailto:<?php echo esc_attr($portfolio->email); ?>" class="social-link" aria-label="Email">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-.904.732-1.636 1.636-1.636h.245l10.119 7.587 10.119-7.587h.245c.904 0 1.636.732 1.636 1.636z" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($portfolio->phone)): ?>
                                    <a href="tel:<?php echo esc_attr($portfolio->phone); ?>" class="social-link" aria-label="Phone">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>

            <!-- Right Content -->
            <main class="right-content">
                <div class="content-container">
                    <!-- About Section -->
                    <section id="about" class="content-section">
                        <div class="about-section">
                            <?php if ($portfolio && !empty($portfolio->about)): ?>
                                <div class="about-content">
                                    <div class="about-text">
                                        <?php echo wp_kses_post($portfolio->about); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <!-- Experience Section -->
                    <section id="experience" class="content-section">
                        <div class="experience-section">
                            <div class="content-timeline">
                                <?php if (!empty($experience)): ?>
                                    <?php foreach ($experience as $index => $exp): ?>
                                        <div class="content-item">
                                            <div class="item-card">
                                                <div class="item-left-column">
                                                    <span class="date-range<?php echo $exp->current ? ' current' : ''; ?>">
                                                        <?php echo esc_html($exp->start_date); ?> <span>-</span> <?php echo $exp->current ? 'PRESENT' : esc_html($exp->end_date); ?>
                                                    </span>
                                                </div>
                                                <div class="item-right-column">
                                                    <div class="item-header">
                                                        <h3 class="item-title">
                                                            <?php echo esc_html($exp->position); ?> <span></span>
                                                            <?php if (!empty($exp->company_website)): ?>
                                                                <a href="<?php echo esc_url($exp->company_website); ?>" target="_blank" rel="noopener noreferrer" class="item-link">
                                                                    <?php echo esc_html($exp->company); ?> <span class="external-link">↗</span>
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="company-text"><?php echo esc_html($exp->company); ?></span>
                                                            <?php endif; ?>
                                                        </h3>
                                                    </div>
                                                    <?php if (!empty($exp->description)): ?>
                                                        <p class="item-description"><?php echo esc_html($exp->description); ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($exp->skills)): ?>
                                                        <div class="tags-section">
                                                            <div class="tag-list">
                                                                <?php 
                                                                $skills = array_filter(array_map('trim', explode(',', $exp->skills)));
                                                                foreach ($skills as $skill): 
                                                                ?>
                                                                    <span class="tag-item"><?php echo esc_html($skill); ?></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if ($portfolio && !empty($portfolio->resume_url)): ?>
                                        <div class="archive-section">
                                            <a href="<?php echo esc_url($portfolio->resume_url); ?>" target="_blank" rel="noopener noreferrer" class="archive-button">
                                                <span class="archive-text">View Full Resume</span>
                                                <span class="archive-arrow">→</span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>

                    <!-- Projects Section -->
                    <section id="projects" class="content-section">
                        <div class="projects-section">
                            <div class="content-timeline">
                                <?php if (!empty($featured_projects)): ?>
                                    <?php foreach ($featured_projects as $index => $project): ?>
                                        <div class="content-item">
                                            <div class="item-card">
                                                <div class="item-left-column">
                                                    <?php if (!empty($project->image_url)): ?>
                                                        <div class="project-image">
                                                            <img src="<?php echo esc_url($project->image_url); ?>" alt="<?php echo esc_attr($project->title . ' Project Image'); ?>" />
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="project-image-placeholder">
                                                            <div class="image-icon">🖼️</div>
                                                            <span class="image-text">No Image</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="item-right-column">
                                                    <div class="item-header">
                                                        <h3 class="item-title">
                                                            <?php echo esc_html($project->title); ?>
                                                            <?php if (!empty($project->github_url) || !empty($project->live_url)): ?>
                                                                <span></span>
                                                            <?php endif; ?>
                                                            <?php if (!empty($project->github_url)): ?>
                                                                <a href="<?php echo esc_url($project->github_url); ?>" target="_blank" rel="noopener noreferrer" class="item-link">
                                                                    GitHub <span class="external-link">↗</span>
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if (!empty($project->github_url) && !empty($project->live_url)): ?>
                                                                <span></span>
                                                            <?php endif; ?>
                                                            <?php if (!empty($project->live_url)): ?>
                                                                <a href="<?php echo esc_url($project->live_url); ?>" target="_blank" rel="noopener noreferrer" class="item-link">
                                                                    Live Demo <span class="external-link">↗</span>
                                                                </a>
                                                            <?php endif; ?>
                                                        </h3>
                                                    </div>
                                                    <?php if (!empty($project->description)): ?>
                                                        <p class="item-description"><?php echo esc_html($project->description); ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($project->technologies)): ?>
                                                        <div class="tags-section">
                                                            <div class="tag-list">
                                                                <?php 
                                                                $technologies = array_filter(array_map('trim', explode(',', $project->technologies)));
                                                                foreach ($technologies as $tech): 
                                                                ?>
                                                                    <span class="tag-item"><?php echo esc_html($tech); ?></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="archive-section">
                                        <a href="#" class="archive-button">
                                            <span class="archive-text">View Full Project Archive</span>
                                            <span class="archive-arrow">→</span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>

                    <!-- Footer Section -->
                    <section class="content-section footer-section">
                        <footer class="portfolio-footer">
                            <div class="container">
                                <div class="footer-content">
                                    <?php if ($portfolio && !empty($portfolio->footer_text)): ?>
                                        <div class="footer-text">
                                            <?php echo wp_kses_post($portfolio->footer_text); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </footer>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <!-- Pass data to JavaScript for Vue hydration -->
    <script>
        var cbPortfolioData = <?php echo wp_json_encode($portfolio_data); ?>;
        var cbExperienceData = <?php echo wp_json_encode($experience_data); ?>;
        var cbProjectsData = <?php echo wp_json_encode($projects_data); ?>;
    </script>
</body>

</html>