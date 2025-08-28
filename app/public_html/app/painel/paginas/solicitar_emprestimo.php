<?php
require_once("cabecalho.php");
require_once("rodape.php");

$pag = 'solicitar_emprestimo';
$itens_pag = 10;

if (@$solicitar_emprestimo == 'ocultar') {
  echo "<script>window.location='index'</script>";
  exit();
}

// pegar a pagina atual
if (@$_POST['pagina'] == "") {
  @$_POST['pagina'] = 0;
}
$pagina = intval(@$_POST['pagina']);
$limite = $pagina * $itens_pag;

$status = @$_POST['status_busca'];


if($status == ""){
  $sql_status = " and status = 'Pendente'";
  $cor_btn_ativo = '#436399';
  $cor_texto_btn_ativo = '#FFF';
}

if($status == "Pendentes"){
  $sql_status = " and status = 'Pendente'";
  $cor_btn_ativo = '#436399';
  $cor_texto_btn_ativo = '#FFF';
}

if($status == "Concluidas"){
  $sql_status = " and status = 'Concluida'";
  $cor_btn_finalizado = '#436399';
  $cor_texto_btn_finalizado = '#FFF';
}

$numero_pagina = $pagina + 1;

if ($pagina > 0) {
  $pag_anterior = $pagina - 1;
  $pag_inativa_ant = '';
} else {
  $pag_anterior = $pagina;
  $pag_inativa_ant = 'desabilitar_botao';
}

$pag_proxima = $pagina + 1;


//totalizar páginas
$query2 = $pdo->query("SELECT * from solicitar_emprestimo where id > 0 $sql_status order by id desc");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$linhas2 = @count($res2);

$num_paginas = ceil($linhas2 / $itens_pag);
if ($pag_proxima == $num_paginas) {
  $pag_inativa_prox = 'desabilitar_botao';
  $pag_proxima = $pagina;
} else {
  $pag_inativa_prox = '';

}

?>

