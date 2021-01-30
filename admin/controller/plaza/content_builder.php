<?php
require_once DIR_SYSTEM . "plaza/inc/widget.php";

class ControllerPlazaContentBuilder extends Controller
{
    private $error = array();

    const PATH_WIDGETS_FILES = DIR_SYSTEM . 'plaza/inc/widget/*.php';

    public function index() {
        $this->load->language('plaza/engine');

        $this->document->setTitle($this->language->get('heading_content_builder'));

        $this->load->model('plaza/engine');
        $this->load->model('plaza/content_builder');

        $this->getList();
    }

    public function add() {
        $this->load->language('plaza/engine');

        $this->document->setTitle($this->language->get('heading_content_builder'));

        $this->load->model('plaza/engine');
        $this->load->model('plaza/content_builder');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_plaza_content_builder->addContent($this->request->post);

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

            $this->response->redirect($this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('plaza/engine');

        $this->document->setTitle($this->language->get('heading_content_builder'));

        $this->load->model('plaza/engine');
        $this->load->model('plaza/content_builder');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_plaza_content_builder->editContent($this->request->get['content_id'], $this->request->post);

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

            $this->response->redirect($this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('plaza/engine');

        $this->document->setTitle($this->language->get('heading_content_builder'));

        $this->load->model('plaza/engine');
        $this->load->model('plaza/content_builder');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $content_id) {
                $this->model_plaza_content_builder->deleteContent($content_id);
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

            $this->response->redirect($this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    public function getList() {
        $data = array();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pcd.name';
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

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('plaza/engine', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_content_builder'),
            'href' => $this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['add'] = $this->url->link('plaza/content_builder/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('plaza/content_builder/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['plaza_contents'] = array();

        $filter_data = array(
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        $content_total = $this->model_plaza_content_builder->getTotalContents($filter_data);

        $results = $this->model_plaza_content_builder->getContents($filter_data);

        if(!empty($results)) {
            foreach ($results as $result) {
                $data['plaza_contents'][] = array(
                    'content_id' => $result['content_id'],
                    'name'       => $result['name'],
                    'sort_order' => $result['sort_order'],
                    'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'edit'       => $this->url->link('plaza/content_builder/edit', 'user_token=' . $this->session->data['user_token'] . '&content_id=' . $result['content_id'] . $url, true)
                );
            }
        }

        $data['user_token'] = $this->session->data['user_token'];

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

        $data['sort_name'] = $this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'] . '&sort=pcd.name' . $url, true);
        $data['sort_status'] = $this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'] . '&sort=pc.status' . $url, true);
        $data['sort_order'] = $this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'] . '&sort=pc.sort_order' . $url, true);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $content_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($content_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($content_total - $this->config->get('config_limit_admin'))) ? $content_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $content_total, ceil($content_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['menu_items'] = $this->model_plaza_engine->displayMenuFeatures('content_builder');

        $this->document->addStyle('view/stylesheet/plaza/engine.css');
        $this->document->addScript('view/javascript/plaza/engine.js');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('plaza/content_builder/list', $data));
    }

    public function getForm() {
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

        if (!isset($this->request->get['content_id'])) {
            $data['action'] = $this->url->link('plaza/content_builder/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('plaza/content_builder/edit', 'user_token=' . $this->session->data['user_token'] . '&content_id=' . $this->request->get['content_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['content_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $content_info = $this->model_plaza_content_builder->getContent($this->request->get['content_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['content_description'])) {
            $data['content_description'] = $this->request->post['content_description'];
        } elseif (isset($this->request->get['content_id'])) {
            $data['content_description'] = $this->model_plaza_content_builder->getContentDescription($this->request->get['content_id']);
        } else {
            $data['content_description'] = array();
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($content_info)) {
            $data['status'] = $content_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($content_info)) {
            $data['sort_order'] = $content_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }

        if (isset($this->request->post['elements'])) {
            $data['elements'] = $this->request->post['elements'];
        } elseif (!empty($content_info)) {
            $data['elements'] = unserialize($content_info['elements']);
        } else {
            $data['elements'] = array();
        }

//        var_dump($data['elements']);die;

        $data['menu_items'] = $this->model_plaza_engine->displayMenuFeatures('content_builder');

        $data['extensions'] = $this->getExtensions();

        $data['widgets'] = $this->getWidgets();

//        $widgets = $this->getWidgets();
//
//        var_dump($widgets);die;

        $this->document->addStyle('view/javascript/jquery/jquery-ui/jquery-ui.min.css');
        $this->document->addStyle('view/stylesheet/plaza/engine.css');
        $this->document->addStyle('view/stylesheet/plaza/content_builder.css');

        $this->document->addScript('view/javascript/jquery/jquery-ui/jquery-ui.min.js');
        $this->document->addScript('view/javascript/plaza/engine.js');
        $this->document->addScript('view/javascript/plaza/content_builder.js');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('plaza/content_builder/form', $data));
    }

    public function getWidgets() {
        $widgets = array();

        $widgetsFiles = array_map( function( $item ) {
            return basename( $item, ".php" );
        }, glob(self::PATH_WIDGETS_FILES) );

        foreach ($widgetsFiles as $widget) {
            $data['title'] = $this->language->get('text_widget_' . $widget);
            $widgets[] = array(
                'title' => $data['title'],
                'name' => $widget,
                'code' => 'widget',
                'url' => $this->url->link('plaza/content_builder/showWidgetForm' , 'user_token=' . $this->session->data['user_token'] . '&widget=' . $widget, true)
            );
        }

        return $widgets;
    }

    public function showWidgetForm() {
        $this->load->language('plaza/engine');

        $json = array();

        if(isset($this->request->get['widget'])) {
            $data = $this->request->get;
            $data['settings'] = array();
            if(isset($this->request->get['settings'])) {
                $data['settings'] = unserialize($this->request->get['settings']);
            }

            $widget = $this->request->get['widget'];

            $data['widget'] = $widget;
            $data['state'] = $this->request->get['state'];

            $data['module_id'] = "";
            if(isset($this->request->get['module_id'])) {
                $data['module_id'] = $this->request->get['module_id'];
            }

            $data['convert_url'] = $this->url->link('plaza/content_builder/convertWidgetData' , 'user_token=' . $this->session->data['user_token'], true);

            $json['result_html'] = $this->load->view('plaza/widget/' . $widget, $data);
        } else {
            $json['error'] = $this->language->get('error_cannot_load_widget');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function convertWidgetData() {
        $this->load->language('plaza/engine');

        $json = array();

        if(isset($this->request->post)) {
            $params = $this->request->post;
            $json['settings'] = serialize($params);
            $json['widget'] = $this->request->post['widget'];
            $json['state'] = $this->request->post['state'];
            $json['module_id'] = $this->request->post['module_id'];
            $json['url'] = $this->url->link('plaza/content_builder/showWidgetForm', 'user_token=' . $this->session->data['user_token'], true);
        }

        if(empty($json)) {
            $json['error'] = $this->language->get('error_cannot_load_widget');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getExtensions() {
        $this->load->model('setting/extension');
        $this->load->model('setting/module');
        $allExtensions = array();
        // Get a list of installed modules
        $extensions = $this->model_setting_extension->getInstalled('module');

        // Add all the modules which have multiple settings for each module
        foreach ($extensions as $code) {
            $this->load->language('extension/module/' . $code);

            $module_data = array();

            $modules = $this->model_setting_module->getModulesByCode($code);

            foreach ($modules as $module) {
                $module_data[] = array(
                    'name' => strip_tags($this->language->get('heading_title') . ' &gt; ' . $module['name']),
                    'code' => $code . '.' .  $module['module_id'],
                    'url'	=> $this->url->link('extension/module/' . $code, 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $module['module_id'], true)
                );
            }

            if ($this->config->has('module_' . $code . '_status') || $module_data) {
                $allExtensions[] = array(
                    'name'   => strip_tags($this->language->get('heading_title')),
                    'code'   => $code,
                    'url'	 => $this->url->link('extension/module/' . $code, 'user_token=' . $this->session->data['user_token'], true),
                    'modules' => $module_data
                );
            }
        }

        $this->load->language('plaza/engine');

        return $allExtensions;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'plaza/content_builder')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['content_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'plaza/content_builder')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
