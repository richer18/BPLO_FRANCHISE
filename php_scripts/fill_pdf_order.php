<?php
require 'vendor/autoload.php';
use setasign\Fpdi\Fpdi;

// Function: Get suffix only
function getOrdinalSuffix($number) {
    if (!in_array(($number % 100), [11, 12, 13])) {
        switch ($number % 10) {
            case 1: return "ST";
            case 2: return "ND";
            case 3: return "RD";
        }
    }
    return "TH";
}

// Create new PDF
$pdf = new Fpdi();
$pdf->AddPage();

// Import PDF template
$pdf->setSourceFile('ORDER_TEMPLATE_v3.pdf');
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 215.9, 330.2); // long bond size (8.5 x 13 in)

// Font setup
$pdf->SetFont('Times', '', 12);

// --- Hardcoded values ---
$operator_name   = "Juan Dela Cruz";
$franchise_no    = "FR-2025-001";
$mch_no          = "001";
$barangay        = "MALONGCAY-DIOT";
$make            = "HONDA";
$motor_no        = "MTR-123456";
$chassis_no      = "CHS-78910";
$date_pay = "OCTOBER 1, 2025";
$date_pay2 = $date_pay;
$date_renewed_from = "JANUARY 30, 2025";
$date_renewed_to = "JANUARY 30, 2026";

// --- Write text on template ---
$pdf->SetFont('Times', 'B', 11);
$pdf->SetXY(37, 55);
$pdf->Write(10, $operator_name);

$pdf->SetXY(158, 44);
$pdf->Write(8, $franchise_no);

$pdf->SetXY(158, 49);
$pdf->Write(7, $mch_no);

$pdf->SetXY(37, 59);
$pdf->Write(11, $barangay);

$pdf->SetXY(50, 145);
$pdf->Write(10, $make);

$pdf->SetXY(99, 145);
$pdf->Write(10, $motor_no);

$pdf->SetXY(161, 145);
$pdf->Write(10, $chassis_no);

$pdf->SetFont('Times', 'B', 12); // 'B' = Bold
$pdf->SetXY(69, 215);
$pdf->Write(10, $date_renewed_from . " TO " . $date_renewed_to);

$pdf->SetXY(87, 98);
$pdf->Write(12, $date_pay);

$pdf->SetXY(37, 255);
$pdf->Write(9, $date_pay2);


// Output PDF
$pdf->Output('I', 'Filled_Application.pdf');
?>
