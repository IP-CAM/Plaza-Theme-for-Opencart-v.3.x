<?php
class ControllerPlazaEngine extends Controller
{
    public function index() {
        $this->load->language('plaza/engine');

        $this->document->setTitle($this->language->get('heading_dashboard'));

        $this->load->model('plaza/engine');

        $data = array();

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_dashboard'),
            'href' => $this->url->link('plaza/engine', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['menu_items'] = $this->model_plaza_engine->displayMenuFeatures();

        $data['menu'][] = array(
            'text' => $this->language->get('text_general'),
            'href' => $this->url->link('plaza/engine', 'user_token=' . $this->session->data['user_token'], true),
        );

        if ($this->user->hasPermission('access', 'plaza/content_builder')) {
            $data['menu'][] = array(
                'text' => $this->language->get('text_content_builder'),
                'href' => $this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'], true),
            );
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('plaza/engine', $data));
    }

    public function import() {

    }
}
