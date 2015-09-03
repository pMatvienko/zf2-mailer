<?php

namespace Mailer;

return [
    'mailer'          => array(
        'notification' => array(
            'transport'       => 'Mailer\Notification\Transport',
            'log'          => 'Mailer\Notification\Log',
            'mailSettings' => array(
                'defaultFrom'     => '',
                'defaultFromName' => '',
                'encoding'        => '',
            )
        ),
    ),
    'service_manager' => [
        'invokables' => [
            'Mailer\Model\Notification'                => 'Mailer\Model\NotificationModel',
            'Mailer\Grid\Notification'                 => 'Mailer\Grid\NotificationGrid',
        ],
        'factories'  => array(
            'Mailer\Service\NotificationEventListener' => 'Mailer\Factory\NotificationEventListenerFactory',
            'Mailer\Notification\Log' => function($sm){
                $logger = new \Zend\Log\Logger();
                $logger->addWriter(
                    new \Zend\Log\Writer\Stream('data/log/notification/'.date('Y-m-d') . '.log')
                );
                return $logger;
            }
        )
    ],
    'view_manager'    => [
        'template_path_stack' => [
            'mailer' => __DIR__ . '/../view',
        ],
    ],
    'translator'      => [
        'translation_file_patterns' => [
            [
                'type'     => 'PhpArray',
                'base_dir' => __DIR__ . '/../i18n',
                'pattern'  => '%s.php',
            ],
        ],
    ],
    'doctrine'        => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity']
            ],
            'orm_default'             => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'moxiemanager'    => [
        'presets' => array(
            'mailer' => array(
//                'endpoint' => '',
                'configuration' => array(
                    'filesystem.rootpath' => $_SERVER['DOCUMENT_ROOT'] . "/uploads/sp",
                )
            )
        ),
    ],
    'richeditor'      => array(
        'mailer' => array(
            'type'         => 'tiny',
            'moxiemanager' => 'mailer',
            'options'      => array(
                'theme'         => 'modern',
                'plugins'       => array(
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ),
                'toolbar1'      => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                'toolbar2'      => "print preview media | forecolor backcolor emoticons",
                'image_advtab'  => true,
                'relative_urls' => false,
                'convert_urls'  => false,
            ),
        )
    )
];
