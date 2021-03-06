<?php
class ControllerModuleDisplay extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('module/display');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('display', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_heading'] = $this->language->get('entry_heading');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_module_add'] = $this->language->get('button_module_add');
        $data['button_remove'] = $this->language->get('button_remove');

        $data['tab_module'] = $this->language->get('tab_module');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/display', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('module/display', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['display_status'])) {
            $data['display_status'] = $this->request->post['display_status'];
        } else {
            $data['display_status'] = $this->config->get('display_status');
        }

        if (isset($this->request->post['display_module'])) {
            $modules = $this->request->post['display_module'];
        } elseif ($this->config->has('display_module')) {
            $modules = $this->config->get('display_module');
        } else {
            $modules = array();
        }

        $data['display_modules'] = array();

        foreach ($modules as $key => $module) {
            $data['display_modules'][] = array(
                'key'         => $key,
                'heading'     => $module['heading'],
                'description' => $module['description']
            );
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/display.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/display')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}