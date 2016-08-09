<?php

/*
 * Пример добавления новой таблицы
 *
$entityTable = new Linemedia\Carsale\Migrations\Entity(
    'CP-3646',
    'Добавление самой нужной таблицы'
);
$entityTable->add(new Linemedia\Carsale\Entity\NewTable());        // Если больше ничего делать не надо
$entityTable->add(new Linemedia\Carsale\Entity\NewTable(), false); // Если нужно сделать что-то ещё, например, заполнить таблицу данными
 * 
 */

/*
 * Пример добавления нового столбца в таблицу
 *
use Linemedia\Carsale\Entity\Existing\Object as Obj;
$entityTable = new Linemedia\Carsale\Migrations\Entity(
    'CP-3646',
    'Добавление и заполнение хитрого поля в имеющуюся таблицу',
    new ExistingTable()
);
 * ------------------- Наименование ------------- Тип ------ exit? - DEFAULT - NULL - COMMENT 
$entityTable->addField(Obj::FIELD_NEW_FILED_NAME, 'int(11)', false,  100500,   false, 'Новое поле');
$object = Obj($id);
$object->setNewField($clever_value);
$entityTable->success('Всё прошло как надо', true);

 *
 * Пример добавление нового индекса в таблицу
 *
 $entityTable = new Entity(
    'CP-XXXX',
    'Добавление индексов',
    new AuctionTable()
 );

 $entityTable->createIndex([BaseAuction::FIELD_USER_ID]);
 */
 
namespace Linemedia\Carsale\Migrations;

use Bitrix\Main\Application;
use Bitrix\Main\Data\ConnectionPool;
use Bitrix\Main\Entity\DataManager;
use Linemedia\Carsale\Exception;

/**
 * Класс для миграций по созданию таблиц сущностей и добавлению в них новых полей
 *
 * @author Александр Акмурзин a.akmurzin@carprice.ru
 */
class Entity extends UpdateTool
{
    
    /** @var string Название таблицы */
    protected $table_name;

    /** @var string Имя соединения с БД */
    protected $connection_name;
    
    /** @var \Bitrix\Main\DB\Connection Объект соединения с БД */
    protected $connection;
    
    
    /**
     * Конструктор объекта миграции
     *
     * @param string        $task_id                Идентификатор задачи.
     * @param string        $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param DataManager   $entity_table           Объект класса таблицы
     * @param string        $connection_name        Имя соединения с БД
     *
     * @link http://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=4803&LESSON_PATH=3913.5062.5748.4803
     */
    public function __construct(
        $task_id,
        $migration_description,
        $entity_table = null,
        $connection_name = ConnectionPool::DEFAULT_CONNECTION_NAME
    ) {
        parent::__construct($task_id, $migration_description);

        $this->connection_name = $connection_name;
        
        if (!is_null($entity_table) && $entity_table instanceof DataManager) {
            $this->getTableName($entity_table);
        }
    }
    
    
    /**
     * Добавляет таблицу указанного класса в БД
     *
     * @param DataManager   $entity_table       Объект класса таблицы
     * @param bool          $exit_on_success    Завершить выполнение скрипта, если таблица успешно добавлена?
     * @param bool          $exit_if_exists     Завершить выполнение скрипта, если таблица уже существует?
     *
     * @return bool Добавление прошло успешно?
     */
    public function add($entity_table, $exit_on_success = true, $exit_if_exists = true)
    {
        if ($this->isExist($entity_table)) {
            $this->cancel("Таблица `{$this->getTableName()}` уже существует.", $exit_if_exists);
            return false;
        } else {
            $dump = $entity_table->getEntity()->compileDbTableStructureDump();
            try {
                $this->getConnection()->query(array_pop($dump));
                $this->success("Таблица `{$this->getTableName()}` успешно создана", $exit_on_success);
                return true;
            } catch (\Exception $e) {
                $this->fail("Ошибка создания таблицы `{$this->getTableName()}`: {$e->getMessage()}");
                return false;
            }
        }
    }
    
    
    /**
     * Добавляет новое поле в таблицу
     *
     * @param string $name              Наименование нового поля
     * @param string $type              Тип поля, например int(11)
     * @param bool   $exit_on_success   Завершить выполнение скрипта после успешного добавления поля?
     * @param string $default           Значение поля по умолчанию
     * @param bool   $is_null           Может ли поле быть NULL?
     * @param string $comment           Комментарий
     * @param string $other             Любые другие дополняющие запрос конструкции, добавятся в конце
     * @param bool   $exit_on_fail      Завершить выполнение скрипта в случае ошибки?
     */
    public function addField($name, $type, $exit_on_success = false, $default = null, $is_null = true, $comment = '', $other = '', $exit_on_fail = false)
    {
        if (!$this->isExist()) {
            $this->cancel("Таблица `{$this->getTableName()}` не существует");
        }

        // Далее - общий стандартный синтаксис
        $query = 'ADD COLUMN ' . strval($name) . ' ' . strval($type);
        if (!$is_null) {
            $query .= ' NOT';
        }
        $query .= ' NULL';
		
        // Если поле NOT NULL, обязательно должно быть значение по умолчанию.
        if (!$is_null && is_null($default)) {
            $this->cancel("Для поля `{$name}` не указано значение по умолчанию");
        }

        if (!is_null($default)) {
            $query .= " DEFAULT '" . strval($default) . "'";
        }
        if (!empty($comment)) {
            $query .= " COMMENT '" . strval($comment) . "'";
        }

        // Всякие $other потенциально могут что-то сломать
        if (!empty($other) && !IS_PRODUCTION) {
            $query .= ' ' . strval($other);
            $this->status($other . ' не будет выполнено на проде');
        }

        // Выполняем запрос
        $this->query(
            $query,
            "Поле $name успешно добавлено в таблицу " . $this->getTableName(),
            'Ошибка',
            $exit_on_success,
            $exit_on_fail
        );
    }

    
    /**
     * Добавляет новый индекс для указанных полей
     *
     * @param array $fields          Массив полей
     * @param bool $exit_on_success  Завершить выполнение скрипта после успешного добавления поля?
     * @param bool $exit_on_fail     Завершить выполнение скрипта в случае ошибки?
     * @param bool $exit_on_exists   Завершить выполнение скрипта в случае существования индекса?
     */
    public function createIndex($fields, $exit_on_success = false, $exit_on_fail = false, $exit_on_exists = false)
    {
        $fields_string = join(', ', $fields);

        if (!$this->isExist()) {
            $this->cancel("Таблица `{$this->getTableName()}` не существует");
        }

        if (empty($fields)) {
            $this->cancel("Передан пустой массив для создания индексов");
        }

        if ($this->isIndexExist($fields)) {
            $this->cancel("Индекс для полей [{$fields_string}] уже добавлен в таблицу {$this->getTableName()}", $exit_on_exists);
            return;
        }

        $index_name = $this->createIndexName($fields);

        try {
            $this->getConnection()->createIndex($this->getTableName(), $index_name, $fields);
            $this->success("Индекс для полей [$fields_string] успешно добавлен в таблицу {$this->getTableName()}", $exit_on_success);
        } catch (\Exception $e) {
            $this->fail("При попытке добавить индекс для полей [$fields_string] произошла ошибка: " . $e->getMessage(), $exit_on_fail);
        }
    }

    
    /**
     * Существует ли в БД индекс по заданным полям
     *
     * @param $fields  Массив полей
     *
     * @return mixed
     */
    public function isIndexExist($fields)
    {
        if (empty($fields)) {
            $this->cancel('Не заданы поля для определения существования индекса');
        }
        if (!$this->isExist()) {
            $this->cancel("Таблица `{$this->getTableName()}` не существует");
        }
        return $this->getConnection()->isIndexExists($this->getTableName(), $fields);
    }

