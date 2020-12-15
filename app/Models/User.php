<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RootLink[] $rootLinks
 * @property-read int|null $root_links_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $verified
 * @property string|null $last_login
 * @property string|null $last_action
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereVerified($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LinkIndex[] $linkIndexers
 * @property-read int|null $link_indexers_count
 */
class User extends Model
{
    public function tryLogin($password)
    {
        if($this->verified == 0) {
            return false;
        }
        return password_verify($password, $this->password ?: null);
    }

    /* Relationships */
    public function rootLinks()
    {
        return $this->hasMany(RootLink::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function linkIndexers()
    {
        return $this->hasMany(LinkIndex::class);
    }
}
