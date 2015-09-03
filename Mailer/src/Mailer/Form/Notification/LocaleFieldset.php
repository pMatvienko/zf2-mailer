<?php

namespace Mailer\Form\Notification;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class LocaleFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name='locale')
    {
        parent::__construct($name);
        $this
            ->add(
                array(
                    'name' => 'languageId',
                    'type' => 'Hidden',
                )
            )
            ->add(array(
                'name' => 'nameFrom',
                'type' => 'Text',
                'options' => array(
                    'label' => 'name-from',
                ),
            ))
            ->add(array(
                'name' => 'subject',
                'type' => 'Text',
                'options' => array(
                    'label' => 'subject',
                ),
                'attributes' => array(
                    'role' => 'pipe-variables',
                ),
            ))
            ->add(array(
                'name' => 'notificationText',
                'type' => 'Textarea',
                'options' => array(
                    'label' => 'notification-text',
                ),
                'attributes' => array(
                    'rows' => '10',
                    'role' => 'pipe-variables',
                ),
            ))
            ->add(
                array(
                    'name' => 'notificationHtml',
                    'type' => 'SbxCommon\Form\Element\RichEditor',
                    'options' => array(
                        'label' => 'notification-html',
                        'editorVersion' => 'mailer',
                    ),
                    'attributes' => array(
                        'rows' => '30',
                    ),
                )
            );
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        if(!$this->isHaveData()){
            return array();
        };
        return array(
            'subject' => array(
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ),
            'notificationHtml' => array(
                'required' => true,
            ),
        );
    }

    public function isHaveData()
    {
        foreach ($this as $name => $item) {
            if($item->getName() == 'languageId') continue;
            if(trim(strip_tags($item->getValue())) != null) return true;
        }
        return false;
    }
}