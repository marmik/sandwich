<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAppAutoload() {
        $moduleLoad = new Zend_Application_Module_Autoloader(array(
            'namespace' => ''
            ,'basePath' => APPLICATION_PATH
        ));

//        $this->bootstrap('modules');
//        Zend_Debug::dump(get_class($this->getResourceLoader()));
        $moduleLoad->addResourceTypes(array(
            'doctrine_mappers' => array(
                'namespace' => 'Mapper',
                'path'      => 'mappers',
            )));

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('partials/pagination/default.phtml');
//        var_dump($this->getApplication()->getAutoloader()->getAutoloaders());
    }

    protected function _initAcl() {
		$this->bootstrap('Frontcontroller');
		$options = $this->getOption('acl');
		$config = new Zend_Config_Ini($options['config']);

		if(!empty($options['config'])) {
			$this->getResource('Frontcontroller')->registerPlugin(
				new Plugin_Acl(new Base_Acl($config))
			);
		}
	}

	protected function _initJavaScript()
	{
		Zend_Dojo_View_Helper_Dojo::useDeclarative();
	}

    protected function _initDoctrine() {
        $this->getApplication()->getAutoloader()->pushAutoloader(array('Doctrine', 'autoload'));
        spl_autoload_register(array('Doctrine', 'modelsAutoload'));

        $doctrineManager = Doctrine_Manager::getInstance();
        $doctrineManager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, TRUE);
        $doctrineManager->setAttribute(
            Doctrine::ATTR_MODEL_LOADING
            , Doctrine::MODEL_LOADING_AGGRESSIVE
        );
        $doctrineManager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, TRUE);

        $doctrineConfig = $this->getOption('doctrine');
        Doctrine::loadModels($doctrineConfig['models_path']);

        $connection = Doctrine_Manager::connection($doctrineConfig['dsn'], 'doctrine');
        $connection->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);
        return $connection;
    }

    protected function _initSession() {
        $lSession = new Zend_Session_Namespace('LIC');
        Zend_Registry::set('appSession', $lSession);
    }


    protected function _initRegistry() {

        $lRegistrys = $this->getOption('registry') === null ? array() : $this->getOption('registry');
        foreach ($lRegistrys as $lRegistryName => $lRegistryValue)
        {
            Zend_Registry::set($lRegistryName, $lRegistryValue);
        }


    }
}