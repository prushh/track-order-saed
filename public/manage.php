<?php
// Initialize session
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header('Location: login.php', true, 307);
    exit(0);
}

if ($_SESSION['type'] == 'user') {
    header('Location: myprofile.php', true, 307);
    exit(0);
}

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Manage</title>
    <!-- Bootstrap Framework -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>

<body class="text-center">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">

        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
                <span class="login_title">T</span>racking <span class="login_title">T</span>ool
            </a>
            <a href="logout.php" class="navbar-brand pull-right">Logout</a>
        </nav>

        <main role="main" class="inner cover">
            <div class="wrapper">

                <div class="page-header">
                    <?php
                        if (isset($_GET['order_id'])) {
                            print "<b><h1>Gestisci ordine n.".htmlspecialchars($_GET['order_id'])."</h1></b>";
                        }else{
                            print "<b><h1>Nessun ordine selezionato...</h1></b>";
                        }
                    ?>
                </div>
            </div>
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">
                <p>© 2020 Tracking Tool - Developed by Amir & Davide</p>
            </div>
        </footer>
    </div>


</body>

</html>