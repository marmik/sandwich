<?php

class Plugin_Layout extends Zend_Controller_Plugin_Abstract
{
    public function  preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if($request->getModuleName() == 'backend') 
        {
            Zend_Registry::set('Zend_Navigation', new Zend_Navigation($this->getNavigation('backend')));
            
            Zend_Controller_Front::getInstance()
                ->getPlugin('Zend_Layout_Controller_Plugin_Layout')
                ->getLayout()
                ->setLayout('admin');
        } elseif ($request->getModuleName() == 'editor') 
        {
            Zend_Registry::set('Zend_Navigation', new Zend_Navigation($this->getNavigation('editor')));
            
            Zend_Controller_Front::getInstance()
                                            ->getPlugin('Zend_Layout_Controller_Plugin_Layout')
                                            ->getLayout()
                                            ->setLayout('layout');
        } else {
            Zend_Registry::set('Zend_Navigation', new Zend_Navigation($this->getNavigation()));
        }

    }
        
    private function getNavigation($type = 'frontend') 
    {
        $pages['editor'] = array(
             
            array(
                'label' => 'Permissions',
                'module' => 'editor',
                'controller' => 'permission',
                'action' => 'index'
            ),
            array(
                'label' => 'Specifying',
                'module' => 'editor',
                'controller' => 'specific',
                'action' => 'index',
                'pages' => array(
                    array(
                        'label' => 'Permission',
                        'module' => 'editor',
                        'controller' => 'specific',
                        'action' => 'permission',
                    )
                )
            ),
            array(
                'label' => 'Groups',
                'module' => 'editor',
                'controller' => 'group',
                'action' => 'index',
                'pages' => array(
                    array(
                        'label' => 'Modify',
                        'module' => 'editor',
                        'controller' => 'group',
                        'action' => 'modify',
                    ),
                    array(
                        'label' => 'New',
                        'module' => 'editor',
                        'controller' => 'group',
                        'action' => 'new',
                    ),
                )
            ),
            array(
                'label' => 'Photos',
                'module' => 'editor',
                'controller' => 'photo',
                'action' => 'index'
            ),
        );
        
        $pages['backend'] = array(
             
            array(
                'label' => 'View GOD\'s base',
                'module' => 'backend',
                'controller' => 'tester',
                'action' => 'index',
                'pages' => array(
                    array(
                        'label' => 'List',
                        'module' => 'backend',
                        'controller' => 'tester',
                        'action' => 'list',
                    )
                )
            )
        );
        
        $pages['frontend'] = array(
             
            array(
                'label' => 'Index',
                'module' => 'editor',
                'controller' => 'index',
                'action' => 'index'
            ),
        );
        
        return $pages[$type];
        
        
    }

}