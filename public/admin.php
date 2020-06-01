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
    <title>Admin</title>
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

        <!-- Decidere se togliere header-->
        <header class="masthead mb-auto">
            <div class="inner">
                <a class="home_logo" href="index.php">
                    <img src="img/logo.png" alt="">
                </a>
                <h2>
                    <span class="login_title">T</span>racking <span class="login_title">T</span>ool
                </h2>
            </div>
        </header>

        <main role="main" class="inner cover">
            <div class="wrapper">

                <div class="page-header">
                    <h1>Benvenuto
                        <b><?php echo htmlspecialchars($_SESSION['name']); ?></b>
                    </h1>
                </div>
                <?php
                    // Simulation of API request
                    require_once "../database/connection.php";
                    require_once "utils.php";

                    $db = new Database();
                    $conn = $db->openConnection();

                    $sql = "SELECT orders.id, orders.n_items, orders.total_cost, orders.order_date,
                                   users.name, users.surname, users.email, users.address
                            FROM orders INNER JOIN users ON orders.user_id = users.id
                            WHERE orders.tracking_id IS NULL;";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    while($row = $stmt->fetch()) {
                        $result[] = $row;
                    }
                    
                    $json = json_encode($result);
                    // End simulation, parsing JSON response

                    $arr = json_decode($json);

                    print "<table class='table'>";
                    print "<table class='table table-dark'>";
                    print "<thead>";
                    print "<tr>";
                    print "<th scope='col'>ID Ordine</th>";
                    print "<th>Effettuato il</th>";
                    print "<th>N.Articoli</th>";
                    print "<th>Costo totale (€)</th>";
                    print "<th>Utente</th>";
                    print "<th>E-mail</th>";
                    print "<th>Indirizzo</th>";
                    print "<th></th>";
                    print "</tr>";
                    print "</thead>";
                    print "<tbody>";
                    foreach ($arr as $key => $obj) {
                        print "<tr>";
                        print "<th scope='row'>".$obj->id."</th>";
                        print "<td>".$obj->order_date."</td>";
                        print "<td>".$obj->n_items."</td>";
                        print "<td>".$obj->total_cost."</td>";
                        print "<td>".$obj->name." ".$obj->surname."</td>";
                        print "<td>".$obj->email."</td>";
                        print "<td>".$obj->address."</td>";
                        print "<td><a href='manage.php?order_id=".$obj->id."' class='btn btn-primary'>Gestisci</a></td>";
                        print "<tr>";
                    }
                    print "</tbody>";
                    print "</table></table>";

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