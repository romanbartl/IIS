<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
class UserManager extends BaseManager implements Nette\Security\IAuthenticator
{
    use Nette\SmartObject;


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

		$row = $this->database->table(self::TABLE_USER)
			->where(self::USER_COLUMN_EMAIL, $email)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!Passwords::verify($password, $row[self::USER_COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($row[self::USER_COLUMN_PASSWORD_HASH])) {
			$row->update([
				self::USER_COLUMN_PASSWORD_HASH => Passwords::hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::USER_COLUMN_PASSWORD_HASH]);
		if ($row[self::USER_COLUMN_ADMIN] == 1) {
		    $role = "admin";
        }
        else {
            $role = "user";
        }
		return new Nette\Security\Identity($row[self::USER_COLUMN_ID], $role, $arr);
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
			$this->database->table(self::TABLE_USER)->insert([
				self::USER_COLUMN_EMAIL => $email,
				self::USER_COLUMN_PASSWORD_HASH => Passwords::hash($password),
                self::USER_COLUMN_CITY => 1
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}


    /**
     * @param array $settings
     * @throws DuplicateNameException
     */
	public function saveUserSettings($idUser, $settings)
    {
        try {
            $this->database->table(self::TABLE_USER)->where(self::USER_COLUMN_ID, $idUser)
                ->update([
                    self::USER_COLUMN_NAME => $settings['name'],
                    self::USER_COLUMN_SURNAME => $settings['surname'],
                    self::USER_COLUMN_BIRTH => $settings['birth'],
                    self::USER_COLUMN_ADDRESS => $settings['address'],
                    self::USER_COLUMN_EMAIL => $settings['email'] // TODO: Email doesn't change
                ]);
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }


    public function updateUserSettings($userId) {
        $row = $this->database->table(self::TABLE_USER)
            ->where(self::USER_COLUMN_ID, $userId)
            ->fetch();

        $arr = $row->toArray();
        unset($arr[self::USER_COLUMN_PASSWORD_HASH]);
        if ($row[self::USER_COLUMN_ADMIN] == 1) {
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
