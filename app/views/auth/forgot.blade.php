<h1>Restore</h1>
{{ Form::open(array('method' => 'AuthController@postForget')); }}

    {{ Form::email('email'); }}
    {{ Form::submit('Restore Password'); }}

    <div>
       @if (Session::has('error'))
           {{ trans(Session::get('reason')) }}
       @endif
    </div>

{{ Form::close(); }}