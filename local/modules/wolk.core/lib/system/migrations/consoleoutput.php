<?php
/**
 * @author Smotrov Dmitriy <smotrov@worksolutions.ru>
 */

namespace Linemedia\Carsale\Migrations;

use Linemedia\Carsale\Entity\MigrationLogsTable;

/**
 * Class Output
 * @package Linemedia\Carsale\Migrations
 */
class ConsoleOutput
{
    /**
     * @param string $text
     */
    public function writeLine($text = "")
    {
        $this->write($text . PHP_EOL);
    }

    /**
     * @param string $text
     */
    public function write($text)
    {
        $text = (string) $text;

        //Определяем номер задачи по которой осуществляется миграция
        if (!isset($this->task)) {
            preg_match("/([A-Z]*-[0-9]*)/", $text, $m);
            $this->task = $m[0];
        }

        /**
         * Проверяем наличие таблицы
         * и при успешной проверке записываем в нее лог миграции
         * для исключения записи в лог ошибок,
         * не касающихся выполняемой миграции
         */
        if (\Bitrix\Main\Application::getConnection()->isTableExists(MigrationLogsTable::getTableName())) {
            MigrationLogsTable::add([
                'TASK_ID' => $this->task,
                'TIME'    => new \Bitrix\Main\Type\DateTime(),
                'LOG'     => $text,
            ]);
        }
        print($text);
    }

    public function clearBuffer()
    {
        while (@ob_end_flush()) {}
    }

    /**
     * @param $text
     */
    public function overwrite($text)
    {
        $this->write("\033[1A" . $text);
    }

    /**
     * @return bool
     */
    public function isConsole()
    {
        return php_sapi_name() == "cli";
    }


    /**
     * Выводит статистические данные о прошедшей миграции
     *
     * @param $start_time Время запуска миграции
     */
    public function writeSummary($start_time)
    {
        $time_spent = time() - $start_time;
        if ($time_spent < 60) {
            $msg_time_spent = sprintf("%s сек", $time_spent);
        } else {
            $msg_time_spent = sprintf("%s мин %s сек", floor($time_spent / 60), $time_spent % 60);
        }
        $this->writeLine("Время выполнения: " . $msg_time_spent);
        $this->writeLine(sprintf(
            "Пиковое использование памяти: %s мб",
            round(memory_get_peak_usage() / 1024 / 1024, 2)
        ));
    }
}
