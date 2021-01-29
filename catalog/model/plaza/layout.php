<?php
class ModelPlazaLayout extends Model
{
    public function getLayoutContent($layout_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "plaza_layout_content WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->row;
    }
}