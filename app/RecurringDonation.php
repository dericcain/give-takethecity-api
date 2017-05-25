<?php

namespace App;

use App\Donation\RecurringDonationCharge;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RecurringDonation extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Get all of the donors that need to be charged today.
     *
     * @param $query
     * @return mixed
     */
    public function scopeNeedChargingToday($query)
    {
        return $query->with('donor')
            ->where('next_donation_on', Carbon::now()->toDateString())
            ->get();
    }

    /**
     * Update the next donation date for the recurring donation.
     *
     * @return bool
     */
    public function updateNextDonationOn()
    {
        return $this->update([
            'next_donation_on' => Carbon::now()->addMonth()->toDateString()
        ]);
    }

    public function charge()
    {
        $charge = new RecurringDonationCharge($this);
        $charge->charge();
    }
}
