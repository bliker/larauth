<h1>:Register</h1>
{{ Form::open(array('method' => 'AuthController@register')); }}

    {{ Form::email('email'); }}
    {{ Form::password('password'); }}
    {{ Form::submit('Register'); }}
    <ul>
        {{ implode('', $errors->all('<li>:message</li>')) }}
    </ul>

{{ Form::close(); }}