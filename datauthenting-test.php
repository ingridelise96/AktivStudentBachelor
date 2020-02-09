<?php

//https://www.youtube.com/watch?v=iCpyL6PkfwE
//https://www.tutorialrepublic.com/php-tutorial/php-json-parsing.php

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://data.brreg.no/enhetsregisteret/api/enheter?kommunenummer=3001,3004&organisasjonsform=FLI&sort=navn.norwegian,asc');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_HEADER, 0);

$output = curl_exec($ch);

if ($output === FALSE) {
  echo "cURL error: " . curl_error($ch);
}

curl_close($ch);


// Define recursive function to extract nested values
function printValues($arr) {
    global $count;
    global $values;

    // Check input is an array
    if(!is_array($arr)){
        die("ERROR: Input is not an array");
    }

    /*
    Loop through array, if value is itself an array recursively call the
    function else add the value found to the output items array,
    and increment counter by 1 for each value found
    */
    foreach($arr as $key=>$value){
        if(is_array($value)){
            printValues($value);
        } else{
            $values[] = $value;
            $count++;
        }
    }

    // Return total count and values found in array
    return array('total' => $count, 'values' => $values);
}

// Decode JSON data into PHP associative array format
$arr = json_decode($output, true);

// Call the function and print all the values
$result = printValues($arr);
echo "<h3>" . $result["total"] . " value(s) found: </h3>";
echo implode("<br>", $result["values"]);