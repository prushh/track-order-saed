<?php
// Initialize session
session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged']) {
    if ($_SESSION['type'] == 'admin') {
        header('Location: admin.php', true, 307);
    } elseif ($_SESSION['type'] == 'user') {
        header('Location: myprofile.php', true, 307);
    }
    exit(0);
}

require_once "../database/connection.php";

$email = $passowrd = "";
$err_message = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if ((isset($_POST['email'])) && (isset($_POST['password']))) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) && empty($password)) {
            $err_message = "Inserisci email e password.";
        } elseif (empty($email)) {
            $err_message = "Inserisci email.";
        } elseif (empty($password)) {
            $err_message = "Inserisci password.";
        }

        if ($err_message == "") {

            $conn = openConnection();
            $sql = "SELECT users.name, users.surname, users.email, users.password
                    FROM users
                    WHERE users.email = :email";

            echo $conn->prepare($sql);

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param(":email", $param_email);
                $param_email = strtolower($email);

                if ($stmt->execute()) {
                    if ($stmt->num_rows == 1) {
                        if ($row = $stmt->fetch()) {
                            $name = $row['name'];
                            $surname = $row['surname'];
                            $email = $row['email'];
                            //$hash_psw = $row['password'];
                            $hash_psw = "887375DAEC62A9F02D32A63C9E14C7641A9A8A42E4FA8F6590EB928D9744B57BB5057A1D227E4D40EF911AC030590BBCE2BFDB78103FF0B79094CEE8425601F5";

                            if (password_verify($password, $hash_psw)) {
                                session_start();

                                // Save session's variables
                                $_SESSIONE['logged'] = true;
                                $_SESSION["email"] = $email;
                                $_SESSION['name'] = $name;
                                $_SESSION['surname'] = $surname;
                                if (strcmp($email, "admin@gmail.com")) {
                                    $_SESSION['type'] = 'admin';
                                } else {
                                    $_SESSION['type'] = 'user';
                                }

                                $err_message = "PASSWORD CORRETTA";

                                header('Location: myprofile.php', true, 302);
                                exit(0);
                            } else {
                                $err_message = "La password inserita non è valida.";
                            }
                        }
                    } else {
                        $err_message = "L'email inserita non è valida.";
                    }
                } else {
                    $err_message = "Oops! Qualcosa è andato storto. Riprova più tardi.";
                }
            } else {
                //$err_message = "TERMINATO";
            }

            unset($stmt);

            closeConnection($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Home - Traking Tool</title>
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
        <header class="masthead mb-auto">
            <div class="inner">
                <a class="navbar-brand" href="index.php">
                    <img src="img/logo.png" alt="">
                </a>
                <nav class="nav nav-masthead justify-content-center">
                    <a class="nav-link" href="index.php">Home</a>
                    <a class="nav-link" href="contacts.php">Contatti</a>
                    <p class="nav-link active">Accesso clienti</p>
                </nav>
            </div>
        </header>

        <main role="main" class="inner cover">
            <div class="wrapper">
                <h2>Accedi</h2>
                <p>Benvenuto, utente.<br>Inserisci le credenziali d'accesso.</p>
                <div class="frm">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label class="lbl">E-mail</label>
                            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>"
                                placeholder="E-mail">
                        </div>
                        <div class="form-group <?php echo (!empty($err_message)) ? 'has-error' : ''; ?>">
                            <label class="lbl">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <span class="help block text-danger"><?php echo $err_message; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Accedi">
                        </div>
                    </form>
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