<?php

namespace Luknei\Translations;

use Illuminate\Translation\LoaderInterface;

class EloquentLoader implements LoaderInterface{


    /**
     * All of the namespace hints.
     *
     * @var array
     */
    protected $hints = array();


    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
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
            $translations = Translation::getNamespaced($locale, $this->hints[$namespace], $group);

            if ($translations->exists()) {
                $array = $translations->get(array('key', 'value'))->toArray();

                return $this->fetchArray($array);
            }
        }

        return array();
    }

    /**
     * Load a locale from a given path.
     *
     * @param  string  $path
     * @param  string  $locale
     * @param  string  $group
     * @return array
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
            $assoc[$item['key']] = $item['value'];
        }

        return $assoc;
    }

} 