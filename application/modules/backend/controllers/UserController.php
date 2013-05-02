<?php
class Backend_UserController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_helper->redirector->gotoUrlAndExit($this->view->url(array('action'=>'list')));
    }

    public function listAction()
    {
        $modelUser = new Model_User();
        $paginator = $modelUser->getUsersList();
        $this->view->paginator = $paginator;
    }

    public function editAction()
    {
        $modelUser = new Model_User();
        $form = new Form_User_Edit();

        $this->view->form = $form;

        $user = $modelUser->getUser($this->getRequest()->getParam('id'));
        if($user) {
            $form->populate($user->toArray());
        }

        if ( $this->getRequest()->isPost() ) {
            if ( $form->isValid( $this->getRequest()->getPost() ) ) {
                $user = $modelUser->saveForm($form);

                if($user->sendRegistered) {
                    $user->sendRegisteredEmail();
                }

                $this->_helper->getHelper('FlashMessenger')->addMessage('User successfully saved!');
                $this->_helper->redirector->goToRoute(array(
                                                            'module' => 'backend',
                                                            'controller' => 'user',
                                                            'action' => 'edit',
                                                            'id' => $user->id
                                                       ), 'default', true);


            }
        }
    }
}

