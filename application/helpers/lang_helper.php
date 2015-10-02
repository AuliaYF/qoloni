<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('lang')){
	function lang($key){
		$ci =& get_instance();
		echo $ci->lang->line($key);
	}
}