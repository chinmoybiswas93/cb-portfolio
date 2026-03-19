<?php

declare(strict_types=1);

namespace ChinmoyBiswas\CBPortfolio\Services;

if (! defined('ABSPATH')) {
    exit;
}

class SanitizationService
{
    public static function allowedHtmlTags(): array
    {
        return [
            'p'      => [],
            'br'     => [],
            'b'      => [],
            'strong'  => [],
            'span'    => ['class' => [], 'style' => []],
            'a'       => ['href' => [], 'target' => [], 'rel' => [], 'title' => []],
        ];
    }

    public static function sanitizePortfolio(array $params): array
    {
        return [
            'name'          => sanitize_text_field($params['name'] ?? ''),
            'title'         => sanitize_text_field($params['title'] ?? ''),
            'tagline'       => sanitize_textarea_field($params['tagline'] ?? ''),
            'about'         => wp_kses($params['about'] ?? '', self::allowedHtmlTags()),
            'email'         => sanitize_email($params['email'] ?? ''),
            'phone'         => sanitize_text_field($params['phone'] ?? ''),
            'location'      => sanitize_text_field($params['location'] ?? ''),
            'github_url'    => esc_url_raw($params['github_url'] ?? ''),
            'linkedin_url'  => esc_url_raw($params['linkedin_url'] ?? ''),
            'twitter_url'   => esc_url_raw($params['twitter_url'] ?? ''),
            'website_url'   => esc_url_raw($params['website_url'] ?? ''),
            'resume_url'    => esc_url_raw($params['resume_url'] ?? ''),
            'profile_image' => esc_url_raw($params['profile_image'] ?? ''),
            'footer_text'   => wp_kses($params['footer_text'] ?? '', self::allowedHtmlTags()),
        ];
    }

    public static function sanitizeExperience(array $params): array
    {
        return [
            'company'         => sanitize_text_field($params['company'] ?? ''),
            'company_website' => esc_url_raw($params['company_website'] ?? ''),
            'position'        => sanitize_text_field($params['position'] ?? ''),
            'start_date'      => sanitize_text_field($params['start_date'] ?? ''),
            'end_date'        => sanitize_text_field($params['end_date'] ?? ''),
            'current'         => (int) ($params['current'] ?? 0),
            'description'     => sanitize_textarea_field($params['description'] ?? ''),
            'skills'          => sanitize_text_field($params['skills'] ?? ''),
        ];
    }

    public static function sanitizeProject(array $params): array
    {
        return [
            'title'        => sanitize_text_field($params['title'] ?? ''),
            'description'  => sanitize_textarea_field($params['description'] ?? ''),
            'image_url'    => esc_url_raw($params['image_url'] ?? ''),
            'live_url'     => esc_url_raw($params['live_url'] ?? ''),
            'github_url'   => esc_url_raw($params['github_url'] ?? ''),
            'technologies' => sanitize_text_field($params['technologies'] ?? ''),
            'year'         => sanitize_text_field($params['year'] ?? ''),
            'made_at'      => sanitize_text_field($params['made_at'] ?? ''),
            'featured'     => (int) ($params['featured'] ?? 0),
        ];
    }
}
