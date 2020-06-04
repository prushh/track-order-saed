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
    <title>Admin</title>
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
            <a href="logout.php" class="navbar-brand pull-right">Logout</a>
        </nav>

        <main role="main" class="inner cover">
            <div class="wrapper">

                <div class="page-header">
                    <h1>Dashboard
                        <b><?php echo htmlspecialchars($_SESSION['name']); ?></b>
                    </h1>
                </div>
                <?php
                $arr = json_decode(curl_api("GET", "http://localhost/track-orders-saed/api/order/get.php?token=1"));

                if (isset($arr->message)) {
                    print $arr->message;
                } else {
                    print "<table class='table'>";
                    print "<table class='table table-dark'>";
                    print "<thead>";
                    print "<tr>";
                    print "<th scope='col'>Ordine</th>";
                    print "<th>Effettuato il</th>";
                    print "<th>Tracking</th>";
                    print "<th>N° Articoli</th>";
                    print "<th>Totale</th>";
                    print "<th>Cliente</th>";
                    print "<th>E-mail</th>";
                    print "<th>Indirizzo</th>";
                    print "<th></th>";
                    print "</tr>";
                    print "</thead>";
                    print "<tbody>";

                    $link = '';

                    foreach ($arr->results as $key => $obj) {
                        print "<tr>";
                        print "<th scope='row'>" . $obj->id . "</th>";
                        print "<td>" . $obj->order_date . "</td>";
                        if ($obj->tracking_id === NULL) {
                            print "<td>-</td>";
                            $link = 'manage.php?order_id=' . $obj->id;
                        } else {
                            print "<td>" . $obj->tracking_id . "</td>";
                            $link = 'manage.php?order_id=' . $obj->id . '&tracking_id=' . $obj->tracking_id;
                        }
                        print "<td>" . $obj->n_items . "</td>";
                        print "<td>€ " . number_format($obj->total_cost, 2) . "</td>";
                        print "<td>" . $obj->name . " " . $obj->surname . "</td>";
                        print "<td>" . $obj->email . "</td>";
                        print "<td>" . $obj->address . "</td>";
                        print "<td><a href='$link' class='btn btn-primary'>Gestisci</a></td>";
                        print "<tr>";
                    }
                    print "</tbody>";
                    print "</table></table>";
                }

                ?>
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