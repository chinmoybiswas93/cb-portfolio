<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
                        echo '<link rel="stylesheet" href="' . CB_PORTFOLIO_PLUGIN_URL . '/assets/' . $css . '">';
                    }
                }
            }
        }
        ?>
        <script type="module" src="<?php echo CB_PORTFOLIO_PLUGIN_URL; ?>/assets/frontend/app.js"></script>
    <?php endif; ?>

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; }
        #cb-portfolio-frontend { min-height: 100vh; }
    </style>
</head>
<body>
    <div id="cb-portfolio-frontend"></div>
</body>
</html>
