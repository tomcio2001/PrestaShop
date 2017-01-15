<?php
/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 13:03
 */
class PsLinksRetriever{

    public function links($orderedProduct){

        $links = json_decode($orderedProduct['links']);

        return $links;
    }
}