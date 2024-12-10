<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختيار المرحلة والمادة</title>
    <link rel="stylesheet" href="../css/absences.css">
</head>

<body>
    <h1>اختيار المرحلة والمادة</h1>
    <div class="container">
        <label for="stage">اختر المرحلة:</label>
        <select id="stage" onchange="fetchSubjects()">
            <option value="">حدد المرحلة</option>
            <option value="1">الأولى</option>
            <option value="2">الثانية</option>
            <option value="3">الثالثة</option>
            <option value="4">الرابعة</option>
        </select>

        <label for="subject">اختر المادة:</label>
        <select id="subject" onchange="fetchAbsence()">
            <option value="">حدد المادة</option>
        </select>

        <div id="absenceInfo">
            <h4>بيانات الغياب:</h4>
            <div id="absenceDetails" class="loading">يتم التحميل...</div>
        </div>
    </div>

    <script>
        function fetchSubjects() {
            const stageSelect = document.getElementById("stage");
            const subjectSelect = document.getElementById("subject");
            const stage = stageSelect.value;

            subjectSelect.innerHTML = '<option value="">جارٍ التحميل...</option>';

            if (stage) {
                const xhr = new XMLHttpRequest();
                xhr.open("GET", "http://localhost/OnlineAttSys/backend/handler/absShow.php?Action=getSubjects&stage=" + stage, true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === "success" && response.data.length > 0) {
                                subjectSelect.innerHTML = '<option value="">حدد المادة</option>';
                                response.data.forEach(subject => {
                                    const option = document.createElement("option");
                                    option.value = subject.id;
                                    option.textContent = subject.name;
                                    subjectSelect.appendChild(option);
                                });
                            } else {
                                alert(response.message || "لا توجد مواد لهذه المرحلة.");
                            }
                        } catch (error) {
                            console.error("خطأ في معالجة الرد:", error);
                            alert("حدث خطأ أثناء معالجة الرد من الخادم.");
                        }
                    }
                };

                xhr.send();
            } else {
                subjectSelect.innerHTML = '<option value="">حدد المادة</option>';
            }
        }

        function fetchAbsence() {
            const subjectSelect = document.getElementById("subject");
            const absenceDetails = document.getElementById("absenceDetails");
            const subjectID = subjectSelect.value;

            absenceDetails.innerHTML = 'جارٍ التحميل...';

            if (subjectID) {
                const xhr = new XMLHttpRequest();
                xhr.open("GET", "http://localhost/OnlineAttSys/backend/handler/absShow.php?Action=getAbs&subjectID=" + subjectID, true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === "success" && response.data.length > 0) {
                                const resultHTML = response.data.map(student => {
                                    const totalHours = 100;
                                    const absencePercentage = (student.total_hours / totalHours) * 100;

                                    let status = '';
                                    if (absencePercentage >= 10) {
                                        status = 'فصل';
                                    } else if (absencePercentage >= 6) {
                                        status = 'إنذار';
                                    } else if (absencePercentage >= 3) {
                                        status = 'تنبيه';
                                    } else {
                                        status = 'حسن السلوك';
                                    }

                                    return `
                                        <div>
                                            <p>اسم الطالب: ${student.student_name}</p>
                                            <p>عدد الساعات الغائبة: ${student.total_hours}</p>
                                            <p>نسبة الغياب: ${absencePercentage.toFixed(2)}%</p>
                                            <p>الحالة: ${status}</p>
                                        </div>
                                        <hr>
                                    `;
                                }).join('');

                                absenceDetails.innerHTML = resultHTML;
                            } else {
                                absenceDetails.innerHTML = "لا توجد بيانات غياب لهذه المادة.";
                            }
                        } catch (error) {
                            console.error("خطأ في معالجة الرد:", error);
                            absenceDetails.innerHTML = "حدث خطأ أثناء معالجة الرد.";
                        }
                    }
                };

                xhr.send();
            } else {
                absenceDetails.innerHTML = "يرجى اختيار المادة أولاً.";
            }
        }
    </script>
</body>
</html>
