<?php
/**
 * EventON Settings Tab for addons and licensing
 * @version 4.5.2
 */

global $ajde, $eventon;

$views = new EVO_Views();
?>

<style type="text/css">
	.ajde_settings.evcal_4 .evo_settings_header{margin-left: -20px;}
</style>
<div class=''>
	<p><a href='https://docs.myeventon.com/documentations/can-download-addon-updates/' class='evo_admin_link_btn' target='_blank'><?php _e('How to update EventON addons to latest version','eventon');?></a>  <br/><a style=''href='https://docs.myeventon.com/documentations/update-eventon/' target='_blank' class='evo_admin_link_btn'><?php _e('How to update EventON Manually','eventon');?></a></p>
</div>
<div id="evcal_4" class="postbox evcal_admin_meta curve" style='overflow: hidden'>	
	<?php
		
		// UPDATE eventon addons list
		EVO_Prods()->update_addons();	
		EVO_Prods()->debug_remote_data();

	?>
	

	<div class='evo_addons_page addons'>		
		<?php

			$admin_url = admin_url();
			$show_license_msg = true;

			echo $views->get_html('evo_box');

		?>
		<?php // ADDONS 			
			global $wp_version; 
		?>				
			<div id='evo_addons_list'></div>
		<div class="clear"></div>
	</div>
	<?php
		// Throw the output popup box html into this page	
		EVO()->lightbox->admin_lightbox_content(array('content'=>"<p class='evo_loader'></p>", 'type'=>'padded'));
	?>
</div>