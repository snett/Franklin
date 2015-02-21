<?php

$Ingredients = new \Franklin\Data\Ingredients($VirtualParent);

$Ingredients->Name = $Data['Ingredient_Name'];
$Ingredients->Content = $Data['Ingredient_Content'];
$Ingredients->Image = $Data['Ingredient_Image'];
$Ingredients->Language->Id = $Data['Ingredient_Language_Id'];

$Results[] = $Ingredients->Create();
