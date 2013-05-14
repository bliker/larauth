<?php

use Illuminate\Support\MessageBag;

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

        $activation = Config::get('auth.activation');

        // Try to create a user
        $user = User::create(array(
            'email'     => Input::get('email'),
            'password'  => Input::get('password'),
            'group'     => Config::get('auth.group'),
            'activated' => !$activation
        ));

        if (!$user->exists)
        {
            // validation failied
            // validator object is now accesible at user
            return Redirect::back()
                           ->withErrors($user->validator)
                           ->withInput(Input::except('password'));
        }

        if ($activation)
        {
            // Get the activation code
            // and send an email
            $this->sendActivation($user);
            return Redirect::to('/')
                           ->with('message', 'Email send');
        }
        else
        {
            // no activation, so we can just log them straight away
            $login = Auth::attempt(Input::only('email','password'));
            if ($login)
            {
                return Redirect::to('/')
                               ->with('message', 'Welcome');
            }
            else
            {
                // This should never happen
                throw 'Attepmt to login newly registered user failied';
            }
        }
    }


    public function getLogin()
    {
        return View::make('auth.login');
    }
    public function postLogin()
    {
        $params = Input::only(array('email', 'password'));

        // add activation if necessary
        if(Config::get('auth.activation')) $params['activated'] = 1;
        $auth = Auth::attempt($params, Input::get('remember'));

        if ($auth)
        {
            return Redirect::to('/')
                           ->with('message', 'Logged in');
        }
        else
        {
            $errors = new MessageBag;
            $errors->add('auth', 'Wrong email or password');
            return Redirect::back()->withErrors($errors);
        }
    }

    public function getLogout()
    {
        Auth::logout();

        return Redirect::to('/')->with('message', 'Succesfully logged out');
    }

    public function getForgot()
    {
        $errors = $this->reminderErrors();
        return View::make('auth.forgot')
                   ->withInput()
                   ->withErrors($errors);
    }
    public function postForgot()
    {
        return Password::remind(array('email'=>Input::get('email')));
    }

    public function getReset($token = null)
    {
        $errors = $this->reminderErrors();
        return View::make('auth.reset')->with('token', $token);
    }
    public function postReset()
    {
        $credentials = compact(Input::get('email'));
        $success = Password::reset($credentials, function($user, $password)
        {
                $user->password = Hash::make($password);
                $user->save();
                return true;
        });
    }

    protected function reminderErrors()
    {
        $errors = new MessageBag;
        if (Session::has('error') ) {
            $reason = trans(Session::get('reason'));
            $errors->add('auth', $reason);
        }

        return $errors;
    }

    public function getActivate($email = null, $token = null)
    {
        // if spme arguments are missing show the form
        if (is_null($email) || is_null($token))
        {
            return View::make('auth.activate');
        }
        return $this->checkActivation($email, $token);
    }

    /**
     * For some odd reason, user will not click on the link in email but rather fill up the form
     */
    public function postActivate()
    {
        return $this->prepareActivation(Input::get('email'), Input::get('token'));
    }


    protected function prepareActivation($email, $token)
    {
        $activation = $this->checkActivation($email, $token);
        if ($activation->success)
        {
           Auth::attempt(compact($email));
           return Redirect::to('/')
                          ->with('message', 'Succesfully activated and logged in');
        }
        else
        {
            return Redirect::to('AuthController@getActivate')
                           ->withErrors($activation->messages);
        }
    }

    /**
     * Create activation token and send ti to email of user
     * @param  [mixed] $user [user model]
     * @return [mixed]       [$email object]
     */
    protected function sendActivation($user)
    {

        $email = $user->email;
        $token = md5($user->created_at->getTimestamp().$email);
        $url = URL::action('AuthController@getActivate');

        $data['url'] = $url.'/'.urlencode($email).'/'.urlencode($token);
        $mail = Mail::send('emails.auth.activate', $data, function($m) use ($email)
        {
            $m->to($email)->subject('Activate your account');
        });

        // error checking or mail?

    }

    protected function checkActivation($email, $token)
    {
        $errors = new MessageBag;
        $user = User::where('email', '=', $email)->first();
        if (is_null($user))
        {
            $errors->add('auth', 'User with this email does not exist, have you registered?');
        }
        elseif ($user->activated == 1)
        {
            $errors->add('auth', 'You are already activated');
        }
        else
        {
            // recreate the token
            $check = md5(strtotime($user->created_at).$user->email);
            if ($token == $check)
            {
                // token matches so we can activate user
                $user->activate();
            }
            else
            {
                $errors->add('auth', 'Token does not match.');
            }
        }

        // now just send report back
        return (object) array(
            'success' => (bool)!$errors->any(),
            'messages' => $errors->any() ? $errors : null
        );
    }
}