<?php
class ControllerExtensiontitle extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/title');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/title');

		$this->getList();
	}

	public function add() {
		$this->load->language('extension/title');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/title');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_title->addtitle($this->request->post);

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

			$this->response->redirect($this->url->link('extension/title', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/title');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/title');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_title->edittitle($this->request->get['title_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('extension/title', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/title');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/title');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $title_id) {
				$this->model_extension_title->deletetitle($title_id);
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

			$this->response->redirect($this->url->link('extension/title', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
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
			'href' => $this->url->link('extension/title', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);
		
		$data['add'] = $this->url->link('extension/title/add', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('extension/title/delete', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

		$data['titles'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$title_total = $this->model_extension_title->getTotaltitles($filter_data);
		$results = $this->model_extension_title->gettitles($filter_data);
		
		foreach ($results as $result) {
			$this->load->model('extension/linkroute');
			
			$route = $this->model_extension_linkroute->getlinkroutebyid($result['linkroute_id']);
			
			if (isset($route['name'])) {
				$routevalue = $route['name'];
			} else {
				$routevalue = '';
			}
			
			$data['titles'][] = array(
				'title_id' 	   => $result['title_id'],
				'title'        => $result['title'],
				'metadesc'     => $result['meta_description'],
				'metakeyword'  => $result['meta_keyword'],
				'route'        => $routevalue,
				'edit'         => $this->url->link('extension/title/edit', 'user_token=' . $this->session->data['user_token'] . '&title_id=' . $result['title_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_route'] = $this->language->get('column_route');
		$data['column_metakeyword'] = $this->language->get('column_metakeyword');
		$data['column_metadesc'] = $this->language->get('column_metadesc');
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

		$data['sort_name'] = $this->url->link('extension/title', 'user_token=' . $this->session->data['user_token'] . '&sort=dd.title_id' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $title_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/title', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($title_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($title_total - $this->config->get('config_limit_admin'))) ? $title_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $title_total, ceil($title_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/title_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['title_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_route'] = $this->language->get('entry_route');

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

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
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
			'href' => $this->url->link('extension/title', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['title_id'])) {
			$data['action'] = $this->url->link('extension/title/add', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/title/edit', 'user_token=' . $this->session->data['user_token'] . '&title_id=' . $this->request->get['title_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/title', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['title_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$title_info = $this->model_extension_title->gettitle($this->request->get['title_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['title_id'])) {
			$data['title_id'] = $this->request->get['title_id'];
		} else {
			$data['title_id'] = 0;
		}

		if (isset($this->request->post['title_description'])) {
			$data['title_description'] = $this->request->post['title_description'];
		} elseif (isset($this->request->get['title_id'])) {
			$data['title_description'] = $this->model_extension_title->gettitleDescriptions($this->request->get['title_id']);
		} else {
			$data['title_description'] = array();
		}
		
		$this->load->model('extension/linkroute');

		$data['routes'] = $this->model_extension_linkroute->getlinkroutes();

		
		if (isset($this->request->post['route_id'])) {
			$data['route_id'] = $this->request->post['route_id'];
		} elseif (!empty($title_info)) {
			$data['route_id'] = $title_info['linkroute_id'];
		} else {
			$data['route_id'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/title_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/title')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['title_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/title')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}