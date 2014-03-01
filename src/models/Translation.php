<?php

namespace Luknei\Translations;

use Eloquent;
use Cache;
use Luknei\Translations\EloquentLoader;

class Translation extends Eloquent {

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