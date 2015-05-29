# Google Geocode Php Api - x-c0der

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
- Include the <code>Location.inc.php</code> on your required Php script as <code>require_once('dir\to\Location.inc.php')</code>
- Done!

## Access API - Trust Me Its Simple
case 1: Using Address to Access Location Information
```
$LocParams = new LocationParams();
$LocParams = $LocParams->setCity('Phagwara');
try{
  $Location = new Location($LocParams)
}catch(Exception $e){
  echo $e->getMessage();
}
