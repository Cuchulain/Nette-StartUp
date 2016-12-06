<?php
/**
 * Created by Jan Cejka <posta@jancejka.cz> in IntelliJ IDEA.
 * Date: 06.12.2016
 * Time: 19:54
 * For project: web2
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User
{
	use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column

	/**
	 * @ORM\Column(type="string", length=32, nullable=false)
	 */
	public $username;

	/**
	 * @ORM\Column(type="string")
	 */
	public $password;

	/**
	 * @ORM\Column(type="string", length=333, nullable=false)
	 */
	public $email;

	/**
	 * @ORM\Column(type="string", length=32, nullable=true)
	 */
	public $telephone;

	/**
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	public $role;

	/**
	 * @var array roleList
	 */
}
