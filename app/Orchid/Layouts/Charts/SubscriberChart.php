<?php

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

class SubscriberChart extends Chart
{
    protected $title = "Динаміка підписок";

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

    protected $target = "subscriberDynamicChart";
}
