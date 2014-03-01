<?php

namespace Luknei\Translations;

use Illuminate\Translation\LoaderInterface;
use Cache;

class EloquentLoader implements LoaderInterface{


    /**
     * All of the namespace hints.
     *
     * @var array
     */
    protected $hints = array();

    /**
     * @var
     */
    protected $cacheName;

    /**
     * @var
     */
    protected $cache;

    /**
     * @var
     */
    protected $useCache = true;

    /**
     * @param string $locale
     * @param string $group
     * @param null $namespace
     * @return array|mixed
     */
    public function load($locale, $group, $namespace = null)
    {
        if($this->cache($locale, $group, $namespace) && $this->useCache) return $this->cache;

        if (is_null($namespace) || $namespace == '*')
        {
            return $this->loadGroup($locale, $group);
        }
        else
        {
            return $this->loadNamespaced($locale, $group, $namespace);
        }
    }

    /**
     * Load a namespaced translation group.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    public function loadNamespaced($locale, $group, $namespace)
    {
        if (isset($this->hints[$namespace]))
        {
            $translations = Translation::getNamespaced($locale, $namespace, $group);

            if ($translations->exists()) {
                $array = $translations->get(array('key', 'value'))->toArray();

                return $this->fetchArray($array);
            }
        }

        return array();
    }


    /**
     * @param $locale
     * @param $group
     * @return array|mixed
     */
    protected function loadGroup($locale, $group)
    {
        $translations = Translation::getGroup($locale, $group);
        if ($translations->exists()) {
            $array = $translations->get(array('key', 'value'))->toArray();

            return $this->fetchArray($array);
        }

        return array();
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
    }

    /**
     * @param array $array
     * @return array
     */
    protected function fetchArray(array $array)
    {
        $assoc = array();
        foreach ($array as $item) {
            if (preg_match('/\./', $item['key'])) {
                list($key, $subKey) = explode('.', $item['key']);
                if(!isset($assoc[$key])) $assoc[$key] = array();
                $assoc[$key][$subKey] = $item['value'];
            } else {
                $assoc[$item['key']] = $item['value'];
            }
        }
        Cache::put($this->cacheName, $assoc, 24*60);

        return $assoc;
    }

    /**
     * @return array
     */
    public function getHints()
    {
        return $this->hints;
    }


    /**
     * @param $locale
     * @param $group
     * @param null $namespace
     * @return bool
     */
    protected function cache($locale, $group, $namespace = null)
    {
        if (is_null($namespace)) $namespace = '*';

        $this->cacheName = implode('.', array($locale, $group, $namespace));

        if (Cache::has($this->cacheName)) {
            $this->cache = Cache::get($this->cacheName);

            return true;
        }

        return false;
    }

    /**
     * @param $useCache
     * @return $this
     */
    public function useCache($useCache)
    {
        $this->useCache = $useCache;

        return $this;
    }
} 