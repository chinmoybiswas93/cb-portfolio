<?php

declare(strict_types=1);

namespace ChinmoyBiswas\CBPortfolio\Services;

if (! defined('ABSPATH')) {
    exit;
}

class ViteAssetService
{
    public static function isDev(): bool
    {
        return file_exists(CB_PORTFOLIO_PLUGIN_PATH . '/.hot');
    }

    public static function getManifest(): ?array
    {
        $path = CB_PORTFOLIO_PLUGIN_PATH . '/assets/.vite/manifest.json';
        if (!file_exists($path)) {
            return null;
        }
        return json_decode(file_get_contents($path), true) ?: null;
    }

    public static function getCssForEntry(string $entry): array
    {
        $manifest = self::getManifest();
        if (!$manifest || !isset($manifest[$entry]['css'])) {
            return [];
        }
        return array_map(
            fn(string $css) => CB_PORTFOLIO_PLUGIN_URL . '/assets/' . $css,
            $manifest[$entry]['css']
        );
    }

    public static function getDevUrl(string $path = ''): string
    {
        return rtrim(CB_PORTFOLIO_VITE_DEV_URL, '/') . '/' . ltrim($path, '/');
    }

    public static function getAssetUrl(string $path): string
    {
        return CB_PORTFOLIO_PLUGIN_URL . '/assets/' . ltrim($path, '/');
    }
}
