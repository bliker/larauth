<h1>:Set new pass</h1>
{{ Form::open(array('method' => 'AuthController@getRestore')); }}
    {{ Form::password('password'); }}
    {{ Form::password('password_again'); }}
{{ Form::close() }}