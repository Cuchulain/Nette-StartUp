<?php
/**
 * Created by Jan Cejka <posta@jancejka.cz> in IntelliJ IDEA.
 * Date: 06.12.2016
 * Time: 19:54
 * For project: web2
 */

namespace App\Model;

use Nette;
use Nette\Security\Passwords;

class UserManager implements Nette\Security\IAuthenticator
{
	use Nette\SmartObject;

	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	protected $em;

	/**
	 * @var \Kdyby\Doctrine\EntityRepository
	 */
	protected $users;

	public function __construct(\Kdyby\Doctrine\EntityManager $em)
	{
		$this->em = $em;
		$this->users = $em->getRepository(\App\Model\User::class);
	}

	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		/**
		 * @var $user \App\Model\User
		 */
		$user = $this->users->findOneBy(['username' => $username]);

		if (!$user) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!Passwords::verify($password, $user->password)) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($user->password)) {
			$user->password = Passwords::hash($password);
			$this->em->flush();
		}

		$arr = (array)$user;
		unset($arr['password']);
		return new Nette\Security\Identity($user->username, $user->role, $arr);
	}


	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return void
	 * @throws DuplicateNameException
	 */
	public function add($username, $email, $password)
	{
		if ($this->users->findOneBy(['username' => $username]) !== null) {
			throw new DuplicateNameException;
		}

		$user = new User();

		$user->username = $username;
		$user->email = $email;
		$user->password = Passwords::hash($password);

		$this->em->persist($user); // start managing the entity
		$this->em->flush(); // save it to the database
	}

}

class DuplicateNameException extends \Exception
{}
