<?php


namespace Tmcycyit\NotificationBundle\Entity;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"list"})
     */
    protected $header;

    /**
     *
     * @ORM\Column(type="text", nullable=true)
     * @JMS\Groups({"list"})
     */
    protected $content;

    /**
     *
     * @ORM\Column(type="text", nullable=true)
     * @JMS\Groups({"list"})
     */
    protected $userInfo;


    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Tmcycyit\NotificationBundle\Model\NoteUserInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id", onDelete="SET NULL")
     *
     */
    protected $fromUser;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="NotificationStatus",  mappedBy="notification",  cascade={"persist", "remove"})
     *
     */
    protected $notificationStatus;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Tmcycyit\NotificationBundle\Entity\PreparedNotification", cascade={"persist"})
     * @ORM\JoinColumn(name="prepared_note_id", referencedColumnName="id", onDelete="SET NULL")
     * @JMS\Groups({"list"})
     */
    protected $preparedNotification;


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
     * @param PreparedNotification $preparedNotification
     * @return $this
     */
    public function setPreparedNotification(\Tmcycyit\NotificationBundle\Entity\PreparedNotification $preparedNotification = null)
    {
        $this->preparedNotification = $preparedNotification;

        return $this;
    }

    /**
     * Get preparedNotification
     *
     * @return mixed
     */
    public function getPreparedNotification()
    {
        return $this->preparedNotification;
    }

    /**
     * set User Info
     *
     * @param $userInfo
     * @return $this
     */
    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;

        return $this;
    }

    /**
     * Get User Info
     *
     * @return mixed
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notificationStatus = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add notificationStatus
     *
     * @param \Tmcycyit\NotificationBundle\Entity\NotificationStatus $notificationStatus
     * @return Notification
     */
    public function addNotificationStatus(\Tmcycyit\NotificationBundle\Entity\NotificationStatus $notificationStatus)
    {
        $this->notificationStatus[] = $notificationStatus;

        return $this;
    }

    /**
     * Remove notificationStatus
     *
     * @param \Tmcycyit\NotificationBundle\Entity\NotificationStatus $notificationStatus
     */
    public function removeNotificationStatus(\Tmcycyit\NotificationBundle\Entity\NotificationStatus $notificationStatus)
    {
        $this->notificationStatus->removeElement($notificationStatus);
    }

    /**
     * Get notificationStatus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificationStatus()
    {
        return $this->notificationStatus;
    }
}