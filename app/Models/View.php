<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * App\Models\View
 *
 * @property int $id
 * @property string $ip_address
 * @property string $user_agent
 * @property int $root_link_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RootLink|null $root
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereRootLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereUserAgent($value)
 * @mixin \Eloquent
 * @property int $ip_info_id
 * @property-read \App\Models\IpInfo $ip_info
 * @property-read \App\Models\RootLink $root_link
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereIpInfoId($value)
 */
class View extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_agent'
    ];

    public static function addViewOrCreate(RootLink $rootLink, Request $req)
    {
        $view = new self;
        $view->ip_info_id = IpInfo::addIpOrFetch($req->ip())->id;
        $view->user_agent = $req->userAgent();
        $view->root_link_id = $rootLink->id;
        $view->save();
    }
    

    /* Relationships */

    public function root_link()
    {
        return $this->belongsTo(RootLink::class);
    }

    public function ip_info()
    { 
        return $this->belongsTo(IpInfo::class);
    }
}
