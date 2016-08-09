<?php
namespace Linemedia\Carsale\Migrations;

use Linemedia\Carsale\Exception;
use Linemedia\Carsale\Filters\BaseFilter;

/**
 * Экспорт комбинированных данных в файл(ы)
 *
 * Class Export
 * @package Linemedia\Carsale\Migrations
 */
class Export extends UpdateTool
{
    const DIR_PATH      = 'upload/export/';
    const CSV_DELIMITER = ';';

    protected $file_name;
    protected $file_resource;
    protected $file_dir;


     /**
     * Конструктор объекта миграции
     *
     * @param string $task_id               ID задачи
     * @param string $migration_description Краткое описание выгрузки
     * @param string $dir_path              Путь от корня сайта к директории для выгрузки
     *
     * @throws MigrationException
     */
    public function __construct($task_id, $migration_description, $dir_path = self::DIR_PATH)
    {
        parent::__construct($task_id, $migration_description, $dir_path);

        // Проверим наличие корректного пути
        $dir_path = strval($dir_path);
        if (empty($dir_path)) {
            throw new MigrationException('Не указан путь к директории для выгрузки');
        }

        // Для каждой задачи - своя директория выгрузки
        $dir_path = rtrim($dir_path, '/') . '/' . $this->task_id;

        try {
            self::makeDir($dir_path);
        } catch (MigrationException $e) {
            $this->fail($e->getMessage());
        }

        $this->status('Выгружаем в директорию ' . $dir_path);
    }


    /**
     * Создаёт директорию И сохраняет ее
     *
     * @param string    $dir_name   Путь для создания
     * @param bool|true $recursive  Создать рекурсивно?
     *
     * @return bool
     *
     * @throws MigrationException
     */
    protected function makeDir($dir_name, $recursive = true)
    {
        // Если директория ещё не создана
        if (!is_dir($dir_name)) {

            // Создаём директорию
            if (!mkdir($dir_name, BX_DIR_PERMISSIONS, $recursive)) {
                throw new MigrationException(
                    'Не удалось создать директорию для выгрузки файлов'
                );
                return false;
            }
        }

        $this->file_dir = $dir_name;

        return true;
    }


    /**
     * Создаёт файл для записи
     *
     * @param string $file_name
     *
     * @return bool
     *
     * @throws MigrationException
     */
    public function createFile($file_name)
    {
        // Запомним имя файла
        $this->file_name = trim($file_name);
        if (empty($this->file_name)) {
            throw new MigrationException('Не задано имя файла');
            return false;
        }

        // Создадим файл
        $this->file_resource = fopen($this->file_dir . '/' . $file_name, 'w');

        // Получилось?
        if (!$this->file_resource) {
            throw new MigrationException(
                'Не удалось создать файл' . $this->file_name
            );
            return false;
        }

        return true;
    }


    /**
     * Записывает строку в формате CSV
     *
     * @param array $row_data
     *
     * @return int
     */
    public function putCsv(array $row_data)
    {
        return fputcsv($this->file_resource, $row_data, self::CSV_DELIMITER);
    }


    /**
     * Закрывает файл выгрузки
     *
     * @return bool
     *
     * @throws MigrationException
     */
    public function close()
    {
        if (!fclose($this->file_resource)) {
            throw new MigrationException(
                'Не удалось сохранить файл' . $this->file_name
            );
            return false;
        }

        return true;
    }


    /**
     * Закрывает файл и выводит эпилог миграции
     *
     * @param string $success_message
     */
    public function finish($success_message, $exit_if_success=true)
    {
        try {
            $this->close();
            $this->success("Файл {$this->file_name} сохранён");
        } catch (MigrationException $e) {
            $this->fail($e->getMessage());
        }

        $this->success($success_message, $exit_if_success);
    }


    /**
     * Обрабатывает все элементы указанного фильтра
     * посредством $callback-функции
     *
     * @param BaseFilter $filter
     * @param callable $callback
     */
    public function processFilter(BaseFilter $filter, callable $callback)
    {
        $this->status('Всего обнаружено записей по фильтру: ' . $filter->getCount());
        $db_result = $filter->execute();

        try {
            while ($element = $db_result->Fetch()) {
                $callback($element, $this);
            }
        } catch (Exception $e) {
            $this->fail('Ошибка обработки данных: ' . $e->getMessage());
        }
    }
}
