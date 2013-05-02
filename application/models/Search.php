<?php
class Model_Search {

    /**
     * @param int $id
     * @return Mapper_User
     */
    public function getDomain($id) {
        return Mapper_UserTable::getInstance()->getUser($id)->fetchOne();
    }
    
    public function getDomainList($filterParams = null) {
        $adapter = new Base_Paginator_Adapter_DoctrineQuery(Mapper_DomainsTable::getInstance()->getDomains($filterParams));

        return new Zend_Paginator($adapter);
    }
    
    public function deleteByDate($deleteDate) {
        return Mapper_DomainsTable::getInstance()->deleteByDate($deleteDate);
    }

    
}