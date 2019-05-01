<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;

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


    /**
     * For select list
     *
     * @return array
     */
    public static function selectList()
    {
        return Arr::pluck(Singer::orderBy('name', 'asc')->get(), 'name', 'id');
    }

    /**
     * Finds singers with cache
     *
     * @param bool $initial
     * @param string $order
     * @param int $paginate
     *
     * @return Singer[]|int
     */
    public static function getListWithCache($initial = false, $order = 'name', $paginate = 14)
    {
        $cache_key  = 'singer_list_'.(string) $initial.'_'.$order.'_'. (integer) $paginate.'_'.Input::get('page', 1);

        return Cache::remember($cache_key, self::CD_LIST, function () use ($initial, $order, $paginate) {
            $singers    = Singer::where(['status' => self::STATUS_ACTIVE]);

            if (!empty($initial)) {
                if ($initial == '09') {
                    $singers->where(function ($query) {
                        for ($i = 0; $i <= 9; $i++) {
                            $query->orWhere('slug', 'like', $i.'%');
                        }
                    });
                } else {
                    $singers->where('slug', 'like', $initial.'%');
                }
            }

            if ($order == 'hit') {
                $singers->orderBy('hit', 'DESC');
            } elseif ($order == 'name') {
                $singers->orderBy('slug', 'ASC');
            }

            return $singers->paginate($paginate);
        });
    }

    /**
     * Finds a singer by slug with cache
     *
     * @param $slug
     *
     * @return Singer
     */
    public static function findOneBySlugWithCache($slug)
    {
        return Cache::remember('find_singer_by_slug_'.$slug, self::CD_ITEM, function () use ($slug) {

            return Singer::where(['slug' => $slug, 'status' => self::STATUS_ACTIVE])->first();

        });
    }

    /**
     * Deletes a singer by slug on cache
     *
     * @param $slug
     *
     * @return bool
     */
    public static function deleteCacheBySlug($slug)
    {
        return Cache::forget('find_singer_by_slug_'.$slug);
    }

    /**
     * Pluses one to hit of a singer
     *
     * @param $id
     * @return int
     */
    public static function plusHit($id)
    {
        return Singer::whereId($id)->increment('hit');
    }
}
