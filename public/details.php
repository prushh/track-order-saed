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

require_once "utils.php";

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
    <link rel="shortcut icon" href="img/favicon.ico?v=1" type="image/x-icon">
</head>

<body class="text-center">

    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">

        <nav class="navbar navbar-dark bg-dark mb-5">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
                <span class="login_title">T</span>racking <span class="login_title">T</span>ool
            </a>
            <a href="logout.php" class="navbar-brand pull-right"><?php echo htmlspecialchars($_SESSION['name']); ?> Logout</a>
        </nav>

        <main role="main" class="inner cover">
            <div class="wrapper">

                <div class="page-header mb-3">
                    <?php
                    if (isset($_GET['order_id'])) {
                        print "<b><h1>Dettagli Ordine #" . htmlspecialchars($_GET['order_id']) . "</h1></b>";
                    } else {
                        print "<b><h1>Nessun ordine selezionato...</h1></b>";
                    }
                    ?>
                </div>

                <div class="container">
                    <?php
                    if (isset($_GET['order_id'])) {

                        $arr = json_decode(curl_api("GET", "http://localhost/track-order-saed/api/tracking/get.php?tracking_id=" . $_GET['tracking_id']));
                        if (isset($arr->results[0])) {
                            $obj = $arr->results[0];
                            if (isset($arr->message)) {
                                print $arr->message;
                            } else {
                                print "<div class='col-md-12 text-center'>";
                                print "ID Ordine: <h5>123123</h5>";
                                print "Numero Articoli: <h5>123123</h5>";
                                print "Totale Ordine: <h5>123123</h5>";
                                print "Effettuato il: <h5>123</h5>";
                                print "Spedito il: <h5>123</h5>";
                                print "Consegnato il: <h5>123</h5>";
                                print "Tracking ID: <h5>123</h5>";
                                print "Corriere: <h5>123</h5>";
                                print "Stato Spedizione: <h5>12312312312312312312123</h5><br>";
                                print "<a href='myprofile.php' class='btn btn-primary'>Torna alla Home</a>";
                                print "</div>";
                            }
                        } else {
                            // DA CENTRARE VERTICALMENTE
                            print "<h5>Nessuna informazione su questo ordine.</h5>";
                            print "<br>";
                            print "<a href='admin.php' class='btn btn-primary'>Torna alla Home</a>";
                        }
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