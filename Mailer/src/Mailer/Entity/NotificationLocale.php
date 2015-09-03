<?php

namespace Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationTemplateLocale
 *
 * @ORM\Table(name="notification_locale", indexes={@ORM\Index(name="idx_notification_template_locale", columns={"template_id"})})
 * @ORM\Entity
 */
class NotificationLocale
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="language_id", type="string", length=5, nullable=false)
     */
    private $languageId;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=false)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_text", type="text", nullable=true)
     */
    private $notificationText;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_html", type="text", nullable=true)
     */
    private $notificationHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="name_from", type="string", length=255, nullable=true)
     */
    private $nameFrom;

    /**
     * @var \Mailer\Entity\Notification
     *
     * @ORM\ManyToOne(targetEntity="Mailer\Entity\Notification")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="template_id", referencedColumnName="id")
     * })
     */
    private $template;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set languageId
     *
     * @param string $languageId
     * @return NotificationTemplateLocale
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;

        return $this;
    }

    /**
     * Get languageId
     *
     * @return string
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return NotificationTemplateLocale
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set notificationText
     *
     * @param string $notificationText
     * @return NotificationTemplateLocale
     */
    public function setNotificationText($notificationText)
    {
        $this->notificationText = $notificationText;

        return $this;
    }

    /**
     * Get notificationText
     *
     * @return string
     */
    public function getNotificationText()
    {
        return $this->notificationText;
    }

    public function compileNotificationText($vars)
    {
        return $this->replaceVarsInString($this->getNotificationText(), $vars);
    }

    /**
     * Set notificationHtml
     *
     * @param string $notificationHtml
     * @return NotificationTemplateLocale
     */
    public function setNotificationHtml($notificationHtml)
    {
        $this->notificationHtml = $notificationHtml;

        return $this;
    }

    /**
     * Get notificationHtml
     *
     * @return string
     */
    public function getNotificationHtml()
    {
        return $this->notificationHtml;
    }

    public function compileNotificationHtml($vars)
    {
        return $this->replaceVarsInString($this->getNotificationHtml(), $vars);
    }

    /**
     * Set nameFrom
     *
     * @param string $nameFrom
     * @return NotificationTemplateLocale
     */
    public function setNameFrom($nameFrom)
    {
        $this->nameFrom = $nameFrom;

        return $this;
    }

    /**
     * Get nameFrom
     *
     * @return string
     */
    public function getNameFrom()
    {
        return $this->nameFrom;
    }

    /**
     * Set template
     *
     * @param \Mailer\Entity\Notification $template
     * @return NotificationTemplateLocale
     */
    public function setTemplate(\Mailer\Entity\Notification $template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return \Mailer\Entity\Notification
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function compileSubject($vars)
    {
        return $this->replaceVarsInString($this->getSubject(), $vars);
    }

    private function replaceVarsInString($text, $replacements)
    {
        return preg_replace_callback('/\{([a-zA-Z0-9\-_\.]+)\}/', function ($match) use ($replacements) {
            return !empty($replacements[$match[1]]) ? $replacements[$match[1]] : $match[0];
        }, $text);
    }
}
