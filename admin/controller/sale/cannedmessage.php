<?php
class ControllerSaleCannedMessage extends Controller { 
	private $error = array();
	protected function CannedMessagedbCheck(){
		$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."cannedmessage'");
			if(!$query->num_rows){
				$this->db->query("CREATE TABLE `".DB_PREFIX."cannedmessage` (
				  `cannedmessage_id` int(11) NOT NULL AUTO_INCREMENT,
				  `sort_order` int(3) NOT NULL DEFAULT '0',
				  `status` tinyint(1) NOT NULL DEFAULT '1',
				  `title` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
				  `description` text COLLATE utf8_bin NOT NULL,
				  PRIMARY KEY (`cannedmessage_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
			}
	}
	public function index() {
		$this->CannedMessagedbCheck();
		$this->load->language('sale/cannedmessage');

		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('sale/cannedmessage');

		$this->getList();
	}

	public function insert() {
		$this->load->language('sale/cannedmessage');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/cannedmessage');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_cannedmessage->addCannedMessage($this->request->post);
			
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
			
			$this->redirect($this->url->link('sale/cannedmessage', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('sale/cannedmessage');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/cannedmessage');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_cannedmessage->editCannedMessage($this->request->get['cannedmessage_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->link('sale/cannedmessage', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('sale/cannedmessage');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/cannedmessage');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $cannedmessage_id) {
				$this->model_sale_cannedmessage->deleteCannedMessage($cannedmessage_id);
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
			
			$this->redirect($this->url->link('sale/cannedmessage', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.title';
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/cannedmessage', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('sale/cannedmessage/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/cannedmessage/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['cannedmessages'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$CannedMessage_total = $this->model_sale_cannedmessage->getTotalcannedmessages();
	
		$results = $this->model_sale_cannedmessage->getcannedmessages($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/cannedmessage/update', 'token=' . $this->session->data['token'] . '&cannedmessage_id=' . $result['cannedmessage_id'] . $url, 'SSL')
			);
						
			$this->data['cannedmessages'][] = array(
				'cannedmessage_id' => $result['cannedmessage_id'],
				'title'          => $result['title'],
				'sort_order'     => $result['sort_order'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['cannedmessage_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
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
		
		$this->data['sort_title'] = $this->url->link('sale/cannedmessage', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('sale/cannedmessage', 'token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $CannedMessage_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/cannedmessage', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/cannedmessage_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
    	
		

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
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
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/cannedmessage', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['cannedmessage_id'])) {
			$this->data['action'] = $this->url->link('sale/cannedmessage/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/cannedmessage/update', 'token=' . $this->session->data['token'] . '&cannedmessage_id=' . $this->request->get['cannedmessage_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('sale/cannedmessage', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['cannedmessage_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$cannedmessage_info = $this->model_sale_cannedmessage->getCannedMessage($this->request->get['cannedmessage_id']);
		}
		
		$this->data['token'] = $this->session->data['token'];
		$this->load->model('localisation/language');

		

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		
		
		if (isset($this->request->post['cannedmessage_description'])) {
			$this->data['cannedmessage_description'] = $this->request->post['cannedmessage_description'];
		} elseif (!empty($cannedmessage_info)) {
			$this->data['cannedmessage_description'] = $cannedmessage_info['description'];
		} else {
			$this->data['cannedmessage_description'] = '';
		}

		if (isset($this->request->post['cannedmessage_title'])) {
			$this->data['cannedmessage_title'] = $this->request->post['cannedmessage_title'];
		} elseif (!empty($cannedmessage_info)) {
			$this->data['cannedmessage_title'] = $cannedmessage_info['title'];
		} else {
			$this->data['cannedmessage_title'] = '';
		}

	
			
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($cannedmessage_info)) {
			$this->data['status'] = $cannedmessage_info['status'];
		} else {
			$this->data['status'] = 1;
		}
				
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($cannedmessage_info)) {
			$this->data['sort_order'] = $cannedmessage_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		
				
		$this->template = 'sale/cannedmessage_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/cannedmessage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

					if ((utf8_strlen($this->request->post['cannedmessage_title']) < 3) || (utf8_strlen($this->request->post['cannedmessage_title']) > 64)) {
				$this->error['title'] = $this->language->get('error_title');
			}
		
			if (utf8_strlen($this->request->post['cannedmessage_description']) < 3) {
				$this->error['description'] = $this->language->get('error_description');
			}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
			
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/cannedmessage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		
		

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>