<?php

namespace Ojs\Common\Form\ChoiceList;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader as BaseORMQueryBuilderLoader;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class ORMQueryBuilderLoader extends BaseORMQueryBuilderLoader
{

    /** @var QueryBuilder */
    private $queryBuilder;

    /**
     * Construct an ORM Query Builder Loader.
     *
     * @param QueryBuilder|\Closure $queryBuilder
     * @param ObjectManager         $manager
     * @param string                $class
     *
     * @throws UnexpectedTypeException
     */
    public function __construct($queryBuilder, $manager = null, $class = null)
    {
        // If a query builder was passed, it must be a closure or QueryBuilder
        // instance
        if (!($queryBuilder instanceof QueryBuilder || $queryBuilder instanceof \Closure)) {
            throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder or \Closure');
        }

        if ($queryBuilder instanceof \Closure) {
            if (!$manager instanceof EntityManager) {
                throw new UnexpectedTypeException($manager, 'Doctrine\ORM\EntityManager');
            }

            $queryBuilder = $queryBuilder($manager->getRepository($class));

            if (!$queryBuilder instanceof QueryBuilder) {
                throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder');
            }
        }

        $this->queryBuilder = $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntities()
    {
        $query = $this->queryBuilder->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->execute();
    }
}
