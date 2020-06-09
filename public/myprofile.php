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
    <title>User</title>
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
                <button class="btn dropdown-toggle dropname" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo htmlspecialchars($_SESSION['name']); ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </div>
        </nav>

        <main role="main" class="inner cover">
            <div class="wrapper">
                <div class="page-header">
                    <h5>Benvenuto <b><?php echo htmlspecialchars($_SESSION['name']); ?></b>, questo è lo storico dei tuoi ordini:</h5>
                </div>
                <?php
                $url = $ROOT_API . "order/get.php?user_id=" . $_SESSION['id'];
                $arr = json_decode(curl_api("GET", $url));

                print "<table class='table'>";
                print "<table class='table table-dark'>";
                print "<thead>";
                print "<tr>";
                print "<th scope='col'>Ordine</th>";
                print "<th>Effettuato il</th>";
                print "<th>Totale</th>";
                print "<th></th>";
                print "</tr>";
                print "</thead>";
                print "<tbody>";
                if (isset($arr->message)) {
                    // RIVEDERE COME MOSTRARE MESSAGGIO
                    print "<tr><td colspan='4'><h5>" . $arr->message . "</h5></td></tr>";
                } else {
                    $num_orders = sizeof($arr->results);
                    if ($num_orders == 0) {
                        print "<tr colspan='4'>";
                        print "<th>" . $arr->message . "</th>";
                        print "<tr>";
                    } else {
                        foreach ($arr->results as $key => $obj) {
                            print "<tr>";
                            print "<th scope='row'>" . $obj->id . "</th>";
                            print "<td>" . $obj->order_date . "</td>";
                            print "<td>€ " . number_format($obj->total_cost, 2) . "</td>";
                            print "<td><a href='details.php?order_id=" . $obj->id . "' class='btn btn-primary'>Dettagli</a></td>";
                            print "<tr>";
                        }
                    }
                }
                print "</tbody>";
                print "</table>";

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