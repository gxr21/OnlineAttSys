<?php
header("Content-Type: application/json");
require '../database/DB.php';

$input = json_decode(file_get_contents("php://input"), true);

$subject_id = $input['subject_id'];
$absentees_ids = $input['absentees_ids'];
$hours = $input['hours'];
$date = $input['date'];

try {
    $db = new DB('absences');

    foreach ($absentees_ids as $student_id) {
        $db->create([
            "student_id" => $student_id,
            "subject_id" => $subject_id,
            "absence_date" => $date,
            "absence_hours" => $hours
        ]);
    }

    echo json_encode(["status" => true, "message" => "Absentees recorded successfully"]);
} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
