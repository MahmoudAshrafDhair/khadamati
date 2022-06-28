<!DOCTYPE html>
<html>
<head>
    <title>{{__('message.account_forget_password')}}</title>
</head>
<body>
<h1>{{__('message.hello')}},{{ $data['name'] }}</h1>

<p>{{__('message.account_forget_password_message')}}</p>

<p>{{__('message.verification_code')}} {{ $data['code'] }}</p>

<p>{{__('message.thank')}}</p>
</body>
</html>
