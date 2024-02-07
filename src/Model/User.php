<?php

namespace N_ONE\App\Model;

class User extends Entity
{
	public function __construct(
		private int    $roleId,
		private string $name,
		private string $email,
		private string $pass,
		private string $number,
		private string $address,
	){}

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