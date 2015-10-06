<?php

namespace CMEN\GoogleChartsBundle\GoogleCharts\Options\BarChart;

use CMEN\GoogleChartsBundle\GoogleCharts\Options\AdvancedAnimation;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\AdvancedChartOptions;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\AdvancedTooltip;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\Bar;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\Explorer;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\AdvancedLegend;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\MediumHAxis;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\AdvancedAnnotations;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\Tooltip;

/**
 * @author Christophe Meneses
 */
class BarChartOptions extends AdvancedChartOptions
{
    /**
     * @var AdvancedAnimation
     */
    protected $animation;

    /**
     * @var AdvancedAnnotations
     */
    protected $annotations;

    /**
     * @var Bar
     */
    protected $bar;

    /**
     * Whether the bars in a Material Bar Chart are vertical or horizontal. This option has no effect on Classic Bar
     * Charts or Classic Column Charts.
     *
     * Values : 'horizontal' or 'vertical'
     *
     * @var string
     */
    protected $bars;

    /**
     * The transparency of data points, with 1.0 being completely opaque and 0.0 fully transparent. In scatter,
     * histogram, bar, and column charts, this refers to the visible data: dots in the scatter chart and rectangles
     * in the others. In charts where selecting data creates a dot, such as the line and area charts, this refers to
     * the circles that appear upon hover or selection. The combo chart exhibits both behaviors, and this option has
     * no effect on other charts. (To change the opacity of a trendline, see
     * {@link https://developers.google.com/chart/interactive/docs/gallery/trendlines#Example4})
     *
     * @var float
     */
    protected $dataOpacity;

    /**
     * @var Explorer
     */
    protected $explorer;

    /**
     *  The type of the entity that receives focus on mouse hover. Also affects which entity is selected by mouse
     * click, and which data table element is associated with events. Can be one of the following :
     * 'datum' - Focus on a single data point. Correlates to a cell in the data table.
     * 'category' - Focus on a grouping of all data points along the major axis. Correlates to a row in the data table.
     *
     * In focusTarget 'category' the tooltip displays all the category values. This may be useful for comparing values
     * of different series.
     *
     * @var string
     */
    protected $focusTarget;

    /**
     * Specifies properties for individual horizontal axes, if the chart has multiple horizontal axes. Each child
     * object is a hAxis object, and can contain all the properties supported by hAxis. These property values
     * override any global settings for the same property.
     *
     * To specify a chart with multiple horizontal axes, first define a new axis using series.targetAxisIndex, then
     * configure the axis using hAxes. The following example assigns series 1 to the bottom axis and specifies a custom
     * title and text style for it :
     * series:{1:{targetAxisIndex:1}}, hAxes:{1:{title:'Losses', textStyle:{color: 'red'}}}
     *
     * This property can be either an object or an array: the object is a collection of objects, each with a numeric
     * label that specifies the axis that it defines--this is the format shown above; the array is an array of objects,
     * one per axis. For example, the following array-style notation is identical to the hAxis object shown above :
     *
     * hAxes: {
     *   {}, // Nothing specified for axis 0
     *   {
     *   title:'Losses',
     *   textStyle: {
     *     color: 'red'
     *   }
     * } // Axis 1
     *
     * @var array
     */
    protected $hAxes;

    /**
     * @var MediumHAxis
     */
    protected $hAxis;

