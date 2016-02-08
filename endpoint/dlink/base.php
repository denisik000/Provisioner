<?PHP
/**
 * D-Link Base File
 *
 * @author Andrew Nagy
 * @modified for DLink phones by denisik@gmail.com
 * @license MPL / GPLv2 / LGPL
 * @package Provisioner
 */
class endpoint_dlink_base extends endpoint_base {

	public $brand_name = 'D-Link';

	function prepare_for_generateconfig() {
		//To upper case letters
		$this->mac = strtoupper($this->mac);
		parent::prepare_for_generateconfig();
		$this->config_file_replacements['$mac']=strtoupper($this->mac);
		$this->config_file_replacements['$model']=strtoupper($this->model);
	}
    function reboot() {
        if (($this->engine == "asterisk") AND ($this->system == "unix")) {
            if ($this->family_line == "traphone") {
                exec($this->engine_location . " -rx 'sip show peers like " . $this->settings['line'][0]['username'] . "'", $output);
                if (preg_match("/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/", $output[1], $matches)) {
                    $ip = $matches[0];
                    //Not tested yet
                $pass = (isset($this->settings['websecret']) ? $this->settings['websecret'] : 'admin');

                if (function_exists('curl_init')) {
                    $ckfile = tempnam($this->sys_get_temp_dir(), "GSCURLCOOKIE");
                    $ch = curl_init('http://' . $ip . '/reset.htm');
                    curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);

                    $data = array(
                        'password' => $pass,
                        'username' => 'admin',
                        'encoded' => '',
			'nonce'=>'',
			'goto'=>'',
			'URL'=>'/reset.htm'
                    );

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    $output = curl_exec($ch);
                    $info = curl_getinfo($ch);
                    curl_close($ch);

                    $ch = curl_init("http://" . $ip . "/reset.htm");
                    curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $output = curl_exec($ch);
                    curl_close($ch);
                }

                }
            }
        }
    }

}
