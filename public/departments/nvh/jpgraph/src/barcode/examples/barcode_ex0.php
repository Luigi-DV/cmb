<?php
// ==============================================
// Output Image using Code 39 using only default values
// ==============================================
require_once "C:/xampp/htdocs/nvh/jpgraph/src/jpgraph_barcode.php";

$encoder = BarcodeFactory::Create(ENCODING_CODE39);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetHeight(50);
$e->SetScale(10.0);
$e->Stroke();

?>
