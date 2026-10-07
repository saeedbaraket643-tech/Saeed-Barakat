<?php

require_once "db.php";


/* ================= PATIENTS ================= */

/* عدد المرضى */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM patients"
);

$row = mysqli_fetch_assoc($result);
$totalPatients = $row["total"];


/* مرضى اليوم */
$today = date("Y-m-d");

$resultToday = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM patients
     WHERE examination_date = '$today'"
);

$rowToday = mysqli_fetch_assoc($resultToday);
$todayPatients = $rowToday["total"];


/* ================= PAYMENTS ================= */

/* إجمالي المدفوعات */
$resultPaid = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(amount), 0) AS total
     FROM payments"
);

$rowPaid = mysqli_fetch_assoc($resultPaid);
$totalPaid = $rowPaid["total"];


/* إجمالي المديونيات القديمة */
$resultDebt = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(old_debt), 0) AS total
     FROM patient_balances"
);

$rowDebt = mysqli_fetch_assoc($resultDebt);
$totalDebt = $rowDebt["total"];


/* إجمالي المتبقي */
$totalRemaining = $totalDebt - $totalPaid;


/* عدد المرضى عليهم مديونية */
$resultDebtors = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM patient_balances
     WHERE old_debt > 0"
);

$rowDebtors = mysqli_fetch_assoc($resultDebtors);
$totalDebtors = $rowDebtors["total"];


/* ================= LATEST PATIENTS ================= */

$latestPatients = mysqli_query(
    $conn,
    "SELECT *
     FROM patients
     ORDER BY id DESC
     LIMIT 5"
);

?>


<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>لوحة التحكم - عيادتي</title>


    <!-- Bootstrap -->

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

        .dashboard-title {
            font-weight: 700;
        }

        .stat-card {
            border: none;
            border-radius: 20px;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            font-size: 25px;
        }

        .service-card {
            border: none;
            border-radius: 20px;
            transition: 0.3s;
        }

        .service-card:hover {
            transform: translateY(-5px);
        }

        .table-card {
            border: none;
            border-radius: 20px;
        }

    </style>

</head>


<body>


<!-- ================= NAVBAR ================= -->

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
                        class="nav-link active text-white fw-bold"
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
                        class="nav-link text-white"
                        href="#"
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


<!-- ================= CONTENT ================= -->

