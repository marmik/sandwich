<?php

class AjaxController extends Zend_Controller_Action
{
    public function getAutocompleteAction()
    {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);

    	$value = $this->getRequest()->getParam('name');
    	$fieldName = $this->getRequest()->getParam('fn');
    	$value = substr($value, 0, strlen($value)-1);

    	$resList = Mapper_NotebooksTable::getInstance()->getOnlyOneFieldDistinct($fieldName, $value) ;
    	$resArray = array();
    	foreach ($resList as $resRow)
    	{
			$resArray[$resRow['n_name']] = $resRow['n_name'];
    	}

		$this->_helper->autoCompleteDojo($resArray);
    }
    
    public function deleteSelectedAction()
    {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
        
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $recievedIds = $this->getRequest()->getPost();
            
            
            if(!empty($recievedIds))
            {
                $modelDomain = new Model_Ajax();
                $response = $modelDomain->fetchDeleteList($recievedIds);

                
                if ($response)
                {
                    $whoisReturnText =  "Selected domains removed from list";
                }        
                else {
                    $whoisReturnText =  "Something went wrong";
                }
            } else {
                $whoisReturnText = "Please select at least 1 field";
            }
            
            
            
        
            $this->_helper->json($whoisReturnText);
        }
        else {
            // if it's not an ajax request then do regular handling here
        }
    	
    }
    
    public function takeAutocompleteAction()
    {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);

    	$value = $this->getRequest()->getParam('name');
    	$fieldName = $this->getRequest()->getParam('fn');
    	$value = substr($value, 0, strlen($value)-1);

    	$resList = Mapper_LaptopsTable::getInstance()->getOnlyOneFieldDistinct($fieldName, $value) ;
    	
    	$resArray = array();
    	foreach ($resList as $resRow)
    	{
			$resArray[$resRow['l_name']] = $resRow['l_name'];
    	}

		$this->_helper->autoCompleteDojo($resArray);
    }
    
    public function testAjaxAction ()
    {
        $this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);

        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $recievedName = $this->getRequest()->getParam('site');
            // do the handling of your ajax request
            require_once 'Whois/phpwhois/whois.main.php';
            $whoisSearch = new Whois();
            $recievedName = str_replace("_",".",$recievedName);
            $whoisSearch->deep_whois = false;
            $result = $whoisSearch->Lookup($recievedName,false);
            
            if ($result['regrinfo']['registered'] == 'yes')
            {
                $whoisReturnText =  "<div style=\"background-color:red\">Domain has been registered</div>";
            }        
            else {
                $whoisReturnText =  "<div style=\"background-color:green\">Domain not registered</div>";
            }
            
//            var_dump($myArrayofData);

            //encode your data into JSON and send the response
            $this->_helper->json($whoisReturnText);
            //nothing else will get executed after the line above

        }
        else {
            // if it's not an ajax request then do regular handling here
        }
        
        
    }
}
