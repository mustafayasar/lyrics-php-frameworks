<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * App\Singer
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Song[] $songs
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Singer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Singer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Singer query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $hit
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Singer whereHit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Singer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Singer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Singer whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Singer whereStatus($value)
 */
class Singer extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_PASSIVE = 0;

    public static $statuses = [
        self::STATUS_PASSIVE    => 'Passive',
        self::STATUS_ACTIVE     => 'Active',
    ];

    // Cache Durations
    const CD_LIST = 6*60;
    const CD_ITEM = 60*60;

    /**
     * @var string
     */
    protected $table = 'singers';

    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'status'];

    /**
     * @var array
     */
    protected $attributes = [
        'hit' => 0
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    public function songs()
    {
        return $this->hasMany('App\Song');
    }

    public static function selectList()
    {
        return Arr::pluck(Singer::orderBy('name', 'asc')->get(), 'name', 'id');
    }
}
