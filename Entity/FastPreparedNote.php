<?php


namespace Yit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Yit\NotificationBundle\Entity\Repository\FastPreparedNoteRepository")
 * @ORM\Table(name="yit_fast_prepared_notification")
 * @UniqueEntity(fields="formUserGroups", message="Տվյալ օգտատերը առկա է")
 */
class FastPreparedNote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     * @ORM\Column(name="from_user_groups", type="string", unique=true)
     */
    protected $formUserGroups;

    /**
     * @var
     * @ORM\Column(name="to_user_groups", type="array")
     */
    protected $toUserGroups;

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
     * Set toUserGroups
     *
     * @param array $toUserGroups
     * @return FastPreparedNote
     */
    public function setToUserGroups($toUserGroups)
    {
        $this->toUserGroups = $toUserGroups;

        return $this;
    }

    /**
     * Get toUserGroups
     *
     * @return array 
     */
    public function getToUserGroups()
    {
        return $this->toUserGroups;
    }

    /**
     * Set formUserGroups
     *
     * @param string $formUserGroups
     * @return FastPreparedNote
     */
    public function setFormUserGroups($formUserGroups)
    {
        $this->formUserGroups = $formUserGroups;

        return $this;
    }

    /**
     * Get formUserGroups
     *
     * @return string 
     */
    public function getFormUserGroups()
    {
        return $this->formUserGroups;
    }
}
