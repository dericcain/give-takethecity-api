<?php

namespace App;

use App\Helpers\Phone;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $guarded = [];

    protected $appends = ['full_name'];
    /**
     * @param $phone
     */
    public function setPhoneAttribute($phone)
    {
        $this->attributes['phone'] = Phone::onlyNumbers($phone);
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recurringDonation()
    {
        return $this->hasMany(RecurringDonation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * @param $query
     * @param $email
     * @return mixed
     */
    public function scopeFindByEmail($query, $email)
    {
        return $query->where('email', $email)->first();
    }

    /**
     * @param $query
     * @param $email
     * @param $phone
     * @return mixed
     */
    public function scopeFindByEmailOrPhone($query, $email, $phone)
    {
        return $query->where('email', $email)
                     ->orWhere('phone', Phone::onlyNumbers($phone))
                     ->first();
    }
}
