<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	protected $table = 'users';
	protected $guarded = array('id');
	protected $hidden = array('password');
	protected $hashed = array('password');

	// Validation variales
	protected $rules = array(
		'email' => 'required|email|unique:users',
		'password' => 'required|min:6|max:64',
		'group' => 'integer'
	);
	public $validator;


	public static function boot()
	{
		parent::boot();
		static::creating(function($data)
		{
			if ($data->validate())
			{
				$data->hash();
				return true;
			}
			else
			{
				return false;
			}
		});
	}

	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	/**
	 * Can user access this feature
	 * @param  int  $group [group number, higher is more premissive]
	 * @return
	 */
	public function can($group)
	{
		return $this->group >= $group;
	}

	/**
	 * Hash attributes
	 */
	public function hash()
	{
		foreach ($this->hashed as $field) {
			$this->attributes[$field] = Hash::make($this->attributes[$field]);
		}
	}

	/**
	 * Validator
	 * @return [mixed] []
	 */
	public function validate()
	{
		$validaton = Validator::make($this->attributes, $this->rules);
		if ($validaton->passes())
		{
			return true;
		}
		else
		{
			$this->validator = $validaton;
			return false;
		}
	}

	public function activate()
	{
		$this->activated = true;
		$this->save();
	}

}