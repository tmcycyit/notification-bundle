<?php


namespace Yit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="yit_notification")
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Groups({"list"})
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     * @JMS\Groups({"list"})
     */
    protected $header;

    /**
     *
     * @ORM\Column(type="text", nullable=false)
     * @JMS\Groups({"list"})
     */
    protected $content;


    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Yit\NotificationBundle\Model\NoteUserInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id", onDelete="SET NULL")
     *
     */
    protected $fromUser;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Yit\NotificationBundle\Entity\NotificationType", cascade={"persist"})
     * @ORM\JoinColumn(name="notification_type_id", referencedColumnName="id")
     */
    protected $notificationType;


    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     * @JMS\Groups({"list"})
     */
    protected $created;

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
     * Set header
     *
     * @param string $header
     * @return Notification
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string 
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Notification
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set fromUser
     *
     * @param  $fromUser
     * @return Notification
     */
    public function setFromUser($fromUser = null)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return user
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Notification
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param NotificationType $notificationType
     * @return $this
     */
    public function setNotificationType(\Yit\NotificationBundle\Entity\NotificationType $notificationType = null)
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    /**
     * Get notificationType
     *
     * @return \Yit\UserBundle\Entity\User
     */
    public function getNotificationType()
    {
        return $this->notificationType;
    }
}
