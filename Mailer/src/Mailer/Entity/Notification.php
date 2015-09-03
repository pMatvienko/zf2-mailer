<?php

namespace Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification", uniqueConstraints={@ORM\UniqueConstraint(name="idx_notification_template", columns={"slug"})})
 * @ORM\Entity
 */
class Notification
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->locale = new \Doctrine\Common\Collections\ArrayCollection();
    }
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
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="email_from", type="string", length=255, nullable=true)
     */
    private $emailFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="variables", type="text", nullable=true)
     */
    private $variables;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_time", type="datetime", nullable=false)
     */
    private $createdTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_time", type="datetime", nullable=true)
     */
    private $modifiedTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="apply_to_new_user", type="boolean", nullable=true)
     */
    private $applyToNewUser = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="user_can_unsubscribe", type="boolean", nullable=false)
     */
    private $userCanUnsubscribe = false;

    /**
     * @ORM\OneToMany(targetEntity="NotificationLocale", mappedBy="template", cascade={"persist", "remove"})
     **/
    private $locale;

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
     * Set slug
     *
     * @param string $slug
     * @return Notification
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set emailFrom
     *
     * @param string $emailFrom
     * @return Notification
     */
    public function setEmailFrom($emailFrom)
    {
        $this->emailFrom = $emailFrom;

        return $this;
    }

    /**
     * Get emailFrom
     *
     * @return string
     */
    public function getEmailFrom()
    {
        return $this->emailFrom;
    }

    /**
     * Set variables
     *
     * @param string $variables
     * @return Notification
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Get variables
     *
     * @return string
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Get variables
     *
     * @return string
     */
    public function getVariablesArray()
    {
        return explode(',', $this->variables);
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return Notification
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set modifiedTime
     *
     * @param \DateTime $modifiedTime
     * @return Notification
     */
    public function setModifiedTime($modifiedTime)
    {
        $this->modifiedTime = $modifiedTime;

        return $this;
    }

    /**
     * Get modifiedTime
     *
     * @return \DateTime
     */
    public function getModifiedTime()
    {
        return $this->modifiedTime;
    }

    /**
     * Set applyToNewUser
     *
     * @param boolean $applyToNewUser
     * @return Notification
     */
    public function setApplyToNewUser($applyToNewUser)
    {
        $this->applyToNewUser = $applyToNewUser;

        return $this;
    }

    /**
     * Get applyToNewUser
     *
     * @return boolean
     */
    public function getApplyToNewUser()
    {
        return $this->applyToNewUser;
    }

    /**
     * Set userCanUnsubscribe
     *
     * @param boolean $userCanUnsubscribe
     * @return Notification
     */
    public function setUserCanUnsubscribe($userCanUnsubscribe)
    {
        $this->userCanUnsubscribe = $userCanUnsubscribe;

        return $this;
    }

    /**
     * Get userCanUnsubscribe
     *
     * @return boolean
     */
    public function getUserCanUnsubscribe()
    {
        return $this->userCanUnsubscribe;
    }

    /**
     * Add locale
     *
     * @param \Mailer\Entity\NotificationLocale $locale
     * @return Notification
     */
    public function addLocale(\Mailer\Entity\NotificationLocale $locale)
    {
        $this->locale[] = $locale;

        return $this;
    }

    /**
     * Remove locale
     *
     * @param \Mailer\Entity\NotificationLocale $locale
     */
    public function removeLocale(\Mailer\Entity\NotificationLocale $locale)
    {
        $this->locale->removeElement($locale);
    }

    /**
     * Get locale
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
