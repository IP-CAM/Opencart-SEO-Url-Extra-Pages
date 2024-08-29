<?php
//lib
require_once(DIR_SYSTEM.'library/tmd/system.php');
//lib
class ControllerExtensionModuleTmdseopage extends Controller {
	private $error = array();

	public function install(){
		$this->load->language('extension/module/tmdseopage');
		
		$this->load->model('extension/linkroute');
		$this->model_extension_linkroute->install();

		// Fix permissions
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/linkroute');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/linkroute');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/title');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/title');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/tmdseopage');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/tmdseopage');
	}

	public function uninstall(){
		// Fix permissions
		$this->load->model('user/user_group');

		$this->load->model('extension/linkroute');
		$this->model_extension_linkroute->uninstall();

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/linkroute');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/linkroute');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/title');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/title');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/module/tmdseopage');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/module/tmdseopage');
	
	}

	public function index() {
		
		$this->registry->set('tmd', new TMD($this->registry));
		$keydata=array(
		'code'=>'tmdkey_tmdseopage',
		'eid'=>'MjE0MjY=',
		'route'=>'extension/module/tmdseopage',
		);
		$tmdseopage=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		$this->load->language('extension/module/tmdseopage');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_tmdseopage', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/tmdseopage', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/tmdseopage', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_tmdseopage_status'])) {
			$data['module_tmdseopage_status'] = $this->request->post['module_tmdseopage_status'];
		} else {
			$data['module_tmdseopage_status'] = $this->config->get('module_tmdseopage_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/tmdseopage', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/tmdseopage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$tmdseopage=$this->config->get('tmdkey_tmdseopage');
		if (empty(trim($tmdseopage))) {			
		$this->session->data['warning'] ='Module will Work after add License key!';
		$this->response->redirect($this->url->link('extension/module/tmdseopage', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		return !$this->error;
	}
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_tmdseopage',
			'eid'=>'MjE0MjY=',
			'route'=>'extension/module/tmdseopage',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new TMD($this->registry));
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}