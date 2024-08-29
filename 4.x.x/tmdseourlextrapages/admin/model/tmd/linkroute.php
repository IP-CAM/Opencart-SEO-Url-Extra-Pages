<?php
namespace Opencart\Admin\Model\Extension\Tmdseourlextrapages\TMD;
class linkroute extends \Opencart\System\Engine\Model {

	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."linkroute` (
		  `linkroute_id` int(11) NOT NULL AUTO_INCREMENT,
		  `status` varchar(255) NOT NULL,
		  PRIMARY KEY (`linkroute_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."title` (
		  `title_id` int(11) NOT NULL AUTO_INCREMENT,
		  `linkroute_id` int(11) NOT NULL,
		  PRIMARY KEY (`title_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."title_description` (
		  `title_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `title` varchar(255)  NOT NULL,
		  `meta_description` varchar(255)  NOT NULL,
		  `meta_keyword` varchar(255)  NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."linkroute_description` (
		  `linkroute_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `name` varchar(64) NOT NULL,
		  `route` varchar(64) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");


	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."linkroute`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."title`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."title_description`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."linkroute_description`");
	}

	public function addlinkroute($data) {
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "linkroute SET  status = '" . $this->db->escape($data['status']) . "' ");

		$linkroute_id = $this->db->getLastId();

		foreach ($data['linkroute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "linkroute_description` SET `linkroute_id` = '" . (int)$linkroute_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "' ,`route` = '" . $this->db->escape($value['route']) . "'");
		}

		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {

					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `key` = 'route' ,`language_id` = '" . (int)$language_id . "', `value` = '" . $this->db->escape($value['route']) . "', `keyword` = '" . $this->db->escape($keyword) . "'");

					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `key` = 'linkroute_id',`language_id` = '" . (int)$language_id . "', `value` = '" . (int)$linkroute_id . "', `keyword` = '" . $this->db->escape($keyword) . "',`sort_order` = '15'");
				}
			}
		}	

		return $linkroute_id;
	}

	public function editlinkroute($linkroute_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "linkroute SET status = '" . $this->db->escape($data['status']) . "' WHERE linkroute_id = '" . (int)$linkroute_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "linkroute_description` WHERE `linkroute_id` = '" . (int)$linkroute_id . "'");

		foreach ($data['linkroute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "linkroute_description` SET `linkroute_id` = '" . (int)$linkroute_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "' ,`route` = '" . $this->db->escape($value['route']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'linkroute_id' AND `value` = '" . (int)$linkroute_id . "'");


		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {

					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `key` = 'route' ,`language_id` = '" . (int)$language_id . "', `value` = '" . $this->db->escape($value['route']) . "', `keyword` = '" . $this->db->escape($keyword) . "'");

					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `key` = 'linkroute_id',`language_id` = '" . (int)$language_id . "', `value` = '" . (int)$linkroute_id . "', `keyword` = '" . $this->db->escape($keyword) . "',`sort_order` = '15'");
				}
			}
		}	
	}

	public function deletelinkroute($linkroute_id) {
		
		$route=$this->getlinkroutebyid($linkroute_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "linkroute WHERE linkroute_id = '" . (int)$linkroute_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "linkroute_description WHERE linkroute_id = '" . (int)$linkroute_id . "'");
				
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route' AND `value` = '" . $this->db->escape($route['route']) . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'linkroute_id' AND `value` = '" . (int)$route['linkroute_id'] . "'");
	
	}

	public function getlinkroute($linkroute_id) {
	
		$query = $this->db->query("SELECT DISTINCT *  FROM " . DB_PREFIX . "linkroute  WHERE `linkroute_id` = '" . (int)$linkroute_id . "'");

		return $query->row;
	}

	public function getDescriptions(int $linkroute_id): array {
		$download_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "linkroute_description` WHERE `linkroute_id` = '" . (int)$linkroute_id . "'");

		foreach ($query->rows as $result) {
			$download_description_data[$result['language_id']] = [
				'name' => $result['name'],
				'route' => $result['route'],
			];
		}

		return $download_description_data;
	}


	public function getStores(int $linkroute_id): array {
		$product_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'linkroute_id' AND `value` = '" . (int)$linkroute_id . "'");

		foreach ($query->rows as $result) {
			$product_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $product_seo_url_data;

	}
	
	public function getlinkroutebyid($linkroute_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "linkroute_description where linkroute_id = '" . (int)$linkroute_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	public function getlinkquery($linkroute_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "linkroute where linkroute_id = '" . (int)$linkroute_id . "'");

		return $query->row;
	}

	public function getlinkroutes($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "linkroute` d LEFT JOIN `" . DB_PREFIX . "linkroute_description` dd ON (d.`linkroute_id` = dd.`linkroute_id`) WHERE dd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'dd.name',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY dd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTotallinkroutes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "linkroute");

		return $query->row['total'];
	}
}