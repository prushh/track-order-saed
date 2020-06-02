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

        <nav class="navbar navbar-dark bg-dark mb-5">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
                <span class="login_title">T</span>racking <span class="login_title">T</span>ool
            </a>
            <a href="logout.php" class="navbar-brand pull-right">Logout</a>
        </nav>

        <main role="main" class="inner cover">
            <div class="wrapper">

                <div class="page-header mb-3">
                    <?php
                        if (isset($_GET['order_id'])) {
                            print "<b><h1>Gestisci Ordine #".htmlspecialchars($_GET['order_id'])."</h1></b>";
                        }else{
                            print "<b><h1>Nessun ordine selezionato...</h1></b>";
                        }
                    ?>
                </div>

                <div class="container">

                    <!-- riga da mostrare solo se non è associato un tracking -->
                    <div class="row">
                        <div class="col-md-12 border_style">
                            <?php
                            if (isset($_GET['order_id'])) {
                                print "<h4 class='mb-5'>Associa un tracking all'ordine:</h4>";
                                echo '<form action="" method="post">
                                        <div class="form-group">
                                            <input type="text" name="email" class="form-control tracking_id" placeholder="Tracking ID">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" value="Associa">
                                        </div>
                                      </form>';
                            }
                            ?>
                        </div>
                    </div>
                    <!-- fine riga inserisci -->

                    <div class="row">
                        <div class="col-md-12">
                            Informazioni
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            Modifica
                        </div>
                        <div class="col-md-6">
                            Cancella
                        </div>
                    </div>
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