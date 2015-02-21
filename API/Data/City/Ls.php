<?php

$Filter = $Data['Filter'];
$GroupBy = $Data['GroupBy'];

if (empty($Filter) && !empty($Data['CountryCode'])){
    $Filter = "`CountryCode`='".$Data['CountryCode']."' AND `SubdivisionCode`='".$Data['TerritoryCode']."'";
}

if (empty($GroupBy)){
    $GroupBy = "";
}

$City = new \Franklin\Data\City($VirtualParent);
$Cities = $City->Ls($Filter, "", 100000, "", $GroupBy);

$CityList = array();
foreach ($Cities as $City) {
    $CityElement['Id'] = $City->Id;
    $CityElement['SubdivisionCode'] = $City->SubdivisionCode;
    $CityElement['SubdivisionName'] = $City->SubdivisionName;
    $CityElement['CityName'] = $City->CityName;
    $CityList[] = $CityElement;
}

$Results['Data.City.Ls'] = $CityList;