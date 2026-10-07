<?php

require_once "db.php";


// =====================================================
// إنشاء جدول المدفوعات تلقائيًا
// =====================================================

$sql = "
CREATE TABLE IF NOT EXISTS payments (

    id INT AUTO_INCREMENT PRIMARY KEY,

    patient_id INT NOT NULL,

    amount DECIMAL(10,2) NOT NULL,

    payment_method VARCHAR(50) NOT NULL,

    payment_date DATE NOT NULL,

    payment_time TIME NOT NULL DEFAULT '00:00:00',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
";

if (!mysqli_query($conn, $sql)) {

    die("خطأ في إنشاء جدول المدفوعات: " . mysqli_error($conn));

}


// =====================================================
// إضافة عمود الوقت إذا كان الجدول موجودًا من قبل
// =====================================================

$checkColumn = mysqli_query(
    $conn,
    "
    SHOW COLUMNS FROM payments
    LIKE 'payment_time'
    "
);

if (mysqli_num_rows($checkColumn) == 0) {

    $addColumn = "
    ALTER TABLE payments
    ADD COLUMN payment_time TIME NOT NULL DEFAULT '00:00:00'
    AFTER payment_date
    ";

    if (!mysqli_query($conn, $addColumn)) {

        die(
            "خطأ في إضافة عمود وقت الدفع: "
            . mysqli_error($conn)
        );

    }

}


// =====================================================
// إنشاء جدول الأرصدة القديمة تلقائيًا
// =====================================================

$sql = "
CREATE TABLE IF NOT EXISTS patient_balances (

    id INT AUTO_INCREMENT PRIMARY KEY,

    patient_id INT NOT NULL UNIQUE,

    old_debt DECIMAL(10,2) DEFAULT 0

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
";

if (!mysqli_query($conn, $sql)) {

    die("خطأ في إنشاء جدول الأرصدة: " . mysqli_error($conn));

}


// =====================================================
// إضافة دفعة
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST["patient_id"])
) {

    $patient_id = (int)($_POST["patient_id"] ?? 0);

    $amount = (float)($_POST["amount"] ?? 0);

    $payment_method = mysqli_real_escape_string(
        $conn,
        $_POST["payment_method"] ?? ""
    );

    $payment_date = mysqli_real_escape_string(
        $conn,
        $_POST["payment_date"] ?? ""
    );

    $payment_time = mysqli_real_escape_string(
        $conn,
        $_POST["payment_time"] ?? ""
    );


    // لو المستخدم لم يدخل الوقت
    // نضع الوقت الحالي تلقائيًا

    if ($payment_time == "") {

        $payment_time = date("H:i");

    }


    if (
        $patient_id > 0 &&
        $amount > 0 &&
        $payment_method != "" &&
        $payment_date != ""
    ) {

        $sql = "
        INSERT INTO payments
        (
            patient_id,
            amount,
            payment_method,
            payment_date,
            payment_time
        )

        VALUES
        (
            $patient_id,
            $amount,
            '$payment_method',
            '$payment_date',
            '$payment_time'
        )
        ";


        if (!mysqli_query($conn, $sql)) {

            die(
                "حدث خطأ أثناء حفظ الدفعة: "
                . mysqli_error($conn)
            );

        }

    }


    header("Location: payments.php");

    exit;

}


// =====================================================
// إضافة رصيد قديم
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST["old_debt"])
) {


    $patient_id = (int)(
        $_POST["old_patient_id"] ?? 0
    );


    $old_debt = (float)(
        $_POST["old_debt"] ?? 0
    );


    if (
        $patient_id > 0
        && $old_debt >= 0
    ) {

        // هل يوجد رصيد سابق للمريض؟

        $check = mysqli_query(
            $conn,

            "
            SELECT id

            FROM patient_balances

            WHERE patient_id = $patient_id
            "
        );


        if (mysqli_num_rows($check) > 0) {

            mysqli_query(
                $conn,

                "
                UPDATE patient_balances

                SET old_debt = $old_debt

                WHERE patient_id = $patient_id
                "
            );

        } else {

            mysqli_query(
                $conn,

                "
                INSERT INTO patient_balances
                (
                    patient_id,
                    old_debt
                )

                VALUES
                (
                    $patient_id,
                    $old_debt
                )
                "
            );

        }

    }


    header("Location: payments.php");

    exit;

}


