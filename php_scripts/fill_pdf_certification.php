<?php
require 'vendor/autoload.php';
use setasign\Fpdi\Fpdi;


// Create new PDF
$pdf = new Fpdi();
$pdf->AddPage();

// Import PDF template
$pdf->setSourceFile('CERTIFICATION_TEMPLATE_v3.pdf');
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 215.9, 330.2); // long bond size (8.5 x 13 in)

// Font setup
$pdf->SetFont('Times', '', 12);

// --- Hardcoded values ---
$operator_name   = "Juan Dela Cruz";
$barangay        = "MALONGCAY-DIOT";
$make            = "HONDA";
$chassis_no      = "CHS-78910";
$plate_no        = "XYZ-321";
$date_registered = "OCTOBER 1, 2025";


// --- Write text on template ---
$pdf->SetXY(60, 135);
$pdf->Write(10, $operator_name);

$pdf->SetXY(35, 140);
$pdf->Write(10, $barangay);

$pdf->SetXY(27, 130);
$pdf->Write(10, $make);

$pdf->SetXY(70, 130);
$pdf->Write(10, $chassis_no);

$pdf->SetXY(129, 130);
$pdf->Write(10, $plate_no);

$pdf->SetXY(150, 75);
$pdf->Write(12, $date_registered);



// Output PDF
$pdf->Output('I', 'Filled_Application.pdf');
?>
