<?php
require_once DIR_SYSTEM . "plaza/inc/widget.php";

class ControllerPlazaContentBuilder extends Controller
{
    const PATH_WIDGETS_FILES = DIR_SYSTEM . 'plaza/inc/widgets/*.php';

    public function index() {
        $widgets = array();

        $widgetFiles = $this->getWidgetsFiles();
        foreach ($widgetFiles as $widgetFile) {
            $widget = new Widget($widgetFile);

            $widgets[] = $widget->getType();
        }

        var_dump($widgets);

        $this->getList();
    }

    public function add() {}

    public function edit() {}

    public function delete() {}

    public function getList() {
        $this->load->language('plaza/engine');

        $this->document->setTitle($this->language->get('page_title'));

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
            'href' => $this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data = array();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('plaza/content_builder', $data));
    }

    public function getForm() {
        $this->load->language('plaza/engine');
    }

    public function getWidgetsFiles() {
        $widgets = array_map( function( $item ) {
            return basename( $item, ".php" );
        }, glob(self::PATH_WIDGETS_FILES) );

        return $widgets;
    }

    public function getModules() {

    }
}