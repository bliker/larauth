<h1>:Login</h1>
{{ Form::open(array('method' => 'AuthController@Login')); }}

    {{ Form::email('email'); }}
    {{ Form::password('password'); }}
    {{ Form::checkbox('remember', 1, true);}}
    {{ Html::link(URL::action('AuthController@getForgot'), 'Forgot Password'); }}
    {{ Form::submit('Login'); }}

    <div> {{ var_dump($errors->all()) }} </div>

{{ Form::close(); }}

