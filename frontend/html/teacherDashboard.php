<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../frontend/html/login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            margin: 0 auto;
            max-width: 1200px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        th {
            background-color: #4CAF50;
            color: white;
        
        }
        tr:hover {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .present {
            background-color: #90EE90; /* أخضر (حاضر) */
        }

        .absent {
            background-color: #FF6347; /* أحمر (غائب) */
        }

        select,
        button {
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>لوحة تحكم المعلم</h2>
        
        <div>
            <label for="stage">اختر المرحلة:</label>
            <select id="stage" onchange="fetchInfo()">
                <option value="">حدد المرحلة</option>
                <option value="1">الأولى</option>
                <option value="2">الثانية</option>
                <option value="3">الثالثة</option>
                <option value="4">الرابعة</option>
            </select>

            <label for="subject">اختر المادة:</label>
            <select id="subject">
                <option value="">حدد المادة</option>
            </select>

            <label for="hours">عدد الساعات:</label>
            <select id="hours">
                <option value="">حدد الساعات</option>
                <option value="1">1 ساعة</option>
                <option value="2">2 ساعة</option>
                <option value="3">3 ساعة</option>
            </select>
        </div>

        <div style="margin-top: 20px;">
            <table>
                <thead>
                    <tr>
                        <th>رقم السجل</th>
                        <th>الاسم</th>
                        <th>المرحلة</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody id="student-table-body"></tbody>
            </table>
        </div>

        <div style="text-align: center;">
            <button onclick="sendAbsentees()">إرسال الغياب</button>
        </div>
    </div>

    <script>
let studentsList = [];
let materials = [];
    
async function fetchInfo() {
    const stage = document.getElementById('stage').value;
    if (!stage) {
        alert("يجب اختيار المرحلة أولًا.");
        return;
    }

    try {
        const response = await fetch("../../backend/handler/getInfo.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ stage })
        });

        const result = await response.json();
        if (result.status) {
            materials = result.subjects; 
            populateStudents(result.students);
            populateSubjects();
        } else {
            alert("لم يتم العثور على بيانات.");
        }
    } catch (error) {
        alert("خطأ في الاتصال بالخادم.");
    }
}

function populateStudents(students) {
    const tableBody = document.getElementById('student-table-body');
    tableBody.innerHTML = "";

    studentsList = students; 
    students.forEach(student => {
        const row = document.createElement("tr");
        row.classList.add("present");
        row.innerHTML = `
            <td>${student.id}</td>
            <td>${student.first_name} ${student.middle_name} ${student.last_name}</td>
            <td>${student.stage}</td>
            <td onclick="toggleAttendance(this)"><button class="status-btn" style="background-color: #90EE90;">حاضر</button></td>
        `;
        tableBody.appendChild(row);
    });
}

function populateSubjects() {
    const subjectSelect = document.getElementById('subject');
    subjectSelect.innerHTML = "<option value=''>حدد المادة</option>";

    materials.forEach(subject => {
        const option = document.createElement("option");
        option.value = subject.id; 
        option.textContent = subject.name; 
        subjectSelect.appendChild(option);
    });
}

function toggleAttendance(cell) {
    if (cell.querySelector("button").style.backgroundColor === "rgb(144, 238, 144)") {
        cell.querySelector("button").style.backgroundColor = "#FF6347";
        cell.querySelector("button").textContent = "غائب";
    } else {
        cell.querySelector("button").style.backgroundColor = "#90EE90";
        cell.querySelector("button").textContent = "حاضر";
    }
}

function sendAbsentees() {
    const hours = document.getElementById('hours').value;
    const subject = document.getElementById('subject').value;

    if (!hours || !subject) {
        alert("يجب اختيار المادة وعدد الساعات.");
        return;
    }

    const absentees = [];
    const rows = document.querySelectorAll("#student-table-body tr");

    rows.forEach(row => {
        const btn = row.querySelector("button");
        if (btn.style.backgroundColor === "rgb(255, 99, 71)") { 
            absentees.push({
                id: row.children[0].textContent
            });
        }
    });

    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; 

    const dataToSend = {
        subject_id: subject, 
        absentees_ids: absentees.map(student => student.id), 
        hours: hours,
        date: formattedDate
    };

    fetch("../../backend/handler/absentees.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dataToSend)
    })
    .then(response => response.json())
    .then(result => {
        if (result.status) {
            alert("تم إرسال بيانات الغياب بنجاح.");
        } else {
            alert("فشل في إرسال البيانات.");
        }
    })
    .catch(error => {
        alert("خطأ في الاتصال بالخادم.");
    });
}
</script>


</body>
</html>
