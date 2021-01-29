<?php
class ModelPlazaContentBuilder extends Model
{
    public function getContent($content_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "plaza_content pc LEFT JOIN " . DB_PREFIX . "plaza_content_description pcd ON (pc.content_id = pcd.content_id) WHERE pc.content_id = '" . (int)$content_id . "' AND pcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }
}