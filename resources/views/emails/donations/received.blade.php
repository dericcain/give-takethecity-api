@component('mail::message')
Hey {{ $donation->donor->first_name }},

Without the generosity of so many people we would not be able to sustain the efforts and ministry that we have been doing across the Chattahoochee Valley area. Countless lives have been touched as the Body of Christ has gone our to share his love with our community and all of this is possible because of people like you.

Thank you so much for partnering with Take the City. Please let us know if there is ever any way that we can pray or help you in any way!

@if($donation->is_recurring)
    You gave a recurring donation of **${{ number_format($donation->amount / 100, 2) }}** and you designated your gift to {{ $donation->designation->name }}.
@else
    You gave a one-time donation of **${{ number_format($donation->amount / 100, 2) }}** and you designated your gift to {{ $donation->designation->name }}.
@endif

Thanks,
Andrew Chalmers
@endcomponent
