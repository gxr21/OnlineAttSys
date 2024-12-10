<?php
require "../database/DB.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['Action']) && $_GET['Action'] === "getSubjects") {
        try {
            $stage = $_GET["stage"] ?? null;
            if ($stage) {
                $subjects = new DB("subjects");
                $subjectList = $subjects->find(["stage" => $stage])->get();
                
                echo json_encode([
                    "status" => "success",
                    "data" => $subjectList
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Stage parameter is missing."
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    } 
    elseif (isset($_GET['Action']) && $_GET['Action'] === "getAbs") {
        try {
            $subID = $_GET["subjectID"] ?? null;
            if ($subID) {
                $absence = new DB("absences");
                $absences = $absence->find(["subject_id" => $subID])->get();
                
                $groupedAbsences = [];
                
                foreach ($absences as $record) {
                    $studentID = $record["student_id"];
                    $absenceHours = (int)$record["absence_hours"];
                    
                    if (!isset($groupedAbsences[$studentID])) {
                        $groupedAbsences[$studentID] = 0;
                    }
                    
                    $groupedAbsences[$studentID] += $absenceHours;
                }

                $resultData = [];
                $studentDB = new DB("students"); 

                foreach ($groupedAbsences as $studentID => $totalHours) {
                    $studentInfo = $studentDB->find(["id" => $studentID]);
                    
                    $studentName = $studentInfo->first_name . " " . $studentInfo->middle_name . " " . $studentInfo->last_name;

                    $resultData[] = [
                        "student_id" => $studentID,
                        "student_name" => $studentName,
                        "total_hours" => $totalHours
                    ];
                }
                
                echo json_encode([
                    "status" => "success",
                    "data" => $resultData
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Subject ID parameter is missing."
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid action."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
