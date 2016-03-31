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

        $objectManagerAlias = $this->params()->fromRoute('objectManager');
        $objectManager = $this->getServiceLocator()->get($objectManagerAlias);

        $metadataFactory = $objectManager->getMetadataFactory();

        print_r($metadataFactory->getLoadedMetadata());

        $console->write("End Metadata\n", Color::GREEN);
    }
}
