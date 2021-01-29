<?php
class ControllerPlazaContentBuilder extends Controller
{
    public function index() {
        $this->load->model('design/layout');
        $this->load->model('plaza/layout');
        $this->load->model('plaza/content_builder');

        if (isset($this->request->get['route'])) {
            $route = (string)$this->request->get['route'];
        } else {
            $route = 'common/home';
        }

        $layout_id = 0;

        if ($route == 'product/category' && isset($this->request->get['path'])) {
            $this->load->model('catalog/category');

            $path = explode('_', (string)$this->request->get['path']);

            $layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
        }

        if ($route == 'product/product' && isset($this->request->get['product_id'])) {
            $this->load->model('catalog/product');

            $layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
        }

        if ($route == 'information/information' && isset($this->request->get['information_id'])) {
            $this->load->model('catalog/information');

            $layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
        }

        if (!$layout_id) {
            $layout_id = $this->model_design_layout->getLayout($route);
        }

        if (!$layout_id) {
            $layout_id = $this->config->get('config_layout_id');
        }

        $layout_content = $this->model_plaza_layout->getLayoutContent($layout_id);
        $content = $this->model_plaza_content_builder->getContent($layout_content['content_id']);

        $data['elements'] = unserialize($content['elements']);

//        var_dump($data['elements']);die;

        return $this->load->view('plaza/builder/content', $data);
    }
}
