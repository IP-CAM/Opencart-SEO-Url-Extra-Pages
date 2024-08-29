<?php
namespace Opencart\Admin\Model\Extension\Tmdseourlextrapages\TMD;
class Title extends \Opencart\System\Engine\Model {
	
	public function addtitle($data) {		

		$this->db->query("INSERT INTO " . DB_PREFIX . "title SET linkroute_id = '" . (int) ($data['route_id']) . "' ");

		$title_id = $this->db->getLastId();

		foreach ($data['title_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "title_description SET title_id = '" . (int)$title_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}		

		return $title_id;
	}

	public function edittitle($title_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "title SET linkroute_id = '" . (int) ($data['route_id']) . "' WHERE title_id = '" . (int)$title_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "title_description WHERE title_id = '" . (int)$title_id . "'");

		foreach ($data['title_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "title_description SET title_id = '" . (int)$title_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}		
	}

	public function deletetitle($title_id) {	

		$this->db->query("DELETE FROM " . DB_PREFIX . "title WHERE title_id = '" . (int)$title_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "title_description WHERE title_id = '" . (int)$title_id . "'");		
	}

	public function gettitle($title_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "title d LEFT JOIN " . DB_PREFIX . "title_description dd ON (d.title_id = dd.title_id) WHERE d.title_id = '" . (int)$title_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function gettitles($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "title d LEFT JOIN " . DB_PREFIX . "title_description dd ON (d.title_id = dd.title_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'dd.title_id',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY dd.title_id";
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

	public function gettitleDescriptions($title_id) {
		$title_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "title_description WHERE title_id = '" . (int)$title_id . "'");

		foreach ($query->rows as $result) {
			$title_description_data[$result['language_id']] = array(
			
			'title' => $result['title'],
			'meta_description' => $result['meta_description'],
			'meta_keyword'     => $result['meta_keyword'],
			);
		}

		return $title_description_data;
	}

	public function getTotaltitles() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "title");

		return $query->row['total'];
	}
}