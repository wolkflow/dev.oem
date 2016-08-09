<?php
/**
 * Пример использования:
 * добавление нового свойства элемента инфоблока
 *
use Linemedia\Carsale\Migrations\IBlockProperty;
$prop = new IBlockProperty(
    'CP_1511',
    'Добавление свойства Фу для объекта Бар',
    [
        'NAME'          => 'Название свойства',
        'ACTIVE'        => 'Y',
        'SORT'          => 500,
        'CODE'          => 'PROPERTY_CODE',
        'PROPERTY_TYPE' => 'S', //S - строка, N - число, F - файл, L - список, E - привязка к элементам, G - привязка к группам.
        'IBLOCK_ID'     => $IBLOCK_ID,
    ]
);
$new_prop_id = $prop->add();    // Для последуйющей работы с ID свойства
$prop->add(true);               // Для вывода сообщения об успешном добавлении и выхода
 *
 */

namespace Wolk\Core\System\Migrations;

/**
 * Класс для совершения действий со свойствами элемента инфоблока
 *
 * @author Александр Акмурзин a.akmurzin@carprice.ru
 */
class IBlockProperty extends UpdateTool
{
    
    /**
     * Инициализирует объект, задаёт набор полей,
     * выводит границу начала задачи
     *
     * @param string    $task_id идентификатор задачи
     * @param string    $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param array     $arFields набор полей свойства элемента инфоблока
     *
     * @link http://dev.1c-bitrix.ru/api_help/iblock/fields.php#fproperty
     */
    public function __construct($task_id, $migration_description, $arFields = [])
    {
        if (!\Bitrix\Main\Loader::includeModule(MODULE_ID_IBLOCK)) {
            exit('Module iblock not installed');
        }
        parent::__construct($task_id, $migration_description, $arFields);
    }
    
    
    /**
     * Проверяет существование свойства элемента инфоблока по заданному ранее набору полей
     *
     * @return mixed - ID обнаруженного свойства или false, если ничего не найдено
     *
     * @throws \Exception
     */
    public function isExist()
    {
        // Формируем из известных полей свойства фильтр
        $arFilter = [];
        if (empty($this->fields["CODE"])) {
            throw new \Exception("Невозможно определить, существует ли свойство, так как не задан его код");
        } else {
            $this->fields["CODE"] = strval($this->fields["CODE"]);
            $arFilter["CODE"] = $this->fields["CODE"];
        }
        if (empty($this->fields["IBLOCK_ID"])) {
            if (empty($this->fields["IBLOCK_CODE"]) && empty($this->fields["IBLOCK_TYPE"])) {
                throw new \Exception("Невозможно определить, существует ли свойство, так как не задан код инфоблока");
            } else {
                $arFilter["IBLOCK_CODE"] = $this->fields["IBLOCK_CODE"];
                $arFilter["IBLOCK_TYPE"] = $this->fields["IBLOCK_TYPE"];
            }
        } else {
            $arFilter["IBLOCK_ID"] = $this->fields["IBLOCK_ID"];
        }
        // Получилось что-нибудь найти по сформированному фильтру?
        $arProperty = \CIBlockProperty::GetList([], $arFilter)->Fetch();
        if (!$arProperty) {
            return false;
        }
        // Если свойство найдено, то запомним его ID
        $this->id = $arProperty["ID"];
        return $this->id;
    }

    /**
     * Добавляет свойство элемента инфоблока
     *
     * @param bool $exit_on_success - работа скрипта завершается на успешном добавлении группы?
     * @param bool $exit_if_exists - прерывать выполнение скрипта, если группа найдена?
     * @param bool $exit_on_fail - прерывать выполнение скрипта, если произошла ошибка при добавлении?
     *
     * @return mixed - ID добавленного свойства или false в случае неудачи
     */
    public function add($exit_on_success = false, $exit_if_exists = false, $exit_on_fail = true)
    {
        // Проверим, было ли уже выполнено добавление
        try {
            if ($this->isExist()) {
                // Если свойство уже существует, сообщим об отмене действия
                $this->cancel("Свойство " . $this->fields["CODE"] . " уже существует", $exit_if_exists);
                return $this->id;
            }
        } catch (\Exception $e) {
            /*
             * Если невозможно проверить, существует ли свойство,
             * то дальше не идём
             */
            $this->fail($e->getMessage());
        }
        // Добавим свойство
        $iblock_property = new \CIBlockProperty();
        $this->id = $iblock_property->Add($this->fields);
        //Получилось?
        if (!$this->id) {
            $this->fail("Не удалось добавить свойство " . $this->fields["CODE"] . ": " . $iblock_property->LAST_ERROR, $exit_on_fail);
            return false;
        }
        // Если да, то сообщим об этом и вернём ID нового свойства
        $this->success("Свойство " . $this->fields["CODE"] . " успешно добавлено, ID=" . $this->id, $exit_on_success);
        return $this->id;
    }


    /**
     * Удаляет свойство инфоблока
     *
     * @param bool|false $exit_on_success
     * @param bool|true $exit_on_fail
     *
     * @return bool
     */
    public function delete($exit_on_success = false, $exit_on_fail = true)
    {
        // Проверим, существует ли свойство
        try {
            if (!$prop_id = $this->isExist()) {
                $this->cancel("Свойство " . $this->fields["CODE"] . " уже удалено", $exit_on_success);
                return false;
            }
        } catch (\Exception $e) {
            /*
             * Если невозможно проверить, существует ли свойство,
             * то дальше не идём
             */
            $this->fail($e->getMessage());
        }

        // Удаляем свойство
        if (\CIBlockProperty::Delete($prop_id)) {
            $this->success('Свойство' . $this->fields['CODE'] . ' успешно удалено', $exit_on_success);
            return true;
        }

        $this->fail('Ошибка удаления свойства' . $this->fields['CODE'], $exit_on_fail);
        return false;
    }
}
