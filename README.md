# Google Geocode Php Api - x-c0der (Beta Release)

Introducing geocode-php-api it enables you to access the location information either by the address or geomteric location i.e. Lat. and Long.
- Easy to setup
- No extra requirements just Php => 5.0
- Results are presented in form of a array

## Accessible Information
- City [long_name] & [short_name]
- District [long_name] & [short_name]
- State [long_name] & [short_name]
- Country [long_name] & [short_name]
- Geometric Locations i.e. Latitude and Longitude
- Geocode Formatted Address for precise location experience
- Geocode Place Id (for further convience) :)

## Setting Up
- Copy the php script i.e. <code>src/Location.inc.php</code> to any directory or root directory of Project
- Include the <code>Location.inc.php</code> on your required Php script as <code>require_once('dir/to/Location.inc.php')</code>
- Now edit <code>Location.inc.php</code> in a text editor and paste/write your google api key on defined place. <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Know More about API key</a><br>Write your api key on this place were it is mentioned <code>define('GOOGLE_API_KEY','YOUR_API_KEY')</code>. Replace 'YOUR_API_KEY' with your google api key.
- Save and close <code>Location.inc.php</code>
- Done!

## Access API - Trust Me Its Simple ;)
```
<?php
$LocParams = new LocationParams();
//Setting up Params for accessing location information from address. you can use any of the option below
  $LocParams = $LocParams->setCity('Phagwara');
  //--OR 
  $LocParams = $LocParams->setCity('Phagwara')->setDistrict('Kapurthala');
  //--OR
  $LocParams = $LocParams->setCity('Phagwara')->setDistrict('Kapurthala')->setRegion('Punjab');
  //--OR SIMPLY
  $LocParams = $LocParams->setAddress('Phagwara, Punjab');
//Setting up Params for accessing location information from GeoCordinates i.e. Lat. and Long.
  $LocParams = $LocParams->setGeoLoc(array("lat" => 31.2240198, "lng" => 75.7708013));

try{
  $Loc = new Location($LocParams)
    //If you are looking up a location with address write this code
    $Loc->setRequestType(Location::BY_ADDRESS);
    //Else if you are looking up a location with GeoCordinates i.e. Lat. and Long. write this
    $Loc->setRequestType(Location::BY_GEOMETRY);
  $Loc->init();
  $Result = $Loc->getResult();
}catch(Exception $e){
  echo $e->getMessage();
}finally{
  echo $Result['city']['long']; //Prints Long Name of City
  echo $Result['city']['short']; //Prints Short Name of City
  
  echo $Result['district']['long']; //Prints Long Name of District
  echo $Result['district']['short']; //Prints Short Name of District
  
  echo $Result['region']['long']; //Prints Long Name of Region
  echo $Result['region']['short']; //Prints Short Name of Region
  
  echo $Result['country']['long']; //Prints Long Name of Country
  echo $Result['country']['short']; //Prints Long Name of Country
  
  echo $Result['postalCode']; //Prints Postal Code of address

  echo $Result['geometry']['lat']; //Prints latitude geometric co-ordinate
  echo $Result['geometry']['lng']; //Prints longitude geometric co-ordinate
  
  echo $Result['google_placeId']; //Prints unique Place Id of Google Geocode
  echo $Result['google_formatted_address']; //Get Precise Location/Address of the address you provided
  
  echo $Result['query_type']; //It contains the array of type of query you have made like locality or country
}
?>
```

## Connect
- website : <a href="http://anandsiddharth.in">www.anandsiddharth.in</a>
- twitter : <a href="https://twitter.com/anandsiddharth5">anandsiddharth5</a>

## Note
Read carefully the usage of Google Geocode API..
