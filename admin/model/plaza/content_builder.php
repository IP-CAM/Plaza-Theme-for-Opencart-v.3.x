<?php
class ModelPlazaContentBuilder extends Model
{
    public function addContent($data) {}

    public function editContent($data) {}

    public function deleteContent($content_id) {}

    public function getContent($content_id) {}

    public function getContents($data = array()) {}

    public function getContentDescription($content_id) {}

    public function getTotalContents($data = array()) {}

    public function setup() {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "plaza_content` (
			    `content_id` INT(11) NOT NULL AUTO_INCREMENT,
	            `sort_order` INT(11) NOT NULL DEFAULT '0',
	            `status` TINYINT(1) NOT NULL DEFAULT '0',
	            `setting` text NOT NULL DEFAULT '',
	        PRIMARY KEY (`content_id`)
		) DEFAULT COLLATE=utf8_general_ci;");

        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "plaza_content_description` (
			    `content_id` INT(11) NOT NULL,
                `language_id` INT(11) NOT NULL,
                `name` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`content_id`, `language_id`)
		) DEFAULT COLLATE=utf8_general_ci;");
    }
}