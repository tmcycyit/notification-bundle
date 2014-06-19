<?php


namespace Yit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="prepared_notification")
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
     * @ORM\Column(type="string", unique=true, length=50, nullable=false)
     */
    protected $code;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $content;

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

}
