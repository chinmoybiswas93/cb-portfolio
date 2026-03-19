<?php
/**
 * Vite asset loader (dev + production).
 *
 * Expected variable: $vite_entry — the JS entry point key
 *                    (e.g. 'frontend/app.js' or 'frontend/projects.js').
 */

if (! defined('ABSPATH')) {
    exit;
}

use ChinmoyBiswas\CBPortfolio\Services\ViteAssetService;

if (ViteAssetService::isDev()): ?>
    <script type="module" src="<?php echo esc_url(ViteAssetService::getDevUrl('@vite/client')); ?>"></script>
    <script type="module" src="<?php echo esc_url(ViteAssetService::getDevUrl($vite_entry)); ?>"></script>
    <script type="module" src="<?php echo esc_url(ViteAssetService::getDevUrl('frontend/spotlight.js')); ?>"></script>
<?php else:
    foreach (ViteAssetService::getCssForEntry($vite_entry) as $css_url) {
        echo '<link rel="stylesheet" href="' . esc_url($css_url) . '">';
    }
?>
    <script type="module" src="<?php echo esc_url(ViteAssetService::getAssetUrl($vite_entry)); ?>"></script>
    <script type="module" src="<?php echo esc_url(ViteAssetService::getAssetUrl('frontend/spotlight.js')); ?>"></script>
<?php endif; ?>
