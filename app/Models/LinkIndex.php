<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LinkIndex
 *
 * @property int $id
 * @property string $url
 * @property string $class
 * @property int $success
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $progress
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex whereSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkIndex whereUserId($value)
 * @mixin \Eloquent
 */
class LinkIndex extends Model
{
    protected $table = 'link_index_queue';
}
