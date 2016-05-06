<?php

namespace Wolk\Core\System;


class Shell
{
    /**
     * Прерывание скрипта, запущенного не из командной строки.
     */
    public static function shellonly()
    {
        // Проверка актуальна только для прода
        if (!self::isProduction()) {
            return;
        }
		
        if (!isset($_SERVER['SHELL'])) {
            throw new \Exception('Script must be run only from shell.');
        }
    }
	
	
    /**
     * IP сервера.
     */
    public static function getIP()
    {
        $command = "/sbin/ifconfig | grep 'inet ' | grep -v '127.0.0.1' | grep -v '192.168.' | awk {' print $2 '} | awk -F':' {' print $2 '}";
        $result  = trim(shell_exec($command));
		
		return $result;
    }
	
	
	/**
	 * Имя сервера.
	 */
	public static function getHost()
	{
		$command = "uname -n";
		$result  = trim(shell_exec($command));
		
		return $result;
	}
	
	
    /**
     * Соответствует ли указанный IP Production-серверу?
     */
    public static function isProduction($ip = '')
    {
        $ip = $ip ?: self::getIP();
		
        return ($ip == PRODUCTION_IP);
    }
}