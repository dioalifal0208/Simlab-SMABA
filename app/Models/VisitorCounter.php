<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorCounter extends Model
{
    protected $fillable = ['page', 'count'];

    /**
     * Increment visitor count for a given page and return the updated count.
     */
    public static function incrementAndGet(string $page = 'landing'): int
    {
        $counter = self::firstOrCreate(
            ['page' => $page],
            ['count' => 0]
        );

        $counter->increment('count');

        return $counter->count;
    }
}
