<?php

function getTimestampByIndex($index, $collection, $criteria){

        $cursor = $collection->find($criteria)->sort(array('timestamp' => $index))->limit(1);

        foreach ($cursor as $obj) {

                $sec = $obj['timestamp']->sec;

                $ts = new MongoDate($sec);

        }

	if(isset($ts))
	        return $ts;
	else
		return '{}'; 
}


?>
