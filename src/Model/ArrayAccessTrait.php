<?php

namespace Bixie\Cimpress\Model;


use Bixie\Framework\Utils\Arr;

trait ArrayAccessTrait
{
    /**
     * @param array $data
     * @return $this
     */
    public function fill (array $data = []) {
        foreach (get_object_vars($this) as $key => $default) {
            $this->$key = Arr::get($data, $key, $default);
        }
        return $this;
    }

    /**
     * @param array $data
     * @param array $ignore
     * @return array
     */
    public function toArray ($data = [], $ignore = []) {
        foreach (get_object_vars($this) as $key => $value) {
            $data[$key] = $value;
        }
        return array_diff_key($data, array_flip($ignore));
    }

    /**
     * Whether an application parameter or an object exists.
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * Gets an application parameter or an object.
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Sets an application parameter or an object.
     *
     * @param  string $offset
     * @param  mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Unsets an application parameter or an object.
     *
     * @param  string $offset
     */
    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }
}