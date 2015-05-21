<?php
/**
 * Created by PhpStorm.
 * User: aram
 * Date: 5/21/15
 * Time: 1:30 PM
 */


namespace Yit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="yit_history_period")
 */
class HistoryPeriod
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="period", type="integer")
     */
    protected $period;

    /**
     * Set period
     *
     * @param integer $period
     * @return HistoryPeriod
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    
        return $this;
    }

    /**
     * Get period
     *
     * @return integer 
     */
    public function getPeriod()
    {
        return $this->period;
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
}