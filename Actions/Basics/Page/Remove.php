<?php

$Page = new \Franklin\Component\Page($VirtualParent);

$Page->Id = $Data['Page_Id'];

$Results[] = $Page->Remove();
