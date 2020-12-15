<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RootLink
 *
 * @property int $id
 * @property string $scheme
 * @property string $domain
 * @property string $url_string
 * @property int $user_id
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Link[] $links
 * @property-read int|null $links_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\View[] $views
 * @property-read int|null $views_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink whereScheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink whereUrlString($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RootLink whereUserId($value)
 * @mixin \Eloquent
 * @property-read mixed $views_per_day
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Link[] $links_with_outdated
 * @property-read int|null $links_with_outdated_count
 * @property-read \App\Models\View|null $view_oldest
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Link[] $backlinks
 * @property-read int|null $backlinks_count
 * @property-read \App\Models\RootLinkCache|null $cache
 * @property-read mixed $crawled_date
 * @property-read mixed $crawls_per_day
 * @property-read mixed $indexed_date
 * @property-read mixed $indexes_per_day
 * @property-read mixed $rating
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\View[] $views_crawled
 * @property-read int|null $views_crawled_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\View[] $views_indexed
 * @property-read int|null $views_indexed_count
 */
class RootLink extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain', 'url_string'
    ];

    public static function addUrlOrCreate($url, $user_id, $category_id)
    {
        $refererUrl = parse_url($url);

        $scheme = $refererUrl['scheme'];
        $domain = $refererUrl['host'];
        $urlString = $refererUrl['path'] .
                     (isset($refererUrl['query']) ? '?' . $refererUrl['query'] : '') .
                     (isset($refererUrl['fragment']) ?  '#' . $refererUrl['fragment'] : '');


        $rootLink = RootLink::where('scheme', '=', $scheme)
                        ->where('domain', '=', $domain)
                        ->where('url_string', '=', $urlString)
                        ->where('user_id', '=', $user_id)
                        ->where('category_id', '=', $category_id)
                        ->firstOrNew();


        $rootLink->scheme = $scheme;
        $rootLink->domain = $domain;
        $rootLink->url_string = $urlString;
        $rootLink->category_id = $category_id;
        $rootLink->user_id = $user_id;

        if ($rootLink->id) {
            Link::reloadLinks($rootLink);
            RootLinkCache::cacheRootLink($rootLink);
        }
        
        $rootLink->save();

        return $rootLink;
    }

    /* Relationships */

    public function cache()
    {
        return $this->hasOne(RootLinkCache::class);
    }
    
    public function links()
    {
        return $this->hasMany(Link::class)->where('outdated', '=', '0');
    }

    public function links_with_outdated()
    {
        return $this->hasMany(Link::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function view_oldest()
    {
        return $this->hasOne(View::class)->oldest('created_at')->with('ip_info');
    }

    public function views_crawled()
    {
        return $this->hasMany(View::class)
                    ->whereHas('ip_info', function (Builder $query) {
                        $query->where('hostname', 'LIKE', '%google%');
                    });
    }

    public function views_indexed()
    {
        return $this->hasMany(View::class)
                    ->whereHas('ip_info', function (Builder $query) {
                        $query->where('hostname', 'LIKE', '%google-proxy%');
			$query->where('hostname', 'LIKE', '%googleusercontent%');
                    });
    }

    public function backlinks()
    {
        return $this->hasMany(Link::class, 'domain', 'domain')->where('root_link_id', '!=', $this->id);
    }

    /* Properties */

    public function getFullUrlAttribute()
    {
        return $this->scheme . '://' . $this->domain . $this->url_string;
    }
    public function getBacklinksCountAttribute()
    {
        return $this->cache->backlinks_count ?? $this->getBacklinksCount();
    }

    public function getViewsPerDayAttribute()
    {
        return $this->cache->views_per_day ?? $this->getViewsPerDay();
    }

    public function getCrawlsPerDayAttribute()
    {
        return $this->cache->crawls_per_day ?? $this->getCrawlsPerDay();
    }

    public function getIndexesPerDayAttribute()
    {
        return $this->cache->indexes_per_day ?? $this->getIndexesPerDay();
    }

    public function getRatingAttribute()
    {
        return $this->cache->rating ?? $this->getRating();
    }

    public function getCrawledDateAttribute()
    {
        return $this->cache ? $this->cache->crawled_date : $this->getCrawledDate();
    }

    public function getIndexedDateAttribute()
    {
        return $this->cache ? $this->cache->indexed_date : $this->getIndexedDate();
    }

    /* Calculations bruv */

    public function getBacklinksCount()
    {
        return $this->backlinks()->count();
    }

    public function getViewsPerDay()
    {
        $from = $this->view_oldest->created_at ?? Carbon::now();
        $days = $from->floatDiffInDays(Carbon::now());
        if ($days < 1) {
            $days = 1;
        }
        
        return round($this->views()->count() / $days);
    }

    public function getCrawlsPerDay()
    {
        $oldest = $this->views_crawled()->oldest()->first();

        if (!$oldest) {
            return 0;
        }

        $from = $oldest->created_at;
        $days = $from->floatDiffInDays(Carbon::now());

        $totalCrawls = $this->views_crawled()->count();

        if ($days <= 1) {
            return 1;
        } else {
            return $days != 0 ? $totalCrawls / $days : 0;
        }
    }

    public function getIndexesPerDay()
    {
        $oldest = $this->views_indexed()->oldest()->first();

        if (!$oldest) {
            return 0;
        }

        $from = $oldest->created_at;
        $days = $from->floatDiffInDays(Carbon::now());

        $totalIndexed = $this->views_indexed()->count();

        if ($days <= 1) {
            return 1;
        } else {
            return $days != 0 ? $totalIndexed / $days : 0;
        }
    }

    public function getRating()
    {
        $views_per_day = $this->getViewsPerDay();
        $indexes_per_day = $this->getIndexesPerDay();
        $crawls_per_day = $this->getCrawlsPerDay();

        $backlinks = $this->backlinks;
        $backlink_inheritance = 0;
        foreach ($backlinks as $backlink) {
            $backlink_inheritance += $backlink->root_link->getRating();
        }

        // can make this go more in depth in future (when data is cached we can go deeper and change rating based on backlinks of backlinks too)
        $backlink_count = count($backlinks);
        $rating = ($views_per_day / 100) +
                  ($indexes_per_day > 0 ? 3 + $indexes_per_day : 0) +
                  ($crawls_per_day > 0 ? 1 + $crawls_per_day : 0) +
                  ($backlink_count / 20 > 2 ? 2 : $backlink_count / 20) + // maximum +1 for backlinks
                  $backlink_inheritance / 5; 

        return $rating;
    }

    public function getCrawledDate()
    {
        $latest_crawl = $this->views_crawled()
                        ->latest()
                        ->first();

        return $latest_crawl ? $latest_crawl->created_at : null;
    }

    public function getIndexedDate()
    {
        $latest_index = $this->views_indexed()
                        ->latest()
                        ->first();

        return $latest_index ? $latest_index->created_at : null;
    }


}
