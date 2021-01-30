<?php
require_once DIR_SYSTEM . "plaza/inc/widget.php";

class Minicart extends Widget
{
    protected $template = "minicart";

    public function __construct($settings) {
        parent::__construct($settings, $this->template);
    }
}