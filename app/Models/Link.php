<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Link
 *
 * @property int $id
 * @property string $scheme
 * @property string $domain
 * @property string $url_string
 * @property string $anchor_text
 * @property int $outdated
 * @property int $root_link_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RootLink|null $root
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereAnchorText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereOutdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereRootLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereScheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereUrlString($value)
 * @mixin \Eloquent
 */
class Link extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain', 'url_string'
    ];

    public static function reloadLinks(RootLink $rootLink)
    {
        $options = array(
            'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-language: en\r\n" .
                        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36 OPR/68.0.3618.104\r\n" // i.e. An iPad 
            )
        );

        $url = $rootLink->full_url;
        
        $context = stream_context_create($options);
        $localDomain = parse_url($url)['host'];
        $page = file_get_contents($url, false, $context);

        $dom = new \DOMDocument;

        @$dom->loadHTML($page);
        
        $pageLinks = $dom->getElementsByTagName('a');

        $newLinks = [];


        $outdated = false;

        foreach ($pageLinks as $pageLink){
            if (!$pageLink->hasAttribute('href')) {
                continue;
            }

            $linkUrl = parse_url($pageLink->getAttribute('href'));

            if(!isset($linkUrl['host'])) {
                continue;
            }

            $scheme = $linkUrl['scheme'] ?? 'https';
            $domain = $linkUrl['host'];

            // check if internal
            if($domain == $localDomain) {
                continue;
            }


            $urlString = $linkUrl['path'] ?? '/'  .
                         (isset($linkUrl['query']) ? '?' . $linkUrl['query'] : '') .
                         (isset($linkUrl['fragment']) ?  '#' . $linkUrl['fragment'] : '');
            $anchorText = $pageLink->nodeValue;
                         
            $link = self::where('scheme', '=', $scheme)
            ->where('domain', '=', $domain)
            ->where('url_string', '=', $urlString)
            ->where('anchor_text', '=', $anchorText)
            ->firstOrNew();

            $link->scheme = $scheme;
            $link->domain = $domain;
            $link->url_string = $urlString;
            $link->anchor_text = $pageLink->nodeValue;

            if ($link->id == null) {
                $outdated = true;
            }

            $newLinks[] = $link;
        }

        
        if ($outdated) {
            $rootLink->links()->increment('outdated');
            $rootLink->links()->saveMany($newLinks);
        }
    }

    /* Relationships */
    
    public function root_link()
    {
        return $this->belongsTo(RootLink::class);
    }
}
