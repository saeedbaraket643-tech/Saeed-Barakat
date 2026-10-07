<?php

require_once "db.php";


$name = $_POST["patient_name"] ?? "";
$age  = $_POST["age"] ?? "";
$phone = $_POST["phone"] ?? "";
$date = $_POST["examination_date"] ?? "";


if (
    empty($name) ||
    empty($age) ||
    empty($phone) ||
    empty($date)
) {

    die("من فضلك أكمل جميع البيانات.");

}


/* حماية البيانات */

$name  = mysqli_real_escape_string($conn, $name);
$age   = (int)$age;
$phone = mysqli_real_escape_string($conn, $phone);
$date  = mysqli_real_escape_string($conn, $date);


/* حفظ البيانات */

$sql = "
    INSERT INTO patients
    (name, age, phone, examination_date)
    VALUES
    ('$name', '$age', '$phone', '$date')
";


if (mysqli_query($conn, $sql)) {

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>تم الحفظ</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-md-7 col-lg-5">

            <div class="card border-0 shadow rounded-4">

                <div class="card-body text-center p-5">

                    <div class="display-1 mb-3">
                        ✅
                    </div>

                    <h2 class="text-success fw-bold">
                        تم حفظ المريض بنجاح
                    </h2>

                    <p class="text-muted mt-3">
                        تم تسجيل بيانات المريض في قاعدة البيانات.
                    </p>


                    <a
                        href="patient.php"
                        class="btn btn-primary mt-3"
                    >
                        إضافة مريض آخر
                    </a>


                    <a
                        href="dashboard.php"
                        class="btn btn-outline-secondary mt-3"
                    >
                        الرئيسية
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


</body>
</html>

<?php

} else {

    echo "حدث خطأ أثناء حفظ البيانات: "
        . mysqli_error($conn);

}

?>
