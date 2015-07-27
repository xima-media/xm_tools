<?php

namespace Xima\XmTools\Classes\Extensions\KeSearch\Hooks;

/**
 * Hooks for ke_search.
 *
 * @author Wolfram Eberius
 */
class DateHooks
{
    /**
     * dateFrom.
     *
     * @var DateTime
     */
    protected $dateFrom;

    /**
     * dateTo.
     *
     * @var DateTime
     */
    protected $dateTo;

    public function __construct()
    {
        if (isset($_GET['tx_kesearch_pi1']) || isset($_GET['tx_kesearch_pi1']['dateUntil'])) {
            $dateFromFilter = $_GET['tx_kesearch_pi1']['dateBegin'];
            $dateToFilter = $_GET['tx_kesearch_pi1']['dateUntil'];

            $this->dateFrom = $this->string2DateTime($dateFromFilter);
            $this->dateTo = $this->string2DateTime($dateToFilter, true);
        }
    }

    public function registerDateFields(&$additionalFields)
    {
        $additionalFields [] = 'period';
        $additionalFields [] = 'date_start';
        $additionalFields [] = 'date_end';
    }

    public function additionalResultMarker(&$markerArray, &$row, $tx_kesearch_lib_Object)
    {
        $markerArray ['period'] = $row ['period'];
    }

    public function additionalSearchboxContent(&$content, \tx_kesearch_pi1 $piObj)
    {
        $dateFromString = '';
        if ($this->getDateFrom()) {
            $dateFromString = $this->getDateFrom()->format('d.m.Y');
        }

        $dateToString = '';
        if ($this->getDateTo()) {
            $dateToString = $this->getDateTo()->format('d.m.Y');
        }

        $content = $piObj->cObj->substituteMarker($content, '###DATEBEGIN_VALUE###', $dateFromString);
        $content = $piObj->cObj->substituteMarker($content, '###DATEUNTIL_VALUE###', $dateToString);

        $content = $piObj->cObj->substituteMarker($content, '###DATEBEGIN_DEFAULT###', 'Tag.Monat.Jahr ');
        $content = $piObj->cObj->substituteMarker($content, '###DATEUNTIL_DEFAULT###', 'Tag.Monat.Jahr ');
    }

    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    public function getDateTo()
    {
        return $this->dateTo;
    }

    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    }
}
