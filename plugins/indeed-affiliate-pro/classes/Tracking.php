<?php
namespace Indeed\Uap;

class Tracking
{
  	/**
  	 * @param none
  	 * @return none
  	 */
  	public function __construct()
  	{
          $allow = get_option( 'uap_allow_tracking', false );
          if ( !$allow ){
              return;
          }

          // aadd weekly cron if not exists
          add_filter( 'cron_schedules', [ $this, 'addWeekly' ], 999 );
          // main function
          add_action( 'uap_send_tracking_cron', [ $this, 'sendTracking' ], 999 );

          // does the action its registered into cron to run every week ?
          if ( !wp_get_schedule( 'uap_send_tracking_cron' ) ){
              wp_schedule_event( time(), 'weekly', 'uap_send_tracking_cron' );
          }
  	}

  	/**
  	 * @param array
  	 * @return array
  	 */
      public function addWeekly( $schedules=[] )
      {
      		if ( isset( $schedules['weekly'] ) ){
      			return $schedules;
      		}
          $interval = 7 * 24 * 60 * 60;
          $schedules['weekly'] = array(
              'interval' => $interval,
              'display'  => esc_html__( 'Weekly', 'uap' )
          );
          return $schedules;
      }

  	/**
  	 * @param none
  	 * @return none
  	 */
  	public function sendTracking()
  	{
    		global $wp_version, $indeed_db;

        $allow = get_option( 'uap_allow_tracking', false );
        if ( !$allow ){
            return;
        }

    		// plugins
    		$plugins          = [];
    		$allPlugins       = get_plugins();
    		if ( empty( $allPlugins ) || $allPlugins === false || !is_array( $allPlugins ) ){
    			   $allPlugins = [];
    		}
    		if (!function_exists('is_plugin_active')){
    		    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    		}
    		foreach ( $allPlugins as $pluginDir => $pluginData ){
      			if ( !isset( $pluginDir ) || $pluginDir === '' || !isset( $pluginData['Version'] ) || !isset( $pluginData['Name'] ) ){
      				  continue;
      			}
    		    if ( is_plugin_active( $pluginDir ) ){
    		        $plugins['active'][ $pluginData['Name'] ] = $pluginData['Version'];
    		    } else {
      		      $plugins['inactive'][ $pluginData['Name'] ] = $pluginData['Version'];
            }
    		}
    		// theme
        $theme = wp_get_theme();
    		if ( $theme ){
    	      $themeName = $theme->get('Name');
    		}

    		// extensions
    		$activeExtensions = [];
    		$inactiveExtensions = [];
    		$extensions = $indeed_db->get_magic_feat_item_list();
    		foreach ( $extensions as $extensionName => $extensionData ){
            if ( !isset( $extensionName ) || $extensionName === '' || !isset( $extensionData['label'] )  ){
                continue;
            }
    		    if ( $indeed_db->is_magic_feat_enable( $extensionName ) ){
    		        $activeExtensions[ $extensionName ] = $extensionData['label'];
    		    } else {
                $inactiveExtensions[ $extensionName ] = $extensionData['label'];
            }
    		}

    		$data = [
          'unique_identificator'        => base64_encode( get_option('siteurl') ),
          'server_type'								  => isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE'] ? sanitize_text_field( $_SERVER['SERVER_SOFTWARE'] ) : 'unknown',
          'php_version'								  => phpversion(),
          'wp_version'								  => $wp_version,
          'is_multisite'								=> is_multisite(),
          'active_theme'								=> $themeName,
          'active_plugins'              => $plugins,
          'site_locale'                 => get_locale(),
          'uap_main_settings'           => [
                'uap_version'                 => indeed_get_plugin_version( UAP_PATH . 'indeed-affiliate-pro.php' ),
                'uap_install_date'            => get_option( 'uap_install_time', false ),
                'active_extensions'						=> $activeExtensions,
                'inactive_extensions'					=> $inactiveExtensions,
                'ranks_count'                 => $indeed_db->ranks_get_count(),
          ],
    		];
        $data = serialize( $data );
        $targetUrl = 'https://portal.ultimateaffiliate.pro/tracking/' . md5('indeed-affiliate-pro-t') . '/';
        $response = wp_remote_post( $targetUrl, [ 'timeout' => 100, 'body' => [ 'data' => $data ] ] );
        return $response;
  	}
}
