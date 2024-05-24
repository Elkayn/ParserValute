<?php
session_start();

require_once("includes/connection.php");


if(isset($_SESSION["session_username"])){
// вывод "Session is set"; // в целях проверки
    header("Location: parserValute.php");
}

if(isset($_POST["login"])){

    if(!empty($_POST['username']) && !empty($_POST['password'])) {
        $username=htmlspecialchars($_POST['username']);
        $password=htmlspecialchars($_POST['password']);
        $query =mysqli_query($connection, "SELECT * FROM usertbl WHERE username='$username'");
        $numrows=mysqli_num_rows($query);
        if($numrows!=0)
        {
            while($row=mysqli_fetch_assoc($query))
            {
                $dbusername=$row['username'];
                $dbpassword=$row['password'];
            }
            if($username == $dbusername && password_verify($password, $dbpassword))
            {
                // старое место расположения
                //  session_start();
                $_SESSION['session_username']=$username;	 
                /* Перенаправление браузера */
                header("Location: parserValute.php");
            } else {
                //  $message = "Invalid username or password!";
                echo "Invalid username or password!";
            }
        }
    } else {
        $message = "All fields are required!";
    }
}
?>

<?php include('includes/header.php');?>
    <title>Авторизация</title>
    <body>
    <div class="container mlogin">
        <div id="login">
            <h1>Вход</h1>
            <form action="" id="loginform" method="post"name="loginform">
                <p><label for="user_login">Имя попльзователя<br>
                    <input class="input" id="username" name="username"size="20"
                    type="text" value=""></label></p>
                <p><label for="user_pass">Пароль<br>
                    <input class="input" id="password" name="password"size="20"
                    type="password" value=""></label></p> 
                <p class="submit"><input class="button" name="login"type= "submit" value="Войти"></p>
                <p class="regtext">Еще не зарегистрированы?<a href= "register.php">Регистрация</a>!</p>
            </form>
        </div>
    </div>
    <?php include("includes/footer.php");?>
</body>
