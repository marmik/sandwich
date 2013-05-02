<?php

class Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

	/**
	 * Copy of ACL object
	 * @var Base_Acl
	 */
	protected $_acl;

	/**
	 * Copy of identity object
	 * @var Doctine_Record|array|null
	 */
	protected $_identity;

	protected $_currentRole;


	public function  __construct(Base_Acl $acl)
	{
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
		$this->_acl = $acl;
	}

	/**
	 * Rerturn current ACL object
	 * @return Base_Acl
	 */
	public function getAcl()
	{
		return $this->_acl;
	}

	/**
	 * Check if allowed current request
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 * @param string $params
	 * @return boolean
	 */
	public function isAllowed($action = null, $controller = null, $module = null, $params = array(), $role = null)
	{
		$request = new Zend_Controller_Request_Simple($action, $controller, $module, $params);
		return $this->_isAllowed($request, $role);
	}

	public function  routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->_identity = Zend_Auth::getInstance()->getIdentity();

        if(!empty($this->_identity->account_type)) {
            $this->_currentRole = strtolower($this->_identity->account_type);
        }
        else {
            $this->_currentRole = 'guest';
        }

        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($this->_currentRole);
    }

	public function  preDispatch(Zend_Controller_Request_Abstract $request)
	{
		if(!$this->_isAllowed($request)) {
			Zend_Controller_Front::getInstance()
						->getPlugin('Zend_Layout_Controller_Plugin_Layout')
						->getLayout()
						->setLayout('login');

			$request
				->setActionName('login')
				->setControllerName('user')
				->setModuleName('frontend');
            return;
		}

        if(!empty($this->_identity) && $this->_identity->active == 0)
        {
            Zend_Auth::getInstance()->clearIdentity();

            Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
                    ->addMessage('This account is inactive! Please contact support!');

            Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')
                    ->gotoUrlAndExit('/');

            return;
        }
	}

	protected function _isAllowed(Zend_Controller_Request_Abstract $request, $role = null)
	{
		$resource = $request->getModuleName().'.'.$request->getControllerName();
		$privilege = $request->getActionName();

		if(empty($role))
			$role = $this->_currentRole;

		return $this->_acl->isAllowed($role, $resource, $privilege);
	}
}