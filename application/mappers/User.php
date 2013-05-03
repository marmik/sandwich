<?php

/**
 * Mapper_User
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Mapper_User extends Mapper_Base_User
{
    public $plain_password = '';
    public $sendRegistered = false;

    public function setPassword($password)
    {
        $this->plain_password = $password;
        return $this->_set('password', md5($password));
    }

    public function sendRegisteredEmail()
    {
        $mail = new Base_Email_Type_UserRegistered();
        $mail->setToUser($this);
        $mail->send();
    }
}