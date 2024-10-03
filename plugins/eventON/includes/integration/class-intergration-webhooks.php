<?php 
/**
 * EventON webhooks integration	
 * @version 4.5.9
 */

class EVO_WebHooks{
	function __construct(){
		add_filter('eventon_settings_3rdparty', array($this, 'settings'),10,1);
		add_action( 'wp_ajax_evo_webhook_settings', array( $this, 'ajax_webhook_settings' ) );
		add_action( 'wp_ajax_evo_webhook_delete', array( $this, 'ajax_webhook_delete' ) );
		add_action( 'wp_ajax_evo_webhook_settings_save', array( $this, 'ajax_webhook_settings_save' ) );
	}

	public function send_webhook($webhook_url, $data_array){

		$request = array(
			'body'=> $data_array,			
			'timeout'=> 30,
		);

		
		$result = wp_remote_post($webhook_url,$request);
		if ( $result['response']['code'] == 200 ) {
	        return array( 1 );
	    } else {
	        return array( 0, $result )  ;
	    }
	}

// admin
	public function settings($A){
		$B = array(
			array('type'=>'sub_section_open','name'=>__('Webhooks [BETA]','eventon')),
			array('id'=>'note',
				'type'=>'note',
				'name'=>'Create webhooks from EventON for these platforms: zapier, IFTTT, Integromat, Automate.io, Built.io, Workato, elastic.io, APIANT, Webhook',				
			),
			array('id'=>'evcal__note','type'=>'customcode','code'=>$this->webhookz_code()),			
			array('type'=>'sub_section_close'),
		);

		return array_merge($A, $B);
	}

	// 4.5.9
	public function ajax_webhook_settings(){
		
		$HELP = new evo_helper();
		$post = $HELP->sanitize_array($_POST);

		if( isset($post['id'])){
			$hook_data = $this->get_hook_data($post['id']);
		}else{
			$hook_data = array('id'=>  wp_rand(10000,99999) );
		}
		
		ob_start();	

		$triggers = $this->get_trigger_events();

		?>
		<div style='padding:20px;'>
			<form class='evo_webhook_settings'>
				<input type="hidden" name="id" value='<?php echo $hook_data['id'];?>'/>
				<input type="hidden" name="action" value='evo_webhook_settings_save'/>
				<?php wp_nonce_field( plugin_basename( __FILE__ ), 'evowh_noncename' );?>
				<p class='evo_elm_row'><?php _e('Webhook ID','eventon');?>: <span><?php echo $hook_data['id'];?></span></p>	
				<?php 

				if( count($triggers)<1):
					echo "<p>". __('You do not have valid trigger points avialable yet.','eventon') ."</p>";
				else:

				echo EVO()->elements->process_multiple_elements(
					array(
						array(
							'type'=>'dropdown',
							'field_class'=>'wh_trigger_point',
							'id'=>'trig',
							'value'=>	(isset($hook_data['trig']) ? $hook_data['trig']: ''),
							'name'=> __('Select available EventON trigger points to pass values to webhook','eventon'),
							'options'=> $triggers
						),array(
							'type'=>'text',
							'id'=>'url',
							'value'=>	(isset($hook_data['url']) ? $hook_data['url']: ''),
							'name'=> __('Webhook URL','eventon'),							
						)
					)
				);
			?>	
				
				<div class='evo_elm_row'>
					<p class='evo_field_label'><?php _e('Fields passed on to webhook','eventon');?></p>
					<p class='evo_field_container evo_whdata_fields'  style='font-style: italic;' data-d=''>-</p>					
				</div>
				<?php 

				// data to fill fields passed on to the webhook @since 4.5
				$webhook_data = array( 'whdata' => apply_filters('evo_webhooks_data', array() ) );
				
				$save_btn_data = array(
					'd'=> array(						
						'uid'=> 'evo_save_webhook_data',
						'lightbox_key'=>'evo_webhooks_config',
						'hide_lightbox'=>2000,
						'load_new_content'=>true,
						'load_new_content_id'=>'evowhs_container',
					)
				);

				?>
				<div class='evo_elm_webhooks_data' <?php echo $HELP->array_to_html_data( $webhook_data );?>></div>
				<p><span class='evo_btn save_webhook_config evo_submit_form' <?php echo $HELP->array_to_html_data( $save_btn_data );?> style='margin-right: 10px'><?php _e('Save Changes','eventon');?></span></p>	

			<?php endif;?>
			</form>
		</div>
		<?php 

		wp_send_json(array(
			'status'=>'good','content'=> ob_get_clean()
		));wp_die();
	}
 	
