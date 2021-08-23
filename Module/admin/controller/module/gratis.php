<?php
class ControllerModuleGratis extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/gratis');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            echo "<pre />\n";

			$this->model_setting_setting->editSetting('gratis', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_kwota'] = $this->language->get('entry_kwota');
		$data['entry_kwota_name'] = $this->language->get('entry_kwota_name');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_ilosc'] = $this->language->get('entry_ilosc');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
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
			'href' => $this->url->link('module/gratis', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/gratis', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        

		if (isset($this->request->post['gratis_status'])) {
			$data['gratis_status'] = $this->request->post['gratis_status'];
		} else {
			$data['gratis_status'] = $this->config->get('gratis_status');
		}
        
		if (isset($this->request->post['gratis_name'])) {
			$data['gratis_name'] = $this->request->post['gratis_name'];
		} elseif ($this->config->get('gratis_name')) {
            $data['gratis_name'] = $this->config->get('gratis_name');
        } else {
			$data['gratis_name'] = '';
		}
        
		if (isset($this->request->post['gratis_kwota'])) {
			$data['gratis_kwota'] = $this->request->post['gratis_kwota'];
		} elseif ($this->config->get('gratis_kwota')) {
            $data['gratis_kwota'] = $this->config->get('gratis_kwota');
        } else {
			$data['gratis_kwota'] = '';
		}
        
		if (isset($this->request->post['gratis_ile_tabletek'])) {
			$data['gratis_ile_tabletek'] = $this->request->post['gratis_ile_tabletek'];
		} elseif ($this->config->get('gratis_ile_tabletek')) {
            $data['gratis_ile_tabletek'] = $this->config->get('gratis_ile_tabletek');
        } else {
			$data['gratis_ile_tabletek'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('catalog/product');

		$data['products'] = array();

		if (isset($this->request->post['gratis_product'])) {
			$products = $this->request->post['gratis_product'];
		} elseif ($this->config->get('gratis_product')) {
			$products = $this->config->get('gratis_product');
		} else {
			$products = array();
		}

		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/gratis.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/gratis')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}