    /**
     * Генерация имени индекса для указанных полей
     *
     * @param $fields ...Массив полей
     *
     * @return string
     */
    public function createIndexName($fields)
    {
        if (empty($fields)) {
            $this->cancel('Не заданы поля для определения имени индекса');
        }
        return 'IX_' . join('_', $fields);
    }

    /**
     * Устанавливает имя таблицы
     *
     * @param string $table_name
     */
    public function setTableName($table_name)
    {
        $this->table_name = $table_name;
    }
    
    /**
     * Возвращает имя таблицы
     *
     * @param DataManager $entity_table Объект класса таблицы
     *
     * @return string
     *
     * @throws Exception
     */
    public function getTableName($entity_table = null)
    {
        if (!is_null($entity_table)) {
            if ($entity_table instanceof DataManager) {
                $this->table_name = $entity_table->getTableName();
            } else {
                throw new Exception('Должен быть передан объект типа Bitrix\Main\Entity\DataManager');
            }
        }
        return $this->table_name;
    }
    
    
    /**
     * Проверяет, существует ли таблица в БД
     *
     * @param DataManager $entity_table
     *
     * @return bool
     *
     * @throws Exception
     */
    public function isExist($entity_table = null)
    {
        $table_name = $this->getTableName($entity_table);
        if (empty($table_name)) {
            throw new Exception('Невозможно определить имя таблицы');
        }
        return $this->getConnection()->isTableExists($this->getTableName());
    }
    
    
    /**
     * Возвращает текущее соединение с БД
     *
     * @return \Bitrix\Main\DB\Connection
     */
    public function getConnection()
    {
        if (!isset($this->connection)) {
            $this->connection = Application::getConnection($this->connection_name);
        }
        return $this->connection;
    }


