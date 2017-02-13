<?php

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Bcrypt Auth driver.
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Auth_Bcrypt extends Auth {

	public function __construct($config = array())
	{
		if (!isset($config['cost']) or ! is_numeric($config['cost']) or $config['cost'] < 10)
		{
			throw new Kohana_Exception(__CLASS__ . ' cost parameter must be set and must be integer >= 10');
		}
		parent::__construct($config);
	}

	/**
	 * Performs login on user
	 * @param string $username Username
	 * @param string $password Password
	 * @param bool $remember Remembers login
	 * @return boolean
	 * @throws Kohana_Exception
	 */
	protected function _login($username, $password, $remember = FALSE)
	{
		if (!is_string($username) or ! is_string($password) OR ! is_bool($remember))
		{
			throw new Kohana_Exception('Username and password must be strings, remember must be bool');
		}

		$user = ORM::factory('user')
				->where('username', '=', $username)
				->limit(1)
				->find();

		if ($user->loaded() and password_verify($password, $user->password))
		{
			$this->complete_login($user);
			$user->complete_login();

			if (password_needs_rehash($user->password, PASSWORD_BCRYPT, [
						'cost' => $this->_config['cost']
					]))
			{
				$user->password = $password;
				$user->save();
			}

			return TRUE;
		} else
		{
			return FALSE;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function check_password($password)
	{
		$user = $this->get_user();

		if (!$user)
		{
			return false;
		}

		return password_verify($password, $user->password);
	}

	/**
	 * Gets stored password for username
	 * @param type $username
	 */
	public function password($username)
	{
		if ($username instanceof Model_Auth_User)
		{
			return $username->password;
		} elseif (is_string($username))
		{
			return parent::get_user($username)->password;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function hash($str)
	{
		return password_hash($str, PASSWORD_BCRYPT, [
			'cost' => $this->_config['cost']
		]);
	}

}
