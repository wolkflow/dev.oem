<?php

namespace Wolk\Core\Helpers;

class Currency
{
    const LANGUAGE_RU = 'ru';
    const LANGUAGE_EN = 'en';
    
    const CURRENCY_RUB = 'RUB';
    const CURRENCY_EUR = 'EUR';
    
    
	public function getCurrencySymbol($currency, $language, $userub = true)
    {
        if (!\Bitrix\Main\Loader::includeModule('currency')) {
            return;
        }
        
        // Обработка символа рубля для PDF.
        if (!$userub) {
            if ($currency == self::CURRENCY_RUB) {
                return (($language == self::LANGUAGE_RU) ? ('руб.') : ('rub.'));
            }
        }
        
        $result = \CCurrencyLang::GetByID($currency, $language);
        $symbol = trim(str_replace('#', '', $result['FORMAT_STRING']));
        
        return $symbol;
    }
}