<?php
function getRates($pdo){
    $url = "https://cbr.ru/scripts/XML_daily.asp";
    $dataObj = simplexml_load_file($url);
    if ($dataObj){
        foreach ($dataObj->Valute as $valute){
            $Name = $valute->Name;
            $Code = $valute->CharCode;
            $Nominal = $valute->Nominal;
            $Value = $valute->Value;
            
            // Проверяем, существуют ли данные уже в базе данных
            $checkSql = "SELECT * FROM valutes WHERE charCode = :code";
            $result = $pdo->prepare($checkSql);
            $result->bindParam(':code', $Code);
            $result->execute();
            $row = $result->fetch(PDO::FETCH_ASSOC);

            if($row){
                // Данные уже существуют, обновляем существующую запись
                $updateSql = "UPDATE valutes SET NameValute = :name, Nominal = :nominal, Value = :value WHERE charCode = :code";
                $updateResult = $pdo->prepare($updateSql);
                $updateResult->bindParam(':name', $Name);
                $updateResult->bindParam(':nominal', $Nominal);
                $updateResult->bindParam(':value', $Value);
                $updateResult->bindParam(':code', $Code);
                $updateResult->execute();
            } else {
                // Данные не существуют, вставляем новую запись
                $insertSql = "INSERT INTO valutes (NameValute, charCode, Nominal, Value) VALUES(:name, :code, :nominal, :value)";
                $insertResult = $pdo->prepare($insertSql);
                $insertResult->bindParam(':name', $Name);
                $insertResult->bindParam(':code', $Code);
                $insertResult->bindParam(':nominal', $Nominal);
                $insertResult->bindParam(':value', $Value);
                $insertResult->execute();
            }
        }
    }
}
function getTable($pdo){
    $sql = "SELECT * FROM valutes";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}
function getDater(){
    $url = "https://cbr.ru/scripts/XML_daily.asp";
    $dataObj = simplexml_load_file($url);
    $date = $dataObj['Date'];
    echo $date;
}
function getValute($pdo){
    $selectValute = $_POST['selectValute'];
    $sql = "SELECT * FROM valutes WHERE NameValute=:selectValutes";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':selectValutes', $selectValute);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row != 0)
    {
        $dbNominal=$row['Nominal'];
        $dbValue=$row['Value'];
        return array('nominal'=>$dbNominal, 'value'=>$dbValue);
    } else {
        echo "All fields are required!";
    }
}
function konverting($pdo){
    if(!empty($_POST['kolvo']) && !empty($_POST['selectValute']))
    {
        $valute = getValute($pdo);
        $edinic = $_POST['kolvo'];
        $numberFormatted = str_replace(',', '.', $valute['value']);
        $floatValue = (float) $numberFormatted;
        $sum = ($floatValue / $valute['nominal']) * $edinic;
        echo $sum;
    }
    else{
        return;
    }
}
function konvertingRound($pdo){
    if(!empty($_POST['kolvoRus']) && !empty($_POST['selectValute']))
    {
        $valute = getValute($pdo);
        $edinic = $_POST['kolvoRus'];
        $numberFormatted = str_replace(',', '.', $valute['value']);
        $floatValue = (float) $numberFormatted;
        $sum = $edinic/$floatValue * $valute['nominal'];
        echo $sum;
    }
    else{
        return;
    }
}
function getInfo($username, $pdo)
{
    $sql = "SELECT * FROM usertbl WHERE username=:username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username',$username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row)
    {
        $dbusername=$row['username'];
        $dbName=$row['full_name'];
        $dbEmail=$row['email'];
    } else {
        echo "All fields are required!";
    }
    return [$dbusername, $dbName, $dbEmail];
}
?>