<?php
/**
 * Shared loader and base styles.
 *
 * Expected variable: $app_root_id — the id of the Vue mount element
 *                    (e.g. 'cb-portfolio-frontend' or 'cb-portfolio-projects').
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<style>
    :root { --cb-loader-duration: 2.4s; }
    * { box-sizing: border-box; }
    body { margin: 0; padding: 0; background: #0f172a; }

    .cb-portfolio-spotlight {
        pointer-events: none; position: fixed; inset: 0;
        z-index: 1; transition: background 300ms ease;
    }

    #<?php echo esc_attr($app_root_id); ?> { min-height: 100vh; }
    #<?php echo esc_attr($app_root_id); ?>.cb-portfolio-hidden { opacity: 0; visibility: hidden; }
    #<?php echo esc_attr($app_root_id); ?>.cb-portfolio-ready  { opacity: 1; visibility: visible; transition: opacity 0.2s ease; }

    .cb-portfolio-loader {
        position: fixed; inset: 0; display: flex; align-items: center;
        justify-content: center; background: #0f172a; z-index: 9999;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .cb-portfolio-loader--hidden { opacity: 0; visibility: hidden; pointer-events: none; }
    .cb-portfolio-loader__icon  { position: relative; width: 120px; height: 120px; animation: loaderScale var(--cb-loader-duration) ease-in-out infinite; }
    .cb-portfolio-loader__svg   { width: 100%; height: 100%; }
    .cb-portfolio-loader__hex   { fill: transparent; stroke: rgb(148,163,184); stroke-width: 6; stroke-linecap: round; stroke-linejoin: round; stroke-dasharray: 420; stroke-dashoffset: 420; animation: hexStroke var(--cb-loader-duration) ease-in-out infinite; }
    .cb-portfolio-loader__letter { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk','Segoe UI',sans-serif; font-size: 42px; font-weight: 500; color: rgb(148,163,184); -webkit-text-stroke: 1px rgb(148,163,184); letter-spacing: 2px; animation: letterReveal var(--cb-loader-duration) ease-in-out infinite; }

    @keyframes hexStroke {
        0%   { stroke-dashoffset: 420; opacity: 0.2; }
        20%  { stroke-dashoffset: 0;   opacity: 1; }
        55%  { stroke-dashoffset: 0;   opacity: 1; }
        75%  { stroke-dashoffset: 420; opacity: 0.3; }
        100% { stroke-dashoffset: 420; opacity: 0.2; }
    }
    @keyframes letterReveal {
        0%   { opacity: 0; transform: scale(0.6); }
        35%  { opacity: 0; transform: scale(0.6); }
        45%  { opacity: 1; transform: scale(1); }
        65%  { opacity: 1; transform: scale(1); }
        85%  { opacity: 0; transform: scale(0.7); }
        100% { opacity: 0; transform: scale(0.5); }
    }
    @keyframes loaderScale {
        0%   { transform: scale(1); }
        70%  { transform: scale(1); }
        100% { transform: scale(0.3); }
    }
</style>
