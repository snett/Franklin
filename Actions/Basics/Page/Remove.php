<?php

$Page = new \Franklin\Basics\Page($VirtualParent);

$Page->Id = $Data['Page_Id'];

$Results[] = $Page->Remove();
