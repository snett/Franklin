<?php

error_reporting(-1);

/*Default Language*/
define('DefaultLanguage', 'en');

/*Default Timezone*/
date_default_timezone_set('Europe/Paris');

/*Basic Header*/
header("Content-type: text/html; charset=utf-8");

/*Root Directory of Application*/
define('RootDir', dirname(__FILE__) . '/');

/*Directories*/
define('ActionDir', RootDir . 'Actions/');
define('PageDir', RootDir . 'Pages/');
define('ModuleDir', RootDir . 'Modules/');
define('ConfigDir', RootDir . 'Config/');

$ModuleList = array();
$FList = array();
$DirHandler = opendir(ModuleDir);
if ($DirHandler) {
    while (($entry = readdir($DirHandler)) !== false) {
        if ($entry != "." && $entry != "..") {
            if (is_dir(ModuleDir . $entry)){
                $ModuleList[] = $entry;
                $FList[$entry] = array();
                $MDirHandler = opendir(ModuleDir . $entry);
                if ($MDirHandler) {
                    while (($mf = readdir($MDirHandler)) !== false) {
                        if ($mf != "." && $mf != "..") {
                            $FList[$entry][] = $mf;
                            $IncElement = ModuleDir . $entry . '/' . $mf;
                            if ($mf[0] == "_"){
                                array_unshift($IncludeList, $IncElement);
                            } else{
                                $IncludeList[] = $IncElement;
                            }
                        }
                    }
                    closedir($MDirHandler);
                }
            } else{
                $ModuleList[] = $entry;
                $FList[$entry][] = $entry;
                $IncElement = ModuleDir . $entry;
                $IncludeList[] = $IncElement;
            }
        }
    }
    closedir($DirHandler);
}

foreach ($IncludeList as $Inc) {
    //print("$Inc <br>");
    include($Inc);
}

/*Please, Franklin*/
$System = new Franklin\System(RootDir);

$VirtualParent = new \stdClass();
$VirtualParent->System = $System;

/*Easy Data Access*/
$Data = filter_input_array(INPUT_POST);
$GetData = filter_input_array(INPUT_GET);

if ($System->Request->get('Action', 'get') === "Logout"){
    $User = new \Franklin\User\User($VirtualParent);
    $Logout = $User->Logout();
    header("Location: /");
}

$User = new Franklin\User\User($VirtualParent);
$User->selfLoad();

$Action = explode('.', $System->Request->get('Action'));

$ActionGroup = $Action[0];
$ActionObject = $Action[1];
$ActionFire = $Action[2];

$ActionModule = ActionDir . $ActionGroup . '/' . $ActionObject . '/' . $ActionFire . ".php";
if (file_exists($ActionModule)){
    include($ActionModule);
}

/*URL handling*/
/*$PageModule = PageDir . $System->Application . '.php';
if (file_exists($PageModule)){
    include($PageModule);
} else{
    include(PageDir . 'Main.php');
}*/

if ($User->PL > 5){
    include(PageDir . 'Admin.php');
} else{
    if (!empty($System->Application)){
        header("Location: http://collab.snett.net/?Location=" . str_replace(array('http://collab.snett.net/', '?Action=Logout'), '', $System->URL));
    }
    include(PageDir . 'Login.php');
}