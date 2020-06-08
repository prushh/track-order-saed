<?php
// Initialize session
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header('Location: login.php', true, 307);
    exit(0);
}

if ($_SESSION['type'] == 'admin') {
    header('Location: admin.php', true, 307);
    exit(0);
}

require_once "utils.php";

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Details</title>
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
                        $url = $ROOT_API . "order/get.php?order_id=" . $_GET['order_id'];
                        $arr_order = json_decode(curl_api("GET", $url));

                        $user_id = NULL;

                        if (isset($arr_order->message)) {
                            print "<h5>" . $arr_order->message . "</h5>";
                        } else if (isset($arr_order->results[0])) {
                            $obj_order = $arr_order->results[0];
                            $user_id = $obj_order->user_id;

                            print "<div class='col-md-12 text-center'>";
                            print "ID Ordine: <h5>" . $obj_order->id . "</h5>";
                            print "Numero Articoli: <h5>" . $obj_order->n_items . "</h5>";
                            print "Totale Ordine: <h5>" . $obj_order->total_cost . "</h5>";
                            print "Effettuato il: <h5>" . $obj_order->order_date . "</h5>";
                            if ($obj_order->tracking_id != NULL) {
                                $url = $ROOT_API . "tracking/get.php?tracking_id=" . $obj_order->tracking_id;
                                $arr_track = json_decode(curl_api("GET", $url));
                                $obj_track = $arr_track->results[0];

                                print "Spedito il: <h5>" . $obj_order->ship_date . "</h5>";
                                print "Consegnato il: <h5>" . $obj_order->delivery_date . "</h5>";
                                print "Tracking ID: <h5>" . $obj_order->tracking_id . "</h5>";
                                print "Corriere: <h5>" . $obj_track->courier . "</h5>";
                                print "Stato Spedizione: <h5>" . $obj_track->title . "</h5><br>";
                            }
                        } else {
                            // DA CENTRARE VERTICALMENTE, ANCHE BUTTON
                            print "<h5>Nessuna informazione su questo ordine.</h5>";
                        }
                        print "<br>";
                        print "<a href='myprofile.php' class='btn btn-primary'>Torna alla Home</a>";
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