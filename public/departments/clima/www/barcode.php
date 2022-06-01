<?php
// ==============================================
// Output Image using Code 39 
// ==============================================
require_once "C:/xampp/htdocs/nvh/jpgraph/src/jpgraph_barcode.php";

$encoder = BarcodeFactory::Create(ENCODING_CODE39);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->NoText(true); // Cette ligne fait disparaitre le text du barcode
$e->SetHeight(40);
$e->Stroke($_REQUEST['code']);

?>
