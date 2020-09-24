<?php
/**
 * class Listing implements \Iterator, \Countable, \ArrayAccess
 * {
 *  use Foreachable;
 *  ...
 * }
 */
namespace TurboLabIt\ServiceEntityBundle;


trait Foreachable
{
    protected $arrData = [];
    protected $position = 0;


    function rewind()
    {
        $this->position = 0;
    }

    function current()
    {
        $key    = $this->getRealForeachablePosition();

        if($key === false) {

            return false;
        }

        return $this->arrData[$key];
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        ++$this->position;
    }

    function valid()
    {
        $key    = $this->getRealForeachablePosition();
        return $key !== false;
    }

    function count()
    {
        return count($this->arrData);
    }

    function first()
    {
        if( $this->arrData == []) {

            return false;
        }

        $firstKey = array_keys($this->arrData)[0];
        return $this->arrData[$firstKey];
    }

    function last()
    {
        if( $this->arrData == []) {

            return false;
        }

        $lastKey = array_reverse(array_keys($this->arrData))[0];
        return $this->arrData[$lastKey];
    }


    protected function getRealForeachablePosition()
    {
        $keys   = array_keys($this->arrData);
        if( !array_key_exists($this->position, $keys) ) {

            return false;
        }

        return $keys[$this->position];
    }

    public function get($key)
    {
        if( !array_key_exists($key, $this->arrData) ) {

            return null;
        }

        return $this->arrData[$key];
    }

    public function clear()
    {
        $this->arrData = [];
        $this->rewind();
    }


    public function getAll()
    {
        return $this->arrData;
    }


    /**
     * The ArrayAccess interface
     * =========================
     *
     * @see https://www.php.net/manual/en/class.arrayaccess.php
     */


    public function offsetExists($offset)
    {
        $arrValue = array_values($this->arrData);
        return isset($arrValue[$offset]);
    }

    public function offsetGet($offset)
    {
        $arrValue = array_values($this->arrData);
        return isset($arrValue[$offset]) ? $arrValue[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if ( is_null($offset) ) {

            $this->arrData[] = $value;
            return null;
        }

        $arrKeys = array_values(array_flip($this->arrData));
        if( !array_key_exists($offset, $arrKeys) ) {

            $this->arrData[$offset] = $value;
            return null;
        }

        $realKey = $arrKeys[$offset];
        $this->arrData[$realKey] = $value;
        return null;
    }

    public function offsetUnset($offset)
    {
        $arrKeys = array_values(array_flip($this->arrData));
        if( !array_key_exists($offset, $arrKeys) ) {

            return null;
        }

        $realKey = $arrKeys[$offset];
        unset($this->arrData[$realKey]);
        return null;
    }
}
