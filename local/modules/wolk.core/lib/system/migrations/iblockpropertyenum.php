<?php
/*
 * Пример использования: добавление нового свойства типа Список
 *
$list = new \Linemedia\Carsale\Migrations\IBlockPropertyEnum(
    "CP-1511",
    "Добавление списка чего-то для чего-то важного",
    [
        'NAME'          => 'Название свойства типа Список',
        'ACTIVE'        => 'Y',
        'SORT'          => 500,
        'CODE'          => 'PROPERTY_CODE',
        'IBLOCK_ID'     => $IBLOCK_ID,
        'PROPERTY_TYPE' => 'L', // Можно не указывать, всё равно подставится L автоматом
    ]
);
$successfully_added = $list->addProperty([
    [
        'VALUE'     => 'Название 1',
        'SORT'      => 100,
        'XML_ID'    => "FIRST",
    ],
    [
        'VALUE'     => 'Название 2',
        'SORT'      => 200,
        'XML_ID'    => "SECOND",
    ],
]);                                 // Для продолжения работы
$list->addProperty([...], true);    // Для вывода сообщения об успешном окончании и прекращении работы скрипта
 * 
 */

/*
 * Пример использования: добавление нового элемента списка
 *
$list = new \Linemedia\Carsale\Migrations\IBlockPropertyEnum(
    "CP-1511",
    "Добавление нового элемента в самый важный список",
    [
        "IBLOCK_ID" => $IBLOCK_ID,      // Можно вместо IBLOCK_ID указать IBLOCK_TYPE и IBLOCK_CODE
        "CODE"      => "PROPERTY_CODE", // Достаточно этих двух полей, чтобы найти свойство
    ]
);
$enum_id = $list->addElement([
    'VALUE'     => 'Новый элемент списка',
    'SORT'      => 150,
    'XML_ID'    => "AWESOME_CODE",
]);                             // Для дальнейших операций с новым элементом
$list->addElement([], true);    // Для вывода сообщения об успешном окончании и прекращении работы скрипта
 * 
 */

namespace Linemedia\Carsale\Migrations;

/**
 * Класс для совершения действий со свойствами инфоблока типа список
 *
 * @author Александр Акмурзин a.akmurzin@carprice.ru
 */
