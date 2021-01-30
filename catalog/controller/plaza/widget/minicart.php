<?php
class ControllerPlazaWidgetMinicart extends Controller
{
    public function index($widget) {
        $data = $widget->getSettings();
        $template = $widget->getTemplate();

        return $this->load->view('plaza/widget/' . $template, $data);
    }
}
