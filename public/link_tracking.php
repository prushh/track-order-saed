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
require_once "../database/connection.php";

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Link Tracking</title>
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

        <header class="masthead mb-auto">
            <div class="inner">
                <a class="home_logo" href="index.php">
                    <img src="img/logo.png" alt="">
                </a>
                <h2><span class="login_title">T</span>racking <span class="login_title">T</span>ool</h2>
            </div>
        </header>

        <main role="main" class="inner cover">
            <!-- DECIDERE SE LASCIARE HEADER, POSIZIONARE MEGLIO MESSAGGIO E BOTTONE -->
            <div class='col-md-12 mb-5'>
                <?php
                $order_id = $tracking_id = NULL;
                if (isset($_POST['order_id']) && isset($_POST['tracking_id'])) {
                    $order_id = $_POST['order_id'];
                    $tracking_id = $_POST['tracking_id'];
                } else if (isset($_GET['order_id']) && isset($_GET['tracking_id'])) {
                    $order_id = $_GET['order_id'];
                    $tracking_id = $_GET['tracking_id'];
                }

                if ($order_id != NULL && $tracking_id != NULL) {
                    if (!empty($tracking_id) && !empty($order_id)) {
                        $data = array(
                            "id" => $order_id,
                            "tracking_id" => $tracking_id
                        );
                        $url = $ROOT_API . "order/put.php";
                        $arr = json_decode(curl_api("PUT", $url, $data));
                        print "<h5>" . $arr->message . "</h5>";
                    } else {
                        print "<h5>Impossibile eseguire l'operazione richiesta.</h5>";
                    }
                } else {
                    print "<h5>Impossibile eseguire l'operazione richiesta.</h5>";
                }
                ?>
                <a href="admin.php" class="btn btn-primary">Torna alla dashboard</a>
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