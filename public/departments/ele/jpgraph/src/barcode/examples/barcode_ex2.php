<?php
// ==============================================
// Output Postscript of nterleaved 2 of 5
// ==============================================
require_once "C:/xampp/htdocs/ele/jpgraph/src/jpgraph_barcode.php";

$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
$e = BackendFactory::Create(BACKEND_PS,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$ps = $e->Stroke('3125134772');
echo nl2br(htmlspecialchars($ps));

?>
