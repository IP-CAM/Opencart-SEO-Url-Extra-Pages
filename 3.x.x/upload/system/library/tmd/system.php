<?php
class Tmd {
private $config;
private $session;
private $db;
	
	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');

		
	}

	public function loadkeyform($data) {
		if(!empty($this->session->data['token'])){
			$token='&token='.$this->session->data['token'];
		}
		if(!empty($this->session->data['user_token'])){
			$token='&user_token='.$this->session->data['user_token'];
		}
		$regkey= $this->config->get($data['code']);
		$url = 'https://www.opencartextensions.in/index.php?route=api/newkey&foldername='.$data['route'].'&regkey='.$regkey.$token;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		return $response = curl_exec($curl);
	}

	public function matchkey($data) {
			$json=array();
			if(empty($data['moduledata_key'])) {
				$json['error']['moduledata_key']= 'Add License Key';
			}
			if(empty($json['error'])) {	
			$tmd_extensiondata= array(
			   'extension_id' => base64_decode($data['eid']),
			   'email' => $this->config->get('config_email'),
			   'store_url' => HTTP_CATALOG,
			   'module_key' =>$data['moduledata_key'],
			);
		
		$url = 'https://www.opencartextensions.in/index.php?route=api/chklicence';
		
		$curl = curl_init($url);
	
		//curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($tmd_extensiondata, '', '&'));
		
		 $response = curl_exec($curl);
		 $status=json_decode($response,true);
			if($status['status']) {
				$keydata=array();
				$keydata[$data['code']]=$data['moduledata_key'];
				$this->editSetting('tmdkey',$keydata);
				$json['success'] = '<div class="success_heading"><i class="fa fa-check-circle" aria-hidden="true"></i>  Success: your License Key has submit successfully!</div>';
				
				} else {
				$json['warningmsg'] = '<div class="warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warring: License Key Information does not Match!</div';	
				}			
			} 
		return $json;			
	}
	public function getkey($config) {
		return $this->config->get($config);
	}
	private function editSetting($code, $data, $store_id = 0) {
		
		foreach ($data as $key => $value) {
			if (substr($key, 0, strlen($code)) == $code) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value, true)) . "', serialized = '1'");
				}
			}
		}
	}

	
}
