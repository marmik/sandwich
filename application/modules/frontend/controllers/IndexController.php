<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $mapper = new Mapper_UserTable();
        $result = $mapper->findCarsByModelId('en');
        var_dump($result);
    }
}

