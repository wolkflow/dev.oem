<?php
/**
 * Пример использования: очистка кеша по названию директории
 *
use Linemedia\Carsale\Migrations\PhpCacheClear;
$cache_dir = '/reviews/list';
$update = new PhpCacheClear('CP-6544', 'Инструмент для миграций по очистке кеша', [$cache_dir]);
$update->clear();
 * 
 */

namespace Linemedia\Carsale\Migrations;

class PhpCacheClear extends UpdateTool
{
    protected $cache_dir_list = [];


    /**
     * @param string    $task_id                Идентификатор задачи
     * @param string    $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param array     $cache_dir_list         Список директорий кеша
     */
    public function __construct($task_id, $migration_description, $cache_dir_list)
    {
        parent::__construct($task_id, $migration_description);
        $this->cache_dir_list = $cache_dir_list;
    }

    /**
     * очищение кеша через метод cleanDir класса CPHPCache
     *
     * @param bool|true $exit_on_success    Завершить выполнение скрипта после удачного добавления?
     * @param bool|true $exit_on_fail       Завершить выполнение скрипта, если произошла ошибка добавления?
     */
    public function clear($exit_on_success = true, $exit_on_fail = true)
    {
        $php_cache = new \CPHPCache();
        if (!$this->cache_dir_list || !is_array($this->cache_dir_list)) {
            $this->fail('Не передан cache_dir_list', $exit_on_fail);
        }
        // если количество директориев больше одного, то нет смысла прерывать цикл в случаи успешного или неуспешного очищения кеша
        if (count($this->cache_dir_list) > 1) {
            $exit_on_success = false;
            $exit_on_fail = false;
        }
        foreach ($this->cache_dir_list as $cache_dir) {
            if ($php_cache->cleanDir($cache_dir)) {
                $this->success(sprintf('Успешное очищение кеша в директории: %s', $cache_dir), $exit_on_success);
            }else {
                $this->fail(sprintf('Не удалось очистить кеш в директории: %s', $cache_dir), $exit_on_fail);
            }
        }
    }
}