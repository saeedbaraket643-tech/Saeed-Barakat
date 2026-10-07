<?php
require_once "db.php";
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>عيادتي - الرئيسية</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">


<!-- ================= Navbar ================= -->

<nav class="navbar navbar-expand-lg bg-primary shadow-sm">

    <div class="container">

        <a
            class="navbar-brand text-white fw-bold fs-4"
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
                        class="nav-link text-white fw-semibold"
                        href="dashboard.php"
                    >
                        الرئيسية
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link text-white fw-semibold"
                        href="patient.php"
                    >
                        المرضى
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link text-white fw-semibold"
                        href="#"
                    >
                        المواعيد
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link text-white fw-semibold"
                        href="payments.php"
                    >
                        المدفوعات
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- ================= Main ================= -->

<main class="container py-5">


    <!-- العنوان -->

    <div class="text-center mb-5">

        <h1 class="fw-bold text-primary">
            مرحباً بك في عيادتي 🏥
        </h1>

        <p class="text-muted">
            نظام إدارة العيادة
        </p>

    </div>


    <!-- Cards -->

    <div class="row justify-content-center g-4">


        <!-- المرضى -->

        <div class="col-12 col-sm-6 col-lg-4">

            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">

                <img
                    src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d"
                    class="card-img-top"
                    alt="المرضى"
                    style="height:220px; object-fit:cover;"
                >

                <div class="card-body text-center p-4">

                    <h4 class="fw-bold">
                        👨‍⚕️ المرضى
                    </h4>

                    <p class="text-muted">
                        إضافة المرضى وعرض بياناتهم وإدارتها.
                    </p>

                    <a
                        href="patient.php"
                        class="btn btn-primary px-4"
                    >
                        إضافة مريض
                    </a>

                </div>

            </div>

        </div>


        <!-- المواعيد -->

        <div class="col-12 col-sm-6 col-lg-4">

            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">

                <img
                    src="https://images.unsplash.com/photo-1584982751601-97dcc096659c"
                    class="card-img-top"
                    alt="المواعيد"
                    style="height:220px; object-fit:cover;"
                >

                <div class="card-body text-center p-4">

                    <h4 class="fw-bold">
                        📅 المواعيد
                    </h4>

                    <p class="text-muted">
                        تنظيم ومتابعة مواعيد الكشف.
                    </p>

                    <a
                        href="#"
                        class="btn btn-success px-4"
                    >
                        المواعيد
                    </a>

                </div>

            </div>

        </div>


        <!-- المدفوعات -->

        <div class="col-12 col-sm-6 col-lg-4">

            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">

                <img
                    src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d"
                    class="card-img-top"
                    alt="المدفوعات"
                    style="height:220px; object-fit:cover;"
                >

                <div class="card-body text-center p-4">

                    <h4 class="fw-bold">
                        💳 المدفوعات
                    </h4>

                    <p class="text-muted">
                        متابعة المدفوعات والمبالغ المستحقة.
                    </p>

                    <a
                        href="payments.php"
                        class="btn btn-warning px-4"
                    >
                        المدفوعات
                    </a>

                </div>

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