<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    // tell Eloquent that uuid is a string, not an integer
    protected $keyType = 'string';

    public $incrementing = true;
}
