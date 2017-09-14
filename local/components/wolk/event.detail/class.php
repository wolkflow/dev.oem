<?

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Sale\Internals\BasketPropertyTable;
use Bitrix\Sale\Internals\BasketTable;
use Bitrix\Sale\Internals\OrderPropsValueTable;
use Bitrix\Sale\Internals\OrderTable;
use Wolk\Core\Helpers\ArrayHelper;
use Wolk\OEM\Components\BaseListComponent;

/**
 * Class EventDetailComponent
 */
class EventDetailComponent extends BaseListComponent
{
    const SERVICES_SECTION_ID = 11;
    const OPTIONS_SECTION_ID = 10;

    protected $cacheKeys = ['ITEMS', 'EVENT', 'ORDER', 'INDIVIDUAL_STAND', 'COLORS_PALETTE', 'SERVICES'];
    protected $curLang;

    /**
     * @var array
     */
    protected $event = null;

    /** @var array */
	protected $standPrices = null;
    protected $equipmentPrices = null;

    protected $equipmentColors = [];

	
    /**
     * EventDetailComponent constructor.
     * @param CBitrixComponent|null $component
     */
    public function __construct($component = null)
    {
        Loader::includeModule('sale');
        $this->siteCurrency = \Bitrix\Currency\CurrencyManager::getBaseCurrency();
        parent::__construct($component);
        $this->curLang = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());
        Loc::loadMessages(__FILE__);
    }

	
    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    protected function getEvent()
    {
        if (!is_null($this->event)) {
            return $this->event;
        }
        if ($event = $this->getEventById($this->arParams['EVENT_ID'])) {
            $arEvent = $this->getEventData($event);
            $this->event = $arEvent;

            return $this->event;
        } else {
            throw new \Bitrix\Main\ArgumentException('Event not found');
        }
    }


    public function onPrepareComponentParams($params)
    {
        if (!$params['EVENT_ID'] && !$params['ORDER_ID']) {
            throw new \Bitrix\Main\ArgumentException('Event not found');
        }
        if ((!$params['WIDTH'] || !$params['DEPTH']) && !$params['ORDER_ID']) {
            throw new \Bitrix\Main\ArgumentException('invalid params');
        }

        return parent::onPrepareComponentParams($params);
    }


    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    public function getResult()
    {
        Loader::includeModule('iblock');
        if ($this->arParams['ORDER_ID']) {
            $this->arResult['ORDER'] = $this->getOrder($this->arParams['ORDER_ID']);
            if (!$this->arResult['ORDER']) {
                throw new \Bitrix\Main\ArgumentException('Order not found');
            }
			$oemorder = new Wolk\OEM\Order($this->arParams['ORDER_ID']);
			$this->arResult['INDIVIDUAL_STAND'] = $oemorder->isIndividual();
        }
        if ($this->arParams['ORDER_TYPE'] == 'individual') {
            $this->arResult['INDIVIDUAL_STAND'] = true;
        }

        $this->getEvent();

        $this->arResult['EVENT'] = ArrayHelper::except($this->event, 'STANDS');
        $this->arResult['EVENT']['SURCHARGE'] = $this->getSurcharge();
        $this->arResult['EVENT']['COLORS_PALETTE'] = array_map(function ($val) {
            $val['NAME'] = $val['UF_LANG_NAME_' . $this->curLang] ?: $val['UF_XML_ID'];

            return $val;
        }, $this->getColors());
        $this->arResult['EVENT']['EXTENTS'] = array_map(function ($val) {
            $val['NAME'] = $val['UF_NAME_' . $this->curLang] ?: $val['UF_NAME'];

            return $val;
        }, $this->getLogoExtents());
        $this->arResult['EVENT']['ALL_PRICES'] = $this->equipmentPrices;
        $this->arResult['ITEMS'] = $this->event['STANDS'];
        $this->arResult['SERVICES'] = $this->getServices($this->event['ID']);
		
		/*
		global $USER;
		if ($USER->GetID() == 1) {
			echo '<pre>'; print_r($this->arResult['SERVICES']); echo '</pre>';
		}
		*/
    }
	

    /**
     * @param $eventId
     * @return array
     */
    public function getServices($eventId)
    {
        $arSections = [];

        if ($event = $this->getEventById($eventId)) {
            $services = $this->getEventServices($event);


            $this->arResult['EVENT']['ALL_SERVICES'] += $services;


            $arServices = [];
            foreach ($services as $arService) {
                $arServices[$arService['IBLOCK_SECTION_ID']][$arService['ID']] = $arService;
            }

            $servicesSections = \Bitrix\Iblock\SectionTable::getList([
				'order' => ['SORT' => 'ASC', 'ID' => 'ASC'],
                'filter' =>
                    [
                        'ID' => array_keys($arServices)
                    ]
            ])->fetchAll();

            $filter = [
                ['LOGIC' => 'OR'],
                [
                    'ID' => array_keys($arServices)
                ]
            ];

            foreach ($servicesSections as $serviceSection) {
                $filter[] = [
                    '<LEFT_MARGIN'  => $serviceSection['LEFT_MARGIN'],
                    '>RIGHT_MARGIN' => $serviceSection['RIGHT_MARGIN'],
                ];
            }

            $obSections = CIBlockSection::GetTreeList([
                    'IBLOCK_ID' => OPTIONS_IBLOCK_ID,
                    'ACTIVE'    => 'Y',
                ] + $filter, [
                'ID',
                'NAME',
                'CODE',
				'SORT',
                'DEPTH_LEVEL',
                'IBLOCK_SECTION_ID',
                'UF_SUBTITLE_' . $this->curLang,
                'UF_NAME_' . $this->curLang,
                'UF_SORT',
                'UF_LANG_NOTE_' . $this->curLang
            ]);

            while ($arSection = $obSections->Fetch()) {
                $arSection['NAME'] = $arSection['UF_NAME_' . $this->curLang] ?: $arSection['NAME'];
                $arSection['SUBTITLE'] = $arSection['UF_SUBTITLE_' . $this->curLang] ?: $arSection['UF_SUBTITLE'];
                $arSection['NOTE'] = $arSection['UF_LANG_NOTE_' . $this->curLang] ?: '';
				
                if ($arSection['IBLOCK_SECTION_ID']) {
                    if (array_key_exists($arSection['ID'], $arServices)) {
                        $arSection['ITEMS'] = $arServices[$arSection['ID']];
                    }
                    $sections[$arSection['IBLOCK_SECTION_ID']]['SECTIONS'][$arSection['ID']] = $arSection;
                    $sections[$arSection['ID']] = &$sections[$arSection['IBLOCK_SECTION_ID']]['SECTIONS'][$arSection['ID']];
                } else {
                    $arSections[$arSection['ID']] = $arSection;
                    $sections[$arSection['ID']] = &$arSections[$arSection['ID']];
                }
            }
			
            
            // Сортировка.
			foreach ($arSections as &$section) {
				foreach ($section['SECTIONS'] as &$subsection) {
					if (isset($subsection['SECTIONS'])) {
						uasort($subsection['SECTIONS'], function ($x1, $x2) { return ($x1['SORT'] - $x2['SORT']); } );
						foreach ($subsection['SECTIONS'] as &$subsect) {
							//usort($subsect['ITEMS'], function ($x1, $x2) { return ($x1['SORT'] - $x2['SORT']); } );
						}
					}
				}
				uasort($section['SECTIONS'], function ($x1, $x2) { return ($x1['SORT'] - $x2['SORT']); } );
			}
            
             // Сортировка.
            foreach ($arSections as &$arSection) {
                foreach ($arSection['SECTIONS'] as &$section) {
                    if (!empty($section['ITEMS'])) {
                        usort($section['ITEMS'], function ($x1, $x2) { return ($x1['SORT'] - $x2['SORT']); });
                    }
                }
            }
        } 
        return $arSections;
    }
	

    /**
	 * Услуги мероприятия.
	 *
     * @param _CIBElement $event
     * @return array
     */
    protected function getEventServices(_CIBElement $event)
    {
        $arServices = [];
        $servicesIds = $event->GetProperty('OPTIONS')['VALUE'];
		
        $colors = array_map(function ($val) {
            return $val['UF_NAME_' . $this->curLang];
        }, $this->getEquipmentColors());
        
        $obServices = CIBlockElement::GetList(
			[],
			[
				'IBLOCK_ID' => OPTIONS_IBLOCK_ID,
				'ACTIVE'    => 'Y',
				'ID'        => $servicesIds
			], 
			false, 
			false, 
			[
				'ID',
				'NAME',
				'PROPERTY_*',
				'CATALOG_GROUP_1',
				'PREVIEW_PICTURE',
				'PREVIEW_TEXT',
				'IBLOCK_SECTION_ID',
				'CODE',
				'SORT',
			]
		);
		
        while ($arService = $obServices->Fetch()) {
            if ($arService['PREVIEW_PICTURE']) {
                $arService['PICTURE'] = [
                    'BIG'   => CFile::ResizeImageGet($arService['PREVIEW_PICTURE'], [
                        'width'  => 1920,
                        'height' => 1080
                    ])['src'],
                    'SMALL' => CFile::ResizeImageGet($arService['PREVIEW_PICTURE'], [
                        'width'  => 320,
                        'height' => 210
                    ])['src']
                ];
            }
			
			// TODO: Убрать ID свойств / вынести в константы.
            $arService['EQ_COLORS'] = ArrayHelper::only($colors, $arService['PROPERTY_38']);
			
            $width  = ($arService['PROPERTY_41'] / 10) <= 5 ? 5 : $arService['PROPERTY_41'] / 10;
            $height = ($arService['PROPERTY_40'] / 10) <= 5 ? 5 : $arService['PROPERTY_40'] / 10;
            $src    = CFile::GetFileArray($arService['PROPERTY_42'])['SRC'];
			
            list(
                $arService['WIDTH'],
                $arService['HEIGHT'],
                $arService['SKETCH_IMAGE'],
                $arService['SKETCH_TYPE'],
            ) = [
                $arService['PROPERTY_41'] / 1000,
                $arService['PROPERTY_40'] / 1000,
                $src ? "/i.php?w={$width}&h={$height}&src={$src}&zc=0" : false,
                $arService['PROPERTY_48'] ?: 'droppable'
            ];
			
            $arService['NAME'] = ($this->curLang == 'RU' ? $arService['PROPERTY_51'] : $arService['PROPERTY_52']) ?: $arService['NAME'];
			
            $arServices[$arService['ID']] = $arService;
        }

        foreach ($arServices as &$service) {
            $service['PRICE'] = $this->equipmentPrices[$service['ID']] ?: 0;
        }
        unset($service);

        return $arServices;
    }
	

    /**
     * @param $data
     * @throws Exception
     */
    protected function processAuth($data)
    {
        global $USER, $APPLICATION;

        if (isset($_POST['placeType']) && $_POST['placeType'] == 'register') {
            #register
            if (!$data['companyName'] || !$data['companyAddress'] || !$data['name'] || !$data['lastName']) {
                throw new Exception(Loc::getMessage('fill_required'));
            }
            $USER->Register(
                $data['email'], $data['name'], $data['lastName'], $data['password'],
                $data['password_confirm'], $data['email']
            );
            if ($APPLICATION->GetException()) {
                throw new Exception($APPLICATION->GetException()->GetString());
            } else {
                if (!$USER->Update($USER->GetID(), [
                    'WORK_COMPANY'   => $data['companyName'],
                    'WORK_STREET'    => $data['companyAddress'],
                    'PERSONAL_PHONE' => $data['phone'],
                    'UF_VAT'         => $data['vatId']
                ])
                ) {
                    throw new Exception($USER->LAST_ERROR);
                }
            }
        } elseif (isset($_POST['placeType']) && $_POST['placeType'] == 'login') {
            #auth
            if ($USER->Login($data['login'], $data['password']) !== true) {
                throw new Exception('Incorrect login or password');
            }
        } else {
            throw new Exception(Loc::getMessage('fill_required'));
        }
    }

    public function addToCart()
    {
        global $USER, $APPLICATION;
        if (!$USER->IsAuthorized()) {
            $userData = \Bitrix\Main\Web\Json::decode($_POST['userData']);
            if (isset($userData['login'], $userData['password']) || isset($userData['email'], $userData['password'])) {
                $this->processAuth($userData);
            } else {
                throw new \Exception(Loc::getMessage('fill_required'));
            }
        }

        $basket = new CSaleBasket();
        if (
            !(array_key_exists('event', $_POST) && is_numeric($_POST['event'])) ||
            !($event = $this->getEventById($_POST['event']))
        ) {
            die('invalid event');
        }

        $this->getEvent();

        $strOrderList = '';
        
        if (
            array_key_exists('stand', $_POST)
            && ($arStand = $this->getSelectedStand($_POST['stand']))
            && (in_array($arStand['ID'], array_keys($this->event['STANDS'])) || $arStand['ID'] == 0)
        ) {
            
            if (empty($arStand['NAME'])) {
                $arStand['NAME'] = 'Индивидуальный стенд'; // TODO: перевести.
            }
            
            $fuserId = \Bitrix\Sale\Fuser::getId();
            $basket->DeleteAll($fuserId);

            #find order
            if (isset($_POST['orderId']) && is_numeric($_POST['orderId'])) {
                $order = OrderTable::getRow([
                    'filter' =>
                        [
                            'ID'      => intval($_POST['orderId']),
                            'USER_ID' => $USER->GetID()
                        ]
                ]);

                $orderItems = BasketTable::getList([
                    'filter' =>
                        [
                            'ORDER_ID' => $order['ID']
                        ]
                ])->fetchAll();
                foreach ($orderItems as $orderItem) {
                    BasketTable::delete($orderItem['ID']);
                }
            }

            $addToCartErrors = [];

            #add stand
            $r = BasketTable::add([
                'PRODUCT_ID'     => $arStand['ID'],
                'QUANTITY'       => 1,
                'PRICE'          => $this->event['STANDS'][$arStand['ID']]['PRICE']['PRICE'] ?: 0,
                'CURRENCY'       => $this->event['CURRENCY']['NAME'],
                'LID'            => SITE_ID,
                'NAME'           => $arStand['NAME'],
                'SET_PARENT_ID'  => 0,
                'TYPE'           => CSaleBasket::TYPE_SET,
                'FUSER_ID'       => $fuserId,
                'RECOMMENDATION' => ($arStand['ID'] > 0) ? ('STAND.STANDARD') : ('STAND.INDIVIDUAL') 
            ]);

            if (!$r->isSuccess()) {
                $addToCartErrors[] = $r->getErrorMessages();
                die(join("\n", $r->getErrorMessages()));
            } else {
                $basketStandId = $r->getId();
                $strOrderList .= "стенд: {$arStand['NAME']}\n";
            }

            if (
                array_key_exists('equipment', $_POST)
                && ($arEquipment = $this->getSelectedEquipment($_POST['equipment'],
                    $this->event['STANDS'][$arStand['ID']]['OFFER']['EQUIPMENT']))
            ) {
                #add including equipment
                foreach ($arEquipment as $eq) {
                    $r = BasketTable::add([
                        'PRODUCT_ID'     => $eq['ID'],
                        'PRICE'          => 0,
                        'QUANTITY'       => ($eq['COUNT']) ?: 1,
                        'CURRENCY'       => $this->event['CURRENCY']['NAME'],
                        'LID'            => SITE_ID,
                        'NAME'           => $this->event['ALL_SERVICES'][$eq['ID']]['NAME'],
                        'SET_PARENT_ID'  => 0,
                        'TYPE'           => CSaleBasket::TYPE_SET,
                        'FUSER_ID'       => $fuserId,
                        'RECOMMENDATION' => 'PRODUCT.BASE'
                    ]);

                    if (!$r->isSuccess()) {
                        $addToCartErrors[] = $r->getErrorMessages();
                    } else {
                        BasketPropertyTable::add([
                            'BASKET_ID' => $r->getId(),
                            'NAME'      => 'Стандартная комплектация',
                            'CODE'      => 'INCLUDING',
                            'VALUE'     => 'Да'
                        ]);
                        $strOrderList .= "{$eq['NAME']} - {$eq['COUNT']}\n";
                    }

                    if ($eq['QUANTITY'] > $eq['COUNT']) {
                        $diff = $eq['QUANTITY'] - $eq['COUNT'];
                        #add over including equipment
                        $r = BasketTable::add([
                            'PRODUCT_ID' => $eq['ID'],
                            'PRICE'      => $this->equipmentPrices[$eq['ID']],
                            'QUANTITY'   => ($diff) ?: 1,
                            'CURRENCY'   => $this->event['CURRENCY']['NAME'],
                            'LID'        => SITE_ID,
                            'NAME'       => $this->event['ALL_SERVICES'][$eq['ID']]['NAME'],
                            'FUSER_ID'   => $fuserId,
                            'RECOMMENDATION' => 'PRODUCT.SALE'
                        ]);

                        if (!$r->isSuccess()) {
                            $addToCartErrors[] = $r->getErrorMessages();
                        }

                        $strOrderList .= "{$eq['NAME']} - {$diff}\n";
                    }
                }
            }

            if (
                array_key_exists('services', $_POST)
                && ($arServices = $this->getSelectedServices($_POST['services'], []))
            ) {
                foreach ($arServices as $groupId => $services) {
                    foreach ($services as $serviceId => $service) {
                        $service['PRICE'] = $this->equipmentPrices[$service['ID']];
                        if ($service['MULTIPLIER']) {
                            $service['PRICE'] *= $service['MULTIPLIER'];
                        }
                        $r = BasketTable::add([
                            'PRODUCT_ID' => $service['ID'],
                            'PRICE'      => $service['PRICE'] ?: 0,
                            'QUANTITY'   => $service['QUANTITY'] ?: 1,
                            'CURRENCY'   => $this->event['CURRENCY']['NAME'],
                            'LID'        => SITE_ID,
                            'NAME'       => $service['NAME'],
                            'FUSER_ID'   => $fuserId,
                            'RECOMMENDATION' => 'PRODUCT.SALE'
                        ]);

                        if (!$r->isSuccess()) {
                            $addToCartErrors[] = $r->getErrorMessages();
                        }

                        $strOrderList .= "{$service['NAME']} - {$service['QUANTITY']}\n";

                        if ($r->isSuccess() && isset($service['PROPS']) && is_array($service['PROPS'])) {
                            foreach ($service['PROPS'] as $prop) {
                                BasketPropertyTable::add([
                                    'BASKET_ID' => $r->getId(),
                                    'NAME'      => $prop['NAME'],
                                    'CODE'      => $prop['CODE'],
                                    'VALUE'     => $prop['VALUE']
                                ]);
                            }
                        }
                    }
                }
            }

            if (
                array_key_exists('options', $_POST)
                && ($arOptions = $this->getSelectedOptions($_POST['options'], []))
            ) {
                foreach ($arOptions as $groupId => $options) {
                    foreach ($options as $optionId => $option) {
                        $r = BasketTable::add([
                            'PRODUCT_ID' => $option['ID'],
                            'PRICE'      => $this->equipmentPrices[$option['ID']] ?: 0,
                            'QUANTITY'   => $option['QUANTITY'] ?: 1,
                            'CURRENCY'   => $this->event['CURRENCY']['NAME'],
                            'LID'        => SITE_ID,
                            'NAME'       => $option['NAME'],
                            'FUSER_ID'   => $fuserId,
                            'RECOMMENDATION' => 'PRODUCT.SALE'
                        ]);

                        if (!$r->isSuccess()) {
                            $addToCartErrors[] = $r->getErrorMessages();
                        }

                        $strOrderList .= "{$option['NAME']} - {$option['QUANTITY']}\n";

                        if ($r->isSuccess() && isset($option['PROPS']) && is_array($option['PROPS'])) {
                            foreach ($option['PROPS'] as $prop) {
                                if (!$prop['VALUE']) {
                                    continue;
                                }
                                BasketPropertyTable::add([
                                    'BASKET_ID' => $r->getId(),
                                    'NAME'      => $prop['NAME'],
                                    'CODE'      => $prop['CODE'],
                                    'VALUE'     => $prop['VALUE']
                                ]);
                            }
                        }
                    }
                }
            }

            if (empty($addToCartErrors)) {
                return $this->placeOrder($fuserId, $strOrderList, $order);
            } else {
                throw new Exception(join("<br>", $addToCartErrors));
            }
        }
        throw new Exception('Error');
    }


    /**
     * Создание заказа.
     */
    protected function placeOrder($fuserId, $strOrderList, $order = null)
    {
        global $USER, $DB, $APPLICATION;

        $totalPrice = BasketTable::getRow([
            'select'  =>
                [
                    'TOTAL_PRICE'
                ],
            'filter'  =>
                [
                    'FUSER_ID' => $fuserId,
                    'LID'      => SITE_ID,
                    'ORDER_ID' => null
                ],
            'runtime' =>
                [
                    new \Bitrix\Main\Entity\ExpressionField('TOTAL_PRICE', 'SUM(PRICE * QUANTITY)')
                ]
        ])['TOTAL_PRICE'];

        $moneySurcharge = 0;
        $surcharge = $this->getSurcharge();
        if ($surcharge) {
            $moneySurcharge = round($totalPrice * $surcharge / 100, 2);
            $totalPrice += $moneySurcharge;
        }
		
		// file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.txt', print_r([$totalPrice, $moneySurcharge], true) . PHP_EOL);
		
        $totalPrice = $totalPrice ?: 1;
        $vat = ($totalPrice / 100 * VAT_DEFAULT);
        
        // НДС уже включен в стоимость.
        if ($this->event['PROPS']['INCLUDE_VAT']['VALUE'] == 'Y') {
            $vat = 0;
        }
        
        $orderData = [
            "LID"              => SITE_ID,
            "PERSON_TYPE_ID"   => 1,
            "PAYED"            => "N",
            "CANCELED"         => "N",
            "STATUS_ID"        => "N",
            "DISCOUNT_VALUE"   => "", // $moneySurcharge,
            "USER_DESCRIPTION" => $_POST['orderDesc'],
            "PRICE"            => $totalPrice + $vat,
            "CURRENCY"         => $this->event['CURRENCY']['NAME'],
            "USER_ID"          => $USER->GetID(),
            "DELIVERY_ID"      => 1,
            "TAX_VALUE"        => $vat,
        ];
		
		// file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.txt', print_r($orderData, true), FILE_APPEND);

        if ($order) {
            $orderId = CSaleOrder::Update($order['ID'], $orderData);
        } else {
            $orderId = CSaleOrder::Add($orderData);
        }

        if (!$totalPrice) {
            OrderTable::update($orderId, ['PRICE' => 0]);
        }

        if ($orderId) {
            if (isset($_POST['orderParams']) && is_array($_POST['orderParams'])) {
                $obProps = CSaleOrderProps::GetList([], [
                    'CODE' => array_keys($_POST['orderParams'])
                ]);
                while ($arProp = $obProps->Fetch()) {
                    $arOrderProperties[$arProp['CODE']] = $arProp;
                }

                if ($order) {
                    $obOrderPropsValues = CSaleOrderPropsValue::GetList([],
                        [
                            "ORDER_ID" => $orderId
                        ]
                    );
                    while ($arValue = $obOrderPropsValues->Fetch()) {
                        CSaleOrderPropsValue::Delete($arValue['ID']);
                    }
                }
				
                // Параметры заказа.
                $params = $_POST['orderParams'];
				
                // Язык, на котором сделан заказ.
                $params['LANGUAGE'] = \Bitrix\Main\Context::getCurrent()->getLanguage();
				
				// Файл со скетчем.
				$file = array(
					'name'    	  => 'sketch-'.$orderId.'.jpg',
					'description' => 'Изображение скетча для заказа №'.$orderId,
					'content'     => base64_decode($params['SKETCH_IMAGE'])
				);
				$params['SKETCH_FILE'] = CFile::SaveFile($file, 'sketchs');
				unlink($filename);
				
				// Не сохраняем base64.
				unset($params['SKETCH_IMAGE']);
				
                // Наценка.
                $params['SURCHARGE'] = (float) $surcharge;
                $params['SURCHARGE_PRICE'] = (float) $moneySurcharge;
				
                foreach ($params as $code => $value) {
                    $res = OrderPropsValueTable::add([
                        'ORDER_ID'       => $orderId,
                        'ORDER_PROPS_ID' => $arOrderProperties[$code]['ID'],
                        'NAME'           => $arOrderProperties[$code]['NAME'] ?: $code,
                        'CODE'           => $code,
                        'VALUE'          => $value
                    ]);
                    if (!$res->isSuccess()) {
                        throw new Exception(join("<br>", $res->getErrorMessages()) . ' ' . $code);
                    }
                }
            }

            $managerEmail = '';
            $arEvent = [];
            if ($eventId = $_POST['orderParams']['eventId']) {
                $obElement = CIBlockElement::GetByID($eventId);
                $obEvent = $obElement->GetNextElement();
                $arEvent = $obEvent->GetFields();
                $arEvent['PROPS'] = $obEvent->GetProperties();
				
				$manager = CUser::getByID((int) $arEvent['PROPS']['MANAGER']['VALUE'])->Fetch();
				
				if ($manager) {
					$managerEmail = $manager['EMAIL'];
				}
				
				/*
				$managerIDs = array_filter((array) $arEvent['PROPS']['MANAGER']['VALUE']);
				if (!empty($managerIDs)) {
					$userres = CUser::getList(($b = 'ID'), ($o = 'ASC'), array('ID' => $managerIDs));
					$manageremails = array();
					while ($manager = $userres->Fetch()) {
						$manageremails []= $manager['EMAIL'];
					}
					$managerEmail = implode(', ', $manageremails);
				}
				*/
			}


            $arFields = [
                "ORDER_ID"      => $orderId,
                "ORDER_DATE"    => Date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT", SITE_ID))),
                "ORDER_USER"    => $USER->GetFormattedName(false),
                "PRICE"         => SaleFormatCurrency($totalPrice, $this->event['CURRENCY']['NAME']),
                "BCC"           => COption::GetOptionString("sale", "order_email", "order@"),
                "EMAIL"         => $USER->GetEmail(),
                "STAND_TYPE"    => $_POST['orderParams']['standType'],
                "WIDTH"         => $_POST['orderParams']['width'],
                "DEPTH"         => $_POST['orderParams']['depth'],
                "EVENT_NAME"    => $_POST['orderParams']['eventName'],
                "STAND_NUM"     => $_POST['orderParams']['standNum'],
                "PAVILLION"     => $_POST['orderParams']['pavillion'],
                "ORDER_LIST"    => $strOrderList,
                "MANAGER_EMAIL" => $managerEmail,
            ];
			
					
			$result = $this->orderBasket($orderId, $fuserId);
			
            if (is_null($order) && $result) {
                global $DB;
                
				/*
				$bSend = true;
                foreach (GetModuleEvents("sale", "OnOrderNewSendEmail", true) as $arEvent) {
                    if (ExecuteModuleEventEx($arEvent, [$orderId, &$eventName, &$arFields]) === false) {
                        // $bSend = false;
                    }
                }
				*/
				
				// Отправка письма о новом заказе клиенту.
				$html = $APPLICATION->IncludeComponent('wolk:mail.order', 'order-info', array('ID' => $arFields['ORDER_ID']));
				$event = new \CEvent();
				$event->Send('SALE_NEW_ORDER_CLIENT', SITE_DEFAULT, [
					'EMAIL' => $arFields['EMAIL'], 
					'HTML'  => $html,
					'THEME' => Loc::getMessage('THEME_NEW_ORDER')
				]);
				
				// Отправка письма о новом закази менеджеру.
				if (!empty($managerEmail)) {
					$html  = $APPLICATION->IncludeComponent('wolk:mail.order', 'order-info-manager', array('ID' => $arFields['ORDER_ID']));
					$event = new \CEvent();
					$event->Send('SALE_NEW_ORDER_MANAGER', SITE_DEFAULT, [
						'EMAIL' => $arFields['MANAGER_EMAIL'],
						'HTML'  => $html,
						'THEME' => Loc::getMessage('THEME_NEW_ORDER_MANAGER')
					]);
				}
			} else {
				// Отправка письма об изменении заказа клиенту.
				$html  = $APPLICATION->IncludeComponent('wolk:mail.order', 'order-change', array('ID' => $arFields['ORDER_ID']));
				$event = new \CEvent();
				$event->Send('SALE_UPDATE_ORDER', SITE_DEFAULT, [
					'EMAIL' => $arFields['EMAIL'],
					'HTML'  => $html,
					'THEME' => Loc::getMessage('THEME_UPDATE_ORDER')
				]);
				
				// Отправка письма об изменении заказа менеджеру.
				if (!empty($managerEmail)) {
					$html  = $APPLICATION->IncludeComponent('wolk:mail.order', 'order-change-manager', array('ID' => $arFields['ORDER_ID']));
					$event = new \CEvent();
					$event->Send('SALE_UPDATE_ORDER_MANAGER', SITE_DEFAULT, [
						'EMAIL' => $arFields['MANAGER_EMAIL'],
						'HTML'  => $html,
						'THEME' => Loc::getMessage('THEME_UPDATE_ORDER_MANAGER')
					]);
				}
            }
			
            return $result;
        } else {
            throw new Exception($APPLICATION->GetException()->GetString());
        }
    }

	
    protected function getSelectedServices($json, $eventServices)
    {
        try {
            return (\Bitrix\Main\Web\Json::decode($json));
        } catch (Exception $e) {
            return false;
        }
    }


    protected function getSelectedOptions($json, $eventOptions)
    {
        try {
            $res = \Bitrix\Main\Web\Json::decode($json);

            return $res;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $arStand
     * @param _CIBElement $event
     * @return bool
     */
    protected function getSelectedStand($json)
    {
        try {
            $stand = \Bitrix\Main\Web\Json::decode($json);
            if (isset($stand['ID'])) { // }, $stand['NAME'])) {
                return $stand;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    

    protected function getSelectedEquipment($json, $availableEquipment)
    {
        try {
            $eq = \Bitrix\Main\Web\Json::decode($json);
            $eq = ArrayHelper::index($eq, 'ID');
            $availableEquipment = ArrayHelper::index($availableEquipment, 'ID');

            return ArrayHelper::only($eq, array_keys($availableEquipment));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $id
     * @return _CIBElement|array
     */
    protected function getEventById($id)
    {
        $eventResult = \CIBlockElement::GetList([], [
            'ID'                                     => $id,
            "!PROPERTY_LANG_ACTIVE_{$this->curLang}" => false
        ]);

        return $eventResult->GetNextElement(false, false);
    }
	
	
	protected function loadStandPrices(_CIBElement $event)
    {
        $prices = \Wolk\OEM\EventStandPricesTable::getList([
            'filter' =>
                [
                    'EVENT_ID' => $event->GetFields()['ID'],
                    'SITE_ID'  => $this->curLang
                ]
        ])->fetchAll();

        foreach ($prices as $price) {
            $this->standPrices[$price['STAND_ID']] = $price['PRICE'];
        }
    }

	
    protected function loadEquipmentPrices(_CIBElement $event)
    {
        $prices = \Wolk\OEM\EventEquipmentPricesTable::getList([
            'filter' =>
                [
                    'EVENT_ID' => $event->GetFields()['ID'],
                    'SITE_ID'  => $this->curLang
                ]
        ])->fetchAll();

        foreach ($prices as $price) {
            $this->equipmentPrices[$price['EQUIPMENT_ID']] = $price['PRICE'];
        }
    }
	

    /**
     * @param _CIBElement $event
     * @param array $params
     * @return array
     */
    protected function getEventData(_CIBElement $event)
    {
        $arStands = [];
        $arEvent = $event->GetFields();
        $props = $event->GetProperties();
        $arEvent['PROPS'] = ArrayHelper::except($props, ['STANDS']);

		$this->loadStandPrices($event);
        $this->loadEquipmentPrices($event);

        if (!empty($props['STANDS']['VALUE'])) {
            $selectedArea = ceil($this->arParams['WIDTH'] * $this->arParams['DEPTH']);
			
			$standsIds = [];
			$standsOfferIds = [];
            $equipmentIds = [];
			
            $obStandOffers = CIBlockElement::getList(['PROPERTY_AREA_MAX' => 'DESC'], [
                'IBLOCK_ID'           => STANDS_OFFERS_IBLOCK_ID,
                'ACTIVE'              => 'Y',
                'PROPERTY_CML2_LINK'  => $props['STANDS']['VALUE'],
                '<=PROPERTY_AREA_MIN' => $selectedArea,
                '>=PROPERTY_AREA_MAX' => $selectedArea
            ]);
			
						
			while ($obStandOffer = $obStandOffers->GetNextElement(false, false)) {
                $arStandOffer = $obStandOffer->GetFields();
                $arStandOffer['PROPS'] = $obStandOffer->GetProperties(
                    [],
                    [
                        'CODE' =>
                            [
                                'CML2_LINK',
                                'EQUIPMENT'
                            ]
                    ]
                );
                $arStandOffers[$arStandOffer['PROPS']['CML2_LINK']['VALUE']] = $arStandOffer;
                $standsIds []= $arStandOffer['PROPS']['CML2_LINK']['VALUE'];
                if (is_array($arStandOffer['PROPS']['EQUIPMENT']['VALUE'])) {
                    $equipmentIds = array_merge($equipmentIds, $arStandOffer['PROPS']['EQUIPMENT']['VALUE']);
                }
				$standsOfferIds[$arStandOffer['ID']] = $arStandOffer['PROPS']['CML2_LINK']['VALUE'];
            }
			
			
			// Дополнение незагруженных стендов (по площади).
            if ($obStandOffers->SelectedRowsCount() < count($props['STANDS']['VALUE'])) {
                $obStandOffers = CIBlockElement::getList(['PROPERTY_AREA_MAX' => 'DESC'], [
                    'IBLOCK_ID'          => STANDS_OFFERS_IBLOCK_ID,
                    'ACTIVE'             => 'Y',
                    'PROPERTY_CML2_LINK' => $props['STANDS']['VALUE'],
					'!PROPERTY_CML2_LINK' => $standsOfferIds, // $props['STANDS']['VALUE'],
					// '!ID'				 => $standsOfferIds // WHY?!
                ]);
				
				while ($obStandOffer = $obStandOffers->GetNextElement(false, false)) {
					$arStandOffer = $obStandOffer->GetFields();
					$arStandOffer['PROPS'] = $obStandOffer->GetProperties(
						[],
						[
							'CODE' =>
								[
									'CML2_LINK',
									'EQUIPMENT'
								]
						]
					);
					
					//if (!in_array($arStandOffer['PROPS']['CML2_LINK']['VALUE'], $standsIds)) {
					if (!array_key_exists($arStandOffer['ID'], $standsOfferIds)) {
						$arStandOffers[$arStandOffer['PROPS']['CML2_LINK']['VALUE']] = $arStandOffer;
						$standsIds []= $arStandOffer['PROPS']['CML2_LINK']['VALUE'];
						if (is_array($arStandOffer['PROPS']['EQUIPMENT']['VALUE'])) {
							$equipmentIds = array_merge($equipmentIds, $arStandOffer['PROPS']['EQUIPMENT']['VALUE']);
						}
						$standsOfferIds[$arStandOffer['ID']] = $arStandOffer['PROPS']['CML2_LINK']['VALUE'];
					}
				}
            }
			
            if (!empty($standsIds)) {
                $obEquipment = CIBlockElement::GetList(
					[],
					[
						'IBLOCK_ID' => EQUIPMENT_IBLOCK_ID,
						'ACTIVE'    => 'Y',
						'ID'        => $equipmentIds
					],
					false,
					false,
					['ID', 'IBLOCK_ID', 'NAME', 'PREVIEW_PICTURE', 'CATALOG_GROUP_1', 'SORT']
				);
				
                while ($obEquipmentItem = $obEquipment->GetNextElement()) {
                    $equipmentItem = $obEquipmentItem->GetFields();
                    $equipmentItem['PROPS'] = $obEquipmentItem->GetProperties();
                    $equipmentItem['PREVIEW_PICTURE_BIG'] = CFile::ResizeImageGet(
                        $equipmentItem['PREVIEW_PICTURE'], ['width' => 420, 'height' => 270],
                        BX_RESIZE_IMAGE_PROPORTIONAL
                    )['src'];
                    $equipmentItem['PREVIEW_PICTURE_SMALL'] = CFile::ResizeImageGet(
                        $equipmentItem['PREVIEW_PICTURE'], ['width' => 320, 'height' => 210],
                        BX_RESIZE_IMAGE_PROPORTIONAL
                    )['src'];

                    $width = ($equipmentItem['PROPS']['WIDTH']['VALUE'] / 10) <= 5 ? 5 : $equipmentItem['PROPS']['WIDTH']['VALUE'] / 10;
                    $height = ($equipmentItem['PROPS']['HEIGHT']['VALUE'] / 10) <= 5 ? 5 : $equipmentItem['PROPS']['HEIGHT']['VALUE'] / 10;
                    $src = CFile::GetPath($equipmentItem['PROPS']['SKETCH_IMAGE']['VALUE']);

                    list(
                        $equipmentItem['WIDTH'],
                        $equipmentItem['HEIGHT'],
                        $equipmentItem['SKETCH_IMAGE'],
                        $equipmentItem['SKETCH_TYPE'],
                        ) = [
                        $equipmentItem['PROPS']['WIDTH']['VALUE'] / 1000,
                        $equipmentItem['PROPS']['HEIGHT']['VALUE'] / 1000,
                        $src ? "/i.php?w={$width}&h={$height}&src={$src}&zc=0" : false,
                        $equipmentItem['PROPS']['SKETCH_TYPE']['VALUE'] ?: 'droppable'
                    ];

                    $equipmentItem['PRICE'] = isset($this->equipmentPrices[$equipmentItem['ID']])
                        ? $this->equipmentPrices[$equipmentItem['ID']]
                        : 0;

                    $equipmentItem['NAME'] = $equipmentItem['PROPS']['LANG_TITLE_' . $this->curLang]['VALUE'] ?: $equipmentItem['NAME'];

                    $arEvent['ALL_SERVICES'][$equipmentItem['ID']] = $equipmentItem;

                    $arEquipment[$equipmentItem['ID']] = $equipmentItem;
                }
				
				
                foreach ($arStandOffers as &$arStandOffer) {
                    foreach ($arStandOffer['PROPS']['EQUIPMENT']['VALUE'] as $num => $val) {
                        if (array_key_exists($val, $arEquipment)) {
                            $arStandOffer['EQUIPMENT'][] = $arEquipment[$val] + ['COUNT' => $arStandOffer['PROPS']['EQUIPMENT']['DESCRIPTION'][$num]];
                        }
                    }
                }
                unset($arStandOffer);
				
                $obStands = CIBlockElement::GetList([], [
                    'IBLOCK_ID'     => STANDS_IBLOCK_ID,
                    'ACTIVE'        => 'Y',
                    'PROPERTY_TYPE' => $this->arParams['TYPE'],
                    'ID'            => $standsIds
                ]);
				
                while ($obStand = $obStands->GetNextElement(false, false)) {
                    $arStand = $obStand->GetFields();
                    $arStand['PROPS'] = $obStand->GetProperties();
					
                    $arStand['PREVIEW_PICTURE'] = CFile::ResizeImageGet(
                        $arStand['PREVIEW_PICTURE'], ['width' => 420, 'height' => 270], BX_RESIZE_IMAGE_EXACT
                    )['src'];
	
					$arStand['PRICE'] = $arStand['BASE_PRICE'] = ['PRICE' => 0];
					
					// Базовый стенд считается по нулевой цене.
					if ($arEvent['PROPS']['PRESELECT']['VALUE'] == $arStand['ID']) {
						$arStand['PRICE']['PRICE'] = 0;
					} else {
						$arStand['PRICE'] = $arStand['BASE_PRICE'] = isset($this->standPrices[$arStand['ID']])
							? ['PRICE' => $this->standPrices[$arStand['ID']]]
							: CPrice::GetBasePrice($arStand['ID']);
						
						$arStand['PRICE']['PRICE'] = $this->calcStandPrice(
							$arStand['BASE_PRICE']['PRICE'],
							$this->arParams['WIDTH'] ?: $this->arResult['ORDER']['PROPS']['width']['VALUE'],
							$this->arParams['DEPTH'] ?: $this->arResult['ORDER']['PROPS']['depth']['VALUE']
						);
					}
					
					$arStand['PRICE']['PRICE'] = (float) $arStand['PRICE']['PRICE'];
					
                    $arStand['PROPS'] = $obStand->GetProperties();
                    $arStand['OFFER'] = $arStandOffers[$arStand['ID']];
                    $arStand['NAME']  = $arStand['PROPS']['LANG_NAME_'.$this->curLang]['VALUE'] ?: $arStand['NAME'];
                    
					$arStands[$arStand['ID']] = $arStand;
                }
            }
        }
        $arEvent['STANDS'] = $arStands;
		
		if (isset($_GET['dbg'])) {
			echo (count($arStands));
		}
		
        if ($eventCurrency = $arEvent['PROPS']['LANG_CURRENCY_' . $this->curLang]['VALUE']) {
            $currencyFormat = \Bitrix\Currency\CurrencyLangTable::getById([
                'CURRENCY' => $eventCurrency,
                'LID'      => $this->curLang
            ])->fetch();

            $arEvent['CURRENCY'] = [
                'NAME'   => $eventCurrency,
                'FORMAT' => $currencyFormat['FORMAT_STRING']
            ];
        }
        return $arEvent;
    }

	
	
    protected function getSurcharge($time = null)
    {
        if (is_null($time)) {
			// Наценка за подний заказ должна считаться со дня следующим за крайней датой.
            $time = strtotime('-1 day'); // time();
        }
		$dates = $this->event['PROPS']['MARGIN_DATES']['VALUE'];
		
        if (!empty($dates) && is_array($dates)) {
            $activeIndex = null;
            foreach ($dates as $n => $date) {
                if (strtotime($date) <= $time && (is_null($activeIndex) || strtotime($date) > $dates[$activeIndex])) {
                    $activeIndex = $n;
                }
            }
            return $this->event['PROPS']['MARGIN_DATES']['DESCRIPTION'][$activeIndex];
        }
        return 0;
    }

	
    /**
     * @param $orderId
     * @param $fuserId
     * @throws Exception
     * @throws \Bitrix\Main\ArgumentException
     */
    protected function orderBasket($orderId, $fuserId)
    {
		//return CSaleBasket::OrderBasket($orderId, $fuserId, SITE_ID);
		
        $basketItems = BasketTable::getList([
            'filter' =>
                [
                    'FUSER_ID' => $fuserId,
                    'ORDER_ID' => null
                ]
        ])->fetchAll();

        foreach ($basketItems as $basketItem) {
            $r = BasketTable::update($basketItem['ID'], ['ORDER_ID' => $orderId]);
            if (!$r->isSuccess()) {
                return false;
            }
        }
		/*  */
		
        return true;
    }

	
    public function getOrder($id)
    {
        global $USER;
        $filter = [
            'USER_ID' => $USER->GetID(),
            'ID'         => $id,
            // 'STATUS_ID'  => 'N'
        ];
        if ($USER->IsAdmin()) {
            unset($filter['CREATED_BY']);
        }
        $order = OrderTable::getRow([
            'filter' => $filter
        ]);

        if ($order) {
            $items = BasketTable::getList([
                'filter' =>
                    [
                        'ORDER_ID' => $order['ID']
                    ]
            ])->fetchAll();
            $items = ArrayHelper::index($items, 'ID');

            $props = BasketPropertyTable::getList([
                'filter' =>
                    [
                        'BASKET_ID' => array_keys($items)
                    ]
            ])->fetchAll();
            foreach ($props as $prop) {
                $newProps[$prop['BASKET_ID']][] = $prop;
            }
			
			
            foreach ($items as &$item) {
                $item['PRICE_FORMATTED'] = CurrencyFormat($item['PRICE'], $order['CURRENCY']);
                if ($item['TYPE'] == 1 && $item['RECOMMENDATION'] == 'PRODUCT.BASE') {
                    $item['COST'] = $item['PRICE'] * $item['QUANTITY'] - $item['COUNT'];
                } else {
                    $item['COST'] = $item['PRICE'] * $item['QUANTITY'];
                }

                $item['COST_FORMATTED'] = CurrencyFormat($item['COST'], $order['CURRENCY']);
                $item['ELEMENT'] = \Bitrix\Iblock\ElementTable::getRowById($item['PRODUCT_ID']);
                $item['ROOT_SECTION'] = CIBlockSection::GetNavChain(
                    $item['ELEMENT']['IBLOCK_ID'],
                    $item['ELEMENT']['IBLOCK_SECTION_ID']
                )->Fetch();
                $item['PROPS'] = ArrayHelper::map($newProps[$item['ID']], 'CODE', 'VALUE');
            }
            unset($item);

            $orderProps = OrderPropsValueTable::getList([
                'filter' =>
                    [
                        'ORDER_ID' => $order['ID']
                    ]
            ])->fetchAll();
            $orderProps = ArrayHelper::index($orderProps, 'CODE');

            $newItems = [];
            foreach ($items as $itemId => $item) {
                if ($item['TYPE'] == 1 && $item['RECOMMENDATION'] == 'PRODUCT.SALE') {
                    $newItems['selectedStand'] = $item;
                } elseif ($item['TYPE'] == 1 && $item['RECOMMENDATION'] == 'PRODUCT.BASE') {
                    $newItems['EQUIPMENT'][$item['PRODUCT_ID']] = $item;
                } elseif ($item['ROOT_SECTION']['ID'] == self::SERVICES_SECTION_ID) {
                    if (isset($item['PROPS']['dateStart'], $item['PROPS']['dateEnd'])) {
                        $item['PROPS']['dates']['dateStart'] = $item['PROPS']['dateStart'];
                        $item['PROPS']['dates']['dateEnd'] = $item['PROPS']['dateEnd'];
                        unset($item['PROPS']['dateStart'], $item['PROPS']['dateEnd']);
                    }
                    $newItems['SERVICES'][$item['ELEMENT']['IBLOCK_SECTION_ID']][] = ArrayHelper::only($item, [
                            'NAME',
                            'PRODUCT_ID',
                            'PRICE',
                            'COST',
                            'PRICE_FORMATTED',
                            'COST_FORMATTED',
                        ]) + ['ID' => $item['ELEMENT']['ID']] + $item['PROPS'] + ['QUANTITY' => intval($item['QUANTITY'])];

                } elseif ($item['ROOT_SECTION']['ID'] == self::OPTIONS_SECTION_ID) {
                    $newItems['OPTIONS'][$item['ELEMENT']['IBLOCK_SECTION_ID']][$item['ELEMENT']['ID']] = ArrayHelper::only($item,
                            [
                                'NAME',
                                'PRODUCT_ID',
                                'PRICE',
                                'COST',
                                'PRICE_FORMATTED',
                                'COST_FORMATTED',
                                'PROPS'
                            ]) + ['ID' => $item['ELEMENT']['ID']] + ['QUANTITY' => intval($item['QUANTITY'])];
                } else {
                    $newItems['EQUIPMENT'][$item['PRODUCT_ID']]['QUANTITY'] += $item['QUANTITY'];
                }
            }
            $newItems['selectedStand']['SERVICES'] = $newItems['SERVICES'];
            $newItems['selectedStand']['OPTIONS'] = $newItems['OPTIONS'];
            $newItems['selectedStand']['EQUIPMENT'] = $newItems['EQUIPMENT'];
            unset($newItems['SERVICES'], $newItems['OPTIONS'], $newItems['EQUIPMENT']);

            if ($orderProps['eventId']) {
                $event = \Bitrix\Iblock\ElementTable::getRowById($orderProps['eventId']['VALUE']);
            }

            $result = $newItems;
            $result['PROPS'] = $orderProps;
            $result['ID'] = $id;
            $result['CURRENCY'] = $order['CURRENCY'];
            $result['taxPrice'] = $order['TAX_VALUE'];
            $result['totalPrice'] = $order['PRICE'] - $order['TAX_VALUE'];
            $result['totalTaxPrice'] = $order['PRICE'];
            $result['status'] = $order['STATUS_ID'];
            $result['TOTAL_PRICE_FORMATTED'] = CurrencyFormat($order['PRICE'] - $order['TAX_VALUE'], $order['CURRENCY']);
            $result['TOTAL_PRICE_TAX_FORMATTED'] = CurrencyFormat($order['PRICE'], $order['CURRENCY']);
            if (isset($event)) {
                $result['curEvent'] = $event;
            }

            $data = [];
            $data['ORDER'] = CSaleOrder::getByID($id);
            $data['PROPS'] = Wolk\Core\Helpers\SaleOrder::getProperties($id);
            $data['BASKETS'] = Wolk\Core\Helpers\SaleOrder::getBaskets($id);

			$result['ORDER_DATA'] = $data['ORDER'];
			
            $basket_price = 0;
            foreach ($data['BASKETS'] as $basket) {
                if ($basket['SUMMARY_PRICE'] > 0) {
                    $basket_price += $basket['SUMMARY_PRICE'];
                }
            }

            $surcharge = (float) $data['PROPS']['SURCHARGE_PRICE']['VALUE_ORIG'];

            $result['PRICES'] = [
                'BASKET'               => CurrencyFormat($basket_price, $order['CURRENCY']),
                'VAT'                  => CurrencyFormat($order['TAX_VALUE'], $order['CURRENCY']),
                'TOTAL_WITH_VAT'       => CurrencyFormat($order['PRICE'] - $surcharge, $order['CURRENCY']),
                'TOTAL_WITH_SURCHARGE' => CurrencyFormat($order['PRICE'], $order['CURRENCY']),
            ];

            if ($surcharge > 0) {
                $result['PRICES']['SURCHARGE'] = $data['PROPS']['SURCHARGE']['VALUE_ORIG'];
                $result['PRICES']['SURCHARGE_PRICE'] = CurrencyFormat($surcharge, $order['CURRENCY']);
            }
            return $result;
        }
        return false;
    }

    /**
     * @param float $sqMPrice
     * @param int $width
     * @param int $depth
     *
     * return float
     */
    protected function calcStandPrice($sqMPrice, $width, $depth)
    {
        return $sqMPrice * $width * $depth;
    }

    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getColors()
    {
        Loader::includeModule('highloadblock');
        $hlblock = HighloadBlockTable::getById(COLORS_ENTITY_ID)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $colorsClass = $entity->getDataClass();

        return $colorsClass::getList(['order' => ['UF_NUM' => 'ASC']])->fetchAll();
    }

    protected function getLogoExtents()
    {
        Loader::includeModule('highloadblock');
        $hlblock = HighloadBlockTable::getById(EXTENTS_ENTITY_ID)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $colorsClass = $entity->getDataClass();

        return $colorsClass::getList(['order' => ['UF_SORT' => 'ASC']])->fetchAll();
    }

    protected function getEquipmentColors()
    {
        Loader::includeModule('highloadblock');
        $hlblock = HighloadBlockTable::getById(EQUIPMENT_COLORS_ENTITY_ID)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $colorsClass = $entity->getDataClass();

        return ArrayHelper::index($colorsClass::getList()->fetchAll(), 'UF_XML_ID');
    }
}
