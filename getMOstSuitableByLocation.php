<?php
    $json_path = "./JSON/madrid.json";
    if (!file_exists($json_path)) {
        echo "The file $json_path does not exist";
        exit;
    }

    // Reading JSON file
    $data = file_get_contents ($json_path);
    $json = json_decode($data, true);
	
    $geojson_elements = getAvtiviyLocationDistrict($json);

	header('Content-type: application/json');
	echo (json_encode($geojson_elements));

	
	// Methods and functions to handle the JSON content and the output on screen.


	/*																										*/
	/*	Gets all the JSON elements that comply with the 3 variables: location, district and category		*/
	/*																										*/

	function getAvtiviyLocationDistrict($json){
		$geojson_elements = array(
		   'type'      => 'FeatureCollection',
		   'features'  => array()
		);
	    foreach ($json as $key => $value) {
	    	// Setting the variable object to the current JSDON item we are working with
	    	$object = $value;
	    	// Checking the filters we are using to filter out the results. We set then as true by default so it is flexible to suing any or all of them.
	    	$flag_category = true;
	    	$flag_district = true;
	    	$flag_location = true;

	    	//They will be false in case they exist and no result matches. 
	    	if(isset($_GET['category'])) $flag_category = ($object['category'] == $_GET['category'] ? true: false);
	    	if(isset($_GET['district'])) $flag_district = ($object['district'] == $_GET['district'] ? true: false);
	    	if(isset($_GET['location'])) $flag_location = ($object['location'] == $_GET['location'] ? true: false);
	    	if ($flag_category==true && $flag_district==true && $flag_location==true) {

	    		// We create the geoJSON object usinf the JSON object read previously.
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
	    
	    return $geojson_elements;
    }


?>
