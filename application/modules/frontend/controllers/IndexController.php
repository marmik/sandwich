<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        echo 'eto default md ';
        for ($i=0;$i<10;$i++) {
            echo '<br />';
        }
        echo 'tuta';
    }
}

