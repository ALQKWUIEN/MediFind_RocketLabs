<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <title>Pharmacy Dashboard</title>

    <!-- Bootstrap and Js Framework -->
     
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- CSS and other Assets -->
    <link rel="stylesheet" href="../07_Assets/css/sidebar.css">
    <link rel="stylesheet" href="../07_Assets/css/topbar.css">
    <link rel="stylesheet" href="../07_Assets/css/04_Includes CSS/sidebar.css">

    <link rel="stylesheet" href="/07_Assets/node_modules/material-symbols/outlined.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap"     rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Page Transition -->
    <?php include '../01_Includes/page-transition.php'; ?>


    <style>

        #sidebar {
            width:  250px;
            height: 100vh;
            background-color: ;
            color: ;
            position: fixed;
            top: 0;
            left: 0;
            overflow:   hidden
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;

            height: 100vh;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <!-- Sidebar Includes -->
    <?php include '../01_Includes/topbar.php'; ?>
    <?php include '../01_Includes/pharmacy-sidebar.php'; ?>

</body>
</html>