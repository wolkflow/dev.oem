<?php

namespace Wolk\Core\System\Migrations;

/**
 *
 * Если в массиве не был передан SORT, то берется максимальное значения SORT + 1
 *
 * $car = new SiteTemplate("CP-XXXX", "Добавляет новый шаблон",
 * [
 *   'SITE_ID'   => LID_CARPRICE,
 *   'TEMPLATE'  => "landing_php_developer_search",
 *   'CONDITION' => "CSite::InDir('/job/dev/')"
 * ]);
 * $car->add();
 *
 */

/**
 * Класс для добавления нового шаблона на сайт
 * Class SiteTemplate
 * @package Linemedia\Carsale\Migrations
 */
class SiteTemplate extends UpdateTool
{
    /**
     * Инициализирует объект, задаёт набор полей, выводит границу начала задачи
     *
     * @param string    $task_id идентификатор задачи
     * @param string    $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param array     $arFields набор полей свойства элемента инфоблока
     *
     * @link http://dev.1c-bitrix.ru/api_help/iblock/fields.php#fproperty
     */
    public function __construct($task_id, $migration_description, $arFields = [])
	{
        parent::__construct($task_id, $migration_description, $arFields);
    }

    /**
     * Добавляет шаблон в БД
     *
     * @param bool|false $exit_on_success
     * @param bool|false $exit_if_exists
     * @param bool|true $exit_on_fail
     */
    public function add($exit_on_success = false, $exit_if_exists = false, $exit_on_fail = true)
    {
        $templates = $this->getTemplates();

        if (empty($templates)) {
            $this->fail("Отсутствуют шаблоны", $exit_on_fail);
        }

        if ($this->isExistTemplate($this->fields['CONDITION'], $templates)) {
            $error = sprintf("Шаблон %s был добавлен ранее", $this->fields['TEMPLATE']);
            $this->fail($error, $exit_on_fail);
        }

        $sort = (empty($this->fields['SORT'])) ? $this->getMaxSortTemplates() + 1 : $this->fields['SORT'];

        $templates[] = [
            'TEMPLATE' => $this->fields['TEMPLATE'],
            'SORT' => $sort,
            'CONDITION' => $this->fields['CONDITION']
        ];

        $langs = new \CLang();
        $langs->Update($this->fields['SITE_ID'], ['TEMPLATE' => $templates]);

        $success = sprintf("Шаблон %s успешно добавлен", $this->fields['TEMPLATE']);
        $this->success($success, $exit_on_success);
    }

    /**
     *
     * Проверяет существует ли шаблон по CONDITION
     * 'CONDITION' => "CSite::InDir('/job/dev/')"
     *
     * @param $condition
     * @param $templates
     * @return bool
     */
    public function isExistTemplate($condition, $templates)
    {
        $is_exist_template = false;

        foreach ($templates as $template) {
            if (in_array($condition, $template)) {
                $is_exist_template = true;
                break;
            }
        }
        return $is_exist_template;
    }
	

    /**
     * Возвращает шаблоны для сайта
     * @return array
     */
    public function getTemplates()
    {
        $rs_templates = \CSite::GetTemplateList($this->fields['SITE_ID']);
        $templates = [];

        while ($template = $rs_templates->Fetch()) {
            unset($template['ID']);
            unset($template['SITE_ID']);
            $templates[] = $template;
        }
        return $templates;
    }
	

    /**
     * Возвращает максимальное значения SORT у шаблонов сайта
     * @return int
     */
    public function getMaxSortTemplates() {
        $max_sort = 0;

        foreach ($this->getTemplates() as $template) {
            if($template['SORT'] > $max_sort) {
                $max_sort = $template['SORT'];
            }
        }
        return $max_sort;
    }
}