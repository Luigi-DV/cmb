<?php
// ==============================================
// Output Image using Code Interleaved 2 of 5
// ==============================================
require_once "C:/xampp/htdocs/ele/jpgraph/src/jpgraph_barcode.php";

$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->Stroke('1234');

?>
