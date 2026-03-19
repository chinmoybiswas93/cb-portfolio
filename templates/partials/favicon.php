<?php

if (! defined('ABSPATH')) {
    exit;
}

if (has_site_icon()):
    $icon_32  = get_site_icon_url(32);
    $icon_192 = get_site_icon_url(192);
    $icon_180 = get_site_icon_url(180);
    $icon_270 = get_site_icon_url(270);
?>
    <link rel="icon" href="<?php echo esc_url($icon_32); ?>" sizes="32x32">
    <link rel="icon" href="<?php echo esc_url($icon_192); ?>" sizes="192x192">
    <link rel="apple-touch-icon" href="<?php echo esc_url($icon_180); ?>">
    <meta name="msapplication-TileImage" content="<?php echo esc_url($icon_270); ?>">
<?php endif; ?>
