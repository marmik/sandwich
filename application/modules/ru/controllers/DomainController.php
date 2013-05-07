<?php

class Ru_DomainController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $searchForm = new Form_Search_Select();
        $deleteForm = new Form_Search_Deletetext();
        $searchValues = array();
        
        if ($this->getRequest()->isPost())
        {
            if($searchForm->isValid($this->getRequest()->getPost()))
            {
                $searchValues = $searchForm->getValues();
                $this->_redirect($this->view->url($searchValues));
                
            }
            
            
        }
        $getParams = $this->getRequest()->getParams();
        $searchForm->populate($getParams);
        
        $modelDomain = new Model_Search();
        $paginator = $modelDomain->getDomainList($getParams);
        //get page no from url 
        $page = $this->_request->getParam('page'); 
        $paginator->setCurrentPageNumber($page); 
        
        $this->view->orderFieldName = $this->getRequest()->getParam('order');
        $this->view->orderDirectionField = $this->getRequest()->getParam('direction');
        $this->view->paginator = $paginator; 
        
        $this->view->deleteform = $deleteForm;
        $this->view->searchform = $searchForm;
    }
    
    public function deleteTextAction ()
    {
        $deleteForm = new Form_Search_Deletetext();
        if ($this->getRequest()->isPost())
        {
            if($deleteForm->isValid($this->getRequest()->getPost()))
            {
                $deleteValues = $deleteForm->getValues();
                $deleteProcess = new Model_Search();
                $currentDate = time();
                $numberToDelete = $deleteValues['deletetext'];
                $oldestDate = strtotime(date('Y-m-d')." -$numberToDelete days ", $currentDate); //Form date from which to delete
                $removeDate = date('Y-m-d', $oldestDate);//Form a delete Query
        
                $deleteResponse = $deleteProcess->deleteByDate($removeDate);
                $this->_helper->getHelper('FlashMessenger')->addMessage("Affected rows are:".$deleteResponse );
				
                
                $this->_redirect('domain/index');
                
            }  else {
//                $this->_forward('index','domain');
            }
            
                    
        }
        
        $this->view->deleteform = $deleteForm;
    }
    
    public function testAction ()
    {
        
        
        
    }
}

