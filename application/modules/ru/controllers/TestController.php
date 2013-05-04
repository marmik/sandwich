<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
		$userMapper = new Mapper_User();
    	$userslist = Mapper_UserTable::getInstance()->findAll();
    	$this->view->list = $userslist;
    	    	
    	$userform = new Form_User_Edit();
		if ($userform->isValid($this->getRequest()->getPost()))
		{
			
			$values = $userform->getValues();
			$userMapper = new Mapper_User();
			$userMapper->fromArray($values);
			$userMapper->save();
		}
		
		$userMapper = new Mapper_User();
		$userMapper->assignIdentifier(1);
		$userMapper->load();
		
//		for ($i=1;$i<11;$i++)
//		{
//			$brandMapper = new Mapper_Brand();
//			$brandMapper->title = "brand ".$i;
//			for ($j=1;$j<11;$j++)
//			{
//				$modelMapper = new Mapper_Model();
//				$modelMapper->title = "model ".$i." ".$j;
//				$brandMapper->Models->add($modelMapper);
//				
//			}
//			$brandMapper->save();
//		}
		$brands = Mapper_NamesTable::getInstance()->findBy('p_id', 0);
		$selectorForm = new Form_Selector();
		$brandsData = array();
		foreach ($brands as $brand)
		{
			$brandsData[$brand->id] = $brand->name;
		}
		$selectorForm->getElement('brand')->addMultiOptions($brandsData);