    /**
     * Изменить тип поля
     *
     * @param string $field название поля
     * @param string $type тип поля
     * @param bool $exit_on_success Завершить выполнение скрипта после успешного добавления поля?
     * @param bool $exit_on_fail Завершить выполнение скрипта в случае ошибки?
     * @throws Exception
     */
    public function updateFieldType($field, $type, $exit_on_success = false, $exit_on_fail = false)
    {
        if (!$this->isExist()) {
            $this->cancel(sprintf("Таблица %s не существует", $this->getTableName()));
        }

        if (empty($field)) {
            $this->cancel("Не указано поле");
        }

        // TODO: Cделать один общий запрос.
        $query = sprintf(
            "CHANGE COLUMN %s %s %s",
            $field,
            $field,
            $type
        );
        
        $this->query($query, sprintf('Поле %s успешно обновлено', $field), 'Ошибка', $exit_on_success, $exit_on_fail);
    }


    /**
     * Пакетное обновление типов полей
     *
     * @param array $fields_settings - массив пар Поле - Тип поля
     * @param bool $exit_on_success Завершить выполнение скрипта после успешного добавления поля?
     * @param bool $exit_on_fail Завершить выполнение скрипта в случае ошибки?
     * @throws Exception
     */
    public function batchUpdateFieldType($fields_settings, $exit_on_success = false, $exit_on_fail = false)
    {
        if (!$this->isExist()) {
            $this->cancel(sprintf("Таблица %s не существует", $this->getTableName()));
        }

        if (!$fields_settings || !is_array($fields_settings)) {
            $this->cancel("Не указан список полей");
        }

        $alter_queries = [];
        foreach ($fields_settings as $field => $type) {
            if (empty($field)) {
                $this->cancel("Не указано поле");
            }

            if (empty($type)) {
                $this->cancel("Не указано тип поля {$field}");
            }

            $alter_queries[] = sprintf(
                "CHANGE COLUMN %s %s %s",
                $field,
                $field,
                $type
            );
        }

        $this->query(
            implode(', ', $alter_queries),
            sprintf('Поля %s успешно обновлены', implode(', ', array_keys($fields_settings))),
            'Ошибка',
            $exit_on_success,
            $exit_on_fail
        );
    }


    /**
     * Удалить поле из таблицы
     *
     * @param string $field - название поля
     * @param bool|false $exit_on_success завершить выполнение скрипта при успешном удалении?
     * @param bool|false $exit_on_fail - завершить удаление скрипта при неудачном удалении?
     * @throws Exception
     */
    public function deleteField($field, $exit_on_success = false, $exit_on_fail = false)
    {
        if (!$this->isExist()) {
            $this->cancel("Таблица `{$this->getTableName()}` не существует");
        }

        // TODO: Cделать один общий запрос.
        $query = sprintf(
            "DROP COLUMN %s;",
            $field
        );

        // Выполняем запрос
        $this->query(
            $query,
            sprintf('Поле %s успешно удалено из таблицы %s', $field, $this->getTableName()),
            'Ошибка',
            $exit_on_success,
            $exit_on_fail
        );
    }
    

    /**
     * Выполняет указанный запрос и выводит сообщение о результате
     *
     * @param string $query             SQL-запрос к базе
     * @param string $msg_success       Сообщение, которое будет выведено в случае успешного выполнения запроса
     * @param string $msg_fail          Сообщение, которое будет выведено в случае ошибки
     * @param bool   $exit_on_success   Завершить выполнение скрипта, если таблица успешно добавлена?
     * @param bool   $exit_on_fail      Завершить выполнение скрипта, если таблица уже существует?
     *
     * @return \Bitrix\Main\DB\Result|bool
     */
    //поменяла по требованию Смотрова Дмитрия
    protected function query($query, $msg_success, $msg_fail = 'Ошибка ', $exit_on_success = false, $exit_on_fail = true)
    {
        $percona = new Percona($this->connection_name);
        try {
            // Сначала dry-run
            $percona->change($query, $this->getTableName(), true);

            // Потом execute, если не было исключения
            $percona->change($query, $this->getTableName());

            $this->success($msg_success, $exit_on_success);
            return true;
        } catch (\Exception $e) {
            $this->fail($msg_fail . ': "'. $e->getMessage() . '" в запросе "' . $query . '"', $exit_on_fail);
            return false;
        }
    }

    /**
     * Удаляет таблицу указанного класса в БД
     *
     * @param DataManager   $entity_table       Объект класса таблицы
     * @param bool          $exit_on_success    Завершить выполнение скрипта, если таблица успешно удалена?
     * @param bool          $exit_if_exists     Завершить выполнение скрипта, если таблицы не существует?
     *
     * @return bool Удаления прошло успешно?
     */
    public function drop($entity_table, $exit_on_success = true, $exit_if_exists = true)
    {
        if (!$this->isExist($entity_table)) {
            $this->cancel("Таблицы `{$this->getTableName()}` не существует.", $exit_if_exists);
            return false;
        } else {
            try {
                $this->getConnection()->query("DROP TABLE {$this->getTableName()}");
                $this->success("Таблица `{$this->getTableName()}` успешно удалена", $exit_on_success);
                return true;
            } catch (\Exception $e) {
                $this->fail("Ошибка удаления таблицы `{$this->getTableName()}`: {$e->getMessage()}");
                return false;
            }
        }
    }
}
