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
$pdf->setSourceFile(__DIR__ . '/../template/APPLICATION_TEMPLATE_v3.pdf');
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 215.9, 330.2); // long bond size

$pdf->SetFont('Times', '', 12);

// --- Assign database values ---
$operator_name   = strtoupper(trim($data['FNAME'] . " " . $data['MNAME'] . " " . $data['LNAME']));
$applicant_1     = $operator_name;
$applicant_2     = $operator_name;
$applicant_affiant = $operator_name;
$franchise_no    = $data['FRANCHISE_NO'];
$mch_no          = $data['MCH_NO'];
$barangay        = strtoupper($data['BARANGAY']);
$make            = strtoupper($data['MAKE']);
$motor_no        = strtoupper($data['MOTOR_NO']);
$chassis_no      = strtoupper($data['CHASSIS_NO']);
$plate_no        = strtoupper($data['PLATE']);
$date_registered = date("F j, Y", strtotime($data['DATE'] ?? date("Y-m-d")));
$cedula_no       = strtoupper($data['CEDULA_NO']);
$municipality    = strtoupper($data['MUNICIPALITY']);
$cedula_date     = date("F j, Y", strtotime($data['CEDULA_DATE'] ?? date("Y-m-d")));

// --- Process date ---
preg_match('/^([A-Z]+ \d{1,2}), (\d{4})$/', $cedula_date, $matches);
$month_day = $matches[1] ?? "";
$year = $matches[2] ?? "";

$date = new DateTime($date_registered);
$day = $date->format('j');
$month = strtoupper($date->format('F'));
$suffix = getOrdinalSuffix($day);

// --- Write data to PDF ---
$pdf->SetXY(29, 55);
$pdf->Write(10, $operator_name);

$pdf->SetXY(148, 56);
$pdf->Write(7, $franchise_no);

$pdf->SetXY(148, 60);
$pdf->Write(7, $mch_no);

$pdf->SetXY(169, 92);
$pdf->Write(10, $barangay);

$pdf->SetXY(25, 115);
$pdf->Write(10, $make);

$pdf->SetXY(68, 115);
$pdf->Write(10, $motor_no);

$pdf->SetXY(120, 115);
$pdf->Write(10, $chassis_no);

$pdf->SetXY(170, 115);
$pdf->Write(10, $plate_no);

$pdf->SetXY(20, 153);
$pdf->Write(12, $date_registered);

$pdf->SetXY(157, 167);
$pdf->Write(12, $applicant_1);

$pdf->SetXY(25, 190);
$pdf->Write(12, $applicant_2);

$pdf->SetXY(157, 212);
$pdf->Write(12, $applicant_affiant);

// Day + suffix
$pdf->SetXY(90, 232);
$pdf->Write(12, $day);
$pdf->SetFont('Times', '', 8);
$pdf->SetXY($pdf->GetX() - 0.01, 234);
$pdf->Write(5, $suffix);

$pdf->SetFont('Times', 'B', 12);
$pdf->SetXY(125, 232);
$pdf->Write(12, $month);

$pdf->SetXY(150, 237);
$pdf->Write(12, $cedula_no);

$pdf->SetXY(15, 242);
$pdf->Write(11, $municipality);

$pdf->SetXY(75, 242);
$pdf->Write(11, $month_day);

$pdf->SetXY(113, 242);
$pdf->Write(11, $year);

// Output to browser
$pdf->Output('I', 'Filled_Application.pdf');
$conn->close();
?>
