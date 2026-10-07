<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>إضافة مريض</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<!-- Navbar -->

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
                        class="nav-link text-white"
                        href="dashboard.php"
                    >
                        الرئيسية
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link active text-white fw-bold"
                        href="patient.php"
                    >
                        المرضى
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


<!-- Form -->

<main class="container py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-md-8 col-lg-6">

            <div class="card border-0 shadow rounded-4">

                <div class="card-body p-4 p-md-5">


                    <h2 class="text-center fw-bold text-primary mb-4">

                        👤 بيانات المريض

                    </h2>


                    <form
                        action="save_patient.php"
                        method="POST"
                    >


                        <!-- الاسم -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                اسم المريض
                            </label>

                            <input
                                type="text"
                                name="patient_name"
                                class="form-control form-control-lg"
                                placeholder="اكتب اسم المريض"
                                required
                            >

                        </div>


                        <!-- السن -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                سن المريض
                            </label>

                            <input
                                type="number"
                                name="age"
                                class="form-control form-control-lg"
                                placeholder="اكتب السن"
                                min="1"
                                max="120"
                                required
                            >

                        </div>


                        <!-- الهاتف -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                رقم الموبايل
                            </label>

                            <input
                                type="tel"
                                name="phone"
                                class="form-control form-control-lg"
                                placeholder="01xxxxxxxxx"
                                required
                            >

                        </div>


                        <!-- التاريخ -->

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                يوم الكشف
                            </label>

                            <input
                                type="date"
                                name="examination_date"
                                class="form-control form-control-lg"
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                        >

                            💾 حفظ بيانات المريض

                        </button>


                    </form>


                </div>

            </div>

        </div>

    </div>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>