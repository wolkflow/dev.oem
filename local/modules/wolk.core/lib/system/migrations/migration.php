<?php
/**
 * @author Smotrov Dmitriy <smotrov@worksolutions.ru>
 */

namespace Wolk\Core\System\Migrations;

/**
 * Class Migration
 * @package Linemedia\Carsale\Migrations
 */
class Migration
{
    protected $name;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Выполнить функцию миграции
     *
     * @param callable $callback
     * @return string
     * @throws Exception
     */
    public function apply($callback)
    {
        $output = new ConsoleOutput();

        $output->clearBuffer();
        $output->writeLine("Выполнение миграции: {$this->name}");

        try {
            $start = time();
            $return = call_user_func($callback, $output);

            if ($return === false) {
                $output->writeLine("Миграция вернула false.");
            }

            if (is_string($return)) {
                $output->writeLine($return);
            }

            if ($return === true || is_null($return)) {
                $output->writeLine("Миграция удачно применена");
            }

            $output->writeSummary($start);

            $output->writeLine();

        } catch (\Exception $e) {
            $output->writeLine(sprintf(
                "При выполнении миграции произошла исключительная ситуация\n %s: %s\n%s\n\n",
                get_class($e),
                $e->getMessage(),
                $e->getTraceAsString()
            ));

            exit(1);
        }

        return $this;
    }


    /**
     * Добавить сообщение для вывода результата выполнения миграции
     *
     * @param $text
     * @return mixed
     * @deprecated use ConsoleOutput->writeLine() instead
     */
    public function addMessage($text = "")
    {

    }
}
