<?php

namespace Wolk\Core\Utils;

class Exception extends \Exception {};

class Browser
{
    protected $curl;
    protected $cookiefile;
    protected $base_url;

    protected $last_request;
	
	
	
    /**
     * В конструкторе создаётся объект cURL и устанавливаются базовые настройки.
     */
    public function __construct() {
        $this->cookiefile = tempnam($_SERVER['DOCUMENT_ROOT'] . '/bitrix/tmp/', "COO");

        $this->curl = curl_init();
        $this->resetSettings();
    }


    /**
     * Выполнение GET запроса.
     */
    public function get($url, $data = array())
	{
        $url = $this->base_url . $url;
        if ($data) {
            $url .= '?' . \http_build_query($data);
        }
		
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, false);
        try {
            return $this->request();
        } catch (\Linemedia\Carsale\Exception $e) {//_d($e);
            throw new Exception("Error making GET request: " . $e->GetMessage());
        }
    }


    /**
     * Выполнение POST запроса.
     */
    public function post($url, $data = array(), $encode = true)
	{
        if ($encode) {
            $data = http_build_query($data);
        }

        curl_setopt($this->curl, CURLOPT_URL, $this->base_url . $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);

        try {
            return $this->request();
        } catch (Exception $e) {
            throw new Exception("Error making POST request: " . $e->GetMessage());
        }
    }


    /**
     * Сброс настроек.
     */
    public function resetSettings()
	{
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 4,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
            CURLOPT_COOKIEFILE => $this->cookiefile,
            CURLOPT_COOKIEJAR => $this->cookiefile,
            CURLINFO_HEADER_OUT => true,
        );

        curl_setopt_array($this->curl, $options);
    }


    /**
     * Установка referer.
     */
    public function setReferer($ref)
	{
        curl_setopt($this->curl, CURLOPT_REFERER, $ref);
    }

	
    /**
     * Установка заголовок
     */
    public function setHeaders($headers = [])
	{
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
    }

	
    /**
     * Установка useragent.
     */
    public function setAgent($agent)
	{
        curl_setopt($this->curl, CURLOPT_USERAGENT, $agent);
    }

	
    /**
     * Установка TIMEOUT.
     */
    public function setTimeOut($timeout)
	{
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $timeout);
    }


    /**
     * Установка базового пути к запросам (обычно домена с http://),
     * который подставляется перед каждым вызовом.
     */
    public function setBaseUrl($url)
	{
        $this->base_url = $url;
    }

	
    public function getBaseUrl()
	{
        return $this->base_url;
    }


    /**
     * Непосредственное выполнение запроса.
     */
    protected function request()
	{
        try {
            $response = curl_exec($this->curl);
        } catch (Exception $e) {
            throw $e;
        }

        $this->last_request = array(
            'info' => curl_getinfo($this->curl),
            'response' => $response,
        );

        if ($response === false) {
            throw new Exception(curl_error($this->curl) . ' (#' . curl_errno($this->curl) . ')');
        }
        if ($this->last_request['info']['http_code'] == 403) {
			throw new Exception('Access denied');
		}
        if ($this->last_request['info']['http_code'] == 404) {
            throw new Exception('Page not found');
		}

        if (strpos($this->last_request['info']['content_type'], '1251') !== false) {
            $response = iconv('Windows-1251', 'UTF-8', $response);
        }
		
        return $response;
    }


    public function getLastRequest()
	{
        return $this->last_request;
    }


    /**
     * Закрытие соединения в деструкторе.
     */
    public function __destruct()
	{
        if ($this->curl) {
            curl_close($this->curl);
		}
        unlink($this->cookiefile);
    }
}
