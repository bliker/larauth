<h1>:Set new pass</h1>
{{ Form::open(array('method' => 'AuthController@postReset')); }}
    {{ Form::hidden('token', $token); }}
    {{ Form::email('email'); }}
    {{ Form::password('password'); }}
    {{ Form::password('password_confirmation'); }}
{{ Form::close() }}