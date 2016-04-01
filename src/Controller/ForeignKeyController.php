<?php

namespace ZF\Doctrine\ORM\DataValidation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Console\Request as ConsoleRequest;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt;
use DoctrineDataFixtureModule\Loader\ServiceLocatorAwareLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use RuntimeException;
use NoteVault\Entity;

class ForeignKeyController extends AbstractActionController
{
    public function relationshipAction()
    {
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console.');
        }

        $console = $this->getServiceLocator()->get('console');

        $objectManagerAlias = $this->params()->fromRoute('object-manager');
        $objectManager = $this->getServiceLocator()->get($objectManagerAlias);

        $console->write(
            'Count'
            . "\t"
            . 'Child Field'
            . "\t"
            . 'Child Entity'
            . "\t"
            . 'Parent Entity'
            . "\n"
        );

        $allMetadata = $objectManager->getMetadataFactory()->getAllMetadata();
        foreach ($allMetadata as $metadata) {

            foreach ($metadata->getAssociationMappings() as $mapping) {
                if ($mapping['type'] == 2) {
                    $queryBuilder = $objectManager->createQueryBuilder();
                    $queryBuilder2 = $objectManager->createQueryBuilder();

                    $queryBuilder->select(
                        "count(child) as ct, '"
                        . $mapping['fieldName']
                        . "' as childField, '"
                        . $metadata->getName()
                        . "' as childEntity, '"
                        . $mapping['targetEntity']
                        . "' as parentEntity"
                        )
                        ->from($metadata->getName(), 'child')
                        ->andWhere($queryBuilder->expr()->not(
                            $queryBuilder->expr()->exists(
                                $queryBuilder2->select('parent')
                                    ->from($mapping['targetEntity'], 'parent')
                                    ->andWhere($queryBuilder2->expr()->eq(
                                        'child.' . $mapping['fieldName'],
                                        'parent'
                                    ))
                                    ->getQuery()
                                    ->getDql()
                                )
                            )
                        )
                        ;

                    // Do not query columns which are nullable and are null
                    $childFieldMapping = $objectManager->getMetadataFactory()
                        ->getMetadataFor($metadata->getName())
                        ->getAssociationMapping($mapping['fieldName'])
                        ;

                    if (! isset($childFieldMapping['joinColumns'][0]['nullable'])
                        || ! $childFieldMapping['joinColumns'][0]['nullable']) {

                        $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('child.' . $mapping['fieldName']));
                    }

                    $result = $queryBuilder->getQuery()->getOneOrNullResult();

                    if ($result['ct']) {
                        $childMapping = $objectManager->getMetadataFactory()
                        ->getMetadataFor($metadata->getName());

                        $console->write($result['ct']
                            . "\t"
                            . $result['childField']
                            . "\t"
                            . $result['childEntity']
                            . "\t"
                            . $result['parentEntity']
                            . "\t"
                            . $queryBuilder->getQuery()->getSql()
                            . "\n"
                        );
                    }
                }
            }
        }
    }
}
