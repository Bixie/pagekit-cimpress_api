<?php

namespace Bixie\CimpressApi\Api;

use Pagekit\Application as App;


trait CimpressApiCacheTrait {
    /**
     * @var array
     */
    protected $cacheDirty = [];
    /**
     * @var array
     */
    protected $cacheRemove = [];
    /**
     * @var array
     */
    protected $cache = [];

    /**
     *
     */
    public function __destruct () {
        foreach ($this->cacheDirty as $key) {
            App::cache()->save($key, $this->cache[$key]);
        }
        foreach ($this->cacheRemove as $key) {
            App::cache()->delete($key, $this->cache[$key]);
        }
    }

    /**
     * @param $key
     * @return array
     */
    protected function loadCache ($key) {
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = App::cache()->fetch($key) ?: [];
        }
        return $this->cache[$key];
    }

    /**
     * @param $key
     * @param $values
     */
    protected function addCache ($key, $values) {
        $this->cache[$key] = $values;
        $this->cacheDirty[] = $key;
    }

    /**
     * @param $key
     */
    protected function removeCache ($key) {
        unset($this->cache[$key]);
        $this->cacheRemove[] = $key;
    }
}