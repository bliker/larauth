<h1>:Activate</h1>
{{ Form::open(array('method' => 'AuthController@postRegister')); }}

    {{ Form::email('email'); }}
    {{ Form::password('password'); }}
    {{ Form::submit('Register'); }}

    <div> {{ var_dump($errors->all()) }} </div>

{{ Form::close(); }}