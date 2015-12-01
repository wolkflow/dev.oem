<?php

namespace Wolk\Core\Heplers;

// TODO: Include module lang.
include(GetLangFileName(dirname(dirname(dirname(__FILE__)))."/", "/classes/helpers/text.php"));


/**
 * Вспомогательный класс для работы с текстом.
 */
class Text
{	
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
		$text  = mb_substr($text, 0, $count, 'UTF-8');
		$index = mb_strrpos($text, ' ', 'UTF-8');
		$text  = mb_substr($text, 0, $index, 'UTF-8');
		
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
			0  => GetMessage('DAY_SUN'),
			1  => GetMessage('DAY_MON'),
			2  => GetMessage('DAY_TUE'),
			3  => GetMessage('DAY_WEN'),
			4  => GetMessage('DAY_THU'),
			5  => GetMessage('DAY_FRI'),
			6  => GetMessage('DAY_SAT'),
			7  => GetMessage('DAY_SUN'),
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
				1  => GetMessage('MONTH_JAN'),
				2  => GetMessage('MONTH_FEB'),
				3  => GetMessage('MONTH_MAR'),
				4  => GetMessage('MONTH_APR'),
				5  => GetMessage('MONTH_MAY'),
				6  => GetMessage('MONTH_JUN'),
				7  => GetMessage('MONTH_JUL'),
				8  => GetMessage('MONTH_AUG'),
				9  => GetMessage('MONTH_SEP'),
				10 => GetMessage('MONTH_OCT'),
				11 => GetMessage('MONTH_NOV'),
				12 => GetMessage('MONTH_DEC'),
			);
		} else {
			$months = array(
				1  => GetMessage('MONTHS_JAN'),
				2  => GetMessage('MONTHS_FEB'),
				3  => GetMessage('MONTHS_MAR'),
				4  => GetMessage('MONTHS_APR'),
				5  => GetMessage('MONTHS_MAY'),
				6  => GetMessage('MONTHS_JUN'),
				7  => GetMessage('MONTHS_JUL'),
				8  => GetMessage('MONTHS_AUG'),
				9  => GetMessage('MONTHS_SEP'),
				10 => GetMessage('MONTHS_OCT'),
				11 => GetMessage('MONTHS_NOV'),
				12 => GetMessage('MONTHS_DEC'),
			);
		}
		return $months[$index];
	}
	
}