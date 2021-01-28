<?php
class ControllerExtensionThemePlaza extends Controller
{
    private $error = array();

    public function install() {
        $this->load->model('plaza/engine');
        $this->model_plaza_engine->setupEngine();
    }

    public function index() {
        $this->load->language('plaza/engine');
    }

    protected function validate() {

    }
}