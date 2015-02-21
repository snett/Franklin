<?php

$System->Stream->WriteLine("Installation started.");

$ObjectList = array();
$StructureFile = $System->Directory['Structures'] . "ObjectList.xml";
$Structure = new \Franklin\IO\XML($StructureFile);
$Objects = $Structure->XML->ObjectList;
foreach ($Objects->Object as $Object) {
    $ObjectList[(string) $Object->Name] = (string) $Object->Class;
}

foreach ($ObjectList as $ObjectName => $ObjectClass) {
    try {
        $VirtualParent = new \stdClass();
        $VirtualParent->System = $System;
        $Object = new $ObjectClass($VirtualParent);
        $System->Stream->WriteLine("$ObjectName instence created.");
        $Object->Build();
        $System->Stream->WriteLine("Build of $ObjectName Object was successful.");
    } catch (\Exception $Ex) {
        $ExMsg = $Ex->getMessage();
        $System->Stream->WriteLine($ExMsg);
    }
}

$runKeptBack = $System->Database->runKeptBack();
foreach ($runKeptBack as $KeptBack) {
    if (!$KeptBack) {
        $System->Stream->WriteLine("Kept back Database Query problem: " . $KeptBack, 'error');
    }
}

$Results['Franklin.System.Install'] = $System->Stream->__print();
