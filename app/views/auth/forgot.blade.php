<h1>Restore</h1>
{{ Form::open(array('method' => 'AuthController@postForget')); }}

    {{ Form::email('email'); }}
    {{ Form::submit('Register'); }}

    <div> {{ var_dump($errors->all()); }} </div>

{{ Form::close(); }}