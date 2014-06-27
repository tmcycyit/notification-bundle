<?php


namespace Yit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="yit_notification_type")
 */
class NotificationType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string", unique=true, length=50, nullable=false)
     */
    protected $code;

    /**
     *
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    protected $title;

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
     * Set title
     *
     * @param string $code
     * @return Notification
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getCode()
    {
        return $this->title;
    }


    /**
     * Set title
     *
     * @param string $title
     * @return Notification
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
