<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends BaseModel
{
    public function causes()
    {
        return $this->belongsToMany(Cause::class, 'cause_organization');
    }

    public function logo() {
        return $this->hasOne(Image::class, 'logo_image_id');
    }
}
