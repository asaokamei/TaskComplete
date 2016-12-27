<?php
namespace AppBundle\Entity\Tasks\Generic;

use DateTime;

class DoneDate
{
    /**
     * @var DateTime
     */
    private $date;

    /**
     * DoneDate constructor.
     *
     * @param DateTime $date
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * @param string $format
     * @return string
     */
    public function format($format = 'Y/m/d')
    {
        if (!$this->date) {
            return '';
        }
        return $this->date->format($format);
    }

    /**
     * @param null|DateTime $today
     * @return string
     */
    public function toString($today = null)
    {
        $today = $today ?: new DateTime('now');
        if (!$this->date) {
            return '';
        }
        $format = 'm/d';
        $diff = $this->date->diff($today)->days;
        if ($diff > 60 || $diff < -300) {
            $format = 'Y/m';
        }
        return $this->date->format($format);
    }
    
    public function __toString()
    {
        return $this->toString();
    }
}