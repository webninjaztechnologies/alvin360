<?php
/**
 * Controls Suggest Domains
 */

namespace Extendify\Assist\Controllers;

defined('ABSPATH') || die('No direct access.');

/**
 * The controller for fetching quick links
 */
class DomainsSuggestionController
{
    /**
     * The url for the server.
     *
     * @var string
     */
    public static $host = 'https://ai.extendify.com';

    /**
     * The list of strings to block from using the api.
     *
     * @var array
     */
    public static $blockList = ['instawp.xyz', 'my blog'];

    // phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
    /**
     * Return domains recommendation.
     *
     * @return \WP_REST_Response
     */
    public static function fetchDomainSuggestions()
    {
        if (!defined('EXTENDIFY_PARTNER_ID')) {
            return new \WP_REST_Response([], 200);
        }

        // Get the data directly from the database.
        $partnerData = \get_option('extendify_partner_data_v2', []);

        // Return early if neither of the banners are enabled.
        if (!($partnerData['showDomainBanner'] ?? false) && !($partnerData['showDomainTask'] ?? false)) {
            return new \WP_REST_Response([]);
        }

        if (!self::hasValidSiteTitle(\get_bloginfo('name'))) {
            return new \WP_REST_Response([]);
        }

        $userSelections = \get_option('extendify_user_selections', ['state' => []]);
        $businessDescription = ($userSelections['state']['businessInformation']['description'] ?? '');
        $data = [
            'query' => self::cleanSiteTitle(\get_bloginfo('name')),
            'devbuild' => defined('EXTENDIFY_DEVMODE') ? constant('EXTENDIFY_DEVMODE') : is_readable(EXTENDIFY_PATH . '.devbuild'),
            'siteId' => \get_option('extendify_site_id', ''),
            'tlds' => ($partnerData['domainTLDs'] ?? []),
            'partnerId' => \esc_attr(constant('EXTENDIFY_PARTNER_ID')),
            'wpLanguage' => \get_locale(),
            'wpVersion' => \get_bloginfo('version'),
            'siteTypeName' => \esc_attr(\get_option('extendify_siteType', ['name' => ''])['name']),
            'businessDescription' => \esc_attr($businessDescription),
        ];

        $response = \wp_remote_post(
            sprintf('%s/api/domains/suggest', static::$host),
            [
                'body' => \wp_json_encode($data),
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        if (is_wp_error($response)) {
            return new \WP_REST_Response([]);
        }

        $body = wp_remote_retrieve_body($response);
        if (empty($body)) {
            return new \WP_REST_Response([]);
        }

        return new \WP_REST_Response(json_decode($body, true), \wp_remote_retrieve_response_code($response));
    }

    /**
     * Clean site title.
     *
     * @param string $siteTitle - The site title to clean.
     * @return string
     */
    public static function cleanSiteTitle($siteTitle)
    {
        $siteTitle = html_entity_decode($siteTitle);
        return preg_replace('/[^\p{L}\p{N}\-]+/u', '', $siteTitle);
    }

    /**
     * Check if the site Title is part of the blocked list.
     *
     * @param string $siteTitle - The site title to check.
     * @return bool
     */
    public static function hasValidSiteTitle($siteTitle)
    {
        return empty(array_filter(self::$blockList, function ($item) use ($siteTitle) {
            // in php 8.0 we can use str_contains.
            return strpos(strtolower($siteTitle), strtolower($item)) !== false;
        }));
    }

    /**
     * Delete the cache for the domains suggestions.
     *
     * @return \WP_REST_Response
     */
    public static function deleteCache()
    {
        \delete_transient('extendify_domains');

        return new \WP_REST_Response(['success' => true]);
    }
}
