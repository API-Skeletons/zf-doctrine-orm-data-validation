<?php

return [
    'controllers' => [
        'invokables' => [
            'ZF\Doctrine\ORM\DataValidation\Controller\ForeignKey' =>
                'ZF\Doctrine\ORM\DataValidation\Controller\ForeignKeyController',
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'zf-doctrine-orm-data-validation-scan' => [
                    'options' => [
                        'route' => 'orm:data-validation:relationship --object-manager=',
                        'defaults' => [
                            'controller' => 'ZF\Doctrine\ORM\DataValidation\Controller\ForeignKey',
                            'action' => 'relationship',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
