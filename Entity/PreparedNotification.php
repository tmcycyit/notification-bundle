<?php


namespace Yit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="yit_prepared_notification")
 * @ORM\Entity(repositoryClass="Yit\NotificationBundle\Entity\Repository\PreparedNoteRepository")
 * @UniqueEntity(fields="code", message="Տվյալ գործողությունը առկա է")
 */
class PreparedNotification
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Yit\NotificationBundle\Entity\NotificationType", cascade={"persist"})
     * @ORM\JoinColumn(name="notification_type_id", referencedColumnName="id")
     * @JMS\Groups({"list"})
     */
    protected $notificationType;

    /**
     * @ORM\Column(name="code", type="string", length=50, unique=true)
     */
    protected $code;

    /**
     * @var
     * @ORM\Column(name="userGroups", type="array")
     */
    protected $userGroups;

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
     * @param NotificationType $notificationType
     * @return $this
     */
    public function setNotificationType(\Yit\NotificationBundle\Entity\NotificationType $notificationType = null)
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotificationType()
    {
        return $this->notificationType;
    }

    /**
     * This function is used to replace substring in notifications.
     * Sub-strings like %address% would be replaced by array('address' => 'some value')
     *
     * @param array $values
     * @return string
     */
    public function getReplacedNotification(array $values)
    {
        // loop array and replace each key
        $rawContent = $this->content;

        foreach($values as $key => $value){
            $rawContent = str_replace("%$key%", $value, $rawContent);
        }

        return $rawContent;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->title) ? $this->title : '';
    }

    /**
     * Set code
     *
     * @param string $code
     * @return PreparedNotification
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set userGroups
     *
     * @param array $userGroups
     * @return PreparedNotification
     */
    public function setUserGroups($userGroups)
    {
        $this->userGroups = $userGroups;
    
        return $this;
    }

    /**
     * Get userGroups
     *
     * @return array 
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }
}