<?php

namespace Wolk\OEM\API;

use \Exception;
use Wolk\OEM\API\User;

/**
 * API.
 *
 *
 * Запрос вида:
 * $request = ['login' => LOGIN, 'method' => METHOD, 'data' => [...], 'sign' => md5(json(data) . KEY)];
 */
class Engine
{   
    const ERROR_INPUT_CODE  = 1;
    const ERROR_LOGIN_CODE  = 2;
    const ERROR_SIGN_CODE   = 3;
    const ERROR_METHOD_CODE = 4;
    
    protected $login  = null;
    protected $method = null;
    protected $data   = array();
    protected $sign   = null;
    protected $user   = null;
    
    
    public function __construct($request)
    {
        $request = (array) $request;
        
        // if (json_last_error() != JSON_ERROR_NONE) {
        //     throw new Exception('Не верный формат входных данных', self::ERROR_INPUT_CODE);
        // }
        
        $this->login  = (string) $request['login'];
        $this->method = (string) $request['method'];
        $this->data   = (array)  $request['data'];
        $this->sign   = (string) $request['sign'];
    }
    
    
    public function getLogin()
    {
        return $this->login;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getSign()
    {
        return $this->sign;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    
    /**
     * Выполнение API запроса.
     */
    public function exec()
    {
        $login = $this->getLogin();
        $sign  = $this->getSign();
        
        if (empty($login)) {
            throw new Exception('Не указан логин', self::ERROR_INPUT_CODE);
        }
        
        if (empty($sign)) {
            throw new Exception('Не указана подпись', self::ERROR_INPUT_CODE);
        }
        
        // Пользователь
        $user = User::getByLogin($login);
        
        if (!is_object($user)) {
            throw new Exception('Пользователь не найден', self::ERROR_LOGIN_CODE);
        }
        $this->user = $user;
        
        if (!$this->checksign()) {
            throw new Exception('Неверная подпись', self::ERROR_SIGN_CODE);
        }
        
        $methods = $this->getMethods();
        
        if (!array_key_exists($this->getMethod(), $methods)) {
            throw new Exception('Неверно указан метод', self::ERROR_METHOD_CODE);
        }
        
        $method = $methods[$this->getMethod()];
        
        $result = call_user_func($method, $this->getData());
        
        return $result;
    }
    
    
    /**
     * Проверка подписи запроса.
     */
    public function checksign()
    {
        $user = $this->getUser();
        
        $sign = md5($this->getMethod() . json_encode($this->getData()) . $user->getKey());
        
        return ($sign == $this->getSign());
    }
    
    
    /**
     * Сбор методов API.
     */
    public function getMethods()
    {
        $methods = array(
            'test' => array(self, 'test'),
        );
        
        return $methods;
    }
    
    
    /**
     * Ответ.
     */
    public static function response($status, $data = array())
    {
        header('Content-Type: application/json');
        
        $response = json_encode(array(
            'status' => (bool) $status,
            'data'   => $data,
        ));
        
        echo $response;
        
        exit();
    }
    
    
    /**
     * Тестовый метод для проверки API.
     */
    public function test()
    {
        $result = 'Test is OK at '.date('d.m.Y H:i');
        
        return $result;
    }
} 