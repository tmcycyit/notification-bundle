<?php


namespace Tmcycyit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use APY\DataGridBundle\Grid\Mapping as Grid;

/**
 * @ORM\Entity
 * @ORM\Table(name="yit_notification_status", indexes={
 *                                                      @ORM\Index(name="note_count_idx", columns={"to_user_id", "status"}),
 *                                                      @ORM\Index(name="all_note_idx", columns={"to_user_id", "notification_id", "status"})
 *             })
 * @ORM\Entity(repositoryClass="Tmcycyit\NotificationBundle\Entity\Repository\NotificationStatusRepository")
 * @Grid\Source(columns="id, notification.header, notification.userInfo , notification.fromUser.email, notification.created, notification.content")
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
     * @ORM\ManyToOne(targetEntity="Tmcycyit\NotificationBundle\Entity\Notification", inversedBy="notificationStatus", cascade={"persist"})
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     * @JMS\Groups({"list"})
     * @Grid\Column(field="notification.header", title="note.title")
     * @Grid\Column(field="notification.fromUser.email", title="note.sender")
     * @Grid\Column(field="notification.content", title="note.content")
     * @Grid\Column(field="notification.created", title="note.created", type="datetime")
     * @Grid\Column(field="notification.userInfo", title="note.userInfo")
     */
    protected $notification;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Tmcycyit\NotificationBundle\Model\NoteUserInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $toUser;

    /**
     * @var integer status
     *
     * @ORM\Column(type="boolean", name="status")
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
     * @param boolean $status
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
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set notification
     *
     * @param \Tmcycyit\NotificationBundle\Entity\Notification $notification
     * @return NotificationStatus
     */
    public function setNotification(\Tmcycyit\NotificationBundle\Entity\Notification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \Tmcycyit\NotificationBundle\Entity\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set toUser
     *
     * @param  $toUser
     * @return NotificationStatus
     */
    public function setToUser($toUser = null)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return user
     */
    public function getToUser()
    {
        return $this->toUser;
    }
}