<?php
namespace Mailer\Service;

use Auth\External\Exception\Exception;
use Mailer\Base\PlainExtractor;
use Zend\EventManager\EventInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class NotificationEventListener implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    private $mailSettings = null;
    /**
     * @var \Zend\Log\Logger
     */
    private $log = null;

    /**
     * @var \Zend\Mail\Transport\Smtp
     */
    private $transport = null;

    public function __construct($transport, $settings)
    {
        $this->setTransport($transport);
        $this->setMailSettings($settings);
    }

    /**
     * @return null
     */
    public function getMailSettings()
    {
        return $this->mailSettings;
    }

    /**
     * @param null $mail
     */
    public function setMailSettings($mail)
    {
        $this->mailSettings = $mail;
    }

    /**
     * @return \Zend\Log\Logger
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param null $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    /**
     * @return \Zend\Mail\Transport\Smtp
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param null $transport
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    }
    private function convertNameToSlug($eventName)
    {
        return strtolower(
            preg_replace(
                '/([^._])([A-Z]{1,1})/',
                '$1-$2',
                $eventName
            )
        );
    }

    public function handle(EventInterface $event)
    {
        $user = $event->getTarget();

        $extractor = new PlainExtractor($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'), '.');
        $data = $event->getParam('data');
        if (!is_array($data)) {
            $data = $extractor->extract($data);
        }
        $data = $data + $extractor->extract($user, 'user') + $extractor->extract($user->getUserInfo(), 'userinfo');

        /**
         * @var \Mailer\Model\NotificationModel $notifyModel
         */
        $notifyModel = $this->getServiceLocator()->get('Mailer/Model/Notification');
        $notificationTpl = $notifyModel->findBySlug($this->convertNameToSlug($event->getName()));

        if (null == $notificationTpl) {
            $this->getLog()->crit(
                'Notification template "' . $this->convertNameToSlug($event->getName()) . '" not found',
                array('template_vars' => implode(',', array_keys($data)))
            );
            throw new \Mailer\Model\Exception\NotificationTemplateNotFound('Notification template "' . $this->convertNameToSlug($event->getName()) . '" not found');
        }
        $translation = $notifyModel->getNotificationTranslation($notificationTpl);

        $messageOptions = $this->getMailSettings();
        $mail = new \Mailer\Mail\Message();
        $mail->setEncoding($messageOptions['encoding']);

        if (null != $notificationTpl->getEmailFrom()) {
            $mail->setFrom($notificationTpl->getEmailFrom(), $translation->getNameFrom());
            $mail->setSender($notificationTpl->getEmailFrom(), $translation->getNameFrom());
        } else {
            $mail->setFrom($messageOptions['defaultFrom'], $messageOptions['defaultFromName']);
            $mail->setSender($messageOptions['defaultFrom'], $messageOptions['defaultFromName']);
        }

        $mail->setTo($user->getEmail(), $user->getUserInfo()->getFullName());
        $mail->setSubject($translation->compileSubject($data));
        $body = new \Zend\Mime\Message();
        if (null != $translation->getNotificationHtml()) {
            $part = new \Zend\Mime\Part($translation->compileNotificationHtml($data));
            $part->type = "text/html";
            $part->charset = $messageOptions['encoding'];
            $part->language = $translation->getLanguageId();
            $body->addPart($part);
        }
        if (null != $translation->getNotificationText()) {
            $part = new \Zend\Mime\Part($translation->compileNotificationText($data));
            $part->type = "text/plain";
            $part->charset = $messageOptions['encoding'];
            $part->language = $translation->getLanguageId();
            $body->addPart($part);
        }

        $mail->setBody($body);

        $this->getTransport()->send($mail);
    }
}