 	function ajax_webhook_delete(){
		$HELP = new evo_helper();
		$post = $HELP->sanitize_array($_POST);

		if(!isset( $post['id'] )){
			echo json_encode(array('status'=>'bad','msg'=> __('Missing webhook ID')	));exit;
		}

		$webhooks = $this->get_hook_data();
		if( !isset($webhooks[ $post['id'] ])) return;

		unset($webhooks[ $post['id'] ]);

		EVO()->cal->set_cur('evcal_1');
		EVO()->cal->set_prop('evowhs', $webhooks );

		echo json_encode(array('status'=>'good',
			'msg'=> __('Successfully saved webhook data'),
			'html'=> $this->get_webhooks_html()
		));exit;

	}

	// plug for adding trigger points
	function get_trigger_events(){
		return apply_filters('evo_webhook_triggers',
			array()
		);
	}


	// save values
		public function ajax_webhook_settings_save(){
			$HELP = new evo_helper();
			$post = $HELP->sanitize_array($_POST);

			if(!isset( $post['id'] )){
				echo json_encode(array('status'=>'bad','msg'=> __('Missing webhook ID')	));exit;
			}

			$webhooks = $this->get_hook_data();
			if(!$webhooks) $webhooks = array();

			$hook_id = (int)$post['id'];

			foreach(array('trig','url') as $valid_field){
				if(!isset( $post[ $valid_field ] )) continue;
				$webhooks[ $hook_id ][$valid_field] =  $post[ $valid_field ];
			}

			EVO()->cal->set_cur('evcal_1');
			EVO()->cal->set_prop('evowhs', $webhooks );

			echo json_encode(array('status'=>'good',
				'msg'=> __('Successfully saved webhook data'),
				'content'=> "<div id='evowhs_container'>" . $this->get_webhooks_html() ."</div>"
			));exit;
		}

	// return a list of all webhooks nicely
		public function get_webhooks_html(){
			$webhooks = $this->get_hook_data();

			$OUT = '';

			if($webhooks){
				$HELP = new evo_helper();
				$available_hooks = $this->get_trigger_events();

				foreach($webhooks as $id=>$data){
					$name = isset($available_hooks[ $data[ 'trig' ]]) ? $available_hooks[ $data[ 'trig' ]]: $data[ 'trig' ];
					$url = isset($data[ 'url' ]) ? $data[ 'url' ] : '-';
					$data = array(
						'lbvals'=>array(
							'lbc'=>'evo_webhooks_config',
							'uid'=>'evo_webhook_config',
							't'=> __('Configure Webhooks'),	
							'd'=> array( 'action'=>'evo_webhook_settings','id'=> $id),
							'lightbox_loader'=> true,
							'ajax'=>'yes'
						)
					);

					$OUT .= "<p data-id='{$id}'><span>{$id}</span><span>{$name}</span><span>{$url}</span><em><i class='fa fa-pencil evowh_edit evolb_trigger' ". $HELP->array_to_html_data($data) ."></i><i class='evowh_del fa fa-minus-circle'></i></em></p>";
				}
			}else{
				$OUT .= "<p data-id=''>".__('No webhooks created yet')."</p>";
			}

			return $OUT;
		}

	// codes for settings
	public function webhookz_code(){
		
		ob_start();
		
		$HELP = new evo_helper();
		?>
		<div id='evowhs_container'><?php echo $this->get_webhooks_html();?></div>

		<p><?php EVO()->elements->print_trigger_element(array(
				'title'=> __('Create a new webhook connection'),
				'dom_element'=> 'span',
				'uid'=>'evo_webhook_config',
				'lb_class' =>'evo_webhooks_config',
				'lb_title'=> __('Configure Webhooks'),	
				'lb_loader'=>true,
				'ajax_data'=>array(
					'action'=>'evo_webhook_settings'
				),
			),'trig_lb');?></p>
		
		<?php

		return ob_get_clean();
	}

	public function get_hook_data($id = ''){
		$whs = EVO()->cal->get_prop('evowhs','evcal_1');

		if(!empty($id)){
			if(isset($whs[ $id ])){
				$whs[$id]['id']= $id;
				return $whs[$id];
			} 
		}
		return $whs;
	}

	// @since 4.2.1
	public function is_hook_active($hook_trig_key){
		$whs = EVO()->cal->get_prop('evowhs','evcal_1');

		if($whs && is_array($whs)){
			foreach($whs as $hook_id=>$data){
				if(!isset( $data['trig'] )) continue;
				if(!isset( $data['url'] )) continue;
				if( $data['trig'] == $hook_trig_key ){
					return $data['url'];
				}
			}
		}
		return false;

	}
	
}