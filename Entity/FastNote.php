<?php
/**
 * Created by PhpStorm.
 * User: aram
 * Date: 8/5/15
 * Time: 12:29 PM
 */

namespace Tmcycyit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="yit_fast_note")
 */
class FastNote
{
    const UN_READ = 0;
    const READ = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string",  nullable=true)
     * @JMS\Groups({"fast-new"})
     */
    protected $title;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @JMS\Groups({"fast-new"})
     */
    protected $content;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Tmcycyit\NotificationBundle\Model\NoteUserInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id", onDelete="SET NULL")
     *
     */
    protected  $fromUser;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="FastNoteStatus",  mappedBy="fastNote",  cascade={"persist", "remove"})
     *
     */
    protected  $noteStatus;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->noteStatus = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set content
     *
     * @param string $content
     * @return FastNote
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
     * Set created
     *
     * @param \DateTime $created
     * @return FastNote
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
     * Add noteStatus
     *
     * @param \Tmcycyit\NotificationBundle\Entity\FastNoteStatus $noteStatus
     * @return FastNote
     */
    public function addNoteStatus(\Tmcycyit\NotificationBundle\Entity\FastNoteStatus $noteStatus)
    {
        $this->noteStatus[] = $noteStatus;
        $noteStatus->setFastNote($this);

        return $this;
    }

    /**
     * Remove noteStatus
     *
     * @param \Tmcycyit\NotificationBundle\Entity\FastNoteStatus $noteStatus
     */
    public function removeNoteStatus(\Tmcycyit\NotificationBundle\Entity\FastNoteStatus $noteStatus)
    {
        $this->noteStatus->removeElement($noteStatus);
    }

    /**
     * Get noteStatus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNoteStatus()
    {
        return $this->noteStatus;
    }

    /**
     * Set fromUser
     *
     * @param \Application\UserBundle\Entity\User $fromUser
     * @return FastNote
     */
    public function setFromUser($fromUser = null)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return \Application\UserBundle\Entity\User 
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return FastNote
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
}
