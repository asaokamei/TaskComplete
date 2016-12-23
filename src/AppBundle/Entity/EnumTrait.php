<?php
namespace AppBundle\Entity;

trait EnumTrait
{
    /**
     * @var string[]
     */
    protected static $choices;

    /**
     * @var string
     */
    protected $value;

    /**
     * TaskStatus constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        if (!$this->isDefined($value)) {
            throw new \InvalidArgumentException(__CLASS__ . ' has no such value: '.$value);
        }
        $this->value = (string) $value;
    }

    /**
     * @return string[]
     */
    public static function choices()
    {
        return static::$choices;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function is($value)
    {
        return $this->value === (string) $value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isDefined($value)
    {
        return array_key_exists($value, static::$choices);
    }

    /**
     * @return string
     */
    public function label()
    {
        return static::$choices[$this->value];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

}