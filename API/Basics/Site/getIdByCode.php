<?php

$Site = new \Franklin\Basics\Site($VirtualParent);
$Site->LoadByCode($Data['SiteCode']);
$Results['Basics.Site.getIdByCode'] = (int) $Site->Id;