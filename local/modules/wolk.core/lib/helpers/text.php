<?php

namespace Wolk\Core\Helpers;

use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);


/**
 * Вспомогательный класс для работы с текстом.
 */
class Text
{	
	const DEFAULT_ENCODING = 'UTF-8';



	/**
	 * Склонение числительных.
	 * 
	 * @param int $number
	 * @param array $titles
	 * @param bool $titles
	 * @return string
	 */
	public static function decofnum($number, $titles, $include = true)
	{
		$cases  = array(2, 0, 1, 1, 1, 2);
		$result = $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
		
		if ($include) {
			$result = $number.' '.$result;
		}
		return $result;
	}
	
	
	/**
	 * Расстановка неразрывных пробелов.
	 *
	 * @param string $text
	 * @return string
	 */
	public static function setNBSP($text)
	{
		$text = preg_replace('/([^а-яА-Яa-zA-Z0-9><])([а-яА-Яa-zA-Z0-9]{1,2})\s+/iu', '$1$2&nbsp;', (string) $text);
		
		return $text;
	}
	
	
	/**
	 * Обрезание текста.
	 * 
	 * @param string $text
	 * @param int $count
	 * @return string
	 */
	public static function crop($text, $count, $stops = false)
	{
		if (mb_strlen($text) <= $count) {
			return $text;
		}
		$text  = mb_substr($text, 0, $count, self::DEFAULT_ENCODING);
		$index = mb_strrpos($text, ' ', self::DEFAULT_ENCODING);
		$text  = mb_substr($text, 0, $index, self::DEFAULT_ENCODING);
		
		if ($stops) {
			$text .= '...';
		}
		return $text;
	}
	
	
	/**
	 * Наименование дней на русском языке.
	 * 
	 * @param int $index
	 * @return string
	 */
	public static function i18nday($index)
	{
		$days = array(
			0  => Loc::getMessage('DAY_SUN'),
			1  => Loc::getMessage('DAY_MON'),
			2  => Loc::getMessage('DAY_TUE'),
			3  => Loc::getMessage('DAY_WEN'),
			4  => Loc::getMessage('DAY_THU'),
			5  => Loc::getMessage('DAY_FRI'),
			6  => Loc::getMessage('DAY_SAT'),
			7  => Loc::getMessage('DAY_SUN'),
		);
		return $days[$index];
	}
	
	
	/**
	 * Наименование месяцев на русском языке.
	 * 
	 * @param int $index
	 * @param bool $nominative
	 * @return string
	 */
	function i18nmonth($index, $nominative = true)
	{
		if ($nominative) {
			$months = array(
				1  => Loc::getMessage('MONTH_JAN'),
				2  => Loc::getMessage('MONTH_FEB'),
				3  => Loc::getMessage('MONTH_MAR'),
				4  => Loc::getMessage('MONTH_APR'),
				5  => Loc::getMessage('MONTH_MAY'),
				6  => Loc::getMessage('MONTH_JUN'),
				7  => Loc::getMessage('MONTH_JUL'),
				8  => Loc::getMessage('MONTH_AUG'),
				9  => Loc::getMessage('MONTH_SEP'),
				10 => Loc::getMessage('MONTH_OCT'),
				11 => Loc::getMessage('MONTH_NOV'),
				12 => Loc::getMessage('MONTH_DEC'),
			);
		} else {
			$months = array(
				1  => Loc::getMessage('MONTHS_JAN'),
				2  => Loc::getMessage('MONTHS_FEB'),
				3  => Loc::getMessage('MONTHS_MAR'),
				4  => Loc::getMessage('MONTHS_APR'),
				5  => Loc::getMessage('MONTHS_MAY'),
				6  => Loc::getMessage('MONTHS_JUN'),
				7  => Loc::getMessage('MONTHS_JUL'),
				8  => Loc::getMessage('MONTHS_AUG'),
				9  => Loc::getMessage('MONTHS_SEP'),
				10 => Loc::getMessage('MONTHS_OCT'),
				11 => Loc::getMessage('MONTHS_NOV'),
				12 => Loc::getMessage('MONTHS_DEC'),
			);
		}
		
		return $months[$index];
	}
	
	
	/**
     * Транслит.
     *
     * @param string $string
	 * @param bool $direct
     * @return string
     */
    public static function translit2rus($string, $direct = true)
    {
        $letters = [
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'yo',  'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'j',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'x',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'shh',
            'ь' => "'",   'ы' => 'y',   'ъ' => "''",
            'э' => "e'",  'ю' => 'yu',  'я' => 'ya',
            'ый' => 'yy',
            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'YO',  'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'J',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'X',   'Ц' => 'C',
            'Ч' => 'CH',  'Ш' => 'SH',  'Щ' => 'SHH',
            'Ь' => "'",   'Ы' => "Y'",  'Ъ' => "''",
            'Э' => "E'",  'Ю' => 'YU',  'Я' => 'YA',
            'ЫЙ' => 'YY',
        ];
		
		if ($direct) {
			$result = strtr($string, $letters);
		} else {
			$result = strtr($string, array_flip($letters));
		}
		
        return $result;
    }
	
	
	
