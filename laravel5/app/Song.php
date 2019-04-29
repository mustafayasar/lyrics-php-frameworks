<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Song
 *
 * @property-read \App\Singer $singer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $singer_id
 * @property string $title
 * @property string $slug
 * @property string|null $lyrics
 * @property int $hit
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereHit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereLyrics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereSingerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereTitle($value)
 */
class Song extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_PASSIVE = 0;
    const STATUS_SINGER_PASSIVE = 2;

    public static $statuses = [
        self::STATUS_ACTIVE         => 'Active',
        self::STATUS_PASSIVE        => 'Passive',
        self::STATUS_SINGER_PASSIVE => 'Singer Passive',
    ];

    // Cache Durations
    const CD_LIST = 6*60;
    const CD_SINGER = 10*60;
    const CD_ITEM = 60*60;
    const CD_RANDOM_LIST = 60*60;

    /**
     * @var string
     */
    protected $table = 'songs';

    /**
     * @var array
     */
    protected $fillable = ['singer_id', 'title', 'slug', 'lyrics', 'status'];

    /**
     * @var array
     */
    protected $attributes = [
        'hit' => 0
    ];

    protected $dateFormat = 'U';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function singer()
    {
        return $this->hasOne('App\Singer', 'id', 'singer_id');
    }
}
