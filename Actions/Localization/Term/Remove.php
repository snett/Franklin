<?php

$Term = new \Franklin\Localization\Term($VirtualParent);

$Term->Id = $Data['Term_Id'];

$Results[] = $Term->Remove();
