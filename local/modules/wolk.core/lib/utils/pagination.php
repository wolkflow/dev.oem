<?php

namespace Wolk\Core\Utils;

use Bitrix\Main\HttpRequest;

/**
 * Class Pagination
 */
class Pagination
{
    /**
     * @var int
     */
    private $offset;
	
    /**
     * @var int
     */
    private $page_size;

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $page_count;

    /**
     * @var int
     */
    private $page_num;

    /**
     * количество элементов на странице по-умолчанию
     */
    const DEFAULT_PAGE_SIZE = 20;
    /**
     * номер страницы по-умолчанию
     */
    const DEFAULT_PAGE_NUM = 1;
    /**
     * название параметра запроса по-умолчанию для количества элементов на странице
     */
    const DEFAULT_PAGE_SIZE_KEY = 'SIZEN_1';
    /**
     * название параметра запроса по-умолчанию для номера страницы
     */
    const DEFAULT_PAGE_NUM_KEY = 'PAGEN_1';

	
	
    /**
     * @param $total
     * @param string $page_size_key
     * @param string $page_num_key
     */
    public function __construct($total, $page_size_key = self::DEFAULT_PAGE_SIZE_KEY, $page_num_key = self::DEFAULT_PAGE_NUM_KEY)
    {
        $request = $this->getRequest();
        $this->page_size = (int) $request->get($page_size_key) ?: self::DEFAULT_PAGE_SIZE;
        $this->page_num = (int) $request->get($page_num_key) ?: self::DEFAULT_PAGE_NUM;

        $this->total = $total;
        $this->page_count = ceil($this->total / $this->page_size);
        $this->offset = $this->page_size * ($this->page_num - 1);
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->page_size;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return $this->page_count;
    }

    /**
     * @return int
     */
    public function getPageNum()
    {
        return $this->page_num;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return HttpRequest
     */
    private function getRequest()
    {
        /**
         * @var $application \Bitrix\Main\HttpApplication
         */
        global $application;
        return $application->getContext()->getRequest();
    }
}
