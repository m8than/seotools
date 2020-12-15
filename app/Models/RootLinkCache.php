<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RootLinkCache
 *
 * @property int $id
 * @property string|null $crawled_date
 * @property string|null $indexed_date
 * @property float $views_per_day
 * @property float $backlinks_count
 * @property float $rating
 * @property int $root_link_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereBacklinksCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereCrawledDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereIndexedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereRootLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereViewsPerDay($value)
 * @mixin \Eloquent
 * @property float $crawls_per_day
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereCrawlsPerDay($value)
 * @property float $indexes_per_day
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLinkCache whereIndexesPerDay($value)
 */
class RootLinkCache extends Model
{

    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'crawled_date',
        'indexed_date',
    ];

    public static function generateCache($updateInterval = 120)
    {
        // where doesn't have cache or cache out of date
        $toUpdate = RootLink::doesntHave('cache')
                            ->orWhereHas('cache', function(Builder $query) use ($updateInterval) {
                                $query->where('updated_at', '<', Carbon::now()->subSeconds($updateInterval)->toDateTimeString());
                            })->get();
        
        foreach ($toUpdate as $rootlink) {
            /** @var RootLink $rootlink */
            self::cacheRootLink($rootlink);
        }
    }

    public static function cacheRootLink(RootLink $rootlink)
    {
        $cache = RootLinkCache::where('root_link_id', $rootlink->id)->firstOrNew();

        $cache->crawled_date = $rootlink->getCrawledDate();
        $cache->indexed_date = $rootlink->getIndexedDate();
        $cache->views_per_day = $rootlink->getViewsPerDay();
        $cache->indexes_per_day = $rootlink->getIndexesPerDay();
        $cache->crawls_per_day = $rootlink->getCrawlsPerDay();
        $cache->backlinks_count = $rootlink->getBacklinksCount();
        $cache->rating = $rootlink->getRating();
        $cache->root_link_id = $rootlink->id;

        $cache->save();
    }
}