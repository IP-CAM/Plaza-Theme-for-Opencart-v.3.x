<?php
class ModelPlazaEngine extends Model
{
    public function setupEngine() {
        self::setPermission();
        self::setModifications();
    }

    private function setPermission() {
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'plaza/engine');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'plaza/engine');
    }

    private function setModifications() {
        $jsonFilePath = DIR_SYSTEM . 'plaza/ocmod/plaza_modifications.json';
        if(file_exists($jsonFilePath)) {
            $jsonFileContent = file_get_contents($jsonFilePath);
            $plazaModifications = json_decode($jsonFileContent);

            if(!empty($plazaModifications->extension_install)) {
                $this->addPlazaExtensionInstaller($plazaModifications->extension_install);
            }

            if(!empty($plazaModifications->modification)) {
                $this->addPlazaModifications($plazaModifications->modification);
            }
        }
    }

    private function addPlazaExtensionInstaller($extensionInstallers) {
        if(!empty($extensionInstallers)) {
            foreach ($extensionInstallers as $installer) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "extension_install WHERE extension_install_id = '" . (int) $installer->extension_install_id . "'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "extension_install SET extension_install_id = '" . (int) $installer->extension_install_id . "', extension_download_id = '" . (int) $installer->extension_download_id . "', filename = '". $this->db->escape($installer->filename) . "', date_added = '" . date('Y-m-d H:i:s') . "'");
            }
        }

        return;
    }

    private function addPlazaModifications($modifications) {
        if(!empty($modifications)) {
            foreach ($modifications as $modification) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int) $modification->modification_id . "'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "modification SET modification_id = '" . (int) $modification->modification_id . "', extension_install_id = '" . (int) $modification->extension_install_id . "', name = '". $this->db->escape($modification->name) . "', code = '". $this->db->escape($modification->code) . "', author = '". $this->db->escape($modification->author) . "', version = '". $this->db->escape($modification->version) . "', link = '". $this->db->escape($modification->link) . "', xml = ". $modification->xml . ", status = '". (int) $modification->status . "', date_added = '" . date('Y-m-d H:i:s') . "'");
            }
        }
    }
}
