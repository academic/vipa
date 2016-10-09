<?php

namespace Ojs\CoreBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class CatalogFilter extends SQLFilter
{
    private $catalogs = [];

    public function setCatalogs($catalogs = [])
    {
        $this->catalogs = $catalogs;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        //if params have asterisk sign, pass catalog filter
        if(in_array('*', $this->catalogs)){
            return '';
        }

        $mappings = $targetEntity->getAssociationMappings();
        if(!array_key_exists('catalogs', $mappings) ||
            $mappings['catalogs']['targetEntity'] !== 'Ojs\JournalBundle\Entity\Catalog'){
            return '';
        }

        //return if journal filter disabled globally for current entity
        if(isset($GLOBALS[$targetEntity->getName().'#catalogFilter']) && $GLOBALS[$targetEntity->getName().'#catalogFilter'] == false){
            return '';
        }

        $addCondSql = $targetTableAlias.'.array_catalogs LIKE \'%'.$this->catalogs[0].'%\'';

        return $addCondSql;
    }
}