<main class="container py-5">


    <!-- العنوان -->

    <div class="mb-5">

        <h1 class="dashboard-title">
            لوحة التحكم 👋
        </h1>

        <p class="text-muted">
            أهلاً بك في نظام إدارة العيادة
        </p>

    </div>


    <!-- ================= STATISTICS ================= -->

    <div class="row g-4 mb-5">


        <!-- إجمالي المرضى -->

        <div class="col-12 col-sm-6 col-lg-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                إجمالي المرضى
                            </p>

                            <h2 class="fw-bold mb-0">
                                <?php echo $totalPatients; ?>
                            </h2>

                        </div>


                        <div class="stat-icon bg-primary bg-opacity-10">
                            👨‍⚕️
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- مرضى اليوم -->

        <div class="col-12 col-sm-6 col-lg-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                مرضى اليوم
                            </p>

                            <h2 class="fw-bold mb-0 text-success">

                                <?php
                                echo $todayPatients;
                                ?>

                            </h2>

                        </div>


                        <div class="stat-icon bg-success bg-opacity-10">
                            📅
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- إجمالي المدفوعات -->

        <div class="col-12 col-sm-6 col-lg-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                إجمالي المدفوعات
                            </p>

                            <h2 class="fw-bold mb-0 text-warning">

                                <?php
                                echo number_format($totalPaid, 2);
                                ?>

                                <small>ج.م</small>

                            </h2>

                        </div>


                        <div class="stat-icon bg-warning bg-opacity-10">
                            💰
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- المتبقي -->

        <div class="col-12 col-sm-6 col-lg-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                إجمالي المتبقي
                            </p>

                            <h2 class="fw-bold mb-0 text-danger">

                                <?php
                                echo number_format($totalRemaining, 2);
                                ?>

                                <small>ج.م</small>

                            </h2>

                        </div>


                        <div class="stat-icon bg-danger bg-opacity-10">
                            💸
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ================= DEBT INFORMATION ================= -->

    <div class="row g-4 mb-5">


        <!-- المديونيات القديمة -->

        <div class="col-12 col-md-6">

            <div class="card stat-card shadow-sm">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                المديونيات القديمة
                            </p>

                            <h3 class="fw-bold text-primary mb-0">

                                <?php
                                echo number_format($totalDebt, 2);
                                ?>

                                ج.م

                            </h3>

                        </div>

                        <div class="stat-icon bg-primary bg-opacity-10">
                            📋
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- عدد المدينين -->

        <div class="col-12 col-md-6">

            <div class="card stat-card shadow-sm">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                مرضى عليهم مديونية
                            </p>

                            <h3 class="fw-bold text-danger mb-0">

                                <?php echo $totalDebtors; ?>

                                مريض

                            </h3>

                        </div>

                        <div class="stat-icon bg-danger bg-opacity-10">
                            👤
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ================= SERVICES ================= -->

    <h4 class="fw-bold mb-4">
        الخدمات
    </h4>


    <div class="row g-4 mb-5">


        <!-- المرضى -->

        <div class="col-12 col-md-4">

            <div class="card service-card shadow-sm h-100">

                <div class="card-body text-center p-4">

                    <div class="display-4 mb-3">
                        👨‍⚕️
                    </div>

                    <h4 class="fw-bold">
                        المرضى
                    </h4>

                    <p class="text-muted">
                        إضافة وعرض وإدارة بيانات المرضى.
                    </p>

                    <a
                        href="patient.php"
                        class="btn btn-primary w-100"
                    >
                        إدارة المرضى
                    </a>

                </div>

            </div>

        </div>


        <!-- المواعيد -->

        <div class="col-12 col-md-4">

            <div class="card service-card shadow-sm h-100">

                <div class="card-body text-center p-4">

                    <div class="display-4 mb-3">
                        📅
                    </div>

                    <h4 class="fw-bold">
                        المواعيد
                    </h4>

                    <p class="text-muted">
                        تنظيم ومتابعة مواعيد المرضى.
                    </p>

                    <a
                        href="#"
                        class="btn btn-success w-100"
                    >
                        إدارة المواعيد
                    </a>

                </div>

            </div>

        </div>


        <!-- المدفوعات -->

        <div class="col-12 col-md-4">

            <div class="card service-card shadow-sm h-100">

                <div class="card-body text-center p-4">

                    <div class="display-4 mb-3">
                        💳
                    </div>

                    <h4 class="fw-bold">
                        المدفوعات
                    </h4>

                    <p class="text-muted">
                        متابعة المدفوعات والحسابات.
                    </p>

                    <a
                        href="payments.php"
                        class="btn btn-warning w-100"
                    >
                        إدارة المدفوعات
                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- ================= LATEST PATIENTS ================= -->

    <div class="card table-card shadow-sm">

        <div class="card-body p-4">


            <div class="d-flex justify-content-between align-items-center mb-4">

                <h4 class="fw-bold mb-0">
                    آخر المرضى
                </h4>


                <a
                    href="patient.php"
                    class="btn btn-primary"
                >
                    + إضافة مريض
                </a>

            </div>


            <div class="table-responsive">

                <table class="table table-hover align-middle text-center">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                اسم المريض
                            </th>

                            <th>
                                السن
                            </th>

                            <th>
                                الهاتف
                            </th>

                            <th>
                                يوم الكشف
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (mysqli_num_rows($latestPatients) > 0): ?>

                        <?php while ($patient = mysqli_fetch_assoc($latestPatients)): ?>

                            <tr>

                                <td>
                                    <?php echo $patient["id"]; ?>
                                </td>


                                <td class="fw-semibold">

                                    <?php
                                    echo htmlspecialchars(
                                        $patient["name"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $patient["age"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $patient["phone"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <span class="badge bg-primary">

                                        <?php
                                        echo htmlspecialchars(
                                            $patient["examination_date"]
                                        );
                                        ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endwhile; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="5"
                                class="text-muted py-4"
                            >

                                لا يوجد مرضى مسجلين حتى الآن.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


</main>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>