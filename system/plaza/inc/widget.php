<?php
class Widget {
    private $type;
    private $settings;
    protected $template = '';

    public function __construct($type, $settings = array())
    {
        $this->type = $type;
        $this->settings = $settings;
    }

    function setType($type) {
        $this->type = $type;
    }

    function getType() {
        return $this->type;
    }

    function setSettings($settings) {
        $this->settings = $settings;
    }

    function getSettings() {
        return $this->settings;
    }

    function render() {
        echo $this->template;
    }
}