<?php
/**
 * Install plugins programmatically.
 */

namespace Extendify\Shared\Services\PluginDependencies;

defined('ABSPATH') || die('No direct access.');

/**
 * Install plugins programmatically.
 */
class PluginInstaller
{
    /**
     * Install or activate a required plugin
     *
     * @param [type] $slug         - The plugin slug.
     * @param [type] $fallbackPath - The fallback path.
     * @return mixed
     */
    public static function installPlugin($slug, $fallbackPath)
    {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/class-language-pack-upgrader.php';

        // Retrieve the details of the plugin based on the slug.
        $api = plugins_api(
            'plugin_information',
            [
                'slug'   => $slug,
                'fields' => [
                    'sections'       => false,
                    'language_packs' => true,
                ],
            ]
        );

        $skin     = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader($skin);

	      // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
        $upgrader->install($api->download_link);
        $file = $upgrader->plugin_info();

        // Install the language pack if available.
        $currentLocale = get_locale();
        // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
        foreach ($api->language_packs as $pack) {
            if ($pack['language'] === $currentLocale) {
                $languageUpgrader = new \Language_Pack_Upgrader($skin);
                $languageUpgrader->upgrade((object) $pack);
                break;
            }
        }

        // Activate the plugin.
        return activate_plugin($file ? $file : $fallbackPath);
    }
}
