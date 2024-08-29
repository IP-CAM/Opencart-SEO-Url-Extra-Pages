<?php
namespace Opencart\Admin\Controller\Extension\Tmdseourlextrapages\TMD;
use \Opencart\System\Helper as Helper;
class title extends \Opencart\System\Engine\Controller {

	public function index(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/title');

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
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/tmdseourlextrapages/tmd/title', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if(VERSION>='4.0.2.0'){

			$data['add'] = $this->url->link('extension/tmdseourlextrapages/tmd/title.form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdseourlextrapages/tmd/title.delete', 'user_token=' . $this->session->data['user_token']);
		}
		else{

			$data['add'] = $this->url->link('extension/tmdseourlextrapages/tmd/title|form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdseourlextrapages/tmd/title|delete', 'user_token=' . $this->session->data['user_token']);
			
		}


		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdseourlextrapages/tmd/title', $data));
	}

	public function list(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/title');

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
			$data['action'] = $this->url->link('extension/tmdseourlextrapages/tmd/title.list', 'user_token=' . $this->session->data['user_token'] . $url);
		}
		else{
			$data['action'] = $this->url->link('extension/tmdseourlextrapages/tmd/title|list', 'user_token=' . $this->session->data['user_token'] . $url);
		}

		$data['titles'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('extension/tmdseourlextrapages/tmd/title');

		$manufacturer_total = $this->model_extension_tmdseourlextrapages_tmd_title->getTotaltitles();

		$results = $this->model_extension_tmdseourlextrapages_tmd_title->gettitles($filter_data);

		foreach ($results as $result) {

			$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');
			$route = $this->model_extension_tmdseourlextrapages_tmd_linkroute->getlinkroutebyid($result['linkroute_id']);

			if (isset($route['route'])) {
				$routevalue = $route['route'];
			} else {
				$routevalue = '';
			}
			
			if(VERSION>='4.0.2.0'){
				$data['titles'][] = [
					'title_id' => $result['title_id'],
					'title'        => $result['title'],
					'metadesc'        => $result['meta_description'],
					'metakeyword'        => $result['meta_keyword'],
					'route'        => $routevalue,
					'edit'         => $this->url->link('extension/tmdseourlextrapages/tmd/title.form', 'user_token=' . $this->session->data['user_token'] . '&title_id=' . $result['title_id'] . $url)
				];
			}
			else{
				$data['titles'][] = [
					'title_id' => $result['title_id'],
					'title'        => $result['title'],
					'metadesc'        => $result['meta_description'],
					'metakeyword'        => $result['meta_keyword'],
					'route'        => $routevalue,
					'edit'         => $this->url->link('extension/tmdseourlextrapages/tmd/title|form', 'user_token=' . $this->session->data['user_token'] . '&title_id=' . $result['title_id'] . $url)
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
			$data['sort_name'] = $this->url->link('extension/tmdseourlextrapages/tmd/title.list', 'user_token=' . $this->session->data['user_token'] . '&sort=dd.title_id' . $url);
		}
		else{
			$data['sort_name'] = $this->url->link('extension/tmdseourlextrapages/tmd/title.list', 'user_token=' . $this->session->data['user_token'] . '&sort=dd.title_id' . $url);
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
				'url'   => $this->url->link('extension/tmdseourlextrapages/tmd/title.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);
		}
		else{
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $manufacturer_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdseourlextrapages/tmd/title|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);			
		}


		$data['results'] = sprintf($this->language->get('text_pagination'), ($manufacturer_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($manufacturer_total - $this->config->get('config_pagination_admin'))) ? $manufacturer_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $manufacturer_total, ceil($manufacturer_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('extension/tmdseourlextrapages/tmd/title_list', $data);
	}

	public function form(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/title');

		$this->document->setTitle($this->language->get('heading_title1'));

		$data['text_form'] = !isset($this->request->get['title_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('extension/tmdseourlextrapages/tmd/title', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if(VERSION>='4.0.2.0'){
			$data['save'] = $this->url->link('extension/tmdseourlextrapages/tmd/title.save', 'user_token=' . $this->session->data['user_token']);
		}
		else{
			$data['save'] = $this->url->link('extension/tmdseourlextrapages/tmd/title|save', 'user_token=' . $this->session->data['user_token']);			
		}

		$data['back'] = $this->url->link('extension/tmdseourlextrapages/tmd/title', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['title_id'])) {
			$this->load->model('extension/tmdseourlextrapages/tmd/title');

			$title_info = $this->model_extension_tmdseourlextrapages_tmd_title->gettitle($this->request->get['title_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['title_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$title_info = $this->model_extension_tmdseourlextrapages_tmd_title->gettitle($this->request->get['title_id']);
		}

		if (isset($this->request->get['title_id'])) {
			$data['title_id'] = $this->request->get['title_id'];
		} else {
			$data['title_id'] = 0;
		}

		if (isset($this->request->post['title_description'])) {
			$data['title_description'] = $this->request->post['title_description'];
		} elseif (isset($this->request->get['title_id'])) {
			$data['title_description'] = $this->model_extension_tmdseourlextrapages_tmd_title->gettitleDescriptions($this->request->get['title_id']);
		} else {
			$data['title_description'] = array();
		}
		
		$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');

		$data['routes'] = $this->model_extension_tmdseourlextrapages_tmd_linkroute->getlinkroutes();

		if (isset($this->request->post['route_id'])) {
			$data['route_id'] = $this->request->post['route_id'];
		} elseif (!empty($title_info)) {
			$data['route_id'] = $title_info['linkroute_id'];
		} else {
			$data['route_id'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdseourlextrapages/tmd/title_form', $data));
	}

	public function save(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/title');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdseourlextrapages/tmd/title')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['title_description'] as $language_id => $value) {
			if(VERSION>='4.0.2.0'){
				if ((oc_strlen(trim($value['title'])) < 1) || (oc_strlen($value['title']) > 255)) {
					$json['error']['title_' . $language_id] = $this->language->get('error_title');
				}				
			}
			else{
				if ((Helper\Utf8\strlen(trim($value['title'])) < 1) || (Helper\Utf8\strlen($value['title']) > 255)) {
					$json['error']['title_' . $language_id] = $this->language->get('error_title');
				}
			}
		}	

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('extension/tmdseourlextrapages/tmd/title');

			if (!$this->request->post['title_id']) {
				$json['title_id'] = $this->model_extension_tmdseourlextrapages_tmd_title->addtitle($this->request->post);
			} else {
				$this->model_extension_tmdseourlextrapages_tmd_title->edittitle($this->request->post['title_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('extension/tmdseourlextrapages/tmd/title');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'extension/tmdseourlextrapages/tmd/title')) {
			$json['error'] = $this->language->get('error_permission');
		}

	
		if (!$json) {
			$this->load->model('extension/tmdseourlextrapages/tmd/title');

			foreach ($selected as $title_id) {
				$this->model_extension_tmdseourlextrapages_tmd_title->deletetitle($title_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/tmdseourlextrapages/tmd/title');

			$filter_data = [
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			];

			$results = $this->model_extension_tmdseourlextrapages_tmd_title->gettitles($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'title_id' => $result['title_id'],
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
