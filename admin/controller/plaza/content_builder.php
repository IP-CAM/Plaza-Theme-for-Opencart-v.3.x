<?php
require_once DIR_SYSTEM . "plaza/inc/widget.php";

class ControllerPlazaContentBuilder extends Controller
{
    const PATH_WIDGETS_FILES = DIR_SYSTEM . 'plaza/inc/widgets/*.php';

    public function index() {
        $this->load->language('plaza/engine');

        $this->document->setTitle($this->language->get('heading_content_builder'));

        $this->load->model('plaza/content_builder');

        $this->getList();
    }

    public function add() {}

    public function edit() {}

    public function delete() {}

    public function getList() {
        $widgets = array();

        $widgetFiles = $this->getWidgetsFiles();
        foreach ($widgetFiles as $widgetFile) {
            $widget = new Widget($widgetFile);

            $widgets[] = $widget->getType();
        }

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

        var_dump($data['plaza_contents']);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('plaza/content_builder', $data));
    }

    public function getForm() {}

    public function getWidgetsFiles() {
        $widgets = array_map( function( $item ) {
            return basename( $item, ".php" );
        }, glob(self::PATH_WIDGETS_FILES) );

        return $widgets;
    }

    public function getModules() {

    }
}