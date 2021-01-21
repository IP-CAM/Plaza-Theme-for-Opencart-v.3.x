<?php
class ModelPlazaContentBuilder extends Model
{
    public function addContent($data) {}

    public function editContent($data) {}

    public function deleteContent($content_id) {}

    public function getContent($content_id) {}

    public function getContents($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "plaza_content pc LEFT JOIN " . DB_PREFIX . "plaza_content_description pcd ON (pc.content_id = pcd.content_id) WHERE pcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sql .= " GROUP BY pc.content_id";

        $sort_data = array(
            'pcd.name',
            'pc.status',
            'pc.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pcd.name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

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