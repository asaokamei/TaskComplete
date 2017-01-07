<?php
namespace AppBundle\Entity;

trait EnumTrait
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @return array
     */
    abstract public function getChoices();

    /**
     * TaskStatus constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        if (!$this->isDefined($value)) {
            throw new \InvalidArgumentException(__CLASS__ . ' has no such value: ' . $value);
        }
        $this->value = (string)$value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function is($value)
    {
        return $this->value === (string)$value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isDefined($value)
    {
        return array_key_exists($value, $this->getChoices());
    }

    /**
     * @return string
     */
    public function label()
    {
        return $this->getChoices()[$this->value];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

}