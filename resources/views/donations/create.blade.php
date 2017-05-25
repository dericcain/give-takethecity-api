<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="X-CSRF-TOKEN" content="{{ csrf_token() }}">
    <title>Give - Take the City</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,300,600" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.3.0/css/bulma.min.css" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/public.css') }}" rel="stylesheet" type="text/css">

    <script>window.stripePublicKey = "{!! config('services.stripe.key') !!}"; </script>

</head>
<body style="background-image: url('{{ asset("images/bg.jpg") }}'); ">
<div id="app">
    <div id="form-wrapper">
        <donation-form></donation-form>
    </div>
</div>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/public.js') }}"></script>
</body>
</html>
