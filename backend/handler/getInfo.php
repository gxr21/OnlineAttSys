<?php
require_once '../database/DB.php';
header("Content-Type: application/json");

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['stage'])) {
        echo json_encode(["status" => false, "message" => "المدخلات غير صحيحة."]);
        exit();
    }

    $stage = $input['stage'];

    $studentsDB = new DB("students");
    $subjectsDB = new DB("subjects");

    $studentsDB->find(["stage" => $stage]);
    $subjectsDB->find(["stage" => $stage]);

    $students = $studentsDB->get();
    $subjects = $subjectsDB->get();

    echo json_encode([
        "status" => true,
        "subjects" => $subjects,
        "students" => $students
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
