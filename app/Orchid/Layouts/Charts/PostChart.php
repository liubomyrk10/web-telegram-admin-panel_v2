<?php

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

class PostChart extends Chart
{
    protected $title = "Історія додавання постів";

    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'line';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;

    protected $target = "postChart";
}
