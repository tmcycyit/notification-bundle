<?php
/**
 * Created by PhpStorm.
 * User: aram
 * Date: 8/5/15
 * Time: 12:29 PM
 */

namespace Yit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="Yit\NotificationBundle\Entity\Repository\FastNotificationStatusRepository")
 * @ORM\Table(name="yit_fast_note_status")
 */
class FastNoteStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer status
     *
     * @ORM\Column(type="boolean", name="status")
     */
    protected $status;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="FastNote", inversedBy="noteStatus", cascade={"persist"})
     * @ORM\JoinColumn(name="note_id", referencedColumnName="id")
     * @JMS\Groups({"fast-new"})
     */
    protected  $fastNote;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Yit\NotificationBundle\Model\NoteUserInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected  $toUser;


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
     * @return FastNoteStatus
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
     * Set fastNote
     *
     * @param \Yit\NotificationBundle\Entity\FastNote $fastNote
     * @return FastNoteStatus
     */
    public function setFastNote(\Yit\NotificationBundle\Entity\FastNote $fastNote = null)
    {
        $this->fastNote = $fastNote;

        return $this;
    }

    /**
     * Get fastNote
     *
     * @return \Yit\NotificationBundle\Entity\FastNote 
     */
    public function getFastNote()
    {
        return $this->fastNote;
    }

    /**
     * Set toUser
     *
     * @param \Application\UserBundle\Entity\User $toUser
     * @return FastNoteStatus
     */
    public function setToUser($toUser = null)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return \Application\UserBundle\Entity\User 
     */
    public function getToUser()
    {
        return $this->toUser;
    }
}
