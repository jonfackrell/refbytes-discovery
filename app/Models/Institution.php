<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userid', 'password', 'profile', 'org',
    ];

    /**
     * The users that belong to the institution.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
