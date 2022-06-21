{{--@component('mail::message')--}}
{{--# Introduction--}}

{{--The body of your message.--}}

{{--@component('mail::button', ['url' => ''])--}}
{{--Button Text--}}
{{--@endcomponent--}}

{{--Thanks,<br>--}}
{{--{{ config('app.name') }}--}}
{{--@endcomponent--}}
    <!DOCTYPE html>
<html>
<head>
    <title>{{__('message.account_confirmation')}}</title>
</head>
<body>
<h1>{{__('message.hello')}},{{ $data['name'] }}</h1>

<p>{{__('message.account_confirmation_message')}}</p>

<p>{{__('message.verification_code')}} {{ $data['code'] }}</p>

<p>{{__('message.thank')}}</p>
</body>
</html>
