<?php

$Ingredients = new \Franklin\Data\Ingredients($VirtualParent);

$Ingredients->Id = $Data['Ingredient_Id'];

$Results[] = $Ingredients->Remove();
