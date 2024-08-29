<?php
namespace Opencart\Catalog\Controller\Extension\Tmdseourlextrapages\TMD;
class linkroute extends \Opencart\System\Engine\Controller {

	public function footerevent(string &$route, array &$args): void{

		$modulestatus=$this->config->get('module_tmdseopage_status');
		if(!empty($modulestatus)){
		$this->load->model('extension/tmdseourlextrapages/tmd/linkroute');
		if(isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		}

		$routeinfo =$this->model_extension_tmdseourlextrapages_tmd_linkroute->getRoute($route);
	
		if($routeinfo){
			$routetitle_info = $this->model_extension_tmdseourlextrapages_tmd_linkroute->getRouteTitle((int)$routeinfo['linkroute_id']);
		}else{
			$routetitle_info = array();
		}
		if($routetitle_info) {
			if(!empty($routetitle_info['title'])) {
				$this->document->setTitle($routetitle_info['title']);
			}
			
			if(!empty($routetitle_info['meta_description'])) {
				$this->document->setDescription($routetitle_info['meta_description']);
			}
			
			if(!empty($routetitle_info['meta_keyword'])) {
				$this->document->setKeywords($routetitle_info['meta_keyword']);
			}
		}
		}
	}

}
