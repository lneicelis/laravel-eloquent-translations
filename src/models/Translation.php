<?php

namespace Luknei\Translations;

use Eloquent;

class Translation extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'translations';

    public function scopeGetGroup($query, $locale, $group)
    {
        return $query->whereNull('namespace')->where('locale', '=', $locale)->where('group', '=', $group);
    }

    public function scopeGetNamespaced($query, $locale, $namespace, $group)
    {
        return $query->where('locale', '=', $locale)
            ->where('namespace', '=', $namespace)->where('group', '=', $group);
    }
}