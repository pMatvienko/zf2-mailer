<?php

namespace Mailer\Form;

use SbxCommon\Crud\AbstractForm;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilterProviderInterface;

class NotificationForm extends AbstractForm implements InputFilterProviderInterface
{

    protected function setUp()
    {
        $this
            ->add(
                array(
                    'name' => 'id',
                    'type' => 'Hidden',
                )
            )
            ->add(array(
                'name' => 'slug',
                'type' => 'Text',
                'options' => array(
                    'label' => 'slug',
                ),
            ))
            ->add(array(
                'name' => 'emailFrom',
                'type' => 'Email',
                'options' => array(
                    'label' => 'email-from',
                ),
            ))
            ->add(array(
                'name' => 'variables',
                'type' => 'Text',
                'options' => array(
                    'label' => 'template-variables',
                ),
                'attributes' => array(
                    'role' => 'tagged-field',
                ),
            ));

        $config = $this->getServiceLocator()->get('config');
        $locales = new Notification\LocalesCollectionFieldset('locale', array(Notification\LocalesCollectionFieldset::OPTION_LNG => $config['slm_locale']['supported']));
        $this->add($locales);

        $this
            ->add(array(
                'name' => 'applyToNewUser',
                'type' => 'Checkbox',
                'options' => array(
                    'label' => 'apply-to-new-user',
                ),
                'attributes' => array(
                    'role' => 'bootstrap-switch',
                ),
            ))
            ->add(array(
                'name' => 'userCanUnsubscribe',
                'type' => 'Checkbox',
                'options' => array(
                    'label' => 'user-can-unsubscribe',
                ),
                'attributes' => array(
                    'role' => 'bootstrap-switch',
                ),
            ));
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'slug' => array(
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToLower'],
                ],
                'validators' => [
                    [
                        'name' => '\DoctrineModule\Validator\UniqueObject',
                        'options' => array(
                            'object_manager' => $this->getEntityManager(),
                            'object_repository' => $this->getEntityManager()->getRepository(
                                '\StaticPage\Entity\StaticPage'
                            ),
                            'fields' => array('slug'),
                        )
                    ],
                ]
            ),
        );
    }

    public function getData($flag = FormInterface::VALUES_NORMALIZED)
    {
        $data = parent::getData($flag);
        foreach($data['locale'] as $language => $translation){
            if(!$this->get('locale')->get($language)->isHaveData()){
                unset($data['locale'][$language]);
            }
        }
        return $data;
    }
}