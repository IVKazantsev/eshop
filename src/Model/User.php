<?php

namespace N_ONE\App\Model;

use N_ONE\App\Model\Service\ValidationService;

class User extends Entity
{
	public function __construct(
		protected int|null  $id,
		private int|null    $roleId,
		private string|null $name,
		private string|null $email,
		private string|null $pass,
		private string|null $number,
		private string|null $address,
	)
	{
	}

	public static function fromFields(array $fields): static
	{
		$new = new static(
			$fields['id'],
			$fields['roleId'],
			$fields['name'],
			$fields['email'],
			$fields['pass'],
			$fields['number'],
			$fields['address'],
		);

		return $new;
	}

	public function getExcludedFields(): array
	{
		return ['pass'];
	}

	public function getClassname(): string
	{
		$array = explode('\\', __CLASS__);

		return strtolower(end($array));
	}

	public function getField(string $fieldName): string
	{
		return ValidationService::safe($this->$fieldName);
	}

	public function getRoleId(): int
	{
		return $this->roleId;
	}

	public function setRoleId(int $roleId): void
	{
		$this->roleId = $roleId;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getPass(): string
	{
		return $this->pass;
	}

	public function setPass(string $pass): void
	{
		$this->pass = $pass;
	}

	public function getNumber(): string
	{
		return $this->number;
	}

	public function setNumber(string $number): void
	{
		$this->number = $number;
	}

	public function getAddress(): string
	{
		return $this->address;
	}

	public function setAddress(string $address): void
	{
		$this->address = $address;
	}

}