//		var_dump(Mapper_NamesTable::getInstance()->getSome()->toArray());
//		foreach ($brands as $brand)
//		{
//			$selectorForm->getElement('brand')->addMultiOption($brand->id, $brand->name);
//		}
//		$num = $this->getRequest()->getParam('num');
//		var_dump($num);exit;
//		if (isset($num))
//		{
//			$models = Mapper_NamesTable::getInstance()->findBy('p_id',num);
//			var_dump($num);
//			$modelsData = array();
//			foreach ($models as $model)
//			{
//				$modelsData[$model->id] = $model->name;
//				
//			}
//			$selectorForm->getElement('model')->addMultiOptions($modelsData);
//			
//			
//		}
//		$this->view->selectorForm = $selectorForm;
//		$this->view->heri = $userform;
    }
    
    public function searchnbAction () 
    {
    	$searchform = new Form_Notebooks_Search();
    	if ($this->getRequest()->isPost())
    	{
			if ($searchform->isValid($this->getRequest()->getPost()))
			{
				$values = $searchform->getValues();
				$searchMapper = new Mapper_Notebooks();
				$searchMapper->fromArray($values);
				$searchunit = Mapper_NotebooksTable::getInstance()->likeNote($values['ref']);
				$this->view->ref = $values['ref'];
				$this->view->result = $searchunit;
			}
    	}
		$this->view->heri = $searchform;
    }
	
	public function fillAction ()
	{
		$user = Zend_Auth::getInstance()->getIdentity();
		$fillform = new Form_Notebooks_Fill();
		$id = $this->getRequest()->getParam('id');
		
		if ($this->getRequest()->isPost())
    	{
			if ($fillform->isValid($this->getRequest()->getPost()))
			{
				$values = $fillform->getValues();
				$fillMapper = new Mapper_Notebooks();
				if (!empty($id))
				{
					$fillMapper->assignIdentifier($id);
				}
				$newValues = $values;
				$newValues['ref'] .= $newValues['barcode_id'];
				$fillMapper->fromArray($newValues);
				$fillMapper->user_id = $user->id;
				$fillMapper->save();
				
				$this->_redirect($this->view->url (
					array (
						'module'	 => 'frontend',
				        'controller' => 'index',
				        'action'     => 'searchnb'),
					'default',
					true));
			}
    	} else 
    	{
    		if (!empty($id))
			{
				$fieldsArray = array('ref','item_type');
				foreach ($fieldsArray as $fieldName)
				{
					$fillform->getElement($fieldName)->setAttrib('readonly','readonly');
				}
				$notebook = Mapper_NotebooksTable::getInstance()->findOneBy('id', $id);
				$newData = $notebook->toArray();
				$newData['barcode_id'] = substr($newData['ref'], 6,6);
				$newData['ref'] = substr($newData['ref'], 0,6);
				$fillform->populate($newData);
			} else 
			{
				$ref = $this->getRequest()->getParam('ref');
				$Array = array('ref'=> substr($ref, 0,6),
								'barcode_id' => substr($ref, 0,6)); 
				
				$fillform->populate($Array);
			}
    		
    	}
		
    	
    	
		
		$this->view->fform = $fillform;
		
	}
	
	public function ot4etAction () 
	{
		$filterForm = new Form_Notebooks_Filter();
		if ($this->getRequest()->isPost())
	    	{
				if ($filterForm->isValid($this->getRequest()->getPost()))
				{
					$values = $filterForm->getValues();
					var_dump($values);exit;
					$filterMapper = new Mapper_Notebooks();
					$filterMapper->fromArray($values);
					$filterDate = Mapper_NotebooksTable::getInstance()->getLinesByDate($values['date']);
					$newData = $filterDate->toArray();
					var_dump($newData);exit;
					$this->view->ref = $values['ref'];
					$this->view->result = $filterDate;
				}
	    	}
		
		
		$this->view->filter = $filterForm;
		
	}
	
	public function moldtestAction ()
	{
		$user = Zend_Auth::getInstance()->getIdentity();
		$moldform = new Form_Notebooks_Mold();
		$ref = $this->getRequest()->getParam('ref');
		
		if ($this->getRequest()->isPost())
    	{
			if ($moldform->isValid($this->getRequest()->getPost()))
			{
				
				
				$values = $moldform->getValues();
				$moldMapper = new Mapper_Tested();
//				var_dump($values['id']);exit;
				
				if (!empty($values['id']))
				{
					$moldMapper->assignIdentifier($values['id']);
				}
				$moldMapper->fromArray($values);
				$moldMapper->user_id = $user->id;
				$moldMapper->save();
				
				$this->_helper->getHelper('FlashMessenger')->addMessage($fillMapper->id." ".print_r($values,true));
				$this->_redirect($this->view->url (
					array(
						'module'	 => 'frontend',
				        'controller' => 'index',
				        'action'     => 'searchnb'),
					'default',
					true));
			}
    	} else 
    	{
    		if (!empty($ref))
			{
				$notebook = Mapper_TestedTable::getInstance()->searchNote($ref);
				$newData = $notebook->toArray();
				
				
				if (!empty($newData))
				{
					$moldform->populate($newData['0']);
					
				} else 
				{
					$newData['ref_number']=$ref;
    				$moldform->populate($newData);
				}
			
			}
    		
    	}
    	
    	$this->view->mold = $moldform;
    	
	}
	
	public function testAction()
	{
		$testMapper = Mapper_PartTable::getInstance()->takeall();
		$newArray = $testMapper->toArray();
		$this->view->result = $testMapper;
	}	
	
	public function createnbAction ()
	{
		$user = Zend_Auth::getInstance()->getIdentity();
		$addform = new Form_Notebooks_Add();
		$id = $this->getRequest()->getParam('lId');
		
		if ($this->getRequest()->isPost())
    	{
			if ($addform->isValid($this->getRequest()->getPost()))
			{
				$values = $addform->getValues();
				$addMapper = new Mapper_Laptops();
				if (!empty($id))
				{
					$addMapper->assignIdentifier($id);
				}
				$addMapper->fromArray($values);
				$addMapper->user_id = $user->id;
				$addMapper->save();
				
				$this->_helper->getHelper('FlashMessenger')->addMessage($addMapper->id." ".print_r($values,true));
				
				$this->_redirect($this->view->url (
					array (
						'module'	 => 'frontend',
				        'controller' => 'index',
				        'action'     => 'createnb'),
					'default',
					true));
			}
    	} 
    	
    	if (!empty($id))
    	{
    		$editMapper = Mapper_LaptopsTable::getInstance()->findOneById($id);
    		$newValues = $editMapper->toArray();
    		$filter = new Zend_Filter_LocalizedToNormalized();
			$newValues[0]['screen_size'] = $filter->filter($newValues[0]['screen_size']);
			if ($newValues[0]['mb_cond'] == 'yes')
			{
				$newValues[0]['mb_cond'] = 'YES';
			}
    		
    		
    		$addform->populate($newValues[0]);
    	}
		
		$this->view->aform = $addform;
		
	}
	
	public function searchforzpAction () 
    {
    	$searchform = new Form_Zap4ast_Search();
    	if ($this->getRequest()->isPost())
    	{
			if ($searchform->isValid($this->getRequest()->getPost()))
			{
				$values = $searchform->getValues();
				$searchunit = Mapper_LaptopsTable::getInstance()->searchNote($values['ref']);
				$this->view->ref = $values['ref'];
				$this->view->result = $searchunit;
			}
    	}
		$this->view->heri = $searchform;
    }
	
	public function createzpAction ()
	{
		$user = Zend_Auth::getInstance()->getIdentity();
		$createform = new Form_Zap4ast_Create();
		$lId = $this->getRequest()->getParam('lId');
		
		if ($this->getRequest()->isPost())
    	{
			if ($createform->isValid($this->getRequest()->getPost()))
			{
				$values = $createform->getValues();
				$createMapper = new Mapper_Zapchasti();
				$createMapper->fromArray($values);
				$createMapper->user_id = $user->id;
				$createMapper->save();
				
				$this->_helper->getHelper('FlashMessenger')->addMessage($createMapper->id." ".print_r($values,true));
				
				
				$this->_redirect($this->view->url());
			}
    	} 
				
		$notebook = Mapper_LaptopsTable::getInstance()->findOneById($lId);
		$someData = $notebook->toArray();
		
		$newData['laptop_id'] = $lId;
		$newData['brand'] = $someData[0]['brand'];
		$newData['model'] = $someData[0]['model'];
		$newData['ref_number'] = $someData[0]['evs'];
		
		
		$createform->populate($newData);
			
    	
		$this->view->fform = $createform;
		
	}
	

	
	public function searchAction ()
	{
		$brands = Mapper_LaptopsTable::getInstance()->selectsome();
//		var_dump($brands);
		
		$searchform = new Form_Search_Search();
		foreach ($brands as $k =>$v){
			$searchform->getElement('brand')->addMultiOption($k, $v);
		}
		
		
		if ($this->getRequest()->isPost())
    	{
			if ($searchform->isValid($this->getRequest()->getPost()))
			{
				$values = $searchform->getValues();
				$MapperName = 'Mapper_'.$values['item_type'].'Table';
//				var_dump($MapperName);exit;
				$lMapper = $MapperName;
//				$lTest = call_user_func($MapperName .'::findOneById',$values['ref'] );
//				$TableObjct = call_user_func($MapperName.'::getInstance');
//				$lResult = $TableObjct->findOneById($values['ref']);
//				$lRes = $lResult->toArray();
				
				
				
				
				$addMapper = new Mapper_Motherboards();
				
				if (!empty($id))
				{
					$fillMapper->assignIdentifier($id);
				}
				//$addMapper->save();
				
				
						
			}
    	}
    	
    	$this->view->searchform = $searchform;
	}
	
	
	public function noutsearchAction () 
	{
		$user = Zend_Auth::getInstance()->getIdentity();
		$sform = new Form_Search_Notebook2();
		$newArray = array();
		
		if ($this->getRequest()->isPost())
		{
			if($sform->isValid($this->getRequest()->getPost()))
			{
				$values = $sform->getValues();
				
				foreach ($values as $k => $v)
				{
					if (!empty($v))
					{
						$newArray[$k] = $v;
						
					}					
					
				}
				$someData = Mapper_LaptopsTable::getInstance()->arrayDev($newArray);
//				foreach ($someData as $tempArray)
//				{
//					foreach ($tempArray as $k => $v)
//					{
//						if(stristr($string, 'earth') === FALSE) {
//							echo '"earth" not found in string';
//						}
//					}
//				}
//				var_dump($someData);exit;
				if (empty($someData))
				{
					$this->_helper->getHelper('FlashMessenger')->addMessage("Nothing found. Please select other criteria.");
					$this->_redirect($this->view->url (
					array (
						'module'	 => 'frontend',
				        'controller' => 'index',
				        'action'     => 'noutsearch'),
					'default',
					true));
				}
				$lCount = 0;
				$anotherArray = array();
				foreach ($someData as $temp)
				{
					$lCount++;
					foreach ($temp as $k => $v)
					{
						
						
						
						if ($v == NULL)
						{
							$anotherArray[$k][] = "&nbsp;";
						} else 
						{
							$anotherArray[$k][] = $v;
						}
						
					}
				}
				$this->view->s4et = $lCount;
				$this->view->ses = $anotherArray;
				
			}
			
			
			
		}
		
		$this->view->sform = $sform;
	}
	
	public function createbpAction ()
	{
		$user = Zend_Auth::getInstance()->getIdentity();
		$addform = new Form_Notebooks_Poweru();
		$lId = $this->getRequest()->getParam('id');
		
		if ($this->getRequest()->isPost())
    	{
			if ($addform->isValid($this->getRequest()->getPost()))
			{
				$values = $addform->getValues();
				$addMapper = new Mapper_Powerunits();
				if (!empty($lId))
				{
					$id = substr($lId, 3);
					$addMapper->assignIdentifier($id);
					$values['vnutr_nr'] = $lId;
					$addMapper->fromArray($values);
					
				} else {
					
					$newValues = $values;
					$count = Mapper_PowerunitsTable::getInstance()->findOneByVnutrNr(); 
					$count_num = $count->toArray();
					$newValues['vnutr_nr'] = $newValues['bp_type'];
					
					
					$newValues['vnutr_nr'] .= $count_num[0]['MAX'] +1;
					$addMapper->fromArray($newValues);
				}
				$addMapper->user_id = $user->id;
				$addMapper->save();
				
				if(empty($id)){
					$this->_helper->getHelper('FlashMessenger')->addMessage($addMapper->id." ".print_r($newValues,true));
					
					$this->_redirect($this->view->url (
						array (
							'module'	 => 'frontend',
					        'controller' => 'index',
					        'action'     => 'createbp'),
						'default',
						true));
				}
				else {
					$this->_helper->getHelper('FlashMessenger')->addMessage($addMapper->id." ".print_r($values,true));
					$this->_redirect($this->view->url (
					array (
						'module'	 => 'frontend',
				        'controller' => 'index',
				        'action'     => 'searchpu'),
					'default',
					true));
				}
			}
    	} 
    	
    	if (!empty($lId))
    	{
    		$editMapper = Mapper_PowerunitsTable::getInstance()->findOneById($lId);
    		$getValues = $editMapper->toArray();
    		$getValues[0]['bp_type'] = substr($getValues[0]['vnutr_nr'],0,3);
    		$addform->populate($getValues[0]);
    		
    	}
		
		$this->view->aform = $addform;
		
		
	}
	
	public function searchzpAction () 
    {
    	$searchform = new Form_Search_Zp();
    	if ($this->getRequest()->isPost())
    	{
			if ($searchform->isValid($this->getRequest()->getPost()))
			{
				$values = $searchform->getValues();
				$searchMapper = new Mapper_Notebooks();
				$searchMapper->fromArray($values);
				$searchunit = Mapper_ZapchastiTable::getInstance()->searchNote($values['ref']);
				$this->view->ref = $values['ref'];
				$this->view->result = $searchunit;
			}
    	}
		$this->view->heri = $searchform;
    }
    
    public function showzpAction () 
    {
    	$this->_helper->layout->disableLayout();
    	$user = Zend_Auth::getInstance()->getIdentity();
		$id = $this->getRequest()->getParam('id');
		$notebook = Mapper_ZapchastiTable::getInstance()->searchNote($id);
		$this->view->nook = $notebook;
		
    }
	
	public function partSelectorAction () 
    {
    	$this->_helper->layout->disableLayout();
    	$id = $this->getRequest()->getParam('num');
    	
    	$formName = $this->getRequest()->getParam("form");
    	
    	if (!empty($id))
    	{
    		$formName = "Form_Parts_".$formName;
    		
    		$byNotebook = Mapper_NotebooksTable::getInstance()->findOneById($id);
    		$newData = $byNotebook->toArray();
    		$nArray = array('brand'=> $newData['0']['brand']
						, 'model' => $newData['0']['model']
    					, 'ref_number'=> $newData['0']['ref']);
//    		var_dump($nArray);exit;
    		$fillForm = new $formName();
//    		var_dump(get_class($fillForm));exit;
    		$fillForm->populate($nArray);
    		
    		$this->view->form = $fillForm;
    	}
  		
    }
    
	public function searchpuAction () 
    {
    	$searchform = new Form_Zap4ast_Search();
    	if ($this->getRequest()->isPost())
    	{
			if ($searchform->isValid($this->getRequest()->getPost()))
			{
				$values = $searchform->getValues();
				$searchunit = Mapper_PowerunitsTable::getInstance()->findOneById($values['ref']);
				$this->view->ref = $values['ref'];
				$this->view->result = $searchunit;
				
				
			}
    	}
		$this->view->heri = $searchform;
    }
    
    public function searchmbAction () 
    {
    	$searchform = new Form_Motherboards_Search();
    	$historyForm = new Form_Motherboards_Mbrepair();
    	if ($this->getRequest()->isPost())
    	{
			if ($searchform->isValid($this->getRequest()->getPost()))
			{
				$values = $searchform->getValues();
				$searchHistory = Mapper_MbrepairTable::getInstance()->findOneByZen($values['zen']);
				$this->view->ref = $values['zen'];
				$this->view->result = $searchHistory;
				
				$searchLastEntry = Mapper_MbrepairTable::getInstance()->findlast($values['zen']);
				$searchLastEntryArray = $searchLastEntry->toArray();
				
				
			}
    	
		
		
		
			if ($historyForm->isValid($this->getRequest()->getPost()))
			{
				$historyValues = $historyForm->getValues();
				
				$historyValues['when_given'] = date("Y-m-d", strtotime($historyValues['when_given']));
				if (!empty($historyValues['when_taken']))
				{
					$historyValues['when_taken'] = date("Y-m-d", strtotime($historyValues['when_taken']));
				} 
				
				
				$addMapper = new Mapper_Mbrepair();
				if (!empty($historyValues['id']))
				{
					
					$addMapper->assignIdentifier($historyValues['id']);
				}
				$addMapper->fromArray($historyValues);
				if (!empty($historyValues['goal']) && !empty($historyValues['master_name']))
				{
					$addMapper->save();
					$this->_helper->getHelper('FlashMessenger')
					->addMessage($addMapper->id." ".print_r($values,true));
				
					$this->_redirect($this->view->url());
				}
				
				
				
			}
			if (!empty($searchLastEntryArray))
			{
				$historyForm->populate($searchLastEntryArray[0]);
				
			}
			$this->view->entry = $historyForm;
    	}	
    	$this->view->heri = $searchform;
    }
    
	public function addmbAction ()
	{
		$user = Zend_Auth::getInstance()->getIdentity();
		$addform = new Form_Motherboards_Add();
		$localId = $this->getRequest()->getParam('zen');
		if ($this->getRequest()->isPost())
    	{
			if ($addform->isValid($this->getRequest()->getPost()))
			{
				
				
				$values = $addform->getValues();
				$addMapper = new Mapper_Motherboards();
				$addMapper->fromArray($values);
				$addMapper->user_id = $user->id;
				
				if (!empty($values['id']))
				{
					$addMapper->assignIdentifier($values['id']);
					
				}
				
				$addMapper->save();
				
				$this->_helper->getHelper('FlashMessenger')
					->addMessage($addMapper->id." ".print_r($values,true));
				
				$this->_redirect($this->view->url());
					
			}
    	}
		if (!empty($localId))
    	{
    		$editMapper = Mapper_MotherboardsTable::getInstance()->findOneByZen($localId);
    		$getValues = $editMapper->toArray();
    		
    		$addform->populate($getValues[0]);
    		
    	}
    	
    	$this->view->aform = $addform;
	}
    
}

