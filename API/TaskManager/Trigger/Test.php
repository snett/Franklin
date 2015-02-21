<?php

//$VirtualParent->System->CMC = new \stdClass();
$VirtualParent->System->CMC->Email = new \Franklin\CMC\Email($VirtualParent);

$VirtualParent->System->Trigger = new \Franklin\TaskManager\Trigger($VirtualParent);

$Results['TaskManager.Trigger.Test']['Run'] = $VirtualParent->System->Trigger->Run();
