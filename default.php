<?php
require_once "classes/page.to.api.class.php";
require_once "classes/mlb.class.php";
require_once "classes/dbops.class.php";
require_once "classes/retval.class.php";
require_once "classes/connection.properties.class.php";

/*
*******************************
******** R E A D   M E ********
*******************************

Class MLB() Extends Class PageToAPI()

MLB() Contains functions that:
    Scrapes https://www.mlb.com/stats/
    Creates an array of stats info on Players[25] into an array name RawData[]
    
    $this->RawData[]
        [0] => Array
            (
                [0] => 'Juan Soto'
                [1] => 'WSH'
                [2] => '42'
                [3] => '141'
                [4] => '37'
                [5] => '49'
                [6] => '12'
                [7] => '0'
                [8] => '12'
                [9] => '35'
                [10] => '35'
                [11] => '25'
                [12] => '4'
                [13] => '2'
                [14] => '.348'
                [15] => '.480'
                [16] => '.688'
                [17] => '1.168'
            )
        
Further contains functions that work against RawData and formats as:
    GetSQL()
    GetJSON()
    GetSQL()
    GetCSV()

Note: Some values in RawData differ than those shown on the website. There maybe a
      manipulation on the site via JavaScript or jQuery to keep hackers from scraping
      the page as we are here?

private function debugSave( $filename, $data ) can be used to save output to a file in the 'junk' folder

*/

$MLB = new MLB();
$MLB->set_Url( "https://www.mlb.com/stats/" );
$MLB->GetRawData();

//$MLB->GetSQL(); // ( Execute INSERT command [ false = default ])
//echo $MLB->get_OutputSQL();

$MLB->GetXML();
echo $MLB->get_OutputXML();

//$MLB->GetJSON();
//echo $MLB->get_OutputJSON();

//$MLB->GetCSV();
//echo $MLB->get_OutputCSV();

unset($MLB);

?>