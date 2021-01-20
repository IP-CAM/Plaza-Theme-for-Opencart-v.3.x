<?php
class ModelPlazaEngine extends Model
{
    public function setupEngine() {
        $this->setPermission();

        $this->load->model('plaza/modification');
        $this->model_plaza_modification->setModifications();
    }

     public function setPermission() {
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'plaza/engine');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'plaza/engine');
    }
}
