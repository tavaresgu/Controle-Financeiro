<?php
class conta
{
    private $pdo;
    public function __construct($dbname, $host, $user, $senha)
    {
        try 
        {
            $this->pdo = new PDO("mysql:dbname=" . $dbname . ";host=" . $host, $user, $senha);
        } catch (PDOException $e) {
            echo "Erro com banco de dados: " . $e->getMessage();
            exit();
        } catch (Exception $e) {
            echo "Erro generico: " . $e->getMessage();
        }
    }

    public function buscarDados_tbl_empresa()
    {
        $res = array();
        $cmd = $this->pdo->query("SELECT id_empresa, nome FROM tbl_empresa ORDER BY id_empresa asc");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    
    public function buscarDados_tbl_conta_pagar($id_conta_pagar = NULL)
    {
        $res = array();
        if (is_null($id_conta_pagar))
        {
           $cmd = $this->pdo->query("SELECT e.nome as nome_empresa, data_pagar, valor, data_pagto, valor_pagto, id_conta_pagar, pago FROM tbl_conta_pagar cp LEFT JOIN tbl_empresa e ON e.id_empresa=cp.id_empresa ORDER BY id_conta_pagar Desc");
        }
        else 
        {
            $cmd = $this->pdo->query("SELECT e.nome as nome_empresa,e.id_empresa as cod_empresa, data_pagar, valor, data_pagto, valor_pagto, id_conta_pagar, pago FROM tbl_conta_pagar cp LEFT JOIN tbl_empresa e ON e.id_empresa=cp.id_empresa where id_conta_pagar = " . $id_conta_pagar . "") ;
        }
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function deletar_conta($id_conta_pagar)
    {
        $cmd = $this->pdo->prepare("DELETE FROM tbl_conta_pagar WHERE id_conta_pagar = :id_conta_pagar");
        $cmd->bindValue(":id_conta_pagar",$id_conta_pagar);
        $cmd->execute();
    }

   public function pagar_conta($id_conta_pagar, $valor_receber,$current_date_php)
   {
       $cmd = $this->pdo->prepare("UPDATE tbl_conta_pagar SET pago = 1,valor_pagto =:valor_receber,data_pagto = :current_date_php WHERE id_conta_pagar=:id_conta_pagar");
       $cmd->bindValue(":id_conta_pagar",$id_conta_pagar);
       $cmd->bindValue(":valor_receber",$valor_receber);
       $cmd->bindValue(":current_date_php",$current_date_php);
       $cmd->execute();
   }

   public function editar_conta($id_conta_pagar,$id_empresa,$data_pagar,$valor)
   {
        $cmd = $this->pdo->prepare("UPDATE tbl_conta_pagar SET id_empresa = :id_empresa,data_pagar =:data_pagar,valor = :valor WHERE id_conta_pagar=:id_conta_pagar");
        $cmd->bindValue(":id_conta_pagar",$id_conta_pagar);
        $cmd->bindValue(":id_empresa",$id_empresa);
        $cmd->bindValue(":data_pagar",$data_pagar);
        $cmd->bindValue(":valor",$valor);
        $cmd->execute();
   }

    public function adicionar_conta($empresa, $data_pagar, $valor)
    {
         $cmd = $this->pdo->prepare("INSERT INTO tbl_conta_pagar(id_empresa,data_pagar,valor,pago) VALUES (:empresa,:data_pagar,:valor,:pago)");
            $cmd->bindValue(":empresa",$empresa);
            $cmd->bindValue(":data_pagar",$data_pagar);
            $cmd->bindValue(":valor",$valor);
            $cmd->bindValue(":pago",0);
            $cmd->execute();
    }

    public function cancelar_pagto($id_conta_pagar)
   {
       $cmd = $this->pdo->prepare("UPDATE tbl_conta_pagar SET pago = 0,valor_pagto =null,data_pagto = null WHERE id_conta_pagar=:id_conta_pagar");
       $cmd->bindValue(":id_conta_pagar",$id_conta_pagar);
       $cmd->execute();
   }
}
