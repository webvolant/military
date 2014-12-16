<?php
class ControllerCommonContentDown extends Controller {
	public function index() {
		$this->load->model('design/layout');


		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$layout_id = 0;

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$data['modules'] = array();
		
		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_down');

		foreach ($modules as $module) {
			$part = explode('.', $module['code']);
			
			if (isset($part[0])) {
				$code = $part[0];
			}
			
			if ($code && $this->config->get($code . '_status')) { 
				$setting = $this->config->get($code . '_module');
				
				if (isset($part[1]) && isset($setting[$part[1]])) {
					$data['modules'][] = $this->load->controller('module/' . $code, $setting[$part[1]]);
				} else {
					$data['modules'][] = $this->load->controller('module/' . $code);
				}			
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/content_down.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/content_down.tpl', $data);
		} else {
			return $this->load->view('default/template/common/content_down.tpl', $data);
		}
	}
}