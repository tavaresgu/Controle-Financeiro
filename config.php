<?php

//connect

try
{
    $pdo = new PDO("mysql:dbname=controle financeiro titan; host=localhost","root","");
}
catch (PDOException $e) 
{
    echo "Erro com Banco de Dados: " .$e->getMessage();
    
}

catch (Exception $e){
    echo "Erro genérico: ".$e->getMessage();
}
    
//insert

//$pdo->query("INSERT INTO tbl_conta_pagar(valor, data_pagar, pago, id_empresa) VALUES ('568.17','2024/07/18','0','0')");


?>
