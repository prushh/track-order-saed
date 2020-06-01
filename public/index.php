<?php

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
                <a class="home_logo" href="index.php">
                    <img src="img/logo.png" alt="">
                </a>
                <h2><span class="login_title">T</span>racking <span class="login_title">T</span>ool</h2>
            </div>
        </header>

        <main role="main" class="inner cover">
            <form action="login.php">
                <input type="submit" class="btn btn-primary custom_login" value="Login">
            </form>
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">
                <p>© 2020 Tracking Tool - Developed by Amir & Davide</p>
            </div>
        </footer>
    </div>


</body>

</html>