// =====================================================
// حذف دفعة
// =====================================================

if (isset($_GET["delete"])) {

    $id = (int)$_GET["delete"];


    if ($id > 0) {

        mysqli_query(
            $conn,

            "
            DELETE FROM payments

            WHERE id = $id
            "
        );

    }


    header("Location: payments.php");

    exit;

}


// =====================================================
// إجمالي المدفوعات
// =====================================================

$result = mysqli_query(
    $conn,

    "
    SELECT
        COALESCE(SUM(amount), 0) AS total

    FROM payments
    "
);


$row = mysqli_fetch_assoc($result);

$totalPayments = $row["total"] ?? 0;


// =====================================================
// مدفوعات اليوم
// =====================================================

$today = date("Y-m-d");


$result = mysqli_query(
    $conn,

    "
    SELECT
        COALESCE(SUM(amount), 0) AS total

    FROM payments

    WHERE payment_date = '$today'
    "
);


$row = mysqli_fetch_assoc($result);

$todayPayments = $row["total"] ?? 0;


// =====================================================
// عدد العمليات
// =====================================================

$result = mysqli_query(
    $conn,

    "
    SELECT
        COUNT(*) AS total

    FROM payments
    "
);


$row = mysqli_fetch_assoc($result);

$paymentCount = $row["total"] ?? 0;


// =====================================================
// جلب المرضى
// =====================================================

$patients = mysqli_query(
    $conn,

    "
    SELECT
        id,
        name

    FROM patients

    ORDER BY name ASC
    "
);


if (!$patients) {

    die(
        "خطأ في جدول المرضى: "
        . mysqli_error($conn)
    );

}


// =====================================================
// جلب سجل المدفوعات
// =====================================================

$payments = mysqli_query(
    $conn,

    "
    SELECT

        payments.id,

        payments.patient_id,

        patients.name AS patient_name,

        payments.amount,

        payments.payment_method,

        payments.payment_date,

        payments.payment_time

    FROM payments

    INNER JOIN patients

    ON payments.patient_id = patients.id

    ORDER BY
        payments.id DESC
    "
);


if (!$payments) {

    die(
        "خطأ في جلب المدفوعات: "
        . mysqli_error($conn)
    );

}

?>


<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>المدفوعات - عيادتي</title>


<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>


<style>

body {

    background-color: #f5f7fb;

}

.navbar-brand {

    font-size: 23px;

}

.card {

    border: none;

    border-radius: 20px;

}

.icon {

    font-size: 40px;

}

</style>

</head>


<body>


<!-- ================================================= -->
<!-- NAVBAR -->
<!-- ================================================= -->

<nav class="navbar navbar-expand-lg bg-primary shadow-sm">

<div class="container">


<a
    class="navbar-brand text-white fw-bold"
    href="dashboard.php"
>

    🏥 عيادتي

</a>


<div>

<a
    href="dashboard.php"
    class="btn btn-light me-2"
>

    الرئيسية

</a>


<a
    href="patient.php"
    class="btn btn-light"
>

    المرضى

</a>

</div>


</div>

</nav>



<main class="container py-5">


<!-- ================================================= -->
<!-- العنوان -->
<!-- ================================================= -->

<div class="text-center mb-5">

<h1 class="fw-bold text-primary">

    💳 نظام المدفوعات

</h1>


<p class="text-muted">

    إدارة دفعات المرضى والأرصدة القديمة

</p>

</div>



<!-- ================================================= -->
<!-- الإحصائيات -->
<!-- ================================================= -->

<div class="row g-4 mb-5">


<div class="col-md-4">

<div class="card shadow-sm h-100">

<div class="card-body text-center p-4">

<div class="icon">

    💰

</div>

<p class="text-muted">

    إجمالي المدفوعات

</p>

<h2 class="text-success fw-bold">

<?php

echo number_format(
    $totalPayments,
    2
);

?>

<small>ج.م</small>

</h2>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card shadow-sm h-100">

<div class="card-body text-center p-4">

<div class="icon">

    📅

</div>

<p class="text-muted">

    مدفوعات اليوم

</p>

<h2 class="text-primary fw-bold">

<?php

echo number_format(
    $todayPayments,
    2
);

