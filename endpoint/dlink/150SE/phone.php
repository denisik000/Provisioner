<?php
/**
 * Norphonic Traphone Phone File
 **
 * @author Thord Matre
 * @license MPL / GPLv2 / LGPL
 * @package Provisioner
 */
class endpoint_dlink_150se_phone extends endpoint_dlink_base {
	public $family_line = 'DPH-150SE';

	function parse_lines_hook($line_data, $line_total) {

		if(isset($line_data['secret'])){
			$line_data['srv_auth']="1";
		} else {
			$line_data['srv_auth']="0";
		}
		
		return($line_data);
	}

	function prepare_for_generateconfig() {
		$this->settings['version']="2.1".str_pad(time(), 10, "0", STR_PAD_LEFT);
		parent::prepare_for_generateconfig();
	}
}
