<?php
require_once DIR_SYSTEM . "plaza/inc/widget.php";

class Block extends Widget
{
    private $title;
    private $html;
    private $isShowTitle;

    protected $template = "block";

    public function __construct($settings, $title, $html, $isShowTitle)
    {
        parent::__construct($settings, $this->template);
        $this->title = $title;
        $this->html = $html;
        $this->isShowTitle = $isShowTitle;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function getTitle() {
        return $this->title;
    }

    function setHtml($html) {
        $this->html = $html;
    }

    function getHtml() {
        return $this->html;
    }

    function setIsShowTitle($isShowTitle) {
        $this->isShowTitle = $isShowTitle;
    }

    function getIsShowTitle() {
        return $this->isShowTitle;
    }
}