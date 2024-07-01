<?php
require_once 'classe_conta.php';
$c = new conta("controle financeiro titan", "localhost", "root", "");
//set a data e hora local
date_default_timezone_set('America/Sao_Paulo');
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle Financeiro</title>
    <link rel="stylesheet" type="text/css" href="style_index.css">
</head>

<body>
<?php
    include("config.php");

    //botao editar
    if (isset($_GET['id_editar_conta'])) 
    {
          $id_editar_conta = intval($_GET['id_editar_conta']);
          $dados3 = $c->buscarDados_tbl_conta_pagar($id_editar_conta);
          foreach ($dados3 as $row_editar) {}
    }

    ?>    
    <section id="esquerda">
        <form method="POST">
            <h2>ADICIONAR CONTA</h2>
            <label for="id_empresa">Empresa</label>
            <select name="id_empresa" id="id_empresa">
            <?php
            
            if(isset($row_editar))
            {
                echo '<option value="' . $row_editar['cod_empresa'] . '">' . $row_editar['nome_empresa'] . '</option>';
            }
            else
            {
                $dados1 = $c->buscarDados_tbl_empresa();
                if (count($dados1) > 0) 
                {
                    foreach ($dados1 as $empresas) 
                    {
                        //<option value="1">Titan24</option>
                        echo '<option value="' . $empresas['id_empresa'] . '">' . $empresas['nome'] . '</option>';
                    }
                }    
                else {
                    echo "Sem empresas cadastradas";
                }
            }
            ?>

            </select>
            <label for="data_pagar">Data Vencimento</label><input type="date" name="data_pagar" id="data_pagar" value="<?php if(isset($row_editar)){echo $row_editar['data_pagar'];} ?>">
            <label for="valor">Valor a ser Pago</label><input type="number" name="valor" id="valor" min="0.01" step="any" value="<?php if(isset($row_editar)){echo $row_editar['valor'];} ?>">
            <input type="submit" value="<?php if(isset($row_editar)){echo 'Atualizar';} else {echo 'Adicionar';}?>">
        </form>
    </section>

    <section id="direita">
        <table>
            <tr id="titulo">
                <td>EMPRESA</td>
                <td>DATA VENCIMENTO</td>
                <td>VALOR</td>
                <td>DATA PAGA</td>
                <td>VALOR PAGO</td>
                <td colspan="2"></td>
            </tr>
        <?php
            $dados = $c->buscarDados_tbl_conta_pagar();
            if (count($dados) > 0) 
            {
                foreach ($dados as $conta_pagar) 
                {
                    echo "<tr>";
                    echo "<td>" . $conta_pagar['nome_empresa'] . "</td>";
                    echo "<td>" . date('d-m-Y', strtotime($conta_pagar['data_pagar']))  . "</td>";
                    echo "<td>" . number_format($conta_pagar['valor'],2,",",".") . "</td>";
                    
                    //validar se tem valor ou nao, se nao tiver mostrar vazio
                    if (!is_null($conta_pagar['data_pagto']))
                    {
                      $data_pagto = date('d-m-Y', strtotime($conta_pagar['data_pagto'])); 
                      $valor_pagto = number_format($conta_pagar['valor_pagto'],2,",",".");
                    }
                    else 
                    {
                      $data_pagto = null;
                      $valor_pagto = null; 
                    }
                    echo "<td>" . $data_pagto  . "</td>";
                    echo "<td>" . $valor_pagto  . "</td>";
                    //echo "<td>" . $conta_pagar['id_conta_pagar']  . "</td>";
                    //botoes 
                   
                   if($conta_pagar['pago'] == 0) //quando for pago, nao exibir botões
                   {
                    echo '<td><a href="index.php?id_editar_conta=' . $conta_pagar['id_conta_pagar'] . '">Editar</a> <a href="index.php?id_conta_pagar=' . $conta_pagar['id_conta_pagar'] . '">Excluir</a> <a href="index.php?id_conta_pg=' . $conta_pagar['id_conta_pagar'] . '">Paga</a></td>';      
                   }
                   else
                   {
                    echo '<td><a href="index.php?id_canc_pagto=' . $conta_pagar['id_conta_pagar'] . '">Cancelar Pagamento</a></td>';
                   }
                    echo "</tr>";
                }
            } 
            else 
            {
                echo "Não há contas cadastradas!";
            }
        ?>
        </table>
    </section>
</body>
</html>

<?php
    //botao adicionar e atualizar
    if (isset($_POST['id_empresa'])) 
    {
        $empresa = addslashes($_POST['id_empresa']);
        $data_pagar = addslashes($_POST['data_pagar']);
        $valor = addslashes($_POST['valor']);    

        if (!empty($empresa) && !empty($data_pagar) && !empty($valor)) 
        {  
            if (isset($_GET['id_editar_conta']) && !empty($_GET['id_editar_conta'])) // ATUALIZAR
            {      
                $id = intval($row_editar['id_conta_pagar']);
                $c->editar_conta($id,$empresa, $data_pagar, $valor);
            }
            else //ADICIONAR
            {
                $c->adicionar_conta($empresa, $data_pagar, $valor);
            }
            header('location: index.php'); //reiniciar formulario, reset variaveis
        } 
        else 
        {
            echo "Preencha todos os campos";
        }
    }

    //botao excluir
    if (isset($_GET['id_conta_pagar'])) 
    {
          $id_cp_excluir = addslashes($_GET['id_conta_pagar']);
          $c->deletar_conta($id_cp_excluir);
          header("location: index.php"); //reiniciar formulario, reset variaveis
    }       
    
    //botao pago
    if (isset($_GET['id_conta_pg'])) 
    {
          $id_cp_pago = intval($_GET['id_conta_pg']);
          // buscar dados 
          $dados2 = $c->buscarDados_tbl_conta_pagar($id_cp_pago);
          foreach ($dados2 as $row_paga) {}
          
          // calcular o valor de acordo com a data do pagamento
          $valor_pago = $row_paga['valor'];
          $dt_pagar = date('Y-m-d', strtotime($row_paga['data_pagar']));
          $current_date_php = date('Y-m-d');
          if ($current_date_php < $dt_pagar)
          {
            $valor_pago = $valor_pago - ($valor_pago / 100 * 5);
          }
          elseif ($current_date_php > $dt_pagar)
          {
            $valor_pago = $valor_pago + ($valor_pago / 100 * 10); 
          }
          $c->pagar_conta($id_cp_pago, $valor_pago,$current_date_php);
          header("location: index.php"); 
    }     

    if (isset($_GET['id_canc_pagto'])) 
    {
          $id_canc = intval($_GET['id_canc_pagto']);
          // buscar dados 
          $dados4 = $c->buscarDados_tbl_conta_pagar($id_canc);
          foreach ($dados4 as $row_cancel) {}
          $c->cancelar_pagto($id_canc);
          header("location: index.php");
    }

?>