<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RootLink[] $rootLinks
 * @property-read int|null $root_links_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category query()
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $user
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $ip_address
 * @property string $hostname
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpInfo whereHostname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpInfo whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IpInfo whereUpdatedAt($value)
 */
class IpInfo extends Model
{
    /**
     * @return static
     */
    public static function addIpOrFetch($ip_address)
    {
        $ipinfo = static::whereIpAddress($ip_address)->first();
        
        if(!$ipinfo)
        {
            $ipinfo = new static;
            $ipinfo->ip_address = $ip_address;
            $ipinfo->hostname = gethostbyaddr($ip_address);
            $ipinfo->save();
        }

        return $ipinfo;
    }
}
