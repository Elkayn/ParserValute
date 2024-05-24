<?php
function getRates($connection){
    $url = "https://cbr.ru/scripts/XML_daily.asp";
    $dataObj = simplexml_load_file($url);
    if ($dataObj){
        foreach ($dataObj->Valute as $valute){
            $Name = $valute->Name;
            $Code = $valute->CharCode;
            $Nominal = $valute->Nominal;
            $Value = $valute->Value;
            
            // Проверяем, существуют ли данные уже в базе данных
            $checkSql = "SELECT * FROM valutes WHERE charCode = '$Code'";
            $result = mysqli_query($connection, $checkSql);
            
            if(mysqli_num_rows($result) > 0){
                // Данные уже существуют, обновляем существующую запись
                $updateSql = "UPDATE valutes SET NameValute = '$Name', Nominal = '$Nominal', Value = '$Value' WHERE charCode = '$Code'";
                mysqli_query($connection, $updateSql);
            } else {
                // Данные не существуют, вставляем новую запись
                $insertSql = "INSERT INTO valutes (NameValute, charCode, Nominal, Value) VALUES('$Name', '$Code', '$Nominal', '$Value')";
                mysqli_query($connection, $insertSql);
            }
        }
    }
}
function getDater(){
    $url = "https://cbr.ru/scripts/XML_daily.asp";
    $dataObj = simplexml_load_file($url);
    $date = $dataObj['Date'];
    echo $date;
}
function konverting($connection){
    if(!empty($_POST['kolvo']) && !empty($_POST['selectValute']))
    {
        $selectValute = $_POST['selectValute'];
        $query = mysqli_query($connection, "SELECT * FROM valutes WHERE NameValute='$selectValute'");
        $numrows=mysqli_num_rows($query);
        if($numrows!=0)
        {
            while($row=mysqli_fetch_assoc($query))
            {
                $dbNominal=$row['Nominal'];
                $dbValue=$row['Value'];
            }
        } else {
            $message = "All fields are required!";
        }
        $edinic = $_POST['kolvo'];
        $numberFormatted = str_replace(',', '.', $dbValue);
        $floatValue = (float) $numberFormatted;
        $sum = ($floatValue / $dbNominal) * $edinic;
        echo $sum;
    }
    else{
        return;
    }
}
function konvertingRound($connection){
    if(!empty($_POST['kolvoRus']) && !empty($_POST['selectValute']))
    {
        $selectValute = $_POST['selectValute'];
        $query = mysqli_query($connection, "SELECT * FROM valutes WHERE NameValute='$selectValute'");
        $numrows=mysqli_num_rows($query);
        if($numrows!=0)
        {
            while($row=mysqli_fetch_assoc($query))
            {
                $dbNominal=$row['Nominal'];
                $dbValue=$row['Value'];
            }
        } else {
            $message = "All fields are required!";
        }
        $edinic = $_POST['kolvoRus'];
        $numberFormatted = str_replace(',', '.', $dbValue);
        $floatValue = (float) $numberFormatted;
        $sum = $edinic/$floatValue;
        echo $sum;
    }
    else{
        return;
    }
}
function getInfo($username, $connection)
{
    $query =mysqli_query($connection, "SELECT * FROM usertbl WHERE username='$username'");
    $numrows=mysqli_num_rows($query);
    if($numrows!=0)
    {
        while($row=mysqli_fetch_assoc($query))
        {
            $dbusername=$row['username'];
            $dbName=$row['full_name'];
            $dbEmail=$row['email'];
        }
    } else {
        $message = "All fields are required!";
    }
    return [$dbusername, $dbName, $dbEmail];
}
?>