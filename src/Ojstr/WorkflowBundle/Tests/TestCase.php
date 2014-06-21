<?php

namespace Ojstr\WorkflowBundle\Tests;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;

class TestCase extends \PHPUnit_Framework_TestCase {

    /**
     * @return array
     */
    protected function getConfig() {
        $yaml = <<<EOF
processes:
    document_proccess:
        start: step_create_doc
        end:   [ step_validate_doc, step_remove_doc ]
        steps:
            step_create_doc:
                roles: [ ROLE_ADMIN, ROLE_USER ]
                next_states:
                    validate:
                        target: step_validate_doc
                    remove:
                        target: step_remove_doc
                    validate_or_remove:
                        type: step_or
                        target:
                            step_validate_doc: "next_state_condition:isClean"
                            step_remove_doc:   ~
            step_validate_doc:
                roles: [ ROLE_ADMIN, ROLE_USER ]
            step_remove_doc:
                roles: [ ROLE_ADMIN ]
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * @return array
     */
    protected function getSimpleConfig() {
        $yaml = <<<EOF
processes:
    document_proccess:
        start:
        steps: []
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * Create the database schema.
     *
     * @param EntityManager $em
     */
    protected function createSchema(EntityManager $em) {
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
        //$schemaTool->dropSchema($em->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());
    }

    /**
     * EntityManager mock object together with annotation mapping driver and
     * pdo_sqlite database in memory
     *
     * @return EntityManager
     */
    protected function getMockSqliteEntityManager() {
        $cache = new \Doctrine\Common\Cache\ArrayCache();

        // xml driver
        $xmlDriver = new \Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver(array(
            __DIR__ . '/../Resources/config/doctrine' => 'Ojstr\WorkflowBundle\Entity',
        ));

        // configuration mock
        $config = $this->getMock('Doctrine\ORM\Configuration');
        $config->expects($this->any())
                ->method('getMetadataCacheImpl')
                ->will($this->returnValue($cache));
        $config->expects($this->any())
                ->method('getQueryCacheImpl')
                ->will($this->returnValue($cache));
        $config->expects($this->once())
                ->method('getProxyDir')
                ->will($this->returnValue(sys_get_temp_dir()));
        $config->expects($this->once())
                ->method('getProxyNamespace')
                ->will($this->returnValue('Proxy'));
        $config->expects($this->once())
                ->method('getAutoGenerateProxyClasses')
                ->will($this->returnValue(true));
        $config->expects($this->any())
                ->method('getMetadataDriverImpl')
                ->will($this->returnValue($xmlDriver));
        $config->expects($this->any())
                ->method('getClassMetadataFactoryName')
                ->will($this->returnValue('Doctrine\ORM\Mapping\ClassMetadataFactory'));
        $config->expects($this->any())
                ->method('getDefaultRepositoryClassName')
                ->will($this->returnValue('Doctrine\\ORM\\EntityRepository'));
        $config->expects($this->any())
                ->method('getQuoteStrategy')
                ->will($this->returnValue(new \Doctrine\ORM\Mapping\DefaultQuoteStrategy()));
        $config->expects($this->any())
                ->method('getRepositoryFactory')
                ->will($this->returnValue(new \Doctrine\ORM\Repository\DefaultRepositoryFactory()));

        $conn = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $em = EntityManager::create($conn, $config);

        return $em;
    }

    /**
     * Returns a mock instance of a SecurityContext.
     *
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    protected function getMockSecurityContext() {
        $authManager = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $decisionManager = $this->getMock('Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface');

        $context = new SecurityContext($authManager, $decisionManager);
        $context->setToken($token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface'));

        return $context;
    }

}
