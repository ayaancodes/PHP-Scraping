<?php

session_start();


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


$finalArray = [];

$counter = 0;

//go through each table row with thte country, case count and death count
foreach ($html_base->find('div[class="cell-group country"]') as $element) {

  // inside the row, get the country
  $country = $element->find('div[class="cell"]');

  // get the row element and find the case count and death count
  $caseCount = $element->find('div[role="cell"]');

  $countryDetails = array(
    "country" => $country[0]->innertext,
    "caseCount" => $caseCount[1]->innertext,
    "deathCount" => $caseCount[2]->innertext
  );

  array_push($finalArray, $countryDetails);
}



$html_base->clear();
unset($html_base);



echo "This is the final array \n";



file_put_contents('./array.txt', print_r($finalArray, true));

if (isset($_POST['jsonparams'])) {
  $json = $_POST['jsonparams'];
  $json = json_decode($json, true);

  print("<pre>".print_r($json,true)."</pre>");


}

$_SESSION['arr'] = $finalArray;



?>



<!DOCTYPE html>
<html>

<head>
  <title>Show Scraper Data </title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <link rel="stylesheet" type="text/css" href="style.css">

  <script>
    $(document).ready(function() {
      $('.js-example-basic-single').select2();
    });

    function getJSONFromSelect (){
    var obj = {};
    var selectedCountries = $('select[name="countries[]"]').find(':selected');
    var settings = $('select[name="fetchparams"]').find(':selected');
    var settingsArr = [];
    for (let options of settings) {
     settingsArr.push(options.outerText);
    }
    var selectedCountriesArr = [];
    for (let countries of selectedCountries) {
      selectedCountriesArr.push(countries.outerText);
    }

    obj.settings = settingsArr;
    obj.countries = selectedCountriesArr;

    document.getElementById('jsonparams').value = JSON.stringify(obj);

    console.log(JSON.stringify(obj));
    return obj;

  }
      </script>


</head>

<body>


  <div class="container">
    <form method="post" action ="showScrapeResults.php">
      <label for="fname">First Name</label>
      <input type="text" id="fname" name="firstname" placeholder="Your name..">

      <label for="lname">Last Name</label>
      <input type="text" id="lname" name="lastname" placeholder="Your last name..">

      <label for="country">Choose the country you want to scrape</label>
      <select class="js-example-basic-single" name="countries[]" multiple="multiple" style="width: 50%">
        <?php
        // dynamically redner the countries in the select 2 form.
        for ($i = 0; $i < count($finalArray); $i++) {
          $country = $finalArray[$i]['country'];
          echo "<option value = '$country'>$country</option>";
        }
        ?>
      </select>
      <br>
      <label for="subject">What do you want to fetch from this?</label>
      <select class="js-example-basic-single" name="fetchparams" multiple="multiple" style="width: 50%">
        <option value="caseCount">Case Count</option>
        <option value="deathCount">Death Count</option>
      </select>

          <input type="hidden" name = "jsonparams"  id = "jsonparams" value = "" >
          <input type="hidden" name = "scraperdata"  id = "jsonparams" value = "" >


      <input type="submit" onclick="getJSONFromSelect()"   value="Submit">
    </form>


  </div>

</body>

</html>
