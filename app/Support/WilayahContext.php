<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class WilayahContext
{
    public static function current(): string
    {
        return strtolower(trim((string) config('app.wilayah', 'gunung_kidul')));
    }

    public static function isGunungKidul(): bool
    {
        return in_array(self::current(), ['gunung_kidul', 'gk', 'jmkgk'], true);
    }

    public static function customerAppUrl(string $path = ''): string
    {
        $baseUrl = self::isGunungKidul()
            ? config('app.jmkgk_app_url', 'https://layanan.beningmedia.co.id')
            : config('app.jmk_app_url', 'https://layanan.jernih.net.id');

        return rtrim((string) $baseUrl, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Scope customer rows to the active wilayah. GK customers use JMK-GK IDs;
     * non-GK deployments must not receive GK-specific notifications.
     */
    public static function scopePelanggan(EloquentBuilder|QueryBuilder $query, ?string $table = null): EloquentBuilder|QueryBuilder
    {
        $column = $table ? $table . '.nomer_id' : 'nomer_id';
        $normalizedColumn = "UPPER(REPLACE(REPLACE(COALESCE({$column}, ''), '.', ''), '-', ''))";

        if (self::isGunungKidul()) {
            return $query->whereRaw("{$normalizedColumn} LIKE ?", ['JMKGK%']);
        }

        return $query->whereRaw("{$normalizedColumn} NOT LIKE ?", ['JMKGK%']);
    }
}
