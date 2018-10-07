<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
class UserManager implements Nette\Security\IAuthenticator
{
    use Nette\SmartObject;

    const
        TABLE_NAME = 'User',
        COLUMN_ID = 'idUser',
        COLUMN_NAME = 'name',
        COLUMN_SURNAME = 'surname',
        COLUMN_BIRTH = 'birth',
        COLUMN_ADDRESS = 'address',
        COLUMN_EMAIL = 'email',
        COLUMN_PASSWORD_HASH = 'password',
        COLUMN_ADMIN = 'admin',
        COLUMN_CITY = 'idCity';


    /** @var Nette\Database\Context */
	private $database;


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;

		$row = $this->database->table(self::TABLE_NAME)
			->where(self::COLUMN_EMAIL, $email)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!Passwords::verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update([
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);
		if ($row[self::COLUMN_ADMIN] == 1) {
		    $role = "admin";
        }
        else {
            $role = "user";
        }
		return new Nette\Security\Identity($row[self::COLUMN_ID], $role, $arr);
	}


	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return void
	 * @throws DuplicateNameException
	 */
	public function add($email, $password)
	{
		try {
			$this->database->table(self::TABLE_NAME)->insert([
				self::COLUMN_EMAIL => $email,
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
                self::COLUMN_CITY => 1
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}


    /**
     * @param array $settings
     * @throws DuplicateNameException
     */
	public function saveUserSettings($settings)
    {
        $this->database->table(self::TABLE_NAME)->where(self::COLUMN_EMAIL, $settings->email)
            ->update([
            self::COLUMN_NAME => $settings->name,
            self::COLUMN_SURNAME => $settings['surname'],
            self::COLUMN_BIRTH => $settings['birth'],
            self::COLUMN_ADDRESS => $settings['address'],
            self::COLUMN_EMAIL => $settings['email'] // TODO: Email doesn't change
        ]);
    }


    public function updateUserSettings($userId) {
        $row = $this->database->table(self::TABLE_NAME)
            ->where(self::COLUMN_ID, $userId)
            ->fetch();

        $arr = $row->toArray();
        unset($arr[self::COLUMN_PASSWORD_HASH]);
        if ($row[self::COLUMN_ADMIN] == 1) {
            $role = "admin";
        }
        else {
            $role = "user";
        }
        return $arr;
    }
}



class DuplicateNameException extends \Exception
{
}
