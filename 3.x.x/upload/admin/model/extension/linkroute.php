<?php
class ModelExtensionLinkroute extends Model {

	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."linkroute` (
		  `linkroute_id` int(11) NOT NULL AUTO_INCREMENT,
		  `route` varchar(255) NOT NULL,		  
		  PRIMARY KEY (`linkroute_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."title` (
		  `title_id` int(11) NOT NULL AUTO_INCREMENT,
		  `linkroute_id` int(11) NOT NULL,
		  PRIMARY KEY (`title_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."title_description` (
		  `title_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `title` varchar(255)  NOT NULL,
		  `meta_description` varchar(255)  NOT NULL,
		  `meta_keyword` varchar(255)  NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."linkroute_description` (
		  `linkroute_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `name` varchar(64) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	}

	public function uninstall() {
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "linkroute`");
		if(!empty($query->row['linkroute_id'])){
			foreach($query->rows as $row)
			{
				$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `query` = '" . $this->db->escape($row['route']) . "'");
			}
		}
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."linkroute`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."title`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."title_description`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."linkroute_description`");
		
	}

	public function addlinkroute($data) {
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "linkroute` SET  `route` = '" . $this->db->escape($data['route']) . "'");

		$linkroute_id = $this->db->getLastId();

		foreach ($data['linkroute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "linkroute_description` SET `linkroute_id` = '" . (int)$linkroute_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {

						$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `query` = '" . $this->db->escape($data['route']) . "' ,`language_id` = '" . (int)$language_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}

		return $linkroute_id;
	}

	public function editlinkroute($linkroute_id, $data) {
		
		$this->db->query("UPDATE `" . DB_PREFIX . "linkroute` SET `route` = '" . $this->db->escape($data['route']) . "' WHERE linkroute_id = '" . (int)$linkroute_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "linkroute_description` WHERE `linkroute_id` = '" . (int)$linkroute_id . "'");

		foreach ($data['linkroute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "linkroute_description` SET `linkroute_id` = '" . (int)$linkroute_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `query` = '" . $this->db->escape($data['route']) . "'");

		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {

						$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `query` = '" . $this->db->escape($data['route']) . "' ,`language_id` = '" . (int)$language_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");

					}
				}
			}
		}
	}

	public function deletelinkroute($linkroute_id) {

		$value=$this->getlinkroute($linkroute_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "linkroute WHERE linkroute_id = '" . (int)$linkroute_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "linkroute_description WHERE linkroute_id = '" . (int)$linkroute_id . "'");
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `query` = '" . $this->db->escape($value['route']) . "'");
	
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
			];
		}

		return $download_description_data;
	}


	public function getlinkrouteSeoUrls(int $linkroute_id): array {
		$linkroute_description_data = array();

		$value=$this->getlinkroute($linkroute_id);
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($value['route']) . "'");

		foreach ($query->rows as $result) {
			$linkroute_description_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $linkroute_description_data;

	}
	
	public function getlinkroutebyid($linkroute_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "linkroute_description where linkroute_id = '" . (int)$linkroute_id . "'AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

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