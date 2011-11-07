<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Db
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * Класс, позволяющий упростить работу с ORM Doctrine
 */
class CompleXml_Db_Doctrine
{
    static public function getPagerArray(Doctrine_Pager $pager, $rangeStyle = 'Sliding', $options = array('chunk'=>5))
    {
        $result = array();
        if (!$pager->getExecuted()){
            throw new CompleXml_Db_Exception('Pager not executed');
        }
        if (!is_null($rangeStyle)){
            $result['range'] = $pager->getRange($rangeStyle, $options)->rangeAroundPage();
        }
        $result['have-pager'] = (int) $pager->haveToPaginate();
        $result['first-page'] = $pager->getFirstPage();
        $result['first-indice'] = $pager->getFirstIndice();
        $result['last-page'] = $pager->getLastPage();
        $result['last-indice'] = $pager->getLastIndice();
        $result['result-count'] = $pager->getNumResults();
        $result['next-page'] = $pager->getNextPage();
        $result['previous-page'] = $pager->getPreviousPage();
        $result['current-page'] = $pager->getPage();
        $result['results-on-page'] = $pager->getResultsInPage();
        $res['pager'] = $result;
        return $res;
    }
}