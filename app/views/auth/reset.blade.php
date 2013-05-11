<h1>:Set new pass</h1>
{{ Form::open(array('method' => 'AuthController@postReset')); }}
    {{ Form::password('email'); }}
    {{ Form::password('password'); }}
    {{ Form::password('password_again'); }}
{{ Form::close() }}