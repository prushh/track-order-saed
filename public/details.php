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
            <div class="dropdown">
                <button class="btn dropdown-toggle" style="color: white;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo htmlspecialchars($_SESSION['name']); ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </div>
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

                <div class="container details">

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

                        print "<table class='table'>";
                        print "<table class='table table-dark'>";
                        print "<tbody>";
                        print "<tr><td>ID Ordine:</td><td><h5>" . $obj_order->id . "</h5></td></tr>";
                        print "<tr><td>Numero Articoli:</td><td><h5>" . $obj_order->n_items . "</h5></td></tr>";
                        print "<tr><td>Totale Ordine:</td><td><h5>" . $obj_order->total_cost . "</h5></td></tr>";
                        print "<tr><td>Effettuato il:</td><td><h5>" . $obj_order->order_date . "</h5></td></tr>";
                        if ($obj_order->tracking_id != NULL) {
                            $url = $ROOT_API . "tracking/get.php?tracking_id=" . $obj_order->tracking_id;
                            $arr_track = json_decode(curl_api("GET", $url));
                            $obj_track = $arr_track->results[0];
                            print "<tr><td>Spedito il:</td><td><h5>" . $obj_order->ship_date . "</h5></td></tr>";
                            if($obj_order->tracking_id == 5){
                                print "<tr><td>Consegnato il:</td><td><h5>" . $obj_order->delivery_date . "</h5></td></tr>";
                            }
                            print "<tr><td>Tracking ID:</td><td><h5>" . $obj_order->tracking_id . "</h5></td></tr>";
                            print "<tr><td>Corriere:</td><td><h5>" . $obj_track->courier . "</h5></td></tr>";
                            print "<tr><td>Stato Spedizione:</td><td><h5>" . $obj_track->title . "</h5></td></tr>";
                        }
                        print "</tbody>";
                        print "</table>";
                    } else {
                        // DA CENTRARE VERTICALMENTE, ANCHE BUTTON
                        print "<h5>Nessuna informazione su questo ordine.</h5>";
                    }
                    print "<br>";
                    print "<a href='myprofile.php' style='margin: 0 auto;' class='btn btn-primary'>Torna alla Home</a>";
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