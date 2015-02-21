<?php

error_reporting(0);

/* Default Language */
define('DefaultLanguage', 'en');

/* Default Timezone */
date_default_timezone_set('UTC');

/* Basic Header */
header("Content-type: text/html; charset=utf-8");

/* Root Directory of Application */
define('RootDir', dirname(__FILE__) . '/');

/* Directories */
define('APIDir', RootDir . 'API/');
define('ActionDir', RootDir . 'Actions/');
define('PageDir', RootDir . 'Pages/');
define('ModuleDir', RootDir . 'Modules/');
define('ConfigDir', RootDir . 'Config/');
define('StructureDir', RootDir . 'Structures/');

$ModuleList = array();
$FList = array();
$DirHandler = opendir(ModuleDir);
if ($DirHandler) {
    while (($entry = readdir($DirHandler)) !== false) {
        if ($entry != "." && $entry != "..") {
            if (is_dir(ModuleDir . $entry)) {
                $ModuleList[] = $entry;
                $FList[$entry] = array();
                $MDirHandler = opendir(ModuleDir . $entry);
                if ($MDirHandler) {
                    while (($mf = readdir($MDirHandler)) !== false) {
                        if ($mf != "." && $mf != "..") {
                            $FList[$entry][] = $mf;
                            $IncElement = ModuleDir . $entry . '/' . $mf;
                            if ($mf[0] == "_") {
                                array_unshift($IncludeList, $IncElement);
                            } else {
                                $IncludeList[] = $IncElement;
                            }
                        }
                    }
                    closedir($MDirHandler);
                }
            } else {
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

/* Please, Franklin */
$System = new \Franklin\System(DefaultLanguage, RootDir);

$VirtualParent = new \stdClass();
$VirtualParent->System = $System;

/* General */
$Statistics = array();

/* Language Settings */
$System->Language = new \Franklin\Data\Language($VirtualParent);
$System->Language->LoadByCode("hu");

define('Language', $System->Language->Code);
define('LanguageUC', $System->Language->CodeUC);
define('LanguageUnix', $System->Language->UnixCode);
define('LanguageId', $System->Language->Id);

setlocale(LC_ALL, LanguageUnix . ".UTF-8");

/* CleanURL */
if (in_array($System->URL->Query, $System->Aliases->Aliases)) {
    define('CleanURL', array_search($System->URL->Query, $System->Aliases->Aliases));
} else {
    define('CleanURL', $System->URL->Query);
}

/* Terms */
$TermObject = new \Franklin\Localization\Term($VirtualParent);
$TermList = $TermObject->Ls('`Status` = 1', '', 10000);
$LanguageUC = LanguageUC;
$Terms = array();
foreach ($TermList as $Term) {
    $Terms[$Term->Name] = $Term->$LanguageUC;
}


/* Easy Data Access */
$Data = filter_input_array(INPUT_POST);
$GetData = filter_input_array(INPUT_GET);

/* Actions */
$Action = explode('.', $System->Request->get('Action'));

$ActionGroup = $Action[0];
$ActionObject = $Action[1];
$ActionFire = $Action[2];

/* User */
$User = new \Franklin\User\User($VirtualParent);
$User->selfLoad();

/* Do the proper action */
$ActionModule = ActionDir . $ActionGroup . '/' . $ActionObject . '/' . $ActionFire . ".php";
if (file_exists($ActionModule)) {
    include($ActionModule);
}

/* Include the proper tracking */
$TrackingModule = TrackingDir . $ActionGroup . '/' . $ActionObject . '/' . $ActionFire . ".php";
if (file_exists($TrackingModule)) {
    include($TrackingModule);
}

define('Q', '/' . $System->Request->get('q', 'get'));

if (strtolower($System->Application) == 'api') {
    include(PageDir . 'API.php');
} else if ($System->Application == 'Admin') {
    if ($User->PL > 5) {
        include(PageDir . 'Admin.php');
    } else {
        if (!empty($System->Application)){
            header("Location: http://".$System->URL->Host."/?Location=" . str_replace(array('http://'.$System->URL->Host.'/', '?Action=Logout'), '', $System->URL));
        }
        include(PageDir . 'Login.php');
    }
} else {
    include(PageDir . 'Main.php');
}