<?php

namespace Luknei\Translations;

use Eloquent;
use Cache;
use Luknei\Translations\EloquentLoader;
use LaravelBook\Ardent\Ardent;

class Translation extends Ardent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'translations';

    /**
     * @var array
     */
    protected $fillable = array('locale', 'namespace', 'group', 'key', 'value');

    public static $rules = array(
        'locale' => 'required',
        'namespace' => 'required',
        'group' => 'required',
        'key' => 'required',
    );

    /**
     * @param $query
     * @param $locale
     * @param $group
     * @return mixed
     */
    public function scopeGetGroup($query, $locale, $group)
    {
        return $query->where('namespace', '=', '*')->where('locale', '=', $locale)->where('group', '=', $group);
    }

    /**
     * @param $query
     * @param $locale
     * @param $namespace
     * @param $group
     * @return mixed
     */
    public function scopeGetNamespaced($query, $locale, $namespace, $group)
    {
        return $query->where('locale', '=', $locale)
            ->where('namespace', '=', $namespace)->where('group', '=', $group);
    }

    public function scopeFindByKey($query, $locale, $namespace, $group, $key)
    {
        return $query->where('locale', '=', $locale)
            ->where('namespace', '=', $namespace)
            ->where('group', '=', $group)
            ->where('key', '=', $key);
    }

    /**
     * Bind model events
     */
    public static function boot()
    {
        parent::boot();

        /**
         * after successful update or inserted translation - updating the cache;
         */
        Translation::saved(function($data){
            $attr = $data->getAttributes();

            $eloquentLoader = new EloquentLoader();

            $eloquentLoader->useCache(false)->load($attr['locale'], $attr['group'], $attr['namespace']);

        });
    }
}