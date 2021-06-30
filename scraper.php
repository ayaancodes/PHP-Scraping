<?php

 include('./libs/simple_html_dom.php');


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

// echo $html_base

//get all category links
foreach($html_base->find('div') as $element) {
    echo "<pre>";
    echo $element;
    // print_r( $element->outertext);
    echo "</pre>";
}

$html_base->clear(); 
unset($html_base);

?>
