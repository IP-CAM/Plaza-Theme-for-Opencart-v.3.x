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
        return;
    }
}