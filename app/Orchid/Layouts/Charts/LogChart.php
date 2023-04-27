<?php

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

class LogChart extends Chart
{
    protected $title = "Популярність по командам";

    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'bar';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;

    protected $target = "logCommandsChart";
}