<div class="page-content header-clear-medium">

  <!-- BARRA DE PESQUISA-->
  <div class="content m-2 p-1">
    <div class="loginform">
      <form method="post">
        
        <div class="">
      <div class="content">
        <div class="tabs tabs-pill" id="tab-group-2">
          <div class="tab-controls rounded-m p-1">
            <a style="color:<?php echo $cor_texto_btn_ativo ?>; background: <?php echo $cor_btn_ativo ?>" class="font-12 rounded-m tabradio" data-bs-toggle="collapse" href="#tab-4"
              aria-expanded="" name="tabs" id="tabone" onclick="$('#status_busca').val('Pendentes'); $('#btn_filtrar').click()">Pendentes</a>
            <a style="color:<?php echo $cor_texto_btn_finalizado ?>; background: <?php echo $cor_btn_finalizado ?>" class="font-12 rounded-m tabradio" data-bs-toggle="collapse" href="#tab-5"
              aria-expanded="" name="tabs" id="tabtwo" onclick="$('#status_busca').val('Concluidas'); $('#btn_filtrar').click()">Concluidas</a>
          
           
          </div>
          
        </div>
      </div>
    </div>

     <input type="hidden" name="status_busca" id="status_busca" value="<?php echo $status ?>">

     <button id="btn_filtrar" class="limpar_botao" type="submit" style="display:none"></button>

      </form>
    </div>
  </div>


  <div class="card card-style" id="listar">
    <div class="content">


      <?php
      $query = $pdo->query("SELECT * from solicitar_emprestimo where id > 0 $sql_status  order by id desc LIMIT $limite, $itens_pag");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $linhas = @count($res);
      if ($linhas > 0) {
        for ($i = 0; $i < $linhas; $i++) {
        $id = $res[$i]['id']; 
  $cliente = $res[$i]['cliente'];
  $valor = $res[$i]['valor'];
  $data = $res[$i]['data'];
  $parcelas = $res[$i]['parcelas']; 
  $obs = $res[$i]['obs'];
  $garantia = $res[$i]['garantia'];
  $status = $res[$i]['status'];
  

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];

  $dataF = implode('/', array_reverse(@explode('-', $data)));
  
  $valorF = number_format($valor, 2, ',', '.');

  if($status == 'Pendente'){
    $classe_square = 'vermelho.jpg';
    $classe_baixar = 'ocultar';
    $icone = 'bi-check-square';
    $titulo_link = 'Marcar como Pendente';
    $acao = 'Concluída';
    $bg_square = 'bg-danger';
    
  }else{
    $classe_square = 'verde.jpg';
    $classe_baixar = '';
    $icone = 'bi-square';
    $titulo_link = 'Marcar como Concluída';
    $acao = 'Pendente';
    $bg_square = 'bg-success';
    
  }


          echo <<<HTML

     <div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows slider-no-dots" id="user-slider-{$id}">
  <div class="splide__track">
    <div class="splide__list">
      <div class="splide__slide mx-3">
        <div class="d-flex">
          <div>            
            <h5 class="mt-0 mb-1" style="font-size: 12px;"><img src="images/{$classe_square}" width="12px" style="float:left; margin-right: 2px; margin-top: 3px">{$nome_cliente}</h5>
            <p class="font-12 mt-n2 mb-0"><span class="text-success">R$ {$valorF} </span> / {$parcelas} Parcelas </p>            
            <p class="font-10 mt-n2 mb-n2">Garantia: {$garantia}</p>

          </div>
          <div class="ms-auto"><span class="slider-next "><span class="badge px-2 py-1 {$bg_square} shadow-bg shadow-bg-s">{$status}</span></span>
          </div>
        </div>
      </div>
      <div class="splide__slide mx-3">
        <div class="d-flex">
          
          <div class="ms-auto">           
           
            <a title="{$titulo_link}" onclick="ativarSolic('{$id}', '{$acao}')" href="#" class=" icon icon-xs rounded-circle shadow-l bg-green"><i class="bi {$icone} text-white"></i></a>          

            <a onclick="excluir_reg('{$id}', '{$nome_cliente}')" href="#" class=" icon icon-xs rounded-circle shadow-l bg-google"><i class="bi bi-trash-fill text-white"></i></a>
           

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      <div class="divider mt-3 mb-3"></div>
HTML;
        }
      } else {
        echo 'Nenhum Registro Encontrado!';
      }
      ?>
      <form method="post" style="display:none">
        <input type="text" name="pagina" id="input_pagina">
        <input type="text" name="buscar" id="input_buscar">
        <button type="submit" id="paginacao"></button>
      </form>
    </div>
  </div>
  <!-- PAGINAÇÃO -->
  <nav aria-label="pagination-demo">
    <ul class="pagination pagination- justify-content-center">
      <li class="page-item">
        <a onclick="paginar('<?php echo $pag_anterior ?>', '<?php echo $buscar ?>')"
          class="page-link py-2 rounded-xs color-black bg-transparent bg-theme shadow-xl border-0 <?php echo $pag_inativa_ant ?>"
          href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-chevron-left"></i></a>
      </li>
      <li class="page-item"><a class="page-link py-2 rounded-xs shadow-l border-0 color-dark"
          href="#"><?php echo @$numero_pagina ?> /
          <?php echo @$num_paginas ?></span></a>
      </li>
      <li class="page-item">
        <a onclick="paginar('<?php echo $pag_proxima ?>', '<?php echo $buscar ?>')"
          class="page-link py-2 rounded-xs color-black bg-transparent bg-theme shadow-l border-0 <?php echo $pag_inativa_prox ?>"
          href="#"><i class="bi bi-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
</div>







<script type="text/javascript">var pag = "<?= $pag ?>"</script>


<script type="text/javascript">
  function ativarSolic(id, acao) {
  $.ajax({
    url: '../../painel/paginas/solicitar_emprestimo/mudar-status.php',
    method: 'POST',
    data: { id, acao },
    dataType: "html",
    success: function (mensagem) {
      //ARMAZENAR O RETORNO PARA A MSG DE SUCESSO
      $('#toast-message').text(mensagem.trim());
      if (mensagem.trim() == "Alterado com Sucesso") {
        $('#not_salvar').click(); // Dispara a notificação de sucesso
        setTimeout(function () {
          $('#btn_filtrar').click()
        }, 1000); // 2000 milissegundos = 2 segundos 
      } else {
        toast(mensagem, 'vermelha');
      }
    }
  });
}
</script>