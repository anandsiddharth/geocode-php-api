<?php 
	require_once('./src/Location.inc.php');
	$LocPar = new LocationParams();
	$LocPar = $LocPar->setCity('Phagwara, Punjab');
	try{
		$Loc = new Location($LocPar);
		$Loc->setRequestType(Location::BY_ADDRESS);
		$Loc->init();
		$Result = $Loc->getResult();
	}catch(Exception $e){
		echo $e->getMessage();
	}finally{
		if (isset($Result)) {
			var_dump(@$Result);
		}
	}
?>