<?php

$Testimonial = new \Franklin\Data\Testimonial($VirtualParent);

$Testimonial->Id = $Data['Testimonial_Id'];
$Testimonial->Name = $Data['Testimonial_Name'];
$Testimonial->Content = $Data['Testimonial_Content'];
$Testimonial->Image = $Data['Testimonial_Image'];
$Testimonial->Language->Id = $Data['Testimonial_Language_Id'];
$Testimonial->Excerpt->Id = $Data['Testimonial_Excerpt'];
$Testimonial->Signature->Id = $Data['Testimonial_Signature'];

$Results[] = $Testimonial->Set();
