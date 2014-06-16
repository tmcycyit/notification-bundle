<?php


namespace Yit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification_status")
 * @ORM\Entity(repositoryClass="Yit\NotificationBundle\Entity\Repository\NotificationStatusRepository")
 */
class NotificationStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Yit\NotificationBundle\Entity\Notification", cascade={"persist"})
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     */
    protected $notification;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Yit\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id")
     */
    protected $toUser;

    /**
     * @var integer status
     *
     * @ORM\Column(type="integer", name="status")
     */
    protected $status;

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
     * Set status
     *
     * @param integer $status
     * @return NotificationStatus
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set notification
     *
     * @param \Yit\NotificationBundle\Entity\Notification $notification
     * @return NotificationStatus
     */
    public function setNotification(\Yit\NotificationBundle\Entity\Notification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \Yit\NotificationBundle\Entity\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set toUser
     *
     * @param \Yit\UserBundle\Entity\User $toUser
     * @return NotificationStatus
     */
    public function setToUser(\Yit\UserBundle\Entity\User $toUser = null)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return \Yit\UserBundle\Entity\User 
     */
    public function getToUser()
    {
        return $this->toUser;
    }
}