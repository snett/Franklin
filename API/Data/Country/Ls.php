<?php

$Country = new \Franklin\Data\Country($VirtualParent);

$Countries = $Country->Ls("`Status`='1'", "`Name` ASC");

$CountryList = array();
foreach ($Countries as $Country) {
    $CountryElement['Id'] = $Country->Id;
    $CountryElement['Name'] = $Country->Name;
    $CountryElement['NativeName'] = $Country->NativeName;
    $CountryElement['Alpha2'] = $Country->Alpha2;
    $CountryList[] = $CountryElement;
}

$Results['Data.Country.Ls'] = $CountryList;