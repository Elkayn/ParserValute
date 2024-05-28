<?php

session_start();

if(!isset($_SESSION["session_username"])):
header("location:login.php");
else:
    require_once("includes/connection.php");
    $username = $_SESSION['session_username'];
    include_once('includes/rate.php');
    $infoPerson = getInfo($username, $pdo);
    include("includes/header.php"); ?>

<title>Parser</title>
<link href="css/parser.css" media="screen" rel="stylesheet">
<div class="menu">
    <p>
        <label class="fullName">Личный кабинет</label><br><br>
        <label class="fullName"><?php echo $infoPerson[1];?></label><br>
        <label class="Username"><?php echo $infoPerson[0];?></label><br>
        <label class="Email"><?php echo $infoPerson[2];?></label><br>
    </p>
    <p><a href="includes/logout.php">Выход</a></p>
</div>
<?php
    getRates($pdo);
?>
<div class="main">
    <div class="konvert-container">
        <div class="konvert">
            <form action="" method="post">
                <select name="selectValute">
                <?php
                    $sql = "SELECT * FROM valutes";
                    $selectResult = $pdo->prepare($sql);
                    $selectResult->execute();
                    $res = $selectResult->fetchAll(PDO::FETCH_ASSOC);
                    foreach($res as $row){
                        $selected = '';
                        if (isset($_POST['selectValute']) && $_POST['selectValute'] == $row['NameValute']){
                            $selected = 'selected';
                        }?>
                    <option <?php echo $selected; ?>><?php echo $row['NameValute']; ?></option>
                <?php } ?>
                </select>   
                <input class="input" name="kolvo" value="<?php isset($_POST['buttonEn']) ? konvertingRound($pdo) : '';?>">
                <input name="buttonRub" type="submit" class="butthon" value="Конвертировать в рубли">
                <p><select><option>Росскийский рубль</option></select>    
                <input class="input" name="kolvoRus" value="<?php isset($_POST['buttonRub']) ? konverting($pdo) : '';?>">
                <input name="buttonEn" type="submit" class="butthon" value="Конвертировать с рублей"></p>
            </form>
        </div>
    </div>
    <div class="title"><h3>Официальные курсы валют на <?php getDater();?></h3></div>
    <div class="table">
        <table>
            <tr>
                <th>№</th>
                <th>Буквенный код</th>
                <th>Валюта</th>
                <th>Единиц</th>
                <th>Курс</th>
            </tr>
            <?php 
            $i = 1;
            $rows = getTable($pdo);
            foreach($rows as $row){
            ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['charCode']; ?></td>
                    <td><?php echo $row['NameValute']; ?></td>
                    <td><?php echo $row['Nominal']; ?></td>
                    <td><?php echo $row['Value']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<?php endif; ?>