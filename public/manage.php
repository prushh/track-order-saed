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
            <div class="dropdown">
                <button class="btn dropdown-toggle dropname" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Admin
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
                        print "<b><h1>Gestisci Ordine #" . htmlspecialchars($_GET['order_id']) . "</h1></b>";
                    } else {
                        print "<b><h1>Nessun ordine selezionato...</h1></b>";
                    }
                    ?>
                </div>

                <div class="container">

                    <!-- NO TRACKING -->
                    <?php
                    if (isset($_GET['order_id']) && !isset($_GET['tracking_id'])) {
                        print "<div class='row'>";

                        print "<div class='col-md-6 mb-5'>";
                        print "<h4 class='mb-5'>Crea Nuovo Tracking</h4>";
                        print "<form action='add_tracking.php' method='post'>
                                   <div class='form-group'>
                                   <div class='bootstrap-select-wrapper'>
                                   <label>Corriere</label><br>
                                      <select name='courier' class='custom-select custom-select-sm tracking_id' title='Scegli un Corriere'>
                                        <option value='BRT'>BRT</option>
                                        <option value='SDA'>SDA</option>
                                        <option value='GLS'>GLS</option>
                                        <option value='DHL'>DHL</option>
                                      </select>
                                      </div>";
                        print "<input type='hidden' name='order_id' value=" . $_GET['order_id'] . ">";
                        print "";
                        print "<br><input type='submit' class='btn btn-primary' value='Crea' style='width:30%;'>
                                   </div>
                                   </form>";
                        print "</div>";

                        print "<div class='col-md-6 mb-5'>";

                        $url = $ROOT_API . "tracking/get.php?no_orders";
                        $arr = json_decode(curl_api("GET", $url));
                        if (isset($arr->results)) {
                            print "<h4 class='mb-5'>Associa Tracking Esistente</h4>";
                            print "<form action='link_tracking.php' method='post'>
                                        <div class='form-group'>";
                            print "<input type='hidden' name='order_id' value=" . $_GET['order_id'] . ">";
                            foreach ($arr->results as $key => $obj) {
                                print "<input name='tracking_id' type='radio' id='" . $obj->id . "' value='" . $obj->id . "'>
                                       <label for='" . $obj->id . "'> #" . $obj->id . "</label><br>";
                            }

                            print "<br><br><input type='submit' class='btn btn-primary' value='Associa' style='width:30%;'>
                                       </div>
                                      </form>";
                            print "</div>";
                        } else {
                            print "<h4 class='mb-5'>" . $arr->message . "</h4>";
                        }
                        print "</div>";
                    }
                    ?>

                    <!-- WITH TRACKING -->
                    <?php
                    if (isset($_GET['order_id']) && isset($_GET['tracking_id'])) {
                        $url = $ROOT_API . "tracking/get.php?tracking_id=" . $_GET['tracking_id'];
                        $arr = json_decode(curl_api("GET", $url));
                        if (isset($arr->results[0])) {
                            $obj = $arr->results[0];
                            if (isset($arr->message)) {
                                print $arr->message;
                            } else {
                                if ($obj->status_id < 5) {
                                    $status_id = $obj->status_id + 1;
                                    $data = array(
                                        "id" => $_GET['tracking_id'],
                                        "status_id" => $status_id
                                    );
                                    $url = $ROOT_API . "tracking/put.php";
                                    $arr = json_decode(curl_api("PUT", $url, $data));
                                }

                                print "<div class='row row_style'>";
                                print "<div class='col-md-6'>";
                                switch ($obj->courier) {
                                    case "BRT":
                                        print "<img src='img/brt.png' width='250px'>";
                                        break;
                                    case "DHL":
                                        print "<img src='img/dhl.png' width='350px'>";
                                        break;
                                    case "SDA":
                                        print "<img src='img/sda.png' width='350px'>";
                                        break;
                                    case "GLS":
                                        print "<img src='img/gls.png' width='230px'>";
                                        break;
                                }
                                print "</div>";
                                print '<div class="col-md-6 text-left">';
                                print "Numero Tracking: <h5 style='display: inline-block'>" . $_GET['tracking_id'] . "</h5><br>";
                                print "Corriere: <h5 style='display: inline-block'>" . $obj->courier . "</h5><br>";
                                print "Stato della Spedizione: <h5 style='display: inline-block'>" . $obj->title . "</h5><br><br>";
                                print "Dettagli: <h5>" . $obj->description . "</h5><br>";
                                print "</div>";
                                print "</div>";

                                // DA POSIZIONARE MEGLIO, DECIDERE SE VISUALIZZARE
                                // BOTTONE ELIMINA SE L'ORDINE è STATO CONSEGNATO
                                if ($obj->status_id != 5) {
                                    print "<div class='col-md-12 mb-5'>";
                                    print "<h4 class='mb-5'>Elimina Tracking</h4>";
                                    print "<form action='delete_tracking.php' method='post'>";
                                    print "<div class='form-group'>";
                                    print "<input type='hidden' name='order_id' value=" . $_GET['order_id'] . ">";
                                    print "<input type='hidden' name='tracking_id' value=" . $_GET['tracking_id'] . ">";
                                    print "<input type='submit' class='btn btn-primary' value='Elimina'>";
                                    print "<br>(Azione non reversibile)";
                                    print "</div>";
                                    print "</form>";
                                }
                            }
                        } else {
                            // DA CENTRARE VERTICALMENTE
                            print "<h5>Nessuna informazione su questo tracking.</h5>";
                            print "<br>";
                            print "<a href='admin.php' class='btn btn-primary'>Torna alla dashboard</a>";
                        }
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