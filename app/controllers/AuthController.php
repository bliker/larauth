<?php

class AuthController extends BaseController {

    function __construct() {
        $this->beforeFilter('guest', array(
            'except' => array('getLogout')
        ));
    }

    public function getRegister()
    {
        return View::make('auth.register');
    }
    public function postRegister()
    {
        $input = Input::all();
        $user = User::create(array(
            'email'     => $input['email'],
            'password'  => Hash::make($input['password']),
            'group'     => Config::get('auth.group'),
            'activated' => Config::get('auth.activation')
        ));
        // validation
        return Redirect::to('/')->with('message', 'hej!');
    }


    public function getLogin()
    {
        return View::make('auth.login');
    }
    public function postLogin()
    {
        $params = Input::only(array('email', 'password'));

        // add activation if necessary
        if(Config::get('auth.activation')) $params['activated'] = 0;

        $auth = Auth::attempt($params, Input::get('remember'));
        if ($auth) {
            return Redirect::to('/')->with('message', 'Logged in');
        } else {
            return Redirect::to('AuthController@Login');
        }
    }

    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('/')->with('message', 'Succesfully logged out');
    }

    public function getForgot()
    {
        return View::make('auth.forgot');
    }
    public function postForgot()
    {

    }

    public function getRestore()
    {

    }
    public function postRestore()
    {

    }

    public function getActivate($token)
    {
        return View::make('auth.activate');
    }
    public function postActivate()
    {

    }
}