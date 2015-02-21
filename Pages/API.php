<?php

header("Content-type: application/json");

if (filter_input(INPUT_GET, 'Key') === "afd7d5d4b34e8ed81a3c276abeba9cdc"){
    $Data['Action'] = 'Franklin.System.Install';
}
$Action = explode('.', $Data['Action']);

$Results['Info'] = array();
if (!empty($Data['Action'])){
    $ActionGroup = $Action[0];
    $ActionObject = $Action[1];
    $ActionFire = $Action[2];
    $ActionModule = APIDir . $ActionGroup . '/' . $ActionObject . '/' . $ActionFire . ".php";
    if (file_exists($ActionModule)) {
        $Site = $Data['Site'];
        $Results['Code'] = '100';
        $Results['Message'] = 'Request accepted. Continue to action.';
        include($ActionModule);
        $Results['Code'] = '200';
        $Results['Message'] = 'Action done.';
    } else{
        $Results['Code'] = '404';
        $Results['Message'] = 'No such action.';
    }
} else{
    $Results['Code'] = '204';
    $Results['Message'] = 'No action defined.';
}

if (empty($Data)){
    $Results['RequestData'] = "No Data";
} else{
    $Results['RequestData'] = $Data;
}

print(json_encode($Results));