<?php

/**
 * Mapper_DomainsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Mapper_DomainsTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Mapper_DomainsTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Mapper_Domains');
    }
    
    public function fetchDelete($deleteList)
    {
        $q =  Doctrine_Query::create()
                ->delete()
                ->from('Mapper_Domains d')
                ->whereIn('d.id ', array_values($deleteList));
        return $q->execute();
    }
    
    public function deleteByDate ($deleteDate)
    {
        $q =  Doctrine_Query::create()
                ->delete()
                ->from('Mapper_Domains d')
                ->where('date_insert < ?', $deleteDate);
        return $q->execute();
        
    }
    
    public function getDomains($filterParams = null)
    {
        $q = Doctrine_Query::create()
                ->select()
                ->from('Mapper_Domains d');
        if (!empty($filterParams['search']))
        {
            $q->andWhere('content LIKE ?', "%".$filterParams['search']."%");
        }
        if (!empty($filterParams['from']))
        {
            $q->andWhere('date_insert > ?', $filterParams['from']);
        }
        if (!empty($filterParams['to']))
        {
            $q->andWhere('date_insert < ?', $filterParams['to']);
        }
        if (!empty($filterParams['wonumbers']))
        {
            $q->andWhere('content NOT REGEXP ?', "[0-9]");
        }
        if (!empty($filterParams['wodashes']))
        {
            $q->andWhere('content NOT REGEXP ?', "[[.hyphen.]]");
        }
        $orderFieldMapper = array('domainName' => 'content', 'dateExpire' =>'date_insert');
        $directionField = array('desc','asc');
        if (!empty($filterParams['order']) && in_array($filterParams['order'], array_keys($orderFieldMapper)))
        {
            $orderDirection = 'asc';
            if (in_array(strtolower($filterParams['direction']), $directionField))
            {
                $orderDirection = strtolower($filterParams['direction']);
            }
            $q->orderBy($orderFieldMapper[$filterParams['order']]." ".$orderDirection);
        }
        
        return $q;
    }
}