<?php

/*
 * Пример использования
 *
$migration = new \Linemedia\Carsale\Migrations\UpdateTool(
    'CP-1511',
    'Очень важная и сложная миграция'
);
// Проверяем, было ли уже ранее выполнено действие из текущей миграции
if ($already_done) {
    $migration->cancel('Очень важное дело уже было сделано ранее!');
}
// Что-то делаем, можно вывести промежуточный результат
$migration->status('Выяснили, что сейчас надо будет модифицировать 100500 элементов.');
// Что-то делаем и оцениваем результат своих действий
if ($ok) {
    // Если передать вторым аргументом true, то на этом скрипт завершится
    $migration->success('Очень сложная миграция удалась.', true);
}
$migration->fail('Миссия провалена');
 * 
 */

namespace Wolk\Core\System\Migrations;

/**
 * Базовый класс-обёртка для создания файлов update_tools
 *
 * @author Александр Акмурзин a.akmurzin@carprice.ru
 */
class UpdateTool extends Migration
{
    
    // Конец строки
    const EOL = PHP_EOL;

    const DATE_FORMAT = '[Y-m-d H:i:s] ';

    // Дельта для ограничения по памяти
    const MEMORY_LIMIT_DELTA = 50*1024*1024;
    
    /** @var string Идентификатор задачи */
    public $task_id;

    /** @var string Описание миграции */
    public $migration_description;

    /** @var int Время начала миграции  */
    public $start_time;

    /** @var ConsoleOutput Объект для вывода информации */
    public $output;
    
    /** @var array Массив данных основного элемента, с которым будем работать */
    public $fields;
    
    /** @var int Идентификатор основного элемента */
    public $id;
    
    
    /**
     * Инициализирует объект, задаёт поля основного элемента,
     * выводит границу начала задачи
     *
     * @param string    $task_id                Идентификатор задачи.
     * @param string    $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param mixed     $data                   Дополнительные инициализационные данные.
     */
    public function __construct($task_id, $migration_description, $data = null)
    {
        parent::__construct($task_id);
        $this->task_id = trim($task_id);
        $this->setDescription($migration_description);
        if (!empty($data)) {
            $this->setFields($data);
        }
        $this->start_time = time();
        $this->output = new ConsoleOutput();
        $this->output->writeLine(
            '==== ' . $this->task_id . ' ====' . PHP_EOL .
            date(self::DATE_FORMAT, $this->start_time) . $migration_description
        );
    }


    /**
     * @param $migration_description Описание сути миграции
     */
    public function setDescription($migration_description)
    {
        $this->migration_description = trim($migration_description);
        if (!empty($this->migration_description)) {
            $this->name = $this->task_id . ': ' . $this->migration_description;
        }
    }
    
    
    /**
     * Задаёт набор полей основного элемента
     *
     * @param array $arFields
     */
    public function setFields($arFields)
    {
        if (empty($arFields)) {
            $this->fail("Задан пустой набор полей");
        }
        $this->fields = $arFields;
        $id = intval($arFields["ID"]);
        if ($id) {
            $this->id = $id;
        }
    }


    /**
     * Выводит статистику выполнения миграции
     */
    public function writeSummary()
    {
        $this->output->writeSummary($this->start_time);
    }
    
    
    /**
     * Выводит статистику задачи и прерывает выполнение скрипта
     */
    public function includeFooter()
    {
        $this->writeSummary();
        exit;
    }
    
    
    /**
     * Выводит текст и переводит строку
     *
     * @param string $message - текст сообщения
     * @param bool $exit - прерывать выполнение скрипта после вывода сообщения?
     */
    protected function showMessage($message, $exit = false)
    {
        $this->output->writeLine(date(self::DATE_FORMAT) . $message . PHP_EOL);
        if ($exit) {
            $this->includeFooter();
        }
    }
    
    
    /**
     * Выводит информацию о ходе миграции
     *
     * @param string $message
     */
    public function status($message)
    {
        $this->showMessage($message, false);
    }


