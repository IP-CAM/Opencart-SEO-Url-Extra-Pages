<?php
class ControllerExtensionlinkroute extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/linkroute');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/linkroute');

		$this->getList();
	}

	public function add() {
		$this->load->language('extension/linkroute');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/linkroute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_linkroute->addlinkroute($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/linkroute', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/linkroute');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/linkroute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_linkroute->editlinkroute($this->request->get['linkroute_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/linkroute', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/linkroute');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/linkroute');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $linkroute_id) {
				$this->model_extension_linkroute->deletelinkroute($linkroute_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/linkroute', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
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
			$page = $this->request->get['page'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/linkroute', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);
		
		$data['add'] = $this->url->link('extension/linkroute/add', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('extension/linkroute/delete', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

		$data['linkroutes'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);


		$linkroute_total = $this->model_extension_linkroute->getTotallinkroutes();

		$results = $this->model_extension_linkroute->getlinkroutes($filter_data);
		foreach ($results as $result) {
		
			$data['linkroutes'][] = array(
				'linkroute_id' => $result['linkroute_id'],
				'name'        => $result['name'],
				'route'  => $result['route'],
				'edit'        => $this->url->link('extension/linkroute/edit', 'user_token=' . $this->session->data['user_token'] . '&linkroute_id=' . $result['linkroute_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_route'] = $this->language->get('column_route');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
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

		$data['sort_name'] = $this->url->link('extension/linkroute', 'user_token=' . $this->session->data['user_token'] . '&sort=dd.name' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}


		$pagination = new Pagination();
		$pagination->total = $linkroute_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/linkroute', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($linkroute_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($linkroute_total - $this->config->get('config_limit_admin'))) ? $linkroute_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $linkroute_total, ceil($linkroute_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/linkroute_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['linkroute_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_route'] = $this->language->get('entry_route');
		$data['entry_keyword'] = $this->language->get('entry_keyword');

		$data['help_filename'] = $this->language->get('help_filename');
		$data['help_mask'] = $this->language->get('help_mask');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['route'])) {
			$data['error_route'] = $this->error['route'];
		} else {
			$data['error_route'] = '';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/linkroute', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['linkroute_id'])) {
			$data['action'] = $this->url->link('extension/linkroute/add', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/linkroute/edit', 'user_token=' . $this->session->data['user_token'] . '&linkroute_id=' . $this->request->get['linkroute_id'] . $url, 'SSL');
		}



		$data['cancel'] = $this->url->link('extension/linkroute', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['linkroute_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$linkroute_info = $this->model_extension_linkroute->getlinkroute($this->request->get['linkroute_id']);
		}


		$data['user_token'] = $this->session->data['user_token'];

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

		
		if (isset($this->request->post['route'])) {
			$data['route'] = $this->request->post['route'];
		} elseif (!empty($linkroute_info['route'])) {
			$data['route'] = $linkroute_info['route'];
		} else {
			$data['route'] = '';
		}

		if (isset($linkroute_id)) {
			$data['linkroute_description'] = $this->model_extension_linkroute->getDescriptions($linkroute_id);
		} else {
			$data['linkroute_description'] = [];
		}

		$this->load->model('setting/store');

		$data['stores'] = array();
		
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);
		
		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}

		if (isset($this->request->post['product_seo_url'])) {
			$data['product_seo_url'] = $this->request->post['product_seo_url'];
		} elseif (isset($this->request->get['linkroute_id'])) {
			$data['product_seo_url'] = $this->model_extension_linkroute->getlinkrouteSeoUrls($this->request->get['linkroute_id']);
		} else {
			$data['product_seo_url'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/linkroute_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/linkroute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['route']) < 1) || (utf8_strlen($this->request->post['route']) > 64)) {
			$this->error['route'] = $this->language->get('error_route');
		}

		foreach ($this->request->post['linkroute_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/linkroute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}