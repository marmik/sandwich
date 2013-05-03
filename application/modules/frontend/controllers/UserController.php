<?php

class UserController extends Zend_Controller_Action {
    public function indexAction() {
        Zend_Debug::dump('UserController');
    }

    public function loginAction() {

        $form = new Form_User_Login();
        if ( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

            $modelUser = new Model_User();
            $result = $modelUser->authenticate($form);
            $slysAuth = Zend_Auth::getInstance();

            switch($result->result) {
                case "OK":
                case "RESET":
                    $slysAuth->getStorage()->write($result->identity);
                    $this->_helper->getHelper('FlashMessenger')->addMessage('You are successful logged!');
                    $this->_helper->redirector->gotoUrlAndExit($this->getRequest()->getRequestUri());
                    break;

                case "INACTIVE":
                    $form->setDecorators(array('Errors', 'FormElements', 'Form'));
                    $form->addError('Your account is inactive.');
                    break;

                case "ERROR":
                    $form->setDecorators(array('Errors', 'FormElements', 'Form'));
                    $form->addError('Wrong combination of username and passwor.');
                    break;

                default:
                    throw new RuntimeException('Unsupported result');
            }

        }
        $this->view->form = $form;
    }

    public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->getHelper('FlashMessenger')->addMessage('You are successfuly logged out!');
        $this->_helper->redirector->gotoUrlAndExit('/');
	}

    protected function _getAuthAdapter($username, $password)
    {
        $authAdapter = new ZendX_Doctrine_Auth_Adapter(Doctrine_Manager::getInstance()->getCurrentConnection());
        $authAdapter->setTableName('Mapper_User u')
            ->setIdentityColumn('u.email')
            ->setCredentialColumn('u.password')
            ->setIdentity($username)
            ->setCredential(md5($password));


        return $authAdapter;
    }

    public function resetAction() {
        $form = new Form_User_ResetPassword();
        $this->view->form = $form;
        if ( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {
            $modelUser = new Model_User();
            $user = $modelUser->getUser(Zend_Auth::getInstance()->getIdentity()->id);
            $user->fromArray($form->getValues());
            $user->reset_password = false;
            $user->save();

            Zend_Auth::getInstance()->clearIdentity();
            Zend_Auth::getInstance()->getStorage()->write($user);

            $this->_helper->getHelper('FlashMessenger')->addMessage('Your password was saved!');
            $this->_helper->redirector->gotoUrlAndExit($this->getRequest()->getRequestUri());
        }
    }

}

