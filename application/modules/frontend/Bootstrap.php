<?php
class Frontend_Bootstrap extends Zend_Application_Module_Bootstrap {
    public function _initResources()
    {
        $pages = array(
            array(
                'label' => 'Dashboard',
                'module' => 'frontend',
                'controller' => 'index',
                'action' => 'index',
            ),
            array(
                'label' => 'Users',
                'module' => 'frontend',
                'controller' => 'user',
                'action' => 'index',
            ),
            array(
                'label' => 'List',
                'module' => 'frontend',
                'controller' => 'user',
                'action' => 'list',
            ),
            array(
                'label' => 'New',
                'module' => 'frontend',
                'controller' => 'user',
                'action' => 'edit',
            ),
        
            
        );

        Zend_Registry::set('Zend_Navigation', new Zend_Navigation($pages));
    }
}