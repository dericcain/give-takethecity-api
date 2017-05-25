<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    /**
     * No need for timestamps on this table.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['name', 'email'];

    /**
     * @return mixed
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
