<?php
namespace Opencart\Catalog\Model\Extension\Tmdseourlextrapages\TMD;
class linkroute extends \Opencart\System\Engine\Model {
	/*TMD*/
	public function getRoute($route): array {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "linkroute_description WHERE route='".$this->db->escape((string)$route)."'AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getRouteTitle($linkroute_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "title t LEFT JOIN " . DB_PREFIX . "title_description td ON (t.title_id = td.title_id) WHERE t.linkroute_id='".(int)$linkroute_id."' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	/*TMD*/
}