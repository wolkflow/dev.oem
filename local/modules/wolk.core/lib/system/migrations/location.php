<?php

namespace Wolk\Core\System\Migrations;


use Bitrix\Sale\Location\LocationTable;
use Bitrix\Sale\Location\Name\TypeTable;

use Linemedia\Carsale\Exception;


//TODO transaction, delete|update methods
/**
 * Class Location
 * Класс миграции, упрощающий добавление новых местоположений в бд
 * @package Linemedia\Carsale\Migrations
 *
 * Пример использования:
 *
 *  require __DIR__ . '/../local/modules/linemedia.carsale/include/migration.php';
 * $migration = new \Linemedia\Carsale\Migrations\Location('CP-3982', 'Добавление данных местоположений Беларуси в бд');
 *  $migration->setScenario(SCENARIO_NAME);
 * $migration->setXml(__DIR__.'/data/CP-3982-Belarus-locations-data.xml');
 *
 * $migration->execute();
 *
 * XML format example:
 * <?xml version="1.0" encoding="utf-8"?>
 * <!DOCTYPE country>
 * <country name="Беларусь" type="country" code="Belarus">
 * <region type="region" name="Брестская область" code="Brestkaya oblast">
 * <city name="Брест" type="city" code="Brest">
 * </city>
 * <city name="Ивацевичи" type="city" code="Ivacevichi">
 * </city>
 *          </region>
 *      </country>
 * XML for district load example:
 * <?xml version="1.0" encoding="utf-8"?>
 * <!DOCTYPE country>
 * <country name="Россия" type="country" code="Russia">
 * <district name="Приволжский ФО" type="district" code="volga">
 * <region name="Башкортостан Респ"></region>
 * <region name="Марий Эл Респ"></region>
 * </district>
 *  </country>
 */
class Location extends UpdateTool
{

    /**
     * Константы сценариев
     */
    CONST FULL_COUNTRY_LOAD_SCENARIO = 'full_country_load';
    CONST DISTRICTS_LOAD_SCENARIO = 'districts_load';

    /**
     * Путь до xml файла с местоположениями
     * @var string
     */
    protected $xml = '';

    /**
     * Данные после парсинга xml
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $scenario = 'full_country_load';

    /**
     * Список возможных сценариев с местоположениями
     * @var array
     */
    protected $scenarios = [
        self::FULL_COUNTRY_LOAD_SCENARIO,
        self::DISTRICTS_LOAD_SCENARIO
    ];

    /**
     * Тип местоположения
     * @var
     */
    protected $types;

    /**
     * Массив объектов на удаление
     * @var array
     */
    protected $deleted_ids = [];

    /**
     * @inheritdoc
     */
    public function __construct($task_id, $migration_description, $arFields = [])
    {
        parent::__construct($task_id, $migration_description, $arFields = []);
    }

    /**
     * Устанавливает путь до xml файла импорта
     * @param $xml_path
     */
    public function setXml($xml_path)
    {
        $this->xml = $xml_path;
    }

    /**
     * @param $scenario
     */
    public function setScenario($scenario)
    {
        if (in_array($scenario, $this->scenarios)) {
            $this->scenario = $scenario;
        }
    }

    /**
     * @param array $params
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    public function addType(Array $params)
    {
        try {
            $type = \Bitrix\Sale\Location\TypeTable::getList([
                'filter' => [
                    '=CODE' => $params['CODE']
                ]
            ]);

            if (empty($type->fetch())) {
                $res = \Bitrix\Sale\Location\TypeTable::add($params);
                if ($res->isSuccess()) {
                    $this->success("Новый тип местоположений {$params['CODE']} успешно добавлен");
                } else {
                    $this->success("Возникла ошибка при добавлении типа.");
                }
            } else {
                $this->cancel('Данный тип уже существует!', false);
            }
        } catch (\Exception $e) {
            $this->cancel($e->getMessage());
        }
    }

    /**
     * Удаляет местоположение по ее идентификатору, удаление откладывается до выполнения метода execute
     * @param $id
     */
    public function deleteById($id)
    {
        $this->deleted_ids[] = $id;
    }

