<?php
require_once DIR_SYSTEM . "plaza/inc/widget.php";
$files = glob(DIR_SYSTEM . 'plaza/inc/widget/*.php');
foreach ($files as $file) {
    require_once "" . $file . "";
}

class ControllerPlazaWidget extends Controller
{
    public function index($data) {
        $widgetHTML = '';
        if(!empty($data)) {
            $type = $data['type'];
            $settings = $data['settings'];

            $widget = $this->getWidget($type, $settings);

            if(!empty($widget)) {
                $widgetHTML = $this->load->controller('plaza/widget/' . $type, $widget);
            }
        }

        return $widgetHTML;
    }

    public function getWidget($type, $settings) {
        $settings = $this->convertSettings($settings);

        $widget = array();
        if($type == 'minicart') {
            $widget = new Minicart($settings);
        }

        return $widget;
    }

    public function convertSettings($settings) {
        $converted_settings = array();

        if(strpos($settings, "&amp;") !== false) {
            $settings = str_replace('&amp;', '&', $settings);
        }
        $settings = explode('&', $settings);
        foreach ($settings as $setting) {
            $setting = explode('=', $setting);
            $converted_settings[$setting[0]] = $setting[1];
        }

        return $converted_settings;
    }
}