	/**
	 * Замена в мультибайтовых строках.
	 */
	public static function mbStrReplace($needle, $replacement, $haystack)
	{
		return implode($replacement, mb_split($needle, $haystack));
	}


	/**
	 * Удаление пробела в мультибайтовых строках.
	 */
    public static function mbTrim($string)
	{
        return preg_replace('/(^\s+)|(\s+$)/us', '', $string);
    }
	
	
	/**
	 * Запуск командной строки.
	 *
	 * @param string $command
	 * @return string
	 */
	public static function mb_ucfirst($string, $encode = 'utf-8')
	{ 
		return mb_strtoupper(mb_substr($string, 0, 1, $encode), $encode).mb_substr($string, 1, mb_strlen($string, $encode), $encode); 
	}
	
	
	
	/**
	 * Перевод текста с помощью сервиса yandex.ru
	 *
	 * @param string $text
	 * @param string $lang
	 * @return string
	 */
	public static function translate($text, $lang = 'en')
    {
    	static $cache = array();
		
    	$ckey = md5($text);
    	if (isset($cache[$ckey])) {
    		return $cache[$ckey];
    	}
    	
		// Ключ.
	    $key = ''; // 'trnsl.1.1.20131113T122756Z.c71cf1d1c8113a23.8b08b474def94672e8de2f37b675252ec2ead2b2';
	    
		// Кодирование текста.
	    $text = urlencode($text);
	    
		// Ссылка для запроса в Яндекс.
	    $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=$key&lang=$lang&text=$text";
	    
		// Получение ответа.
		$response = file_get_contents($url);
		$response = json_decode($response, 1);
		
		$cache[$ckey] = $response['text'][0];
		
		return $response['text'][0];
    }
	
	
	
	public static function num2str($num)
	{
		$nul='ноль';
		$ten=array(
			array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
			array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
		);
		$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
		$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
		$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
		$unit=array( // Units
			array('копейка' ,'копейки' ,'копеек',	 1),
			array('рубль'   ,'рубля'   ,'рублей'    ,0),
			array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
			array('миллион' ,'миллиона','миллионов' ,0),
			array('миллиард','милиарда','миллиардов',0),
		);
		//
		list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
		$out = array();
		if (intval($rub)>0) {
			foreach (str_split($rub,3) as $uk => $v) { // by 3 symbols
				if (!intval($v)) {
					continue;
				}
				$uk = sizeof($unit)-$uk-1; // unit key
				$gender = $unit[$uk][3];
				list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				if ($i2 > 1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
				else $out[]= $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
				// units without rub & kop
				if ($uk > 1) $out[]= self::decofnum($v, array($unit[$uk][0], $unit[$uk][1], $unit[$uk][2]), false);
			} //foreach
		} else {
			$out []= $nul;
		}
		// $out[] = self::decofnum(intval($rub), array($unit[1][0],$unit[1][1],$unit[1][2]), false); // rub
		// $out[] = $kop.' '.self::decofnum($kop, array($$unit[0][0],$unit[0][1],$unit[0][2]), false); // kop
		
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}
	
}
