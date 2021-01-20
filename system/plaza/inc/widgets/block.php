<?php
require_once DIR_SYSTEM . "plaza/inc/widget.php";

class Block extends Widget
{
    protected $template = "block";

    public function __construct($type, $settings = array())
    {
        parent::__construct($type, $settings);
    }
}