<?php
/**
 * Controller for updating patterns based on their dependencies.
 */

namespace Extendify\Shared\Controllers;

defined('ABSPATH') || die('No direct access.');

use Extendify\Shared\Services\PluginDependencies\Forms\WPForms;
use Extendify\Shared\Services\PluginDependencies\Forms\ContactForm7;

/**
 * The controller for interacting with the pattern deps.
 */
class PatternPlaceholderController
{
    /**
     * Replace all placeholders in the given content
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function processPlaceholders($request)
    {
        $patterns = $request->get_param('patterns');
        $patterns = array_map(function ($pattern) {
            $newCode = ($pattern['patternReplacementCode'] ?? null);
            $pluginDependency = ($pattern['pluginDependency'] ?? null);
            if (!$newCode || !$pluginDependency) {
                return $pattern;
            }

            $metadata = null;
            if (isset($pattern['patternMetadata'])) {
                $metadata = json_decode($pattern['patternMetadata'], true);
            }

            if ($pluginDependency === 'contact-form-7' && isset($metadata['key'])) {
                $pattern['code'] = ContactForm7::create($pattern['code'], $metadata['key'], $newCode);
                return $pattern;
            }

            if ($pluginDependency === 'wpforms-lite' && isset($metadata['key'])) {
                $pattern['code'] = WPForms::create($pattern['code'], $metadata['key'], $newCode);
                return $pattern;
            }

            // We added a feature this version doesn't yet support.
            return $pattern;
        }, $patterns);

        // if any of the pattern code is a wp_error, we need to fail.
        foreach ($patterns as $pattern) {
            if (is_wp_error($pattern['code'])) {
                return new \WP_REST_Response($pattern['code'], 422);
            }
        }

        return new \WP_REST_Response($patterns);
    }
}
