<?php

namespace N_ONE\src\Model;

class User
{
	private string $role;
	private string $name;
	private string $email;
	private string $pass;
	private string $number;
	private string $address;

	public function __construct(
		string $role,
		string $name,
		string $email,
		string $pass,
		string $number,
		string $address
	)
	{
		$this->role = $role;
		$this->name = $name;
		$this->email = $email;
		$this->pass = $pass;
		$this->number = $number;
		$this->address = $address;
	}

	public function getRole(): string
	{
		return $this->role;
	}

	public function setRole(string $role): void
	{
		$this->role = $role;
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