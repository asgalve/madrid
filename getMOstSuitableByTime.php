<?php
    $json_path = "./JSON/madrid.json";
    if (!file_exists($json_path)) {
        echo "The file $json_path does not exist";
        exit;
    }

    // Reading JSON file
    $data = file_get_contents ($json_path);
    $json = json_decode($data, true);

	// Calling the method that gets the results 
    $geojson_elements = getAvtiviyDayTime($json);

    // printing geoJSON
	header('Content-type: application/json');
	echo (json_encode($geojson_elements));

	
	// Methods and functions to handle the JSON content and the output on screen.

    /*																										*/
	/*	Coverts time into integer a given time 																*/
	/*																										*/


    function setTime2Int($var_time){
    	$hours= explode(":",$var_time)[0];
    	$minutes= explode(":",$var_time)[1];
    	return (($hours*60)+$minutes);
    }

    /*																										*/
	/*	Coverts hours(float) into min(int) 																	*/
	/*																										*/

    function sethours2Int($var_hours){
    	return (($var_hours*60));
    }
	/*																										*/
	/*	Gets all the JSON elements that comply with the 2 variables: day and time		*/
	/*																										*/

	function getAvtiviyDayTime($json){
		

    	$cutomer_init_time= explode("-",$_GET['time'])[0];
    	$cutomer_end_time = explode("-",$_GET['time'])[1];

    	$customer_day = $_GET['day'];


    	// Since we are working with time formats and then float values "hours_spent". I decided to normalize everything into integers so we can  work with the values.

    	$c_init_time_int = setTime2Int($cutomer_init_time);
    	$c_end_time_int = setTime2Int($cutomer_end_time);


	    foreach ($json as $key => $value) {
	    	// Setting the variable object to the current JSDON item we are working with
	    	$object = $value;
	    	$activity_min = sethours2Int($object['hours_spent']);
	    	foreach ($object['opening_hours'] as $key => $val) {
	    		// We make sure there is activiy on that day by discarding empty days.
	    		if ((!empty($val[0]))&&($key==$customer_day)){
	    			$activity_init_time = explode('-',$val[0])[0];
	    			$activity_end_time = explode('-',$val[0])[1];
	    			$a_init_time_int = setTime2Int($activity_init_time);
	    			$a_end_time_int = setTime2Int($activity_end_time);
	    			// pre-calculating variables so the if statement is not a mess.
	    			$c_expected_end_time = $c_init_time_int + $activity_min;

	    			

	    			if (($c_expected_end_time <= $a_end_time_int )&& ($c_expected_end_time <= $c_end_time_int)&&($c_init_time_int >= $a_init_time_int)&&($object['category'] == $_GET['category'])){

	    				if ($activity_min > $hours_spent_tracker){
    				  		//we'll get the longest activity stored on this one.
    				  		$hours_spent_tracker = $activity_min;
    				  		
    				  		// We clear the geoJSON object so we just keep the longest one within the time range.
    				  		$geojson_elements = array(
							   'type'      => 'FeatureCollection',
							   'features'  => array()
							);
							$feature = array(
							    'type' => 'Feature', 
								 'properties' => array(
									 'name' => $object['name'],
									 'hours_spent' => $object['hours_spent'],
									 'opening_hours' => Array($object['opening_hours']),
									 'category' => $object['category'],
									 'location' => $object['location'],
									 'district' => $object['district']
							        ),
							  	'geometry' => array(
							    	'type' => 'Point',
							    	// Switchin lat/long to x/y so "El Prado" is not in the middle of the Ocean.
								    'coordinates' => array((float)$object['latlng'][1], (float)$object['latlng'][0])
								)
							);

							// Pushing the object to the geoJSON array.
							array_push($geojson_elements['features'], $feature);
						}
		    		}
	    		}
	    	}
	    	
	    }
	    	
	    return $geojson_elements;
    }

?>
