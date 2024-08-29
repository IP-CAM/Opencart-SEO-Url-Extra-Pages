<?php
namespace Opencart\Admin\Controller\Extension\Tmdseourlextrapages\TMD;
use \Opencart\System\Helper as Helper;
class linkroute extends \Opencart\System\Engine\Controller {

	public function index(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/linkroute');

		$this->document->setTitle($this->language->get('heading_title1'));

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/tmdseourlextrapages/tmd/linkroute', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if(VERSION>='4.0.2.0'){
			$data['add'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute.form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute.delete', 'user_token=' . $this->session->data['user_token']);
		}
		else{
			$data['add'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute|form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute|delete', 'user_token=' . $this->session->data['user_token']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdseourlextrapages/tmd/linkroute', $data));
	}

	public function list(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/linkroute');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'dd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if(VERSION>='4.0.2.0'){
			$data['action'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute.list', 'user_token=' . $this->session->data['user_token'] . $url);
		}
		else{
			$data['action'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute|list', 'user_token=' . $this->session->data['user_token'] . $url);
		}

		$data['linkroutes'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');

		$manufacturer_total = $this->model_extension_tmdseourlextrapages_tmd_linkroute->getTotallinkroutes($filter_data);

		$results = $this->model_extension_tmdseourlextrapages_tmd_linkroute->getlinkroutes($filter_data);

		foreach ($results as $result) {

			if(VERSION>='4.0.2.0'){
				$data['linkroutes'][] = [
					'linkroute_id' => $result['linkroute_id'],
					'name'         => $result['name'],
					'route'  	   => $result['route'],
					'edit'         => $this->url->link('extension/tmdseourlextrapages/tmd/linkroute.form', 'user_token=' . $this->session->data['user_token'] . '&linkroute_id=' . $result['linkroute_id'] . $url)
				];
			}
			else{
				$data['linkroutes'][] = [
					'linkroute_id' => $result['linkroute_id'],
					'name'         => $result['name'],
					'route'  	   => $result['route'],
					'edit'         => $this->url->link('extension/tmdseourlextrapages/tmd/linkroute|form', 'user_token=' . $this->session->data['user_token'] . '&linkroute_id=' . $result['linkroute_id'] . $url)
				];				
			}
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if(VERSION>='4.0.2.0'){
			$data['sort_name'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute.list', 'user_token=' . $this->session->data['user_token'] . '&sort=dd.name' . $url);
		}
		else{
			$data['sort_name'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute|list', 'user_token=' . $this->session->data['user_token'] . '&sort=dd.name' . $url);
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if(VERSION>='4.0.2.0'){
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $manufacturer_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdseourlextrapages/tmd/linkroute.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);
		}
		else{
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $manufacturer_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdseourlextrapages/tmd/linkroute|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);	
		}		

		$data['results'] = sprintf($this->language->get('text_pagination'), ($manufacturer_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($manufacturer_total - $this->config->get('config_pagination_admin'))) ? $manufacturer_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $manufacturer_total, ceil($manufacturer_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('extension/tmdseourlextrapages/tmd/linkroute_list', $data);
	}

	public function form(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/linkroute');

		$this->document->setTitle($this->language->get('heading_title1'));

		$data['text_form'] = !isset($this->request->get['linkroute_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdseourlextrapages/tmd/linkroute', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if(VERSION>='4.0.2.0'){
			$data['save'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute.save', 'user_token=' . $this->session->data['user_token']);
		}
		else{
			$data['save'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute|save', 'user_token=' . $this->session->data['user_token']);
		}



		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['back'] = $this->url->link('extension/tmdseourlextrapages/tmd/linkroute', 'user_token=' . $this->session->data['user_token'] . $url);
		$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');

		if (isset($this->request->get['linkroute_id'])) {
			$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');

			$linkroute_info = $this->model_extension_tmdseourlextrapages_tmd_linkroute->getlinkroute($this->request->get['linkroute_id']);
		}

		if (isset($this->request->get['linkroute_id'])) {
			$linkroute_id = (int)$this->request->get['linkroute_id'];
		}  else {
			$linkroute_id = 0;
		}

		if (isset($this->request->get['linkroute_id'])) {
			$data['linkroute_id'] = (int)$this->request->get['linkroute_id'];
		} else {
			$data['linkroute_id'] = 0;
		}
		
		if (isset($linkroute_info['status'])) {
			$data['status'] = (int)$linkroute_info['status'];
		} else {
			$data['status'] = 0;
		}

		if (isset($linkroute_id)) {
			$data['linkroute_description'] = $this->model_extension_tmdseourlextrapages_tmd_linkroute->getDescriptions($linkroute_id);
		} else {
			$data['linkroute_description'] = [];
		}

		
		$data['stores'] = [];
		
		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');
		$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');


		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		if ($linkroute_id) {
			$data['product_seo_url'] = $this->model_extension_tmdseourlextrapages_tmd_linkroute->getStores($linkroute_id);
		} else {
			$data['product_seo_url'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdseourlextrapages/tmd/linkroute_form', $data));
	}

	public function save(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/linkroute');

		$json = [];

			if (!$this->user->hasPermission('modify', 'extension/tmdseourlextrapages/tmd/linkroute')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

		if(VERSION>='4.0.2.0'){
			// if ((oc_strlen($this->request->post['name']) < 1) || (oc_strlen($this->request->post['name']) > 64)) {
			// 	$json['error']['name'] = $this->language->get('error_name');
			// }

			foreach ($this->request->post['linkroute_description'] as $language_id => $value) {

				if ((oc_strlen(trim($value['name'])) < 3) || (oc_strlen($value['name']) > 64)) {
					$json['error']['name_' . $language_id] = $this->language->get('error_name');
				}

				// if ((oc_strlen(trim($value['routevalue'])) < 3) || (oc_strlen($value['routevalue']) > 64)) {
				// 	$json['error']['route_' . $language_id] = $this->language->get('error_route');
				// }
			}

			// if ((oc_strlen($this->request->post['route']) < 1) || (oc_strlen($this->request->post['route']) > 64)) {
			// 	$json['error']['route'] = $this->language->get('error_route');
			// }
		}
		else{

			foreach ($this->request->post['linkroute_description'] as $language_id => $value) {
				if ((Helper\Utf8\strlen(trim($value['name'])) < 3) || (Helper\Utf8\strlen($value['name']) > 64)) {
					$json['error']['name_' . $language_id] = $this->language->get('error_name');
				}

				// if ((Helper\Utf8\strlen(trim($value['route'])) < 3) || (Helper\Utf8\strlen($value['route']) > 64)) {
				// 	$json['error']['route_' . $language_id] = $this->language->get('error_route');
				// }
			}

			// if ((Helper\Utf8\strlen(trim($this->request->post['route'])) < 1) || (Helper\Utf8\strlen($this->request->post['route']) > 64)) {
			// 	$json['error']['route'] = $this->language->get('error_route');
			// }			
		}


		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');

			if (!$this->request->post['linkroute_id']) {
				$json['linkroute_id'] = $this->model_extension_tmdseourlextrapages_tmd_linkroute->addlinkroute($this->request->post);
			} else {
				$this->model_extension_tmdseourlextrapages_tmd_linkroute->editlinkroute($this->request->post['linkroute_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/linkroute');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'extension/tmdseourlextrapages/tmd/linkroute')) {
			$json['error'] = $this->language->get('error_permission');
		}

	
		if (!$json) {
			$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');

			foreach ($selected as $linkroute_id) {
				$this->model_extension_tmdseourlextrapages_tmd_linkroute->deletelinkroute($linkroute_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');

			$filter_data = [
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			];

			$results = $this->model_extension_tmdseourlextrapages_tmd_linkroute->getlinkroutes($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'linkroute_id' => $result['linkroute_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				];
			}
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