    /**
     * Выводит текст об успешном завершении операции
     * Например SUCCESS: все данные обновлены
     *
     * @param string $message - текст сообщения
     * @param bool $exit - прерывать выполнение скрипта после вывода сообщения?
     */
    public function success($message, $exit = false)
    {
        $this->showMessage("SUCCESS (операция выполнена): $message", $exit);
    }
    
    
    /**
     * Выводит сообщение о неудачном выполнении операции
     * Например: FAIL: не удалось обновить объект ID=XXXX
     *
     * @param string $message - текст сообщение об ошибке
     * @param bool $exit - прерывать выполнение скрипта после вывода ошибки?
     */
    public function fail($message, $exit = true)
    {
        $this->showMessage("FAIL (ошибка операции): $message", $exit);
    }

    /**
     * Для ручного запуска
     */
    public function manualStarting()
    {
        global $argv;

        if (in_array('--force', $argv)) {
            return;
        }
        $this->fail('Только для ручного запуска с аргументом --force.');
    }
    
    /**
     * Выводит сообщение об отмене операции
     * Например: CANCEL: свойство с кодом PROP_CODE уже существует
     *
     * @param string $message - текст сообщение об отмене
     * @param bool $exit - прерывать выполнение скрипта после вывода ошибки?
     */
    public function cancel($message, $exit = true)
    {
        $this->showMessage("CANCEL (отмена операции): $message", $exit);
    }


    /**
     * Завершает выполнение миграции, если она запущена не на production-сервере
     * @param bool $can_be_run_forced может ли запущена миграция не на production-сервере принудительно
     */
    public function setProductionOnly($can_be_run_forced = false)
    {
        global $argv;
		
        if ($can_be_run_forced && in_array('--force', $argv)) {
            return;
        }

        if ($can_be_run_forced && isset($_REQUEST['force']) && $_REQUEST['force']) {
            return;
        }

        if (!defined('IS_PRODUCTION') || !IS_PRODUCTION) {
            $this->cancel('Только для запуска на Production-сервере');
        }
    }


    /**
     * Проверка на НЕ превышение лимита по памяти
     *
     * @param string $message
     * @param int $memory_limit = 0
     * @return bool true - если ок, в противном случае прерывание миграции
     *
     * @throws \Linemedia\Carsale\System\Exception
     */
    function checkMemoryLimit($message, $memory_limit = 0)
    {
        $message_type_delta = false;
        if (!$memory_limit) {
            $memory_limit = \Linemedia\Carsale\System\Environment::getMemoryLimit();

            // Если лимит сервера больше дельты - то мы вычтем из него еще и дельту
            if ($memory_limit > self::MEMORY_LIMIT_DELTA) {
                $memory_limit -= self::MEMORY_LIMIT_DELTA;
                $message_type_delta = true;
            }
        }

        if (memory_get_peak_usage() > $memory_limit) {
            if ($message_type_delta) {
                $message = sprintf(
                    "ВНИМАНИЕ: Превышен лимит по памяти %s мб (с учетом вычета дельты %s мб). Повторите запуск миграции!\n%s",
                    round($memory_limit / 1024 / 1024, 2),
                    round(self::MEMORY_LIMIT_DELTA / 1024 / 1024, 2),
                    $message
                );
            }
            else {
                $message = sprintf(
                    "ВНИМАНИЕ: Превышен лимит по памяти %s мб. Повторите запуск миграции!\n%s",
                    round($memory_limit / 1024 / 1024, 2),
                    $message
                );
            }

            $this->cancel($message, true);
        }
        return true;
    }


    /**
     * Копирует файл из установочной директории
     * модуля linemedia.carsale в админку Битрикса
     *
     * @param string $file_name
     * @param bool|true $exit_on_success
     * @param bool|true $exit_on_fail
     *
     * @return bool Прошло ли копирование успешно?
     */
    public function copy2bitrix($file_name, $exit_on_success = true, $exit_on_fail = true)
    {
        if (!CopyDirFiles(
            $_SERVER['DOCUMENT_ROOT'] . '/local/modules/linemedia.carsale/install/admin/' . $file_name,
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $file_name,
            false
        )) {
            $this->fail("Ошибка переноса файла $file_name в админку", $exit_on_fail);
            return false;
        }
        $this->success("Файл $file_name успешно перенесён в админку", $exit_on_success);
        return true;
    }
}
