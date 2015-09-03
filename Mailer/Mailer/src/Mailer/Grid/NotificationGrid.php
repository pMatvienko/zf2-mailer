<?php
namespace Mailer\Grid;

use DataGrid\DataGrid;

class NotificationGrid extends DataGrid
{
    public function __construct()
    {
        $this->setId('ntf');
        $this->setDefaultOrder('id', 'asc');
        $this
            ->appendCell(array(
                'type' => 'text',
                'content' => '{$id}',
                'label' => 'id',
                'orderBy' => 'id',
                'attribs' => array(
                    'cell:style' => 'width:65px; text-align:center;',
                ),
            ), 'identifier')
            ->appendCell(array(
                'type' => 'text',
                'content' => '{$slug}',
                'label' => 'slug',
                'orderBy' => 'slug',
            ), 'slug')

            ->appendCell(array(
                'type' => 'text',
                'content' => '{$locale.subject}',
                'label' => 'subject',
                'orderBy' => 'subject',
            ), 'metaTitle')
            ->appendCell(array(
                'type'    => 'boolean',
                'content' => 'applyToNewUser',
                'label'   => 'apply-to-new-user',
                'attribs' => array(
                    'cell:style'       => 'width:120px; text-align:center;',
                    'element:disabled' => 'disabled',
                    'element:name'     => 'applyToNewUser[{$id}]'
                ),
            ), 'isPublished')
            ->appendCell(array(
                'type'    => 'boolean',
                'content' => 'userCanUnsubscribe',
                'label'   => 'user-can-unsubscribe',
                'attribs' => array(
                    'cell:style'       => 'width:120px; text-align:center;',
                    'element:disabled' => 'disabled',
                    'element:name'     => 'userCanUnsubscribe[{$id}]'
                ),
            ), 'userCanUnsubscribe')
            ->appendCell(array(
                'type' => 'text',
                'content' => '{$modifiedTime:|date%d M, Y; H:i}',
                'label' => 'modifiedTime',
                'orderBy' => 'modifiedTime',
            ), 'modifiedTime')
            ->appendCell(array(
                'type' => 'union',
                'label' => '',
                'joinBy' => '&nbsp;&nbsp;',
                'attribs' => array(
                    'cell:style' => 'width:55px; text-align:center;',
                ),
                'content' => array(
                    array(
                        'type' => 'action',
                        'label' => 'actions',
                        'content' => array(
                            'action' => 'edit',
                            'id' => '{$id}'
                        ),
                        'attribs' => array(
                            'element:class' => 'glyphicon glyphicon-edit',
                        )
                    ),
                    array(
                        'type' => 'action',
                        'label' => 'actions',
                        'content' => array(
                            'action' => 'remove',
                            'id' => '{$id}'
                        ),
                        'attribs' => array(
                            'element:class' => 'glyphicon glyphicon-trash',
                        )
                    )
                )
            ))
        ;
    }
}