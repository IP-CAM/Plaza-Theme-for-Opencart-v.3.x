<?php
class Widget {
    private $settings;
    protected $template = '';

    public function __construct($settings, $template)
    {
        $this->settings = $settings;
        $this->template = $template;
    }

    function setSettings($settings) {
        $this->settings = $settings;
    }

    function getSettings() {
        return $this->settings;
    }

    function setTemplate($template) {
        $this->template = $template;
    }

    function getTemplate() {
        return $this->template;
    }
}