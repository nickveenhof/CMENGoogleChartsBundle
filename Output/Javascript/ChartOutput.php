<?php

namespace CMEN\GoogleChartsBundle\Output\Javascript;

use CMEN\GoogleChartsBundle\Exception\GoogleChartsException;
use CMEN\GoogleChartsBundle\GoogleCharts\Chart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Diff\DiffChart;
use CMEN\GoogleChartsBundle\Output\AbstractChartOutput;
use CMEN\GoogleChartsBundle\Output\OptionOutputInterface;

/**
 * @author Christophe Meneses
 */
class ChartOutput extends AbstractChartOutput
{
    /** @var OptionOutputInterface */
    private $optionOutput;

    /**
     * JavascriptChartOutput constructor.
     *
     * @param string                $version
     * @param string                $language
     * @param OptionOutputInterface $optionOutput
     */
    public function __construct($version, $language, OptionOutputInterface $optionOutput)
    {
        parent::__construct($version, $language);

        $this->optionOutput = $optionOutput;
    }

    /**
     * {@inheritdoc}
     */
    public function startChart(Chart $chart)
    {
        if (null === $chart->getElementID()) {
            throw new GoogleChartsException('Container is not defined.');
        }

        $js = 'var '.$chart->getName().' = new google.'.$chart->getLibrary().'.'.$chart->getType().
            '(document.getElementById("'.$chart->getElementID().'"));';

        if (!$chart instanceof DiffChart) {
            $js .= $chart->getData()->draw($chart->getDataName());
        } else {
            $js .= $chart->getOldChart()->getData()->draw('old_'.$chart->getDataName()).
                $chart->getNewChart()->getData()->draw('new_'.$chart->getDataName()).
                'var '.$chart->getDataName().' = '.$chart->getName().
                '.computeDiff(old_'.$chart->getDataName().',
                 new_'.$chart->getDataName().');';
        }

        $js .= $this->optionOutput->draw($chart->getOptionsName(), $chart->getOptions());

        return $js;
    }

    /**
     * {@inheritdoc}
     */
    public function endChart(Chart $chart)
    {
        if ('visualization' == $chart->getLibrary()) {
            $options = $chart->getOptionsName();
        } else {
            /* Options conversion for material charts */
            $options = 'google.'.$chart->getLibrary().'.'.$chart->getType().
                '.convertOptions('.$chart->getOptionsName().')';
        }

        return $chart->getEvents()->draw().$chart->getName().
            '.draw('.$chart->getDataName().', '.$options.');';
    }

    /**
     * {@inheritdoc}
     */
    public function startCharts($charts, $elementsID = null)
    {
        if ($charts instanceof Chart) {
            $charts = [$charts];

            if ($elementsID) {
                if (!is_string($elementsID)) {
                    throw new GoogleChartsException('A string is expected for HTML element ID.');
                }

                $elementsID = [$elementsID];
            }
        } elseif (is_array($charts) && !empty($charts)) {
            $this->checkChartsTypes($charts);

            if (null !== $elementsID) {
                $this->checkElementsId($charts, $elementsID);
            }
        } else {
            throw new GoogleChartsException('An instance of Chart or an array of Chart is expected.');
        }

        $packages = [];
        $drawChartName = '';
        for ($i = 0; $i < count($charts); ++$i) {
            if ($elementsID) {
                $charts[$i]->setElementID($elementsID[$i]);
            }

            if (!in_array($charts[$i]->getPackage(), $packages)) {
                $packages[] = $charts[$i]->getPackage();
            }
            $drawChartName .= $charts[$i]->getElementID();
        }

        $js = $this->loadLibraries($packages);

        $js .= $this->startCallback('drawChart'.ucfirst(md5($drawChartName)));

        foreach ($charts as $chart) {
            $js .= $this->startChart($chart);
        }

        return $js;
    }

    /**
     * {@inheritdoc}
     */
    public function endCharts($charts)
    {
        if ($charts instanceof Chart) {
            $js = $this->endChart($charts).$this->endCallback();
        } elseif (is_array($charts) && !empty($charts)) {
            $this->checkChartsTypes($charts);

            $js = '';
            foreach ($charts as $chart) {
                $js .= $this->endChart($chart);
            }

            $js .= $this->endCallback();
        } else {
            throw new GoogleChartsException('An instance of Chart or an array of Chart is expected.');
        }

        return $js;
    }

    /**
     * {@inheritdoc}
     */
    public function fullCharts($charts, $elementsID = null)
    {
        return $this->startCharts($charts, $elementsID).$this->endCharts($charts);
    }

    /**
     * {@inheritdoc}
     */
    public function loadLibraries($packages)
    {
        array_walk($packages, function (&$item) {
            $item = "'".$item."'";
        });

        ($this->language) ? $language = ", language: '".$this->language."'" : $language = '';

        $load = "'".$this->version."', {packages:[".implode(',', $packages).']'.$language.'}';

        return "google.charts.load($load);";
    }

    /**
     * {@inheritdoc}
     */
    public function startCallback($name)
    {
        return "google.charts.setOnLoadCallback($name); function $name() {";
    }

    /**
     * {@inheritdoc}
     */
    public function endCallback()
    {
        return '}';
    }
}