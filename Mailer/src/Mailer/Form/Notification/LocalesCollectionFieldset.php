<?php

namespace Mailer\Form\Notification;

use SbxCommon\Form\TabbedFieldset as Fieldset;

class LocalesCollectionFieldset extends Fieldset
{
    const OPTION_LNG = 'languages';

    public function __construct($name='locale', $options = array())
    {
        parent::__construct($name, $options);
        foreach($this->getLanguages() as $lngLocale){
            $fst = new LocaleFieldset($lngLocale);
            $fst->get('languageId')->setValue($lngLocale);
            $fst->setLabel('translation-'.$lngLocale);

            $fst->setAttribute('id', 'translation-' . $lngLocale);
            $this->add($fst);
        }
    }

    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->options[self::OPTION_LNG];
    }
}