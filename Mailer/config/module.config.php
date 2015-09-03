<?php

namespace Mailer;

return [
    'mailer'          => array(
        'notification' => array(
            'transport'       => 'Mailer/Notification/Transport',
            'log'          => 'Mailer/Notification/Log',
            'mailSettings' => array(
                'defaultFrom'     => 'noreply@sandbox.in.ua',
                'defaultFromName' => 'Sandbox Notification',
                'encoding'        => 'UTF-8',
            )
        ),
    ),
    'service_manager' => [
        'invokables' => [
            'Mailer/Model/Notification'                => 'Mailer\Model\NotificationModel',
            'Mailer/Grid/Notification'                 => 'Mailer\Grid\NotificationGrid',
//            'Mailer/Service/NotificationEventListener' => 'Mailer\Service\NotificationEventListener',
        ],
        'factories'  => array(
            'Mailer/Service/NotificationEventListener' => 'Mailer\Factory\NotificationEventListenerFactory',
            'Mailer/Notification/Transport' => function($sm){
                $transport = new \Zend\Mail\Transport\Smtp();
                $transport->setOptions(
                    new \Zend\Mail\Transport\SmtpOptions(array(
                        'name'              => 'mandrillapp.com',
                        'host'              => 'smtp.mandrillapp.com',
                        'port'              => 587,
                        'connection_class'  => 'login',
                        'connection_config' => array(
                            'username' => 'shturman.p@gmail.com',
                            'password' => 'd8SrF30PZI1ps2uDSM_XeA',
                        ),
                    ))
                );
                return $transport;
            },
            'Mailer/Notification/Log' => function($sm){
                $logger = new \Zend\Log\Logger();
                $logger->addWriter(
                    new \Zend\Log\Writer\Stream(dirname(dirname(dirname(__DIR__))).'/data/log/notification/'.date('Y-m-d') . '.log')
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