?>

<small>ج.م</small>

</h2>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card shadow-sm h-100">

<div class="card-body text-center p-4">

<div class="icon">

    🧾

</div>

<p class="text-muted">

    عدد العمليات

</p>

<h2 class="text-warning fw-bold">

<?php

echo $paymentCount;

?>

</h2>

</div>

</div>

</div>


</div>



<!-- ================================================= -->
<!-- إضافة رصيد قديم -->
<!-- ================================================= -->

<div class="card shadow-sm mb-5">

<div class="card-body p-4">


<h3 class="fw-bold mb-4">

    📌 إضافة مديونية قديمة

</h3>


<form method="POST">


<div class="row g-4">


<div class="col-md-6">

<label class="form-label fw-bold">

    المريض

</label>


<select
    name="old_patient_id"
    class="form-select form-select-lg"
    required
>


<option value="">

    اختر المريض

</option>


<?php


$oldPatients = mysqli_query(
    $conn,

    "
    SELECT
        id,
        name

    FROM patients

    ORDER BY name ASC
    "
);


while (
    $patient =
    mysqli_fetch_assoc($oldPatients)
):

?>


<option
    value="<?php echo $patient["id"]; ?>"
>


<?php

echo htmlspecialchars(
    $patient["name"]
);

?>


</option>


<?php

endwhile;

?>


</select>

</div>



<div class="col-md-6">

<label class="form-label fw-bold">

    المبلغ القديم عليه

</label>


<div class="input-group">


<input
    type="number"
    name="old_debt"
    class="form-control form-control-lg"
    placeholder="مثال: 500"
    min="0"
    step="0.01"
    required
>


<span class="input-group-text">

    ج.م

</span>


</div>

</div>



<div class="col-12">


<button
    type="submit"
    class="btn btn-warning btn-lg px-5"
>

    💾 حفظ المديونية القديمة

</button>


</div>


</div>

</form>


</div>

</div>



<!-- ================================================= -->
<!-- إضافة دفعة جديدة -->
<!-- ================================================= -->

<div class="card shadow-sm mb-5">

<div class="card-body p-4">


<h3 class="fw-bold mb-4">

    ➕ إضافة دفعة جديدة

</h3>


<form method="POST">


<div class="row g-4">


<!-- المريض -->

<div class="col-md-6">

<label class="form-label fw-bold">

    المريض

</label>


<select
    name="patient_id"
    class="form-select form-select-lg"
    required
>


<option value="">

    اختر المريض

</option>


<?php


while (
    $patient =
    mysqli_fetch_assoc($patients)
):

?>


<option
    value="<?php echo $patient["id"]; ?>"
>


<?php

echo htmlspecialchars(
    $patient["name"]
);

?>


</option>


<?php

endwhile;

?>


</select>

</div>



<!-- المبلغ -->

<div class="col-md-6">

<label class="form-label fw-bold">

    المبلغ المدفوع

</label>


<div class="input-group">


<input
    type="number"
    name="amount"
    class="form-control form-control-lg"
    placeholder="مثال: 300"
    min="1"
    step="0.01"
    required
>


<span class="input-group-text">

    ج.م

</span>


</div>

</div>



<!-- طريقة الدفع -->

<div class="col-md-6">

<label class="form-label fw-bold">

    طريقة الدفع

</label>


<select
    name="payment_method"
    class="form-select form-select-lg"
    required
>


<option value="">

    اختر طريقة الدفع

</option>


<option value="نقدي">

    💵 نقدي

</option>


<option value="بطاقة">

    💳 بطاقة

</option>


<option value="تحويل">

    🏦 تحويل

</option>


</select>

</div>



<!-- التاريخ -->

<div class="col-md-6">

<label class="form-label fw-bold">

    تاريخ الدفع

</label>


<input
    type="date"
    name="payment_date"
    class="form-control form-control-lg"
    value="<?php echo date("Y-m-d"); ?>"
    required
>

</div>



<!-- الوقت -->

<div class="col-md-6">

<label class="form-label fw-bold">

    وقت الدفع

</label>


<input
    type="time"
    name="payment_time"
    class="form-control form-control-lg"
    value="<?php echo date("H:i"); ?>"
    required
>

</div>



<div class="col-12">

<button
    type="submit"
    class="btn btn-primary btn-lg px-5"
