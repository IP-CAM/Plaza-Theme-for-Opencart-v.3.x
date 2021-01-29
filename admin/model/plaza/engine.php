<?php
class ModelPlazaEngine extends Model
{
    public function setupEngine() {
        $this->setPermission();

        $this->load->model('plaza/modification');
        $this->model_plaza_modification->setModifications();

        $this->load->model('plaza/content_builder');
        $this->model_plaza_content_builder->setup();

        $this->load->model('plaza/layout');
        $this->model_plaza_layout->setup();
    }

    public function setPermission() {
        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'plaza/engine');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'plaza/content_builder');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'plaza/module');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'plaza/layout');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'plaza/engine');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'plaza/content_builder');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'plaza/module');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'plaza/layout');
    }

    public function displayMenuFeatures($route = "") {
        $this->load->language('plaza/engine');

        $menuItems = array();

        if ($this->user->hasPermission('access', 'plaza/engine')) {
            $menuItems[] = array(
                'text' => $this->language->get('text_general'),
                'href' => $this->url->link('plaza/engine', 'user_token=' . $this->session->data['user_token'], true),
                'class' => $route == 'engine' ? 'active' : ''
            );
        }

        if ($this->user->hasPermission('access', 'plaza/content_builder')) {
            $menuItems[] = array(
                'text' => $this->language->get('text_content_builder'),
                'href' => $this->url->link('plaza/content_builder', 'user_token=' . $this->session->data['user_token'], true),
                'class' => $route == 'content_builder' ? 'active' : ''
            );
        }

        if ($this->user->hasPermission('access', 'plaza/layout')) {
            $menuItems[] = array(
                'text' => $this->language->get('text_page_layouts'),
                'href' => $this->url->link('plaza/layout', 'user_token=' . $this->session->data['user_token'], true),
                'class' => $route == 'layout' ? 'active' : ''
            );
        }

        if ($this->user->hasPermission('access', 'plaza/module')) {
            $menuItems[] = array(
                'text' => $this->language->get('text_modules'),
                'href' => $this->url->link('plaza/module', 'user_token=' . $this->session->data['user_token'], true),
                'class' => $route == 'module' ? 'active' : ''
            );
        }

        return $menuItems;
    }
}
