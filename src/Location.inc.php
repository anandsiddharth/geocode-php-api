<?php 

/**
* Anand Siddharth (x-c0der)
*  
*
* Google Geocode Php Api
*
* geocode-php-api enables you to access the location information either by the address or geomteric location i.e. Lat. and Long. using Google Geocode
*
* @author Anand Siddharth <anandsiddharth21@gmail.com>
* @license http://www.opensource.org/licenses/MIT
* @version v1.0
*/

define('GOOGLE_API_KEY', 'YOUR_API_KEY');

class Location{
	private $LocationParams;
	private $reponse;
	private $requestType;

	private $customResult;
	private $extraQueryParams;

	const STATUS_OK = 'OK';
	const ZERO_RESULTS = 'ZERO_RESULTS';
	const OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';
	const REQUEST_DENIED = 'REQUEST_DENIED';
	const INVALID_REQUEST = 'INVALID_REQUEST';
	const ERROR = 'UNKNOWN_ERROR';

	const BY_ADDRESS = 'byADDRESS';
	const BY_GEOMETRY = 'byGeoLoc';

	public function __construct($Params = null){
		if (is_object($Params) && get_class($Params) == 'LocationParams'){
			$this->LocationParams = $Params;
			$this->requestType = false;
			$this->extraQueryParams = '';
		}else{
			throw new Exception("Invalid Location Paramaters. `LocationParams` class is required as the constructor argument.", 1);
		}
	}

	public function setCountryRestriction($country_code){
		$this->extraQueryParams .= '&components=country:'.$country_code.'&';
	}

	public function setRequestType($Type){
		if ($Type == self::BY_ADDRESS
		|| $Type == self::BY_GEOMETRY){
			$this->requestType = $Type;
		}else{
			throw new Exception("Invalid Request Type. Read Documentation for valid request types", 1);
		}
	}

	public function init(){
		if ($this->requestType && $this->requestType == self::BY_ADDRESS){
			$address = ($this->LocationParams->getAddress() == null || trim($this->LocationParams->getAddress()) == '') ? urlencode($this->LocationParams->getCity().' '.$this->LocationParams->getDistrict().' '.$this->LocationParams->getRegion()) : urlencode($this->LocationParams->getAddress());
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.$this->extraQueryParams.'&sensor=false&key='.GOOGLE_API_KEY;
			$get = file_get_contents($url);
			$this->response = json_decode($get);
			self::_processresponse();
			self::_status_error();			
		}elseif($this->requestType && $this->requestType == self::BY_GEOMETRY){
			$geometricAddress = $this->LocationParams->getGeoLoc()["lat"].','.$this->LocationParams->getGeoLoc()["lng"];
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$geometricAddress.$this->extraQueryParams.'&sensor=false&key='.GOOGLE_API_KEY;
			$get = file_get_contents($url);
			$this->response = json_decode($get);
			self::_processresponse();
			self::_status_error();
		}else{
			throw new Exception("Request type not specified. Read the documentation for valid request types and setting up request type.", 1);
		}
	}

	private function _status_error(){
		if($this->response->{'status'} == self::STATUS_OK){
			//COOL-- NO ERROR REPORT
		}elseif($this->response->{'status'} == self::REQUEST_DENIED){
			throw new Exception("Invalid Google API KEY. please recheck", 1);
		}elseif($this->response->{'status'} == self::OVER_QUERY_LIMIT){
			throw new Exception("You made a lot of request today. Please check Google Geocode Documentation for more info regarding access laws.", 1);
		}elseif ($this->response->{'status'} == self::ZERO_RESULTS) {
			throw new Exception("Your query returned zero results.", 1);
		}else{
			throw new Exception("We received following error. ".$this->response->{'status'}, 1);
		}
	}

	private function _processresponse(){
		if ($this->response->{'status'} == self::STATUS_OK) {
			$this->customResult = array();
			foreach ($this->response->{'results'}[0]->{'address_components'} as $component) {
				if (in_array("locality", @$component->{'types'})){
					@$this->customResult['city']['long'] = $component->{'long_name'};
					@$this->customResult['city']['short'] = $component->{'short_name'};
				}
				if (in_array("administrative_area_level_2", $component->{'types'})){
					@$this->customResult['district']['long'] = $component->{'long_name'};
					@$this->customResult['district']['short'] = $component->{'short_name'}; 
				}
				if (in_array("administrative_area_level_1", $component->{'types'})) {
					@$this->customResult['region']['long'] = $component->{'long_name'};
					@$this->customResult['region']['short'] = $component->{'short_name'};						
				}
				if (in_array("country", @$component->{'types'})) {
					@$this->customResult['country']['long'] = $component->{'long_name'};
					@$this->customResult['country']['short'] = $component->{'short_name'};
				}
				if (in_array('postal_code', @$component->{'types'})) {
					@$this->customResult['postalCode'] = $component->{'long_name'};	
				}
			}
			@$this->customResult['google_formatted_address'] = $this->response->{'results'}[0]->{'formatted_address'};
			@$this->customResult['google_placeId'] = $this->response->{'results'}[0]->{'place_id'};
			@$this->customResult['query_type'] = $this->response->{'results'}[0]->{'types'};
			@$this->customResult['geometry']['lat'] = $this->response->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
			@$this->customResult['geometry']['lng'] = $this->response->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
		}
	}

	public function getResult(){
		if ($this->response->{'status'} == self::STATUS_OK) {
			return @$this->customResult;
		}
		return false;
	}

	public function getRequestStatus(){
		if (@$this->response->{'status'}) {
			return @$this->response->{'status'};
		}
		return false;
	}
}

class LocationParams{
	private $geometry;
	private $city;
	private $district;
	private $region;
	private $address;

	public function __construct(){
		$this->geometry = array();
	}
	public function setCity($value){
		$this->city = $value;
		return $this;
	}
	public function setDistrict($value){
		$this->district = $value;
		return $this;
	}
	public function setRegion($value){
		$this->region = $value;
		return $this;
	}
	public function setGeoLoc($geo){
		$this->geometry = array("lat"=>$geo['lat'], "lng"=>$geo["lng"]);
		return $this;
	}
	public function setAddress($addr){
		$this->address = $addr;
		return $this;
	}

	public function getAddress(){
		return @$this->address;
	}
	public function getCity(){
		return @$this->city;
	}
	public function getDistrict(){
		return @$this->district;
	}
	public function getRegion(){
		return @$this->region;
	}
	public function getGeoLoc(){
		return @$this->geometry;
	}
}
?>