<?php

 include('./libs/simple_html_dom.php');



=======

 //base url
$base = 'https://apps.npr.org/dailygraphics/graphics/coronavirus-d3-world-map-20200323/table.html?initialWidth=1238&childId=responsive-embed-coronavirus-d3-world-map-20200323-table&parentTitle=Coronavirus%20World%20Map%3A%20Tracking%20The%20Spread%20Of%20The%20Outbreak%20%3A%20Goats%20and%20Soda%20%3A%20NPR&parentUrl=https%3A%2F%2Fwww.npr.org%2Fsections%2Fgoatsandsoda%2F2020%2F03%2F30%2F822491838%2Fcoronavirus-world-map-tracking-the-spread-of-the-outbreak';

$curl = curl_init();
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_URL, $base);
curl_setopt($curl, CURLOPT_REFERER, $base);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
$str = curl_exec($curl);
curl_close($curl);

// Create a DOM object
$html_base = new simple_html_dom();

// Load HTML from a string
$html_base->load($str);

// echo $html_base;

//get all category links
foreach($html_base->find('div') as $element) {
    echo "<pre>";
    echo $element;
    // print_r( $element->outertext);
    echo "</pre>";
}

$html_base->clear(); 
unset($html_base);

=======

// Load HTML from a string
$html_base->load($str);


$finalArray = [];

$counter = 0;

//go through each table row with thte country, case count and death count
foreach($html_base->find('div[class="cell-group country"]') as $element) {

    echo "<pre>";
    // inside the row, get the country
    $country = $element->find('div[class="cell"]');
    
    // get the row element and find the case count and death count
    $caseCount = $element->find('div[role="cell"]');

    $countryDetails = array(
      "country" => $country[0]->innertext,
      "caseCount" => $caseCount[1]->innertext,
      "deathCount" => $caseCount[2]->innertext
  );

  array_push($finalArray , $countryDetails);
  
  /* if you want to log this stuff you can
      echo "This is the country"  . $country[0]; 
    echo "<br>";
    echo "This is the case count"  . $caseCount[1]; 
    echo "<br>";
    echo "This is the death count"  . $caseCount[2]; 
    echo "</pre>";
  */
}



$html_base->clear(); 
unset($html_base);



echo "This is the final array \n";

print("<pre>".print_r($finalArray,true)."</pre>");

file_put_contents('./array.txt', print_r($finalArray, true));


?>
