<?php

require_once "db.php";

date_default_timezone_set("Africa/Cairo");


// =====================================================
// أيام عمل الدكتور
// الجمعة إجازة
// =====================================================

$days = [
    "Saturday"  => "السبت",
    "Sunday"    => "الأحد",
    "Monday"    => "الإثنين",
    "Tuesday"   => "الثلاثاء",
    "Wednesday" => "الأربعاء",
    "Thursday"  => "الخميس"
];


// =====================================================
// مواعيد الدكتور
// من الساعة 1 ظهرًا إلى 9 مساءً
// =====================================================

$startHour = 13;
$endHour   = 21;


// =====================================================
// اليوم الحالي
// =====================================================

$todayEnglish = date("l");


// =====================================================
// تحويل الوقت
// =====================================================

function formatTime($hour)
{
    if ($hour == 12) {
        return "12:00 ظهرًا";
    }

    if ($hour < 12) {
        return $hour . ":00 صباحًا";
    }

    return ($hour - 12) . ":00 مساءً";
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

<title>مواعيد الدكتور - عيادتي</title>


<!-- Bootstrap -->

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>


<style>

/* =====================================================
   GENERAL
===================================================== */

body {

    background-color: #f5f7fb;

    font-family:
        Tahoma,
        Arial,
        sans-serif;

}


/* =====================================================
   NAVBAR
===================================================== */

.navbar-brand {

    font-size: 23px;

}


.nav-link {

    transition: 0.2s;

}


.nav-link:hover {

    opacity: 0.8;

}


/* =====================================================
   MAIN
===================================================== */

.main-container {

    padding-top: 40px;

    padding-bottom: 60px;

}


/* =====================================================
   CARDS
===================================================== */

.card {

    border: none;

    border-radius: 20px;

}


.day-card {

    border: none;

    border-radius: 20px;

    transition: 0.3s;

    overflow: hidden;

}


.day-card:hover {

    transform: translateY(-5px);

}


/* =====================================================
   DAY HEADER
===================================================== */

.day-header {

    background-color: #0d6efd;

    color: white;

    padding: 18px;

    text-align: center;

}


.day-header h5 {

    margin: 0;

    font-weight: bold;

}


/* =====================================================
   TIME
===================================================== */

.time-box {

    border: 1px solid #e9ecef;

    border-radius: 12px;

    padding: 12px;

    margin-bottom: 10px;

    background-color: #f8f9fa;

    font-weight: bold;

    text-align: center;

    transition: 0.2s;

}


.time-box:hover {

    background-color: #e7f1ff;

    border-color: #0d6efd;

    color: #0d6efd;

}


/* =====================================================
   FRIDAY
===================================================== */

.friday-box {

    border-radius: 20px;

}


/* =====================================================
   CLINIC HOURS BUTTON
===================================================== */

.clinic-hours-btn {

    border-radius: 12px;

    padding: 12px 25px;

    font-size: 17px;

    font-weight: bold;

}


/* =====================================================
   CLINIC HOURS
===================================================== */

.clinic-hours-card {

    background: white;

    border: none;

    border-radius: 20px;

    box-shadow:
        0 5px 20px
        rgba(0, 0, 0, 0.06);

}


.clinic-day {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 14px 16px;

    margin-bottom: 10px;

    background: #f8f9fa;

    border: 1px solid #e9ecef;

    border-radius: 12px;

}


.clinic-day strong {

    color: #212529;

}


.clinic-time {

    color: #0d6efd;

    font-weight: bold;

}


.clinic-friday {

    background-color: #fff1f1;

    border-color: #ffcaca;

}


.clinic-friday .clinic-time {

    color: #dc3545;

}


/* =====================================================
   TODAY
===================================================== */

.today-card {

    border: 3px solid #0d6efd;

}


.today-badge {

    font-size: 11px;

}


/* =====================================================
   FOOTER
===================================================== */

.footer {

    text-align: center;

    color: #6c757d;

    padding: 25px 0;

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 768px) {

    .main-container {

        padding-top: 25px;

    }

    .clinic-day {

        flex-direction: column;

        gap: 6px;

        text-align: center;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar navbar-expand-lg bg-primary shadow-sm">

<div class="container">


<a
    class="navbar-brand text-white fw-bold"
    href="dashboard.php"
>

    🏥 عيادتي

</a>


<button
    class="navbar-toggler bg-light"
    type="button"
    data-bs-toggle="collapse"
    data-bs-target="#navbarContent"
    aria-controls="navbarContent"
    aria-expanded="false"
    aria-label="القائمة"
>

<span class="navbar-toggler-icon"></span>

</button>


<div
    class="collapse navbar-collapse justify-content-center"
    id="navbarContent"
>


<ul class="navbar-nav gap-lg-3 text-center">


<li class="nav-item">

<a
    class="nav-link text-white"
    href="dashboard.php"
>

    الرئيسية

</a>

</li>


<li class="nav-item">

<a
    class="nav-link text-white"
    href="patient.php"
>

    المرضى

</a>

</li>


<li class="nav-item">

<a
    class="nav-link active text-white fw-bold"
    href="appointments.php"
>

    المواعيد

</a>

</li>


<li class="nav-item">

<a
    class="nav-link text-white"
    href="payments.php"
>

    المدفوعات

</a>

</li>


</ul>

</div>

</div>

</nav>



<!-- =====================================================
     CONTENT
===================================================== -->

<main class="container main-container">


<!-- =====================================================
     عنوان الصفحة
===================================================== -->

<div class="text-center mb-4">

<h1 class="fw-bold">

    📅 مواعيد الدكتور

</h1>

<p class="text-muted">

    جدول مواعيد الدكتور خلال أيام العمل

</p>

</div>



<!-- =====================================================
     زر مواعيد عمل العيادة
===================================================== -->

<div class="text-center mb-4">

<button
    type="button"
    class="btn btn-primary clinic-hours-btn"
    data-bs-toggle="collapse"
    data-bs-target="#clinicHours"
    aria-expanded="false"
    aria-controls="clinicHours"
>

    🕐 مواعيد عمل العيادة

</button>

</div>



<!-- =====================================================
     مواعيد عمل العيادة
===================================================== -->

<div
    class="collapse mb-5"
    id="clinicHours"
>


<div class="card clinic-hours-card shadow-sm">


<div class="card-body p-4">


<h4 class="text-center fw-bold mb-4">

    🏥 مواعيد عمل العيادة

</h4>


<div class="alert alert-primary text-center">

    العيادة تعمل من
    <strong>1:00 ظهرًا</strong>
    إلى
    <strong>9:00 مساءً</strong>

</div>



<!-- السبت -->

<div class="clinic-day">

<strong>

    السبت

</strong>

<span class="clinic-time">

    1:00 ظهرًا - 9:00 مساءً

</span>

</div>



<!-- الأحد -->

<div class="clinic-day">

<strong>

    الأحد

</strong>

<span class="clinic-time">

    1:00 ظهرًا - 9:00 مساءً

</span>

</div>



<!-- الإثنين -->

<div class="clinic-day">

<strong>

    الإثنين

</strong>

<span class="clinic-time">

    1:00 ظهرًا - 9:00 مساءً

</span>

</div>



<!-- الثلاثاء -->

<div class="clinic-day">

<strong>

    الثلاثاء

</strong>

<span class="clinic-time">

    1:00 ظهرًا - 9:00 مساءً

</span>

</div>



<!-- الأربعاء -->

<div class="clinic-day">

<strong>

    الأربعاء

</strong>

<span class="clinic-time">

    1:00 ظهرًا - 9:00 مساءً

</span>

</div>



<!-- الخميس -->

<div class="clinic-day">

<strong>

    الخميس

</strong>

<span class="clinic-time">

    1:00 ظهرًا - 9:00 مساءً

</span>

</div>



<!-- الجمعة -->

<div class="clinic-day clinic-friday">

<strong>

    الجمعة

</strong>

<span class="clinic-time">

    إجازة

</span>

</div>


</div>

</div>

</div>



<!-- =====================================================
     مواعيد الدكتور
===================================================== -->

<div class="row g-4">


<?php foreach ($days as $englishDay => $arabicDay): ?>


<?php

$isToday = ($todayEnglish === $englishDay);

?>


<div class="col-12 col-md-6 col-lg-4">


<div
    class="card day-card shadow-sm h-100
    <?php echo $isToday ? 'today-card' : ''; ?>"
>


<!-- رأس اليوم -->

<div class="day-header">


<h5>

<?php echo htmlspecialchars($arabicDay); ?>


<?php if ($isToday): ?>

<span class="badge bg-warning text-dark today-badge">

    اليوم

</span>

<?php endif; ?>


</h5>


</div>



<!-- المواعيد -->

<div class="card-body">


<?php for ($hour = $startHour; $hour <= $endHour; $hour++): ?>


<div class="time-box">

    🕐

    <?php echo formatTime($hour); ?>

</div>


<?php endfor; ?>


</div>


</div>


</div>


<?php endforeach; ?>



<!-- =====================================================
     الجمعة
===================================================== -->

<div class="col-12 col-md-6 col-lg-4">


<div class="card friday-box shadow-sm h-100">


<div class="card-body text-center d-flex flex-column justify-content-center">


<div style="font-size: 50px;">

    🛌

</div>


<h4 class="fw-bold mt-3">

    الجمعة

</h4>


<span class="badge bg-danger fs-6 mx-auto">

    إجازة

</span>


<p class="text-muted mt-3 mb-0">

    العيادة مغلقة يوم الجمعة

</p>


</div>


</div>


</div>


</div>


</main>



<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">

© <?php echo date("Y"); ?>

عيادتي - جميع الحقوق محفوظة

</footer>



<!-- =====================================================
     BOOTSTRAP JAVASCRIPT
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>















