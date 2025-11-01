<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? $data['title'] : 'Default Title' ?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= BASEURL ?>/img/favicon.png">
    <!-- CSS -->
    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?= BASEURL ?>assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASEURL ?>assets/modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="<?= BASEURL ?>assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet"
        href="<?= BASEURL ?>assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= BASEURL ?>assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">


    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= BASEURL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASEURL ?>assets/css/components.css">

</head>

<body>

    <div id="app">
        <?php
        // Get the current full URL path
        $url = $_SERVER['REQUEST_URI'] ?? '';
        $basename = basename($url);
        ?>

        <?php if ($basename == 'admstr'): ?>
            <!-- Display login form for '/adm' -->
            <?= $content ?>
        <?php elseif (strpos($url, '/admstr') !== false): ?>
            <div class="container-fluid">
                <div class="row flex-nowrap">
                    <!-- Sidebar -->
                    <?php // include "sidebar.php"; ?>
                    <div class="col px-0">
                        <!-- Admin Navbar -->
                        <?php //    include "nav-admin.php" ?>
                        <!-- Main Content -->
                        <?= $content ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Navbar -->
            <?php // include "navbar.php"; ?>
            <!-- Main Content -->
            <?php Flasher::flash(); ?>
            <?= $content ?>
            <!-- Footer -->
            <?php // include "footer.php"; ?>
        <?php endif ?>
    </div>


    <!-- General JS Scripts -->
    <script src="<?= BASEURL ?>assets/modules/jquery.min.js"></script>
    <script src="<?= BASEURL ?>assets/modules/popper.js"></script>
    <script src="<?= BASEURL ?>assets/modules/tooltip.js"></script>
    <script src="<?= BASEURL ?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= BASEURL ?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="<?= BASEURL ?>assets/modules/moment.min.js"></script>
    <script src="<?= BASEURL ?>assets/js/stisla.js"></script>

    <script src="<?= BASEURL ?>assets/modules/datatables/datatables.min.js"></script>
    <script src="<?= BASEURL ?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= BASEURL ?>assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <script src="<?= BASEURL ?>assets/modules/jquery-ui/jquery-ui.min.js"></script>

    <!-- Page Specific JS File -->
    <script src="<?= BASEURL ?>assets/js/page/modules-datatables.js"></script>

    <!-- Template JS File -->
    <script src="<?= BASEURL ?>assets/js/scripts.js"></script>
    <script src="<?= BASEURL ?>assets/js/custom.js"></script>

</body>

</html>