    /**
     *  If set to true, stacks the elements for all series at each domain value. The isStacked option also supports
     * 100% stacking, where the stacks of elements at each domain value are rescaled to add up to 100%.
     *
     * The options for isStacked are:
     * false — elements will not stack. This is the default option.
     * true — stacks elements for all series at each domain value.
     * 'percent' — stacks elements for all series at each domain value and rescales them such that they add up to
     * 100%, with each element's value calculated as a percentage of 100%.
     * 'relative' — stacks elements for all series at each domain value and rescales them such that they add up to 1,
     * with each element's value calculated as a fraction of 1.
     * 'absolute' — functions the same as isStacked: true.
     *
     * For 100% stacking, the calculated value for each element will appear in the tooltip after its actual value.
     * The target axis will default to tick values based on the relative 0-1 scale as fractions of 1 for 'relative',
     * and 0-100% for 'percent' (Note: when using the 'percent' option, the axis/tick values are displayed as
     * percentages, however the actual values are the relative 0-1 scale values. This is because the percentage axis
     * ticks are the result of applying a format of "#.##%" to the relative 0-1 scale values. When using isStacked :
     * 'percent', be sure to specify any ticks/gridlines using the relative 0-1 scale values). You can customize the
     * gridlines/tick values and formatting using the appropriate hAxis/vAxis options.
     * 100% stacking only supports data values of type number, and must have a baseline of zero.
     *
     * @var boolean|string
     */
    protected $isStacked;

    /**
     * @var AdvancedLegend
     */
    protected $legend;

    /**
     * The orientation of the chart. When set to 'vertical', rotates the axes of the chart.
     *
     * @var string
     */
    protected $orientation;

    /**
     * If set to true, will draw series from right to left. The default is to draw left-to-right.
     *
     * @var boolean
     */
    protected $reverseCategories;

    /**
     * @var AdvancedTooltip
     */
    protected $tooltip;

    /**
     * Displays trendlines on the charts that support them. By default, linear trendlines are used, but this can be
     * customized with the trendlines.n.type option. Trendlines are specified on a per-series basis, so most of the
     * time your options will look like this :
     * var options = {
     *    trendlines: {
     *        0: {
     *            type: 'linear',
     *            color: 'green',
     *            labelInLegend: 'label',
     *            lineWidth: 3,
     *            opacity: 0.3,
     *            pointSize: 1,
     *            pointsVisible : true,
     *            showR2: true,
     *            visibleInLegend: true
     *          }
     *       }
     *    }
     *
     * @var array
     */
    protected $trendlines;


    public function __construct()
    {
        parent::__construct();

        $this->animation = new AdvancedAnimation();
        $this->annotations = new AdvancedAnnotations();
        $this->bar = new Bar();
        $this->explorer = new Explorer();
        $this->hAxis = new MediumHAxis();
        $this->legend = new AdvancedLegend();
        $this->tooltip = new AdvancedTooltip();
    }


    /**
     * @return AdvancedAnimation
     */
    public function getAnimation()
    {
        return $this->animation;
    }

    /**
     * @return AdvancedAnnotations
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * @return Bar
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @return Explorer
     */
    public function getExplorer()
    {
        return $this->explorer;
    }

    /**
     * @return MediumHAxis
     */
    public function getHAxis()
    {
        return $this->hAxis;
    }

    /**
     * @return AdvancedLegend
     */
    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * @return AdvancedTooltip
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * @param string $bars
     */
    public function setBars($bars)
    {
        $this->bars = $bars;
    }

    /**
     * @param float $dataOpacity
     */
    public function setDataOpacity($dataOpacity)
    {
        $this->dataOpacity = $dataOpacity;
    }

    /**
     * @param string $focusTarget
     */
    public function setFocusTarget($focusTarget)
    {
        $this->focusTarget = $focusTarget;
    }

    /**
     * @param array $hAxes
     */
    public function setHAxes($hAxes)
    {
        $this->hAxes = $hAxes;
    }

    /**
     * @param bool|string $isStacked
     */
    public function setIsStacked($isStacked)
    {
        $this->isStacked = $isStacked;
    }

    /**
     * @param string $orientation
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    }

    /**
     * @param boolean $reverseCategories
     */
    public function setReverseCategories($reverseCategories)
    {
        $this->reverseCategories = $reverseCategories;
    }

    /**
     * @param array $trendlines
     */
    public function setTrendlines($trendlines)
    {
        $this->trendlines = $trendlines;
    }
}