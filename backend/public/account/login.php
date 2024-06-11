<?php

require_once '../../function/init.php';


//check are thoose variable have value
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $passsword = $_POST['password'];

    var_dump($username);
    var_dump($passsword);
    //check up into class login
    $query = new login($username, $passsword);
    $query->login();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- 👇 css code file 👇 -->
    <link rel="stylesheet" href="../style.css" />
    <title>LOGIN | DiagnoTani</title>
</head>

<body>
    <!-- 👇 your content here 👇 -->

    <div class="container" id="container">
        <!--login-left-->
        <div class="login-left">
            <header class="header">
                <h1>Login</h1>
                <p>Please fill your information below</p>
            </header>

            <!-- form input -->
            <form class="form-signin" method="POST">
                <!-- user -->
                <div class="input-user">
                    <img src="../assets/person.svg" alt="icon-user" />
                    <input type="text" name="username" id="username" required />
                    <label class="label-group">Username</label>
                </div>
                <!-- password -->
                <div class="input-password">
                    <img src="../assets/key.svg" alt="icon-password" />
                    <input type="password" name="password" id="password" required />
                    <label class="label-group">Password</label>
                </div>

                <!-- button -->
                <button type="submit" class="button-login" name="login">
                    <span class="button-text">
                        <a href="#" name="login" class="url-login">Login</a>
                    </span>
                    <span class="button-icon">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </span>
                </button>
            </form>
            <div class="border"></div>
            <!--footer-->
            <footer class="footer-login">
                <p>Don't you have account</p>
                <a href="#">Create your account</a>
            </footer>
        </div>

        <!--login-right-->
        <div class="login-right"></div>
    </div>

    <!--👇 javascript code file 👇 -->
    <script src="../main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>