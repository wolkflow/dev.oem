<?php

namespace Glyf\Core\System;

use Bitrix\Main\Application;
use Bitrix\Main\Context;


class Environment
{
	/**
     * Признак нахождения в административном разделе.
     */
    public static function isAdminSection()
    {
        return (defined('ADMIN_SECTION') && ADMIN_SECTION);
    }
}