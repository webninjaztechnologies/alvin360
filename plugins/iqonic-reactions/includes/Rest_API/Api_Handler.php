<?php

namespace IR\Rest_API;


class Api_Handler{

	public function init() {
		$api_folder_path = IQONIC_REACTION_PATH . 'includes/Rest_API/API/';
		$dir = scandir($api_folder_path);
		
		if (count($dir)) {
			foreach ($dir as $controller_name) {
				if ($controller_name !== "." && $controller_name !== "..") {
					$controller_name = explode( ".", $controller_name)[0];
					if( $controller_name != '' ) {
						$this->call($controller_name);
					}
				}
			}
		}
	}

	public function call($controllerName) {
		$controller = 'IR\\Rest_API\\API\\' . $controllerName;
		(new $controller);
	}

}