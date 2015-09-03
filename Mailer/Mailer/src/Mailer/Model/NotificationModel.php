<?php
namespace Mailer\Model;

use Mailer\Mail\Notification as MailNotification;
use SbxCommon\Crud\CrudModelInterface;
use Mailer\Base\Model;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject AS DoctrineObjectHydrator;
use Mailer\Entity\Notification as NotificationEntity;
use Mailer\Entity\NotificationLocale as NotificationLocaleEntity;

class NotificationModel extends Model implements CrudModelInterface
{
    public function findById($id)
    {
        return $this->getEntityManager()->getRepository('Mailer\Entity\Notification')->find($id);
    }

    /**
     * @param \Mailer\Entity\Notification $notification
     *
     * @return \Mailer\Entity\NotificationLocale
     */
    public function getNotificationTranslation(\Mailer\Entity\Notification $notification)
    {
        $currentLang = $this->getServiceLocator()->get('translator')->getLocale();
        $config = $this->getServiceLocator()->get('config');
        $defaultLang = $config['slm_locale']['default'];

        $translation = null;
        $defaultLngTranslation = null;
        $existingTranslation = null;
        /**
         * @var \StaticPage\Entity\StaticPageLocale $locale
         */
        foreach($notification->getLocale() as $locale){
            if($locale->getLanguageId() == $currentLang){
                $translation = $locale;
                break;
            } elseif($locale->getLanguageId() == $defaultLang){
                $defaultTranslation = $locale;
            } else {
                $existingTranslation = $locale;
            }
        }

        return null != $translation ? $translation : (
        null != $defaultTranslation ? $defaultTranslation : $existingTranslation
        );
    }

    /**
     * @param $slug
     *
     * @return \Mailer\Entity\Notification
     */
    public function findBySlug($slug)
    {
        return $this->getEntityManager()->getRepository('Mailer\Entity\Notification')->findOneBy(
            array(
                'slug' => $slug
            )
        );
    }

    public function getGrid()
    {
        $grid = $this->getServiceLocator()->get('Mailer/Grid/Notification');
        $grid->setDataSource($this->getEntityManager()->getRepository('Mailer\Entity\Notification'));
        return $grid;
    }

    public function getForm($entity = null)
    {
        if ($entity == null) {
            $entity = new NotificationEntity();
        }
        $hydrator = new DoctrineObjectHydrator($this->getEntityManager());

        $form = $this->getServiceLocator()->get('Mailer\Form\Notification');
        $data = $hydrator->extract($entity);
        $localesData = array();
        foreach($data['locale'] as $translation){
            $localesData[$translation->getLanguageId()] = $hydrator->extract($translation);
            unset($localesData[$translation->getLanguageId()]['mailer']);
        }
        $data['locale'] = $localesData;

        $form->setData($data);
        return $form;
    }

    public function saveForm($form, $entity = null)
    {
        if ($entity == null) {
            $entity = new NotificationEntity();
            $entity->setCreatedTime(new \DateTime());
        }
        $entity->setModifiedTime(new \DateTime());
        $data = $form->getData();
        $translationsData = $data['locale'];
        unset($data['locale']);
        unset($data['footer']);

        $hydrator = new DoctrineObjectHydrator($this->getEntityManager());

        $hydrator->hydrate(
            $data,
            $entity
        );

        foreach($entity->getLocale() as $translation) {
            if (array_key_exists($translation->getLanguageId(), $translationsData)) {
                $hydrator->hydrate(
                    $translationsData[$translation->getLanguageId()],
                    $translation
                );
                unset($translationsData[$translation->getLanguageId()]);
            } else {
                $entity->removeLocale($translation);
                $this->getEntityManager()->remove($translation);
            }
        }

        foreach($translationsData as $transData){
            $translation = new NotificationLocaleEntity();
            $hydrator->hydrate(
                $transData,
                $translation
            );
            $entity->addLocale($translation);
            $translation->setTemplate($entity);
        }
        $this->getEntityManager()->persist($entity);
        return $entity;
    }

    public function removeEntity($entity)
    {
        $this->getEntityManager()->remove($entity);
    }
}