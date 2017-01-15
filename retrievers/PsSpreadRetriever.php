<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 14:01
 */
class PsSpreadRetriever
{

    public function getSpreadParams(){

        $cwSpread = intval(Configuration::get('CODESWHOLESALE_SPREAD_VALUE'));
        $cwSpreadType = intval(Configuration::get('CODESWHOLESALE_SPREAD_TYPE'));

        $spreadParams = array(
            'cwSpread' => $cwSpread,
            'cwSpreadType' => $cwSpreadType
        );

        return $spreadParams;
    }

}