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

        $elements = unserialize($content['elements']);

        $data['elements'] = array();
        if(!empty($elements)) {
            $data['elements'] = $this->getAllElements($elements);
        }

//        var_dump($data['elements']);die;

        return $this->load->view('plaza/builder/content', $data);
    }

    public function getAllElements($elements) {
        $all_elements = array();

        foreach($elements as $main_row) {
            $main_row_info = array();

            foreach($main_row['main_cols'] as $main_col) {
                $main_col_info = array();

                if(isset($main_col['sub_rows']) && $main_col['sub_rows']) {
                    foreach($main_col['sub_rows'] as $sub_row) {
                        $sub_row_info = array();

                        foreach ($sub_row['sub_cols'] as $sub_col) {
                            $sub_col_info = array();
                            if(isset($sub_col['info'])) {
                                foreach ($sub_col['info'] as $modules) {
                                    $module_in_col = array();
                                    foreach ($modules as $module) {
                                        if($module['code'] == "widget") {
                                            $params = array(
                                                'type' => $module['name'],
                                                'settings' => $module['settings']
                                            );

                                            $module_in_col[] = $this->load->controller('plaza/widget', $params);
                                        } else {
                                            $part = explode('.', $module['code']);

                                            if (isset($part[0]) && $this->config->get('module_' . $part[0] . '_status')) {
                                                $module_data = $this->load->controller('extension/module/' . $part[0]);

                                                if ($module_data) {
                                                    $module_in_col[] = $module_data;
                                                }
                                            }

                                            if (isset($part[1])) {
                                                $setting_info = $this->model_setting_module->getModule($part[1]);

                                                if ($setting_info && $setting_info['status']) {
                                                    $module_data = $this->load->controller('extension/module/' . $part[0], $setting_info);

                                                    if ($module_data) {
                                                        $module_in_col[] = $module_data;
                                                    }
                                                }
                                            }
                                        }

                                        $sub_col_info['info'] = $module_in_col;
                                    }

                                }
                            } else {
                                $sub_col_info['info'] = array();
                            }

                            $sub_col_info['format'] = $sub_col['format'];
                            $sub_row_info[] = $sub_col_info;
                        }

                        $main_col_info['sub_rows'][] = $sub_row_info;
                        $main_col_info['format'] = $main_col['format'];
                    }
                }
                $main_row_info['main_cols'][] = $main_col_info;
                $main_row_info['class'] = $main_row['class'];

            }
            $all_elements[] = $main_row_info;
        }

        return $all_elements;
    }
}
