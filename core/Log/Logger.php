<?php

namespace N_ONE\Core\Log;

use Exception;
use N_ONE\Core\Configurator\Configurator;

class Logger
{
	private static string $rootLogDir;
	public static function setRootLogDir(string $rootLogDir):void
	{
		self::$rootLogDir = $rootLogDir;
	}
	// /**
	//  * @throws Exception
	//  */
	// public function __construct(
	// 	private string $logPath
	// )
	// {
	// 	if (empty(self::$rootLogDir))
	// 	{
	// 		throw new Exception('Must set root log dir');
	// 	}
	//
	// 	$path = $this->getValidPath($this->logPath);
	// 	$this->logPath = self::$rootLogDir . '/' . $path;
	//
	// 	if (!file_exists($this->logPath))
	// 	{
	// 		$pathInArray = explode('/', $path);
	// 		$currentPathString = self::$rootLogDir . '/';
	// 		foreach ($pathInArray as $key => $value)
	// 		{
	// 			$currentPathString .= $value . '/';
	// 			if($key === count($pathInArray) - 1)
	// 			{
	// 				continue;
	// 			}
	//
	// 			if (!mkdir($currentPathString) && !is_dir($currentPathString))
	// 			{
	// 				throw new \RuntimeException(sprintf('Directory "%s" was not created', $currentPathString));
	// 			}
	// 		}
	// 	}
	// }
	//
	// private function getValidPath(string $path): string
	// {
	// 	$path = str_replace('\\', '/', $path);
	//
	// 	return trim($path, " /");
	// }

	public static function log(string $level, string $message, string $callPlace): void
	{
		$logFile = self::$rootLogDir . date('Y-m-d') . '.log';
		$time = date('H:i:s');
		$logEntry = "[$time][$level][$callPlace] $message" . PHP_EOL;

		file_put_contents($logFile, $logEntry, FILE_APPEND);
	}

	public static function info(string $message, string $callPlace): void
	{
		self::log('info', $message, $callPlace);
	}

	public static function notice(string $message, string $callPlace): void
	{
		self::log('notice', $message, $callPlace);
	}

	public static function warning(string $message, string $callPlace): void
	{
		self::log('warning', $message, $callPlace);
	}

	public static function error(string $message, string $callPlace): void
	{
		self::log('error', $message, $callPlace);
	}
}