    /**
     * Удаляет местоположение по имени из базы (по имени на случай, если идентификаторы местоположений разные на бою
     * и на тесте/деве
     * удаление откладывается до метода execute
     * @param $location_name
     */
    public function deleteByName($location_name)
    {
        $this->deleted_ids[] = $this->findByName($location_name);
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     * @throws \Bitrix\Main\ArgumentException
     */
    protected function findByName($name)
    {
        $res = LocationTable::getListFast([
            'filter' => [
                'NAME' => trim($name),
            ],
            'select' => ['ID']
        ]);

        if ($data = $res->fetch()) {
            return $data['ID'];
        }

        throw new \Exception("Ошибка поиска по имени, элемент не найден: {$name}");
    }

    /**
     * Выполняет миграцию
     */
    public function execute()
    {
        try {
            $this->types = $this->getTypes();
            $this->deleteLocations();
            $this->loadXml();
            $this->success('Миграция успешно применена.');
        } catch (\Exception $e) {
            $this->cancel($e->getMessage());
        }
    }

    /**
     * Получает массив названий типов местоположений
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    public function getTypes()
    {
        $res = TypeTable::getList([
            'filter' => [
                'LANGUAGE_ID' => 'en'
            ],
            'select' => ['NAME', 'TYPE_ID']
        ]);

        $types = [];
        while ($type = $res->fetch()) {
            $types[$type['TYPE_ID']] = ToUpper($type['NAME']);
        }

        return $types;
    }

    /**
     * Удаляет идентификаторы всех местоположений, попавших в массив deleted_ids
     */
    protected function deleteLocations()
    {
        foreach ($this->deleted_ids as $id) {
            if (LocationTable::delete($id)->isSuccess()) {
                $this->showMessage('Успешно удален Объект: ' . $id);
            } else {
                $this->showMessage('Не возможно удалить объект: ' . $id);
            }
        }
    }

    /**
     * Загружает данные из xml в массив и запускает обработку, в зависимости от сценария
     * @throws Exception
     */
    protected function loadXml()
    {
        $data = simplexml_load_file($this->xml);
        if (!$data) {
            throw new \Exception('Файл загрузки не подключен');
        }
        switch ($this->scenario) {
            case self::FULL_COUNTRY_LOAD_SCENARIO :
                $this->loadCountryData($data);
                $this->addLocations();
                break;

            case self::DISTRICTS_LOAD_SCENARIO:
                $this->loadDistrictsData($data);
                $this->addDistricts();
                break;
        }
    }

    /**
     * @param \SimpleXMLElement $data
     * @throws Exception
     */
    protected function loadCountryData(\SimpleXMLElement $data)
    {
        $country = $data;
        $data = [];
        $country_attributes = $country->attributes();

        if (!empty($country_attributes)) {
            $data['country'] = [
                'name' => (string)$country_attributes['name'],
                'code' => (string)$country_attributes['code'],
                'type' => (string)$country_attributes['type'],
            ];
        } else {
            throw new \Exception('Ошибка загрузки данных, нет информации по стране.');
        }

        $regions_number = 0;
        if (!empty($country->region)) {
            foreach ($country->region as $region) {
                $region_attributes = $region->attributes();
                $data['country']['regions'][$regions_number] = [
                    'name' => (string)$region_attributes['name'],
                    'code' => (string)$region_attributes['code'],
                    'type' => (string)$region_attributes['type'],
                ];

                if (!empty($region->city)) {
                    foreach ($region->city as $city) {
                        $cities_attributes = $city->attributes();
                        $data['country']['regions'][$regions_number]['cities'][] = [
                            'name' => (string)$cities_attributes['name'],
                            'code' => (string)$cities_attributes['code'],
                            'type' => (string)$cities_attributes['type'],
                        ];
                    }
                }

                $regions_number++;
            }
        }

        $this->data = $data;
    }

    /**
     * Обрабатывает данные из xml, или другого источника
     * @param array $data
     * @throws Exception
     */
    protected function addLocations(Array $data = [])
    {
        if (!empty($data) || !empty($this->data)) {
            $data = empty($data) ? $this->data : $data;
            /*Добавляем страну*/
            $country_id = $this->addLocation($data['country']);

            /*Добавляем регионы и города*/
            foreach ($data['country']['regions'] as $region) {
                $region_id = $this->addLocation($region, $country_id);
                foreach ($region['cities'] as $city) {
                    $this->addLocation($city, $region_id);
                }
            }
        } else {
            throw new \Exception('Ошибка добавления данных, пустые данные!');
        }
    }

    /**
     * Добавляет местоположение
     * @param array $data
     * @param null $parent_id
     * @throws Exception
     */
    public function addLocation(Array $data, $parent_id = null)
    {
        $res = LocationTable::add([
            'CODE'      => $data['code'],
            'TYPE_ID'   => $this->getTypeId($data['type']),
            'PARENT_ID' => $parent_id,
            'NAME'      => [
                'ru' => [
                    'NAME' => $data['name']
                ]
            ]
        ]);

        /* @var $res \Bitrix\Main\Entity\AddResult */

        if ($res->isSuccess()) {
            $this->showMessage('Добавлен элемент ' . $data['name'] . ' (' . $res->getId() . ')' . ', тип: ' . $data['type']);
            return $res->getId();
        } else {
            throw new \Exception(array_pop($res->getErrorMessages()));
        }
    }

    /**
     * Получает идентификатор типа местоположения
     * @param $type_code - код типа на англ, например country
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentException
     */
    public function getTypeId($type_code)
    {
        $type_id = null;
        if (empty($this->types)) {

            $res = TypeTable::getList([
                'filter' => [
                    'NAME' => $type_code
                ],
                'select' => ['TYPE_ID']
            ]);
            if ($type_data = $res->fetch()) {
                $type_id = $type_data['TYPE_ID'];
            }
        } else {
            $type_id = array_search(ToUpper($type_code), $this->types);
        }

        return $type_id;
    }

    /**
     * @param \SimpleXMLElement $data
     */
    protected function loadDistrictsData(\SimpleXMLElement $data)
    {
        $country = $data;
        $data = [];
        $country_attributes = $country->attributes();
        $data['country']['id'] = $this->findByName((string)$country_attributes['name']);
        $i = 0;
        foreach ($country->district as $district) {
            $district_attributes = $district->attributes();
            $data['country']['districts'][$i] = [
                'name' => (string)$district_attributes['name'],
                'code' => (string)$district_attributes['code'],
                'type' => (string)$district_attributes['type']
            ];

            foreach ($district->region as $region) {
                $region_attributes = $region->attributes();
                $data['country']['districts'][$i]['regions'][] = $this->findByName((string)$region_attributes['name']);
            }
            $i++;
        }
        $this->data = $data;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function addDistricts(Array $data = [])
    {
        if (!empty($data) || !empty($this->data)) {
            $data = empty($data) ? $this->data : $data;

            /*Добавляем регионы и города*/
            foreach ($data['country']['districts'] as $district) {
                $district_id = $this->addLocation($district, $this->data['country']['id']);
                foreach ($district['regions'] as $region) {
                    $this->updateLocationParent($region, $district_id);
                }
            }
        } else {
            throw new \Exception('Ошибка добавления данных, пустые данные!');
        }
    }

    /**
     * @param $region_id
     * @param $parent_id
     */
    protected function updateLocationParent($region_id, $parent_id)
    {
        $res = \Bitrix\Sale\Location\LocationTable::update($region_id, [
            'PARENT_ID' => $parent_id
        ]);

        /* @var $res \Bitrix\Main\Entity\UpdateResult */
        if ($res->isSuccess()) {
            $this->showMessage('Обновлено родительское значение локации ' . $region_id);
        } else {
            throw new \Exception(array_pop($res->getErrorMessages()));
        }
    }
} 