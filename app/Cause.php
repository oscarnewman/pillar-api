<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cause extends BaseModel
{
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'cause_organization');
    }

    public function image()
    {
        return $this->hasOne(Image::class);
    }
}
