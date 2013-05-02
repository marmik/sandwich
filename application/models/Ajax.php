<?php
class Model_Ajax {

    /**
     * @param int $id
     * @return Mapper_Ajax
     */
    public function getDomain($id) {
        return Mapper_UserTable::getInstance()->getUser($id)->fetchOne();
    }
    
    public function fetchDeleteList($deleteList) {
        return Mapper_DomainsTable::getInstance()->fetchDelete($deleteList);
    }
    
    public function getDomainList($filterParams = null) {
        $adapter = new Base_Paginator_Adapter_DoctrineQuery(Mapper_DomainsTable::getInstance()->getDomains($filterParams));

        return new Zend_Paginator($adapter);
    }

    
}