>

    💾 حفظ الدفعة

</button>

</div>


</div>

</form>

</div>

</div>



<!-- ================================================= -->
<!-- سجل المدفوعات -->
<!-- ================================================= -->

<div class="card shadow-sm">

<div class="card-body p-4">


<h3 class="fw-bold mb-4">

    📋 سجل المدفوعات

</h3>


<div class="table-responsive">


<table class="table table-hover text-center align-middle">


<thead class="table-primary">


<tr>

<th>#</th>

<th>المريض</th>

<th>الرصيد القديم</th>

<th>الدفعة الحالية</th>

<th>إجمالي المدفوع</th>

<th>المتبقي</th>

<th>طريقة الدفع</th>

<th>التاريخ</th>

<th>الوقت</th>

<th>حذف</th>

</tr>


</thead>


<tbody>


<?php


if (mysqli_num_rows($payments) > 0):


while (
    $payment =
    mysqli_fetch_assoc($payments)
):


    $patientId =
        (int)$payment["patient_id"];


    // =============================================
    // جلب الرصيد القديم
    // =============================================

    $balanceQuery = mysqli_query(
        $conn,

        "
        SELECT
            old_debt

        FROM patient_balances

        WHERE patient_id = $patientId
        "
    );


    $balanceRow =
        mysqli_fetch_assoc($balanceQuery);


    $oldDebt =
        $balanceRow["old_debt"] ?? 0;


    // =============================================
    // حساب إجمالي المدفوع للمريض
    // =============================================

    $paidQuery = mysqli_query(
        $conn,

        "
        SELECT
            COALESCE(SUM(amount), 0) AS total

        FROM payments

        WHERE patient_id = $patientId
        "
    );


    $paidRow =
        mysqli_fetch_assoc($paidQuery);


    $paidTotal =
        $paidRow["total"] ?? 0;


    // =============================================
    // حساب المتبقي
    // =============================================

    $remaining =
        $oldDebt - $paidTotal;


?>


<tr>


<td>

<?php

echo $payment["id"];

?>

</td>



<td class="fw-bold">

<?php

echo htmlspecialchars(
    $payment["patient_name"]
);

?>

</td>



<td>

<?php

echo number_format(
    $oldDebt,
    2
);

?>

 ج.م

</td>



<td class="text-success fw-bold">

<?php

echo number_format(
    $payment["amount"],
    2
);

?>

 ج.م

</td>



<td class="text-primary fw-bold">

<?php

echo number_format(
    $paidTotal,
    2
);

?>

 ج.م

</td>



<td>


<?php


if ($remaining > 0) {


?>

<span class="badge bg-danger fs-6">

متبقي

<?php

echo number_format(
    $remaining,
    2
);

?>

 ج.م

</span>


<?php


} elseif ($remaining == 0) {


?>

<span class="badge bg-success fs-6">

    ✅ تم السداد

</span>


<?php


} else {


?>

<span class="badge bg-info fs-6">

له رصيد

<?php

echo number_format(
    abs($remaining),
    2
);

?>

 ج.م

</span>


<?php

}

?>


</td>



<td>


<?php


if (
    $payment["payment_method"] == "نقدي"
) {

    echo "💵 نقدي";

} elseif (
    $payment["payment_method"] == "بطاقة"
) {

    echo "💳 بطاقة";

} else {

    echo "🏦 تحويل";

}

?>


</td>



<td>

<?php

echo htmlspecialchars(
    $payment["payment_date"]
);

?>

</td>



<td>

<?php

echo date(
    "H:i",
    strtotime($payment["payment_time"])
);

?>

</td>



<td>

<a
    href="payments.php?delete=<?php
        echo $payment["id"];
    ?>"
    class="btn btn-sm btn-outline-danger"
    onclick="
        return confirm(
            'هل أنت متأكد من حذف الدفعة؟'
        );
    "
>

    🗑️

</a>

</td>


</tr>


<?php

endwhile;


else:

?>


<tr>

<td
    colspan="10"
    class="text-muted py-5"
>

<div class="fs-1">

    💳

</div>


لا توجد مدفوعات حتى الآن.


</td>

</tr>


<?php

endif;

?>


</tbody>

</table>

</div>

</div>

</div>


</main>


</body>

</html>
