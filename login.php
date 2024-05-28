<?php
session_start();

require_once("includes/connection.php");


if(isset($_SESSION["session_username"])){
// вывод "Session is set"; // в целях проверки
    header("Location: parserValute.php");
}

if(isset($_POST["login"])){

    if(!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM usertbl WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $dbusername = $row['username'];
            $dbpassword = $row['password'];
            
            if($username == $dbusername && password_verify($password, $dbpassword)) {
                $_SESSION['session_username'] = $username;  
                header("Location: parserValute.php");
            } else {
                echo "Invalid username or password!";
            }
        } else {
            echo "User not found!";
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
