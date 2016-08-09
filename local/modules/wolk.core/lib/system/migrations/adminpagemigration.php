<?php

namespace Linemedia\Carsale\Migrations;

/**
 * Класс для миграций по созданию админских страниц
 *
 * $admin_page = new AdminPageMigration(
 *  'CP-6308',
 *  'Создание файла со страницей поиск по группе корпоративные клиенты и дилеры',
 *  'dealer_and_corporate_client_search.php'
 * );
 * $admin_page->add();
 */
class AdminPageMigration extends UpdateTool
{
    /**
     * Приставка к названию файла
     *
     * @var string
     */
    private $file_prefix = '';

    /**
     * Имя файла
     *
     * @var string
     */
    private $file_name = '';

    /**
     * Путь к файлу от корня сайта
     *
     * @var string
     */
    private $dir_path = '';

    /**
     * @param string $task_id
     * @param string $migration_description
     * @param string $file_name - имя файла без пути
     * @param string $dir_path - путь от корня сайта
     */
    public function __construct($task_id, $migration_description, $file_name, $dir_path = '/bitrix/admin/')
    {
        parent::__construct($task_id, $migration_description);

        $this->file_name = $file_name;
        $this->dir_path = $dir_path;
    }

    /**
     * Добавить админскую страницу
     *
     * @param bool|true $exit_on_success
     * @param bool|true $exit_if_exists
     * @param bool|true $exit_on_fail
     * @param string $file_content - содержимое файла
     * @return bool
     */
    public function add($exit_on_success = true, $exit_if_exists = true, $exit_on_fail= true, $file_content = '')
    {
        if ($this->isExist()) {
            $this->cancel(sprintf('Файл %s уже существует', $this->getFileName()), $exit_if_exists);
            return false;
        }

        if (!$file_content) {
            $file_content = sprintf("<?php\r\nrequire_once(\$_SERVER['DOCUMENT_ROOT'] . '/local/modules/linemedia.carsale/admin/%s');", $this->getFileName());
        }

        $result = file_put_contents($this->getFilePath(), $file_content);

        if (!$result) {
            $this->fail(sprintf('Файл %s не создан', $this->getFileName()), $exit_on_fail);
            return false;
        }

        $this->success(sprintf('Файл %s создан успешно', $this->getFileName()), $exit_on_success);

        return true;
    }

    /**
     * Файл существует?
     *
     * @return bool
     */
    public function isExist()
    {
        return file_exists($this->getFilePath());
    }

    /**
     * Получить директорию файла
     *
     * @return string
     */
    public function getDir()
    {
        return  __DIR__ . '/../../../../..' . $this->getDirPath();
    }


    /**
     * Получить префикс файла
     *
     * @return string
     */
    public function getFilePrefix()
    {
        return $this->file_prefix;
    }

    /**
     * Получить название файла
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Получить путь к файлу
     *
     * @return string
     */
    public function getFilePath() {
        return $this->getDir() . $this->getFilePrefix() . $this->getFileName();
    }

    /**
     * Установить название файла
     *
     * @param string $file_name
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * Получить путь к файлу от корня сайта
     *
     * @return string
     */
    public function getDirPath()
    {
        return $this->dir_path;
    }

    /**
     * Установить префикс к названию файла
     * 
     * @param string $file_prefix
     */
    public function setFilePrefix($file_prefix)
    {
        $this->file_prefix = $file_prefix;
    }

}
