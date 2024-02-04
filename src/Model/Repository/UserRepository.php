<?php

namespace N_ONE\App\Model\Repository;

use N_ONE\App\Model\User;
use N_ONE\App\Model\Entity;
use N_ONE\Core\DbConnector\DbConnector;
use RuntimeException;

class UserRepository extends Repository
{
	private DbConnector $dbConnection;

	public function __construct(DbConnector $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		$users = [];

		$result = mysqli_query($connection, "
		SELECT u.ID, u.NAME, u.ROLE_ID, u.EMAIL, u.PASSWORD, u.PHONE_NUMBER, u.ADDRESS, r.TITLE, u.ROLE_ID
		FROM N_ONE_USERS u
		JOIN N_ONE_ROLES r on r.ID = u.ROLE_ID;
		");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$users[] = new User(
				$row['ID'],
				$row['ROLE_ID'],
				$row['TITLE'],
				$row['NAME'],
				$row['EMAIL'],
				$row['PASSWORD'],
				$row['PHONE_NUMBER'],
				$row['ADDRESS'],
			);
		}

		if (empty($users))
		{
			throw new RuntimeException("Items not found");
		}

		return $users;
	}
	public function getById(int $id): User
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query($connection, "
		SELECT u.ID, u.NAME, u.ROLE_ID, u.EMAIL, u.PASSWORD, u.PHONE_NUMBER, u.ADDRESS, r.TITLE
		FROM N_ONE_USERS u
		JOIN N_ONE_ROLES r on r.ID = u.ROLE_ID
		WHERE u.ID = $id;
		");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$user = new User(
				$row['ID'],
				$row['ROLE_ID'],
				$row['TITLE'],
				$row['NAME'],
				$row['EMAIL'],
				$row['PASSWORD'],
				$row['PHONE_NUMBER'],
				$row['ADDRESS'],
			);
		}

		if (empty($user))
		{
			throw new RuntimeException("Item with id $id not found");
		}

		return $user;
	}
	public function getByIds(array $ids): array
	{
		$connection = $this->dbConnection->getConnection();
		$users = [];

		$result = mysqli_query($connection, "
		SELECT u.ID, u.NAME, u.ROLE_ID, u.EMAIL, u.PASSWORD, u.PHONE_NUMBER, u.ADDRESS, r.TITLE, u.ROLE_ID
		FROM N_ONE_USERS u
		JOIN N_ONE_ROLES r on r.ID = u.ROLE_ID
		WHERE u.ID IN (" . implode(',', $ids) . ");
		");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		while($row = mysqli_fetch_assoc($result))
		{
			$users[] = new User(
				$row['ID'],
				$row['ROLE_ID'],
				$row['TITLE'],
				$row['NAME'],
				$row['EMAIL'],
				$row['PASSWORD'],
				$row['PHONE_NUMBER'],
				$row['ADDRESS'],
			);
		}

		if (empty($users))
		{
			throw new RuntimeException("Items not found");
		}

		return $users;
	}
	public function add(User|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$tagId = $entity->getId();
		$roleId = $entity->getRoleId();
		$name = mysqli_real_escape_string($connection, $entity->getName());
		$email = mysqli_real_escape_string($connection, $entity->getEmail());
		$password = mysqli_real_escape_string($connection, $entity->getPass());
		$phoneNumber = mysqli_real_escape_string($connection, $entity->getNumber());
		$address = mysqli_real_escape_string($connection, $entity->getAddress());

		$result = mysqli_query($connection, "
		INSERT INTO N_ONE_USERS (ID, ROLE_ID, NAME, EMAIL, PASSWORD, PHONE_NUMBER, ADDRESS) 
		VALUES (
			$tagId,
			'$roleId',
			$name,
			$email,
			'$password',
			$phoneNumber,
			{$address}
		);");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}
	public function update(User|Entity $entity): bool
	{
		$connection = $this->dbConnection->getConnection();
		$tagId = $entity->getId();
		$roleId = $entity->getRoleId();
		$name = mysqli_real_escape_string($connection, $entity->getName());
		$email = mysqli_real_escape_string($connection, $entity->getEmail());
		$password = mysqli_real_escape_string($connection, $entity->getPass());
		$phoneNumber = mysqli_real_escape_string($connection, $entity->getNumber());
		$address = mysqli_real_escape_string($connection, $entity->getAddress());

		$result = mysqli_query($connection, "
		UPDATE N_ONE_USERS 
		SET ROLE_ID = $roleId,
			NAME = '$name', 
			EMAIL = '$email', 
			PASSWORD = '$password', 
			PHONE_NUMBER = '$phoneNumber', 
			ADDRESS = {$address}
		WHERE ID = $tagId");

		if (!$result)
		{
			throw new RuntimeException(mysqli_error($connection));
		}

		return true;
	}
}