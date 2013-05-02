<?php
class Backend_Bootstrap extends Zend_Application_Module_Bootstrap {

    protected function _initNavigation() {
        $pages = array(
            array(
                'label' => 'Dashboard',
                'module' => 'backend',
                'controller' => 'index',
                'action' => 'index',
            ),
            array(
                'label' => 'Users',
                'module' => 'backend',
                'controller' => 'user',
                'action' => 'index',
                'pages' => array(
                    array(
                        'label' => 'List',
                        'module' => 'backend',
                        'controller' => 'user',
                        'action' => 'list',
                    ),
                    array(
                        'label' => 'New',
                        'module' => 'backend',
                        'controller' => 'user',
                        'action' => 'edit',
                    ),
                )
            )
        );

        Zend_Registry::set('Zend_Navigation', new Zend_Navigation($pages));
    }

}