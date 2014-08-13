<?php


namespace Yit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @ORM\Entity
 * @ORM\Table(name="yit_notification_type")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    protected $title;

    /**
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\Column(name="file_size", type="integer")
     */
    private $fileSize;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @VirtualProperty
     * @JMS\Groups({"list"})
     */
    public function getPath()
    {
        return '/' . $this->getUploadDir() . '/' . $this->getFileName();
    }

    public function setPath() {}

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->fileName
                ? null
                : $this->getUploadRootDir().$this->fileName;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->fileName
                ? null
                : $this->getUploadDir().'/'.$this->fileName;
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../../../web/' . $this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/notification';
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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->getFile()->move(
                $this->getUploadRootDir(),
                $this->getFile()->getClientOriginalName()
        );

        $this->fileSize = $this->getFile()->getClientSize();
        $this->fileName = $this->getFile()->getClientOriginalName();

        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (is_file($this->getAbsolutePath())) {
            unlink($this->getAbsolutePath());
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->title) ? $this->title : '';
    }


    /**
     * Set fileName
     *
     * @param string $fileName
     * @return NotificationType
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    
        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     * @return NotificationType
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    
        return $this;
    }

    /**
     * Get fileSize
     *
     * @return integer 
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }
}