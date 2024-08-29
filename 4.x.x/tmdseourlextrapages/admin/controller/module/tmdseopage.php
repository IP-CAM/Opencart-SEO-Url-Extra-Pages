<?php
namespace Opencart\Admin\Controller\Extension\Tmdseourlextrapages\Module;
// Lib Include 
require_once(DIR_EXTENSION.'/tmdseourlextrapages/system/library/tmd/system.php');
// Lib Include 
class Tmdseopage extends \Opencart\System\Engine\Controller {

	public function index(): void {
		
		$this->registry->set('tmd', new  \Tmdseourlextrapages\System\Library\Tmd\System($this->registry));
		$keydata=array(
		'code'=>'tmdkey_tmdseopage',
		'eid'=>'MjE0MjY=',
		'route'=>'extension/tmdseourlextrapages/module/tmdseopage',
		);
		$tmdseopage=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		
		$this->load->language('extension/tmdseourlextrapages/module/tmdseopage');

		$this->document->setTitle($this->language->get('heading_title1'));
		
		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdseourlextrapages/module/tmdseopage', 'user_token=' . $this->session->data['user_token'])
		];

		if(VERSION>='4.0.2.0'){
			$data['save'] = $this->url->link('extension/tmdseourlextrapages/module/tmdseopage.save', 'user_token=' . $this->session->data['user_token']);
		}
		else{
			$data['save'] = $this->url->link('extension/tmdseourlextrapages/module/tmdseopage|save', 'user_token=' . $this->session->data['user_token']);
		}

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		$data['module_tmdseopage_status'] = $this->config->get('module_tmdseopage_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdseourlextrapages/module/tmdseopage', $data));
	}

	public function install(): void{
		$this->load->language('extension/tmdseourlextrapages/module/tmdseopage');
		
		$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');
		$this->model_extension_tmdseourlextrapages_tmd_linkroute->install();

		// Fix permissions
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdseourlextrapages/tmd/linkroute');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlextrapages/tmd/linkroute');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdseourlextrapages/tmd/title');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlextrapages/tmd/title');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdseourlextrapages/module/tmdseopage');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlextrapages/module/tmdseopage');

		// Register events
		if(VERSION>='4.0.2.0')
		{
			$eventaction='extension/tmdseourlextrapages/module/tmdseopage.menu';
		}
		else{
			$eventaction='extension/tmdseourlextrapages/module/tmdseopage|menu';
		}

		$this->model_setting_event->deleteEventByCode('tmd_seopage');
		$eventrequest=[
					'code'=>'tmd_seopage',
					'description'=>'TMD Seo Page',
					'trigger'=>'admin/view/common/column_left/before',
					'action'=>$eventaction,
					'status'=>'1',
					'sort_order'=>'1',
				];
		if(VERSION=='4.0.0.0')
		{
		$this->model_setting_event->addEvent('tmd_seopage', 'TMD Seo Page', 'admin/view/common/column_left/before','extension/tmdseourlextrapages/module/tmdseopage|menu', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

		// Front footer events 
		$this->model_setting_event->deleteEventByCode('tmd_footertitle');
		if(VERSION>='4.0.2.0')
		{
			$eventaction='extension/tmdseourlextrapages/tmd/linkroute.footerevent';
		}
		else{
			$eventaction='extension/tmdseourlextrapages/tmd/linkroute|footerevent';
		}
		$eventrequest=[
					'code'=>'tmd_footertitle',
					'description'=>'TMD Front footer event',
					'trigger'=>'catalog/controller/common/footer/before',
					'action'=>$eventaction,
					'status'=>'1',
					'sort_order'=>'1',
				];
				
		if(VERSION=='4.0.0.0')
		{
		$this->model_setting_event->addEvent('tmd_footertitle', 'TMD Front footer event', 'catalog/view/common/footer/before','extension/tmdseourlextrapages/tmd/linkroute|footerevent', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}
			
	}

	public function uninstall(): void{
		$this->load->language('extension/tmdseourlextrapages/module/tmdseopage');
		
		$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');
		$this->model_extension_tmdseourlextrapages_tmd_linkroute->uninstall();
		
		// Register events
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('tmd_seopage');

		// Fix permissions
		$this->load->model('user/user_group');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdseourlextrapages/tmd/linkroute');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlextrapages/tmd/linkroute');
		
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdseourlextrapages/tmd/title');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlextrapages/tmd/title');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdseourlextrapages/module/tmdseopage');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdseourlextrapages/module/tmdseopage');
		
	}

	public function menu(string &$route, array &$args, mixed &$output): void{	
		$this->load->language('extension/tmdseourlextrapages/module/tmdseopage');

		$modulestatus=$this->config->get('module_tmdseopage_status');
		if(!empty($modulestatus)){
			$this->load->language('extension/tmdseourlextrapages/module/tmdseopage');
			
			$tmdseo = [];
				
			if ($this->user->hasPermission('access', 'extension/tmdseourlextrapages/tmd/linkroute')) {
				$tmdseo[] = [
					'name'	   => $this->language->get('text_linkroute'),
					'href'     => $this->url->link('extension/tmdseourlextrapages/tmd/linkroute', 'user_token=' . $this->session->data['user_token']),
					'children' => []		
				];
			}

			if ($this->user->hasPermission('access', 'extension/tmdseourlextrapages/tmd/title')) {
				$tmdseo[] = [
					'name'	   => $this->language->get('text_title'),
					'href'     => $this->url->link('extension/tmdseourlextrapages/tmd/title', 'user_token=' . $this->session->data['user_token']),
					'children' => []		
				];
			}
				
			if ($tmdseo) {					
				$args['menus'][] = [
					'id'       => 'menu-extension',
					'icon'	   => 'far fa-file-excel', 
					'name'	   => $this->language->get('text_addtitle'),
					'href'     => '',
					'children' => $tmdseo
				];	
			}
		}
	}

	public function save(): void {
		$this->load->language('extension/tmdseourlextrapages/module/tmdseopage');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdseourlextrapages/module/tmdseopage')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		$tmdseopage=$this->config->get('tmdkey_tmdseopage');
		if (empty(trim($tmdseopage))) {			
		$json['error'] ='Module will Work after add License key!';
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('module_tmdseopage', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_tmdseopage',
			'eid'=>'MjE0MjY=',
			'route'=>'extension/tmdseourlextrapages/module/tmdseopage',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new  \Tmdseourlextrapages\System\Library\Tmd\System($this->registry));
		
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}