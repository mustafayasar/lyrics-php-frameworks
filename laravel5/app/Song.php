<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

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
    protected $fillable = ['singer_id', 'title', 'slug', 'lyrics', 'status', 'created_at'];

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

    public static function getListWithCache($singer_id = 0, $initial = false, $order = 'name', $paginate = 14)
    {
        $cache_key  = 'song_list_'.$singer_id.'_'.(string) $initial.'_'.$order.'_'. (integer) $paginate.'_'.Input::get('page', 1);

        return Cache::remember($cache_key, self::CD_LIST, function () use ($singer_id, $initial, $order, $paginate) {
            $songs  = Song::with('singer')->where(['status' => self::STATUS_ACTIVE]);

            if ($singer_id > 0) {
                $songs->where(['singer_id' => $singer_id]);
            }

            if (!empty($initial)) {
                if ($initial == '09') {
                    $songs->where(function ($query) {
                        for ($i = 0; $i <= 9; $i++) {
                            $query->orWhere('slug', 'like', $i.'%');
                        }
                    });
                } else {
                    $songs->where('slug', 'like', $initial.'%');
                }
            }

            if ($order == 'new') {
                $songs->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');
            } elseif ($order == 'old') {
                $songs->orderBy('created_at', 'ASC')->orderBy('id', 'ASC');
            } elseif ($order == 'hit') {
                $songs->orderBy('hit', 'DESC');
            } elseif ($order == 'name') {
                $songs->orderBy('slug', 'ASC');
            }

            return $songs->paginate($paginate);
        });
    }

    /**
     * @param $singer_slug
     * @param $song_slug
     * @return mixed
     */
    public static function findOneBySlugsWithCache($singer_slug, $song_slug)
    {
        return Cache::remember('find_song_by_slugs_'.$singer_slug.'_'.$song_slug, self::CD_ITEM, function () use ($singer_slug, $song_slug) {
            $singer = Singer::findOneBySlugWithCache($singer_slug);

            if ($singer) {
                return Song::with('singer')->where(['singer_id' => $singer->id, 'slug' => $song_slug, 'status' => self::STATUS_ACTIVE])->first();
            }

            return false;
        });
    }

    /**
     * Finds a song random
     *
     * @return Song
     */
    public static function getRandomSong()
    {
        $songs  = Cache::remember('random_lists', self::CD_RANDOM_LIST, function () {

            $songs  = Song::where('status', self::STATUS_ACTIVE)->with('singer')
                            ->orderByRaw('RAND()')->take(500)->get();

            return $songs;
        });

        return $songs->shuffle()->first();
    }

    /**
     * @param $singer_slug
     * @param $song_slug
     * @return bool
     */
    public static function deleteCacheBySlugs($singer_slug, $song_slug)
    {
        return Cache::forget('find_song_by_slugs_'.$singer_slug.'_'.$song_slug);
    }

    /**
     * Pluses one to hit of a singer
     *
     * @param $id
     * @return int
     */
    public static function plusHit($id)
    {
        return Song::find($id)->increment('hit');
    }

}
