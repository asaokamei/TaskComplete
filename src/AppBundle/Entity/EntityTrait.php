<?php
namespace AppBundle\Entity;

trait EntityTrait
{
    /**
     * @param $key
     * @param $value
     */
    protected function _setVariable($key, $value)
    {
        if (!$this->isFillAble($key)) {
            return ;
        }
        $method = 'set' . $key;
        if (method_exists($this, $method)) {
            $this->$method($value);
            return;
        }
        $this->$key = $value;
    }

    /**
     * @override
     * @param string $key
     * @return bool
     */
    protected function isFillAble($key)
    {
        return $key ? true: true;
    }

    /**
     * @param array $data
     */    
    public function fill(array $data)
    {
        foreach($data as $key => $value) {
            $this->_setVariable($key, $value);
        }
    }
}