class IBlockPropertyEnum extends IBlockProperty
{
    /**
     * Инициализирует объект, задаёт набор полей, выводит границу начала задачи
     *
     * @param string $task_id               Идентификатор задачи
     * @param string $migration_description Описание сути миграции. Можно задать позже через setDescription.
     * @param array  $arPropertyFields      Массив полей свойства элемента инфоблока
     */
    public function __construct($task_id, $migration_description, $arPropertyFields = [])
    {
        parent::__construct($task_id, $migration_description, $arPropertyFields);
    }
    
    
    /**
     * Задаёт набор полей свойства элемента типа Список, синоним setFields
     *
     * @param array $arFields - массив полей
     */
    public function setPropertyFields($arFields)
    {
        $this->setFields($arFields);
    }
    
    
    /**
     * Проверяет существование свойства по заданному набору полей, синоним isExist
     *
     * @return mixed - ID свойства или false, если ничего не найдено
     *
     * @throws Exception
     */
    public function isPropertyExist()
    {
        return parent::isExist();
    }
    
    
    /**
     * Проверяет существование элемента списка свойства инфоблока
     *
     * @param array $arFields - массив полей элемента списка
     *
     * @return mixed - ID элемента или false, если ничего не найдено
     *
     * @throws \Exception
     */
    public function isElementExist(&$arFields)
    {
        // Проверим наличие обязательных полей в переданном массиве
        if (empty($this->id)) {
            if (empty($arFields["PROPERTY_ID"])) {
                throw new \Exception("Невозможно добавить элемент списка, так как неизвестен ID свойства");
            }
            $this->id = intval($arFields["PROPERTY_ID"]);
        }
        if (empty($arFields["XML_ID"])) {
            throw new \Exception("Невозможно добавить элемент списка без указания его XML_ID");
        } else {
            $arFields["XML_ID"] = strval($arFields["XML_ID"]);
        }
        // Сформируем фильтр и поищем элемент
        $arFilter = [
            'PROPERTY_ID'   => $this->id,
            'XML_ID'        => $arFields["XML_ID"],
        ];
        $arElement = \CIBlockPropertyEnum::GetList([], $arFilter)->Fetch();
        if (!$arElement) {
            return false;
        }
        // Если нашли, вернём его ID
        return $arElement["ID"];
    }
    
    
    /**
     * Добавляет элемент списка свойтсва элемента инфоблока
     *
     * @param array $arFields - массив полей элемента списка
     * @param boolean $exit_on_success - заверщить выполнение скрипта после удачного добавления?
     * @param boolean $check_property_exist - проверить существование свойства типа Список, к которому будет добавлен элемент
     * @param boolean $exit_on_fail - завершить выполнение скрипта, если при добавлении элемента произошла ошибка?
     * @param boolean $exit_if_element_exists - заверщить выполнение скрипта, если элемент уже существует?
     *
     * @return mixed - ID добавленного элемента или false, если произошла ошибка
     */
    public function addElement($arFields, $exit_on_success = false, $check_property_exist = true, $exit_on_fail = true, $exit_if_element_exists = false)
    {
        // Если нужно проверить существование свойства типа "Список", то проверим
        if ($check_property_exist) {
            try {
                if (!$this->isPropertyExist()) {
                    // Если свойство не существует, то некуда добавлять элемент, выходим
                    $this->fail("Свойство " . $this->fields["CODE"] . " не обнаружено");
                }
            } catch (\Exception $e) {
                // Если не удайтся проверить, то дальше не пойдём
                $this->fail($e->getMessage());
            }
        }
        
        // Проверим, был ли уже добавлен элемент
        try {
            $enum_id = $this->isElementExist($arFields);
            if ($enum_id) {
                // Если да, то нужно про это сообщить
                $this->cancel("Элемент " . $arFields["XML_ID"] . " уже существует", $exit_if_element_exists);
                return $enum_id;
            }
        } catch (\Exception $e) {
            // Если не удайтся проверить, то дальше не пойдём
            $this->fail($e->getMessage());
        }
        
        // Добавим элемент списка
        $property_enum = new \CIBlockPropertyEnum();
        $arFields["PROPERTY_ID"] = $this->id;
        $enum_id = $property_enum->Add($arFields);
        if (!$enum_id) {
            $this->fail("Не удалось добавить элемент " . $arFields["XML_ID"] . ": " . $property_enum->LAST_ERROR, $exit_on_fail);
            return false;
        }
        $this->success("Элемент списка " . $arFields["XML_ID"] . " успешно добавлен, ID=$enum_id", $exit_on_success);
        return $enum_id;
    }
    
    
    /**
     * Добавляет несколько элементов в свойство элемента инфоблока типа Список
     *
     * @param array $arElementsFields - массив элементов, каждый из которых является массивом полей элемента списка
     * @param boolean $exit_on_success - завершить выполнение скрипта после удачного добавления?
     * @param boolean $exit_on_fail - завершить выполнение скрипта, если не удалось добавить ни одного элемента?
     *
     * @return int - количество успешно добавленных элементов
     */
    public function addElements($arElementsFields, $exit_on_success = false, $exit_on_fail = true)
    {
        $count_success = 0;
        // На первом элементе проверим, существует ли само свойство списка элементов
        $first_element = array_shift($arElementsFields);
        if ($this->addElement($first_element, false, true, false)) {
            $count_success++;
        }
        // Добавим все остальные элементы
        foreach ($arElementsFields as $element) {
            if ($this->addElement($element, false, false, false)) {
                $count_success++;
            }
        }
        // Посмотрим, сколько добавлений прошло успешно
        if (!$count_success) {
            $this->fail("Не удалось добавить ни одного элемента списка", $exit_on_fail);
        } else {
            $this->success("Удачно добавлено $count_success элементов списка из " . (count($arElementsFields) + 1), $exit_on_success);
        }
        return $count_success;
    }
    
    
    /**
     * Добавляет свойство типа Список и запоняет его элементы
     *
     * @param array $arElementFields - массив элементов, каждый из которых является массивом полей элемента списка
     * @param boolean $exit_on_success - завершить выполнение скрипта после удачного добавления всех элементов?
     * @param boolean $exit_if_exists - прервать выполнение, если свойство уже существует? Можно всё равно попытаться добавить элементы списка.
     * @param boolean $exit_on_fail - завершить выполнение скрипта, если не удалось дбоавить ни одного элемента списка?
     *
     * @return int - колчество добавленных элементов списка или false, если ничего не получилось
     */
    public function addProperty($arElementFields, $exit_on_success = false, $exit_if_exists = false, $exit_on_fail = true)
    {
        // Добавим само свойство типа список, поля должны быть уже установлены ранее
        // Если возникнет ошибка, то будет выдано сообщение об ошибке и exit
        $this->fields["PROPERTY_TYPE"] = "L";   // Тип свойства должен быть "Список"
        $this->add(false, $exit_if_exists);
        // Если свойство добавили, то теперь добавим элементы списка
        return $this->addElements($arElementFields, $exit_on_success, $exit_on_fail);
    }
}
