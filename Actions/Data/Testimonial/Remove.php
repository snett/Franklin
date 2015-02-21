<?php

$Testimonial = new \Franklin\Data\Testimonial($VirtualParent);

$Testimonial->Id = $Data['Testimonial_Id'];

$Results[] = $Testimonial->Remove();
