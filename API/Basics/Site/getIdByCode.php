<?php

$Site = new \Franklin\Basic\Site($VirtualParent);
$Site->LoadByCode($Data['SiteCode']);
$Results['Basic.Site.getIdByCode'] = (int) $Site->Id;