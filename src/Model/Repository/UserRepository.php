<?php

namespace N_ONE\App\Model\Repository;

use mysqli_result;
use mysqli_sql_exception;
use N_ONE\App\Model\User;
use N_ONE\App\Model\Entity;
use N_ONE\Core\Configurator\Configurator;
use N_ONE\Core\Exceptions\DatabaseException;

class UserRepository extends Repository
{
	public function getUserFromResult(mysqli_result $result): ?User
	{
		$user = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$user = new User(
				$row['ID'],
				$row['ROLE_ID'],
				$row['NAME'],
				$row['EMAIL'],
				$row['PASSWORD'],
				$row['PHONE_NUMBER'],
				$row['ADDRESS'],
			);
		}

		return $user;
	}

	/**
	 * @return User[]
	 */
	public function getUsersFromResult(mysqli_result $result): array
	{
		$users = [];
		while ($row = mysqli_fetch_assoc($result))
		{
			$users[] = new User(
				$row['ID'],
				$row['ROLE_ID'],
				$row['NAME'],
				$row['EMAIL'],
				$row['PASSWORD'],
				$row['PHONE_NUMBER'],
				$row['ADDRESS'],
			);
		}

		return $users;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getList(array $filter = null): array
	{
		$connection = $this->dbConnection->getConnection();
		$numItemsPerPage = Configurator::option('NUM_OF_ITEMS_PER_PAGE');
		$currentLimit = $numItemsPerPage + 1;
		$offset = ($filter['pageNumber'] ?? 0) * $numItemsPerPage;
		$isActive = $filter['isActive'] ?? 1;
		$whereQueryBlock = $this->getWhereQueryBlock($isActive);

		$result = mysqli_query(
			$connection,
			"
			SELECT u.ID, u.NAME, u.ROLE_ID, u.EMAIL, u.PASSWORD, u.PHONE_NUMBER, u.ADDRESS, u.ROLE_ID
			FROM N_ONE_USERS u
			JOIN N_ONE_ROLES r on r.ID = u.ROLE_ID
			$whereQueryBlock
			LIMIT $currentLimit OFFSET $offset;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return $this->getUsersFromResult($result);
	}

	private function getWhereQueryBlock(int $isActive): string
	{
		return "WHERE u.IS_ACTIVE = $isActive";
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getByEmail(string $email): ?User
	{
		$connection = $this->dbConnection->getConnection();
		$escapedEmail = mysqli_real_escape_string($connection, $email);
		$result = mysqli_query(
			$connection,
			"
			SELECT u.ID, u.NAME, u.ROLE_ID, u.EMAIL, u.PASSWORD, u.PHONE_NUMBER, u.ADDRESS, r.TITLE
			FROM N_ONE_USERS u
			JOIN N_ONE_ROLES r on r.ID = u.ROLE_ID
			WHERE u.EMAIL = '$escapedEmail';"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return $this->getUserFromResult($result);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getById(int $id, bool $isPublic = false): ?User
	{
		$connection = $this->dbConnection->getConnection();
		if ($isPublic)
		{
			$isActive = "(1)";
		}
		else
		{
			$isActive = "(1, 0)";
		}
		$result = mysqli_query(
			$connection,
			"
			SELECT u.ID, u.NAME, u.ROLE_ID, u.EMAIL, u.PASSWORD, u.PHONE_NUMBER, u.ADDRESS
			FROM N_ONE_USERS u
			JOIN N_ONE_ROLES r on r.ID = u.ROLE_ID
			WHERE u.ID = $id and u.IS_ACTIVE in $isActive;"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return $this->getUserFromResult($result);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getByNumber(string $phone): ?User
	{
		$connection = $this->dbConnection->getConnection();
		$phone = mysqli_real_escape_string($connection, $phone);

		$result = mysqli_query(
			$connection,
			"
			SELECT u.ID, u.NAME, u.ROLE_ID, u.EMAIL, u.PASSWORD, u.PHONE_NUMBER, u.ADDRESS
			FROM N_ONE_USERS u
			JOIN N_ONE_ROLES r on r.ID = u.ROLE_ID
			WHERE u.PHONE_NUMBER = '$phone';"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		$user = null;
		while ($row = mysqli_fetch_assoc($result))
		{
			$user = new User(
				$row['ID'],
				$row['ROLE_ID'],
				$row['NAME'],
				$row['EMAIL'],
				$row['PASSWORD'],
				$row['PHONE_NUMBER'],
				$row['ADDRESS'],
			);
		}

		return $user;
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function getByIds(array $ids): array
	{
		$connection = $this->dbConnection->getConnection();

		$result = mysqli_query(
			$connection,
			"
			SELECT u.ID, u.NAME, u.ROLE_ID, u.EMAIL, u.PASSWORD, u.PHONE_NUMBER, u.ADDRESS, u.ROLE_ID
			FROM N_ONE_USERS u
			JOIN N_ONE_ROLES r on r.ID = u.ROLE_ID
			WHERE u.ID IN (" . implode(',', $ids) . ");"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return $this->getUsersFromResult($result);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function add(User|Entity $entity): int
	{
		$connection = $this->dbConnection->getConnection();
		$roleId = $entity->getRoleId();
		$name = mysqli_real_escape_string($connection, $entity->getName());
		$email = mysqli_real_escape_string($connection, $entity->getEmail());
		$password = password_hash(mysqli_real_escape_string($connection, $entity->getPass()), PASSWORD_DEFAULT);
		$phoneNumber = mysqli_real_escape_string($connection, $entity->getNumber());
		$address = mysqli_real_escape_string($connection, $entity->getAddress());

		$result = mysqli_query(
			$connection,
			"
			INSERT INTO N_ONE_USERS (ROLE_ID, NAME, EMAIL, PASSWORD, PHONE_NUMBER, ADDRESS) 
			VALUES (
				'$roleId',
				'$name',
				'$email',
				'$password',
				'$phoneNumber',
				'$address'
			);"
		);

		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return mysqli_insert_id($connection);
	}

	/**
	 * @throws DatabaseException
	 * @throws mysqli_sql_exception
	 */
	public function update(User|Entity $entity): bool
	{

		$connection = $this->dbConnection->getConnection();
		$userId = $entity->getId();
		$roleId = $entity->getRoleId();
		$name = mysqli_real_escape_string($connection, $entity->getName());
		$email = mysqli_real_escape_string($connection, $entity->getEmail());
		$password = $entity->getPass() !== 'null' ? password_hash(
			mysqli_real_escape_string($connection, $entity->getPass()),
			PASSWORD_DEFAULT
		) : '';
		$phoneNumber = mysqli_real_escape_string($connection, $entity->getNumber());
		$address = mysqli_real_escape_string($connection, $entity->getAddress());

		if (!empty($password))
		{
			$result = mysqli_query(
				$connection,
				"
			UPDATE N_ONE_USERS 
			SET 
				ROLE_ID = $roleId,
				NAME = '$name', 
				EMAIL = '$email', 
				PASSWORD = '$password', 
				PHONE_NUMBER = '$phoneNumber', 
				ADDRESS = '$address'
			WHERE ID = $userId ;"
			);
		}
		else
		{
			$result = mysqli_query(
				$connection,
				"
			UPDATE N_ONE_USERS 
			SET 
				ROLE_ID = $roleId,
				NAME = '$name', 
				EMAIL = '$email', 
				PHONE_NUMBER = '$phoneNumber', 
				ADDRESS = '$address'
			WHERE ID = $userId ;"
			);
		}
		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}

	public function getUserByToken(string $token): int
	{
		$connection = $this->dbConnection->getConnection();
		$result = mysqli_query(
			$connection,
			"
			SELECT ID FROM N_ONE_USERS 
			WHERE TOKEN = '$token' ;"
		);
		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}
		while ($row = mysqli_fetch_assoc($result))
		{
			$userId = $row['ID'];
		}

		return $userId;
	}

	public function addToken(int $userId, string $token): bool
	{
		$connection = $this->dbConnection->getConnection();
		$result = mysqli_query(
			$connection,
			"
			UPDATE N_ONE_USERS 
			SET 
				TOKEN = '$token'
			WHERE ID = $userId ;"
		);
		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}

	public function deleteToken(int $userId): bool
	{
		$connection = $this->dbConnection->getConnection();
		$result = mysqli_query(
			$connection,
			"
			UPDATE N_ONE_USERS 
			SET 
				TOKEN = null
			WHERE ID = $userId ;"
		);
		if (!$result)
		{
			throw new DatabaseException(mysqli_error($connection));
		}

		return true;
	}
}