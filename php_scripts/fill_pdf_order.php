<?php
require __DIR__ . '/../backend/vendor/autoload.php';
use setasign\Fpdi\Fpdi;


// --- Database connection (same as your .env in Laravel) ---
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "business_permit_license"; // ⚠️ change to your actual DB name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// --- Get record ID from URL ---
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid record ID.");
}

// --- Fetch record from database ---
$sql = "SELECT * FROM bplo_records WHERE ID = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Record not found.");
}

$data = $result->fetch_assoc();

// --- FPDI setup ---
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

$pdf = new Fpdi();
$pdf->AddPage();
$pdf->setSourceFile(__DIR__ . '/../template/ORDER_TEMPLATE_v3.pdf');
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 215.9, 330.2); // long bond size

$pdf->SetFont('Times', '', 12);

// --- Hardcoded values ---
$operator_name   = strtoupper(trim($data['FNAME'] . " " . $data['MNAME'] . " " . $data['LNAME']));
$franchise_no    = $data['FRANCHISE_NO'];
$mch_no          = $data['MCH_NO'];
$barangay        = strtoupper($data['BARANGAY']);
$make            = strtoupper($data['MAKE']);
$motor_no        = strtoupper($data['MOTOR_NO']);
$chassis_no      = strtoupper($data['CHASSIS_NO']);
$date_pay = date("F j, Y", strtotime($data['DATE'] ?? date("Y-m-d")));
$date_pay2 = $date_pay;
$date_renewed_from = date("F j, Y", strtotime($data['RENEW_FROM'] ?? date("Y-m-d")));
$date_renewed_to = date("F j, Y", strtotime($data['RENEW_TO'] ?? date("Y-m-d")));

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
