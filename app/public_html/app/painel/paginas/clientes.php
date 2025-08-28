<?php
require_once("cabecalho.php");
require_once("rodape.php");

$data_atual = date('Y-m-d');
$pag = 'clientes';
$itens_pag = 10;

$buscar = @$_POST['buscar'];
$ativo = @$_POST['ativo']; // Adicionar esta linha para capturar o valor do filtro
$status_busca = @$_POST['status_cliente'];
$cor_status = @$_POST['cor_status'];

if (@$clientes == 'ocultar') {
  echo "<script>window.location='index'</script>";
  exit();
}

// pegar a pagina atual
if (@$_POST['pagina'] == "") {
  @$_POST['pagina'] = 0;
}
$pagina = intval(@$_POST['pagina']);
$limite = $pagina * $itens_pag;

$numero_pagina = $pagina + 1;

if ($pagina > 0) {
  $pag_anterior = $pagina - 1;
  $pag_inativa_ant = '';
} else {
  $pag_anterior = $pagina;
  $pag_inativa_ant = 'desabilitar_botao';
}

$pag_proxima = $pagina + 1;


if($status_busca != ""){
  $sql_status = " and status_cliente = '$status_busca' ";
}else{
  $sql_status = " ";
}


//totalizar páginas
$query2 = $pdo->query("SELECT * from $pag  where (nome like '%$buscar%' or telefone like '%$buscar%' or email like '%$buscar%' or cpf like '%$buscar%') $sql_status order by id desc");
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

<style>
  .tabradio.selected {
    background-color: #007bff !important;
    color: white !important;
  }
</style>

<div class="page-content header-clear-medium">

  <!-- BARRA DE PESQUISA-->
  <div class="content m-2 p-1">
    <div class="loginform">
      <form method="post">
<input type="hidden" name="cor_status" id="cor_status">
                  <!-- Status Cliente -->
<div class="form-floating mb-3 position-relative">
  <!-- Ícone esquerdo -->
  <i class="bi bi-info-circle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>

  <!-- Preview da cor: posicionado à direita -->
  <div id="preview_cor_status_busca"
    class="position-absolute top-50 translate-middle-y"
    style="right: 2.5rem; width: 17px; height: 17px; border: 1px solid #ccc; border-radius: 4px; background: <?php echo $cor_status ?>">
  </div>

  <!-- Select -->
  <select class="form-select rounded-xs ps-5 pe-5" name="status_cliente" id="status_cliente_busca" onchange="atualizarCorStatus_busca()">
    <option value="" data-cor="">Selecionar Status</option>
    <?php 
      $query = $pdo->query("SELECT * from status_clientes order by id asc");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $linhas = @count($res);
      if($linhas > 0){
        for($i=0; $i<$linhas; $i++){
    ?>
      <option value="<?= $res[$i]['nome'] ?>" data-cor="<?= $res[$i]['cor'] ?>" <?php if($status_busca == $res[$i]['nome']){ ?> selected <?php } ?> ><?= $res[$i]['nome'] ?></option>
    <?php } } ?>
  </select>

  <!-- Label flutuante -->
  <label class="color-theme ps-5">Status Cliente Busca</label>
</div>

        <div class="search-box search-color bg-theme rounded-xl">
          <input type="text" name="buscar" id="buscar" value="<?php echo $buscar ?>" class="form_input required p-1"
            placeholder="Buscar <?php echo ucfirst($pag); ?>"
            style="background: transparent !important; width:86%; float:left; border: none !important" />
          <button id="btn_filtrar" class="limpar_botao" type="submit"><img src="images/icons/black/search.png"
              width="20px" style="float:left; margin-top: 5px"></button>
        </div>

        

      </form>
    </div>
  </div>








  <div class="card card-style">
    <div class="content">
      <?php
      $query = $pdo->query("SELECT DISTINCT c.* from $pag c 
    WHERE (c.nome LIKE '%$buscar%' OR c.telefone LIKE '%$buscar%' OR c.email LIKE '%$buscar%' OR c.cpf LIKE '%$buscar%') $sql_status " .
        ($ativo === 'Não' ? " AND c.ativo = 'Não'" :
          ($ativo === 'Sim' ? " AND c.ativo = 'Sim'" :
            ($ativo === '' ? " AND c.ativo = ''" :
              ($ativo === 'ina' ? " AND EXISTS (
        SELECT 1 FROM receber r 
        WHERE r.cliente = c.id 
        AND r.pago = 'Não' 
        AND r.vencimento < CURDATE()
     )" : "")))) .
        " ORDER BY c.id desc LIMIT $limite, $itens_pag");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $linhas = @count($res);
      if ($linhas > 0) {
        for ($i = 0; $i < $linhas; $i++) {
          $id = $res[$i]['id'];
  $nome = $res[$i]['nome'];
  $telefone = $res[$i]['telefone'];
  $email = $res[$i]['email'];
  $cpf = $res[$i]['cpf']; 
  $endereco = $res[$i]['endereco'];
  $data_nasc = $res[$i]['data_nasc'];
  $data_cad = $res[$i]['data_cad'];
  $obs = $res[$i]['obs'];
  $pix = $res[$i]['pix'];
  $indicacao = $res[$i]['indicacao'];
  $bairro = $res[$i]['bairro'];
  $cidade = $res[$i]['cidade'];
  $estado = $res[$i]['estado'];
  $cep = $res[$i]['cep'];
  $pessoa = $res[$i]['pessoa'];

  $nome_sec = @$res[$i]['nome_sec'];
  $telefone_sec = @$res[$i]['telefone_sec'];
  $endereco_sec = @$res[$i]['endereco_sec'];
  $grupo = @$res[$i]['grupo'];
  $status = @$res[$i]['status'];
  $comprovante_rg = @$res[$i]['comprovante_rg'];
  $comprovante_endereco = @$res[$i]['comprovante_endereco'];
  $dados_emprestimo = @$res[$i]['dados_emprestimo'];

  $telefone2 = @$res[$i]['telefone2'];
  $foto = @$res[$i]['foto'];
  $status_cliente = @$res[$i]['status_cliente'];

  $dados_emprestimoF = @rawurlencode($dados_emprestimo);

  $data_nascF = implode('/', array_reverse(explode('-', $data_nasc)));
  $data_cadF = implode('/', array_reverse(explode('-', $data_cad)));

  $tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

$query2 = $pdo->query("SELECT * from status_clientes where nome = '$status_cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$cor = @$res2[0]['cor'];
if($cor == ""){
  $ocultar_cor = 'none';
}else{
  $ocultar_cor = '';
}


  //verificar total de emprestimos do cliente
$query2 = $pdo->query("SELECT * from emprestimos where cliente = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_emprestimos = @count($res2);

$query2 = $pdo->query("SELECT * from cobrancas where cliente = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_cobrancas = @count($res2);

$query2 = $pdo->query("SELECT * from receber where referencia = 'Conta' and cliente = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_contas = @count($res2);


$classe_status = '';  
$badge_status = '';

if($status == "Ativo"){
  $classe_status = 'green'; 
  $badge_status = 'bg-success';
}

if($status == "Inativo"){
  $classe_status = 'gray';
  $badge_status = 'bg-secondary'; 
}

if($status == "Alerta"){
  $classe_status = 'orange';
  $badge_status = 'bg-alert'; 
}
  
if($status == "Atenção"){
  $classe_status = 'red'; 
  $badge_status = 'bg-danger';
}

$ocultar_empre = '';
if($recursos == 'Cobranças'){
  $ocultar_empre = 'ocultar';
}

$ocultar_cobr = '';
if($recursos == 'Empréstimos'){
  $ocultar_cobr = 'ocultar';
}

      //extensão do arquivo
$ext = pathinfo($comprovante_endereco, PATHINFO_EXTENSION);
if($ext == 'pdf'){
  $tumb_comprovante_endereco = 'pdf.png';
}else if($ext == 'rar' || $ext == 'zip'){
  $tumb_comprovante_endereco = 'rar.png';
}else{
  $tumb_comprovante_endereco = $comprovante_endereco;
}


      //extensão do arquivo
$ext = pathinfo($comprovante_rg, PATHINFO_EXTENSION);
if($ext == 'pdf'){
  $tumb_comprovante_rg = 'pdf.png';
}else if($ext == 'rar' || $ext == 'zip'){
  $tumb_comprovante_rg = 'rar.png';
}else{
  $tumb_comprovante_rg = $comprovante_rg;
}

$enderecoF2 = rawurlencode($endereco);

          echo <<<HTML
      <div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows slider-no-dots" id="user-slider-{$id}">
        <div class="splide__track">
          <div class="splide__list">
            <div class="splide__slide mx-3">
              <div class="d-flex">
              <div ><img src="../../painel/images/clientes/{$foto}" class="me-3 rounded-circle shadow-l" width="40"></div>

                <div onclick="mostrar('{$id}', '{$nome}','{$telefone}','{$cpf}','{$email}','{$enderecoF2}','{$data_nascF}', '{$data_cadF}', '{$obs}', '{$pix}', '{$indicacao}', '{$bairro}', '{$cidade}', '{$estado}', '{$cep}', '{$total_emprestimos}', '{$total_cobrancas}', '{$pessoa}', '{$total_contas}', '{$nome_sec}', '{$telefone_sec}', '{$endereco_sec}', '{$grupo}', '{$dados_emprestimoF}', '{$comprovante_endereco}', '{$comprovante_rg}', '{$tumb_comprovante_endereco}', '{$tumb_comprovante_rg}', '{$telefone2}', '{$foto}')">
                <h5 class="mt-1 mb-0" style="font-size: 13px;"><i class="fa fa-square" style="color:{$cor}; display:{$ocultar_cor}"></i> {$nome}</h5>
                <p class="font-12 mt-n2 mb-0 ">{$telefone}</p>
                <p class="font-12 mt-n2 mb-0 color-blue-dark"></p>
                <p class="font-12 mt-n2 mb-0 color-blue-dark"> <span class="">{$email}</span></p>
                <p class="font-12 mt-n2 mb-0">Cadastrado: {$data_cadF}</p>
                </div>
                <div class="ms-auto"><span class="px-2 py-1 badge mt-4 p-2 font-12 shadow-bg shadow-bg-s" style="background:{$cor}">{$status_cliente}</span></div>

                <div class="ms-auto"><span class="badge bg-blue-dark mt-0 p-1 font-8 shadow-bg-s"><i class="fa fa-arrow-right"></i></span></div>
              </div>
            </div>
            <div class="splide__slide mx-3">

              <div class="d-flex">
                <div class="ms-auto">

                 
                  <a onclick="editar('{$id}','{$nome}','{$telefone}','{$cpf}','{$email}','{$enderecoF2}','{$data_nascF}', '{$obs}', '{$pix}', '{$indicacao}', '{$bairro}', '{$cidade}', '{$estado}', '{$cep}', '{$pessoa}', '{$nome_sec}', '{$telefone_sec}', '{$endereco_sec}', '{$grupo}', '{$tumb_comprovante_endereco}', '{$tumb_comprovante_rg}', '{$telefone2}', '{$foto}', '{$status_cliente}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-twitter"><i class="fa fa-edit text-white"></i></a>                  

                  <a onclick="arquivo('{$id}','{$nome}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-cinza"><i
                      class="fa fa-file text-white"></i></a>

                  <a target="_blank" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}" class="icon icon-xs rounded-circle shadow-l bg-whatsapp"><i class="fa-brands fa-whatsapp text-white"></i></a>

                  <a onclick="excluir_reg('{$id}', '{$nome}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-google"><i class="bi bi-trash-fill text-white"></i></a>

                  <a onclick="emprestimo('{$id}','{$nome}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-phone"><i class="bi bi-currency-dollar text-white"></i></a>

                  <a onclick="cobranca('{$id}','{$nome}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-phone"><i class="bi bi-credit-card-fill text-white"></i></a>


                 <a onclick="mostrarContas('{$id}','{$nome}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-twitter">
                  <i class="bi bi-eye text-white"></i>
                </a>

                 

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


      <form method="post" style="display: none;">
        <input type="text" name="pagina" id="input_pagina">
        <input type="text" name="buscar" id="input_buscar">
        <button type="submit" id="paginacao"></button>
      </form>
    </div>
  </div>
</div>


<div class="fab" style="z-index: 100 !important; margin-bottom: 60px">
  <button onclick="limparCampos()" id="btn_novo" class="main open-popup bg-highlight" data-bs-toggle="offcanvas"
    data-bs-target="#popupForm">
    +
  </button>
</div>


<div hidden="hidden" class="fab" style="z-index: 100 !important; margin-bottom: 60px">
  <button id="btn_novo_editar" class="main open-popup bg-highlight" data-bs-toggle="offcanvas"
    data-bs-target="#popupForm">
  </button>
</div>




<!-- MODAL CADASTRO-->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:98%" id="popupForm">
  <div class="content mb-4">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h2 align="center" class="mb-n1 font-12 color-highlight font-700 text-uppercase pt-1" id="titulo_inserir">
          INSERIR DADOS</h2>
      </div>
      <div class="align-self-center ms-auto">
        <button style="border: none; background: transparent; margin-right: 12px" data-bs-dismiss="offcanvas"
          id="btn-fechar" aria-label="Close" data-bs-dismiss="modal" type="button"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i>
        </button>
      </div>
    </div>
    <form id="form" method="post" class="demo-animation needs-validation m-0">

      <!-- Nome -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-person-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="nome" name="nome" placeholder="" required>
  <label class="color-theme ps-5">Nome</label>
  <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger" style="font-size: 9px;">(Obrigatório)</small>
</div>

<!-- Email -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-envelope-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="email" class="form-control rounded-xs ps-5" id="email" name="email" placeholder="">
  <label class="color-theme ps-5">Email</label>
</div>

<!-- Status Cliente -->
<div class="form-floating mb-3 position-relative">
  <!-- Ícone esquerdo -->
  <i class="bi bi-info-circle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>

  <!-- Preview da cor: posicionado à direita -->
  <div id="preview_cor_status"
    class="position-absolute top-50 translate-middle-y"
    style="right: 2.5rem; width: 17px; height: 17px; border: 1px solid #ccc; border-radius: 4px;">
  </div>

  <!-- Select -->
  <select class="form-select rounded-xs ps-5 pe-5" name="status_cliente" id="status_cliente" onchange="atualizarCorStatus()">
    <option value="" data-cor="">Selecionar Status</option>
    <?php 
      $query = $pdo->query("SELECT * from status_clientes order by id asc");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $linhas = @count($res);
      if($linhas > 0){
        for($i=0; $i<$linhas; $i++){
    ?>
      <option value="<?= $res[$i]['nome'] ?>" data-cor="<?= $res[$i]['cor'] ?>"><?= $res[$i]['nome'] ?></option>
    <?php } } ?>
  </select>

  <!-- Label flutuante -->
  <label class="color-theme ps-5">Status Cliente</label>
</div>


<!-- Telefone -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-telephone-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="telefone" name="telefone" placeholder="" required>
  <label class="color-theme ps-5">Telefone</label>
</div>

<!-- Telefone2 -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-telephone-plus-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="telefone2" name="telefone2" placeholder="">
  <label class="color-theme ps-5">Outro Telefone</label>
</div>

<!-- Tipo Pessoa -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-person-badge-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <select class="form-select rounded-xs ps-5 pe-5" name="pessoa" id="pessoa" onchange="mascara_pessoa()">
    <option value="Física">Física</option>
    <option value="Jurídica">Jurídica</option>
  </select>
  <label class="color-theme ps-5">Tipo Pessoa</label>
</div>

<!-- CPF / CNPJ -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-file-earmark-person-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="cpf" name="cpf" placeholder="" required>
  <label class="color-theme ps-5">CPF / CNPJ</label>
</div>

<!-- Nascimento -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-calendar-event-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="data_nasc" name="data_nasc" placeholder="dd/mm/aaaa">
  <label class="color-theme ps-5">Nascimento</label>
</div>

<!-- CEP -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-geo-alt-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="cep" name="cep" placeholder="" onblur="pesquisacep(this.value);">
  <label class="color-theme ps-5">CEP</label>
</div>

<!-- Endereço -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-house-door-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="endereco" name="endereco" placeholder="">
  <label class="color-theme ps-5">Endereço</label>
</div>

<!-- Bairro -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-signpost-2-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="bairro" name="bairro" placeholder="">
  <label class="color-theme ps-5">Bairro</label>
</div>

<!-- Cidade -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-building position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="cidade" name="cidade" placeholder="">
  <label class="color-theme ps-5">Cidade</label>
</div>

<!-- Estado -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-map-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <select class="form-select rounded-xs ps-5 pe-5" id="estado" name="estado">
    <option value="">Selecionar</option>
    <option value="AC">Acre</option>
    <option value="AL">Alagoas</option>
    <option value="AP">Amapá</option>
    <option value="AM">Amazonas</option>
    <option value="BA">Bahia</option>
    <option value="CE">Ceará</option>
    <option value="DF">Distrito Federal</option>
    <option value="ES">Espírito Santo</option>
    <option value="GO">Goiás</option>
    <option value="MA">Maranhão</option>
    <option value="MT">Mato Grosso</option>
    <option value="MS">Mato Grosso do Sul</option>
    <option value="MG">Minas Gerais</option>
    <option value="PA">Pará</option>
    <option value="PB">Paraíba</option>
    <option value="PR">Paraná</option>
    <option value="PE">Pernambuco</option>
    <option value="PI">Piauí</option>
    <option value="RJ">Rio de Janeiro</option>
    <option value="RN">Rio Grande do Norte</option>
    <option value="RS">Rio Grande do Sul</option>
    <option value="RO">Rondônia</option>
    <option value="RR">Roraima</option>
    <option value="SC">Santa Catarina</option>
    <option value="SP">São Paulo</option>
    <option value="SE">Sergipe</option>
    <option value="TO">Tocantins</option>
    <option value="EX">Estrangeiro</option>
  </select>
  <label class="color-theme ps-5">Estado</label>
</div>

<!-- Pix -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="pix" name="pix" placeholder="">
  <label class="color-theme ps-5">Chave Pix / Conta Bancária</label>
</div>

<!-- Indicação -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-person-plus-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="indicacao" name="indicacao" placeholder="">
  <label class="color-theme ps-5">Indicação</label>
</div>

<!-- Status -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-shield-fill-check position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <select class="form-select rounded-xs ps-5 pe-5" id="status" name="status">
    <option value="Ativo">Ativo</option>
    <option value="Inativo">Inativo</option>
    <option value="Alerta">Alerta</option>
    <option value="Atenção">Atenção</option>
  </select>
  <label class="color-theme ps-5">Status</label>
</div>

<!-- Nome Secundário -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-person-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="nome_sec" name="nome_sec" placeholder="">
  <label class="color-theme ps-5">Nome Secundário</label>
</div>

<!-- Telefone Secundário -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-telephone-inbound-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="telefone_sec" name="telefone_sec" placeholder="">
  <label class="color-theme ps-5">Telefone Secundário</label>
</div>

<!-- Endereço Secundário -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-geo-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="endereco_sec" name="endereco_sec" placeholder="">
  <label class="color-theme ps-5">Endereço Secundário</label>
</div>

<!-- Grupo -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-diagram-3-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="grupo" name="grupo" placeholder="">
  <label class="color-theme ps-5">Grupo (Empresa, outro)</label>
</div>

<!-- Observações -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-chat-left-text-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="obs" name="obs" placeholder="">
  <label class="color-theme ps-5">Observações</label>
</div>


<div class="row" style="margin-top: 20px">

  <!-- Comprovante de Endereço -->
  <div class="col-6 col-md-4">
    <div class="form-group text-center" onclick="comprovante_endereco.click()">
      <img src="../../painel/images/comprovantes/sem-foto.png" width="80px" id="target-comprovante-endereco"><br>
      <img src="../painel/images/icone-arquivo.png" width="100px" style="margin-top: -12px">
      <div><small>Comprovante Endereço</small></div>
    </div>
    <input type="file" name="comprovante_endereco" id="comprovante_endereco" style="display:none" onchange="carregarImgComprovanteEndereco();">
  </div>

  <!-- Comprovante RG/CPF -->
  <div class="col-6 col-md-4">
    <div class="form-group text-center" onclick="comprovante_rg.click()">
      <img src="../../painel/images/comprovantes/sem-foto.png" width="80px" id="target-comprovante-rg"><br>
      <img src="../painel/images/icone-arquivo.png" width="100px" style="margin-top: -12px">
      <div><small>Comprovante RG / CPF</small></div>
    </div>
    <input type="file" name="comprovante_rg" id="comprovante_rg" style="display:none" onchange="carregarImgComprovanteRG();">
  </div>

  <!-- Foto do Cliente -->
  <div class="col-6 col-md-4">
    <div class="form-group text-center" onclick="foto.click()">
      <img src="../../painel/images/clientes/sem-foto.jpg" width="80px" id="target"><br>
      <img src="../painel/images/icone-arquivo.png" width="100px" style="margin-top: -12px">
      <div><small>Foto do Cliente</small></div>
    </div>
    <input type="file" name="foto" id="foto" style="display:none" onchange="carregarImg();">
  </div>

</div>



      <input type="hidden" name="id" id="id">
      <button name="btn_salvar" id="btn_salvar"
        class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
        type="submit">SALVAR <i class="fa-regular fa-circle-check"></i></button>
      <button class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
        type="button" id="btn_carregando" style="display: none">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...
      </button>
    </form>
  </div>
</div>





<!--BOTÃO PARA CHAMAR A MODAL MOSTRAR -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-mostrar" class="list-group-item"
  id="btn_mostrar">
</a>

<!-- MODAL MOSTRAR -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:98%" id="menu-share-mostrar">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="titulo_dados"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <div class="card overflow-visible">
      <div class="content mb-1">
        <div class="table-responsive">
          <table class="table color-theme mb-4">
            <tbody>
              
                <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Nome:</td>
      <td style="font-size: 11px;" id="nome_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">CPF:</td>
      <td style="font-size: 11px;" id="cpf_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Data Nasc.:</td>
      <td style="font-size: 11px;" id="data_nasc_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Telefone:</td>
      <td style="font-size: 11px;" id="telefone_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Telefone 2:</td>
      <td style="font-size: 11px;" id="telefone2_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">E-mail:</td>
      <td style="font-size: 11px;" id="email_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Endereço:</td>
      <td style="font-size: 11px;" id="endereco_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Bairro:</td>
      <td style="font-size: 11px;" id="bairro_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Cidade:</td>
      <td style="font-size: 11px;" id="cidade_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Estado:</td>
      <td style="font-size: 11px;" id="estado_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">CEP:</td>
      <td style="font-size: 11px;" id="cep_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">PIX:</td>
      <td style="font-size: 11px;" id="pix_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Pessoa:</td>
      <td style="font-size: 11px;" id="pessoa_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Grupo:</td>
      <td style="font-size: 11px;" id="grupo_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Indicação:</td>
      <td style="font-size: 11px;" id="indicacao_dados"></td>
    </tr>
    <tr id="div_obs_dados" class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Observações:</td>
      <td style="font-size: 11px;" id="obs_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Data Cadastro:</td>
      <td style="font-size: 11px;" id="data_cad_dados"></td>
    </tr>
    
    <!-- Contato Secundário -->
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Nome Secundário:</td>
      <td style="font-size: 11px;" id="nome_sec_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Telefone Secundário:</td>
      <td style="font-size: 11px;" id="telefone_sec_dados"></td>
    </tr>
    <tr class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Endereço Secundário:</td>
      <td style="font-size: 11px;" id="endereco_sec_dados"></td>
    </tr>

    <!-- Arquivos -->
    <tr id="div_link_comprovante_endereco" class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Comprovante Endereço:</td>
      <td style="font-size: 11px;">
        <a href="#" target="_blank" id="link_comprovante_endereco">
          <img src="" width="50" id="target_mostrar_comprovante_endereco">
        </a>
      </td>
    </tr>

    <tr id="div_link_comprovante_rg" class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Comprovante RG/CPF:</td>
      <td style="font-size: 11px;">
        <a href="#" target="_blank" id="link_comprovante_rg">
          <img src="" width="50" id="target_mostrar_comprovante_rg">
        </a>
      </td>
    </tr>

    <tr id="div_foto" class="border-fade-blue">
      <td class="color-highlight" style="font-size: 11px;">Foto Cliente:</td>
      <td style="font-size: 11px;">
        <img src="" width="50" id="target_mostrar_foto">
      </td>
    </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>









<!--BOTÃO PARA CHAMAR A MODAL NOVO EMPRÉSTIMO -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-emprestimo" class="list-group-item"
  id="btn_emprestimo">
</a>

<!-- MODAL NOVO EMPRESTIMO -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:98%" id="menu-share-emprestimo">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="titulo_emp"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_emp" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    <!-- Valor -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="valor" name="valor" placeholder="Valor" required onkeyup="mascara_valor('valor')">
        <label class="color-theme ps-5">Valor</label>
      </div>
    </div>

    <!-- Parcelas + Juros Final -->
    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-stack position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="number" class="form-control rounded-xs ps-5" id="parcelas" name="parcelas" placeholder="Parcelas" required value="1">
        <label class="color-theme ps-5">Parcelas</label>
      </div>
    </div>

    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-percent position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="juros_emp" name="juros_emp" placeholder="Juros Final %" required value="<?php echo $juros_emprestimo ?>">
        <label class="color-theme ps-5">Juros Final %</label>
      </div>
    </div>

    <!-- Data + Vencimento -->
    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-date position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_emp" name="data" required value="<?php echo $data_atual ?>">
        <label class="color-theme ps-5">Data</label>
      </div>
    </div>

    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-check-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_venc" name="data_venc" value="<?php echo $data_atual ?>">
        <label class="color-theme ps-5">Vencimento</label>
      </div>
    </div>

    <!-- Juros % Dia + Multa -->
    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-piggy-bank position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="juros" name="juros" placeholder="Juros por Dia" onkeyup="mascara_valor('juros')" value="<?php echo $juros_sistema ?>">
        <label class="color-theme ps-5">Juros % Dia</label>
      </div>
    </div>

    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-exclamation-triangle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="multa" name="multa" placeholder="Multa" onkeyup="mascara_valor('multa')" value="<?php echo $multa_sistema ?>">
        <label class="color-theme ps-5">Multa R$</label>
      </div>
    </div>

    <!-- Frequência -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-arrow-repeat position-absolute start-0 top-50 translate-middle-y ms-3"></i>

        <select class="form-select rounded-xs ps-5" name="frequencia" id="frequencia" required="">
          <option value="">Selecione</option>
          <?php 
            $query = $pdo->query("SELECT * from frequencias order by id asc");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach($res as $r){
                echo '<option value="'.$r['dias'].'">'.$r['frequencia'].'</option>';
            }
          ?>
        </select>
        <label class="color-theme ps-5">Frequência</label>
      </div>
    </div>

    <!-- Tipo de Juros -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-bar-chart-line-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <select class="form-select rounded-xs ps-5" name="tipo_juros" id="tipo_juros">
          <option value="Padrão">Juros Padrão (Básico)</option>
          <option value="Simples">Juros Simples (Price JS)</option>
          <option value="Composto_Price">Juros Composto Banco</option>
          <option value="Composto">Juros Composto Comum</option>
          <option value="Prefixado">Juros Prefixados</option>
          <option value="Somente Júros">Somente Juros</option>
          <option value="Sem Júros">Sem Juros</option>
        </select>
        <label class="color-theme ps-5">Tipo de Juros</label>
      </div>
    </div>

    <!-- Enviar WhatsApp -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-whatsapp position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <select name="enviar_whatsapp" id="enviar_whatsapp_emp" class="form-select rounded-xs ps-5">  
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
        <label class="color-theme ps-5">Enviar WhatsApp</label>
      </div>
    </div>

    <!-- Observações -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-chat-left-text-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" name="obs" placeholder="Observações">
        <label class="color-theme ps-5">Observações</label>
      </div>
    </div>


    <input type="hidden" class="form-control" id="id_emp" name="id">
    <input type="hidden" class="form-control" id="nome_emprest" name="nome">

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_emprestimo" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Salvar Empréstimo
      </button>
    </div>

  </div>


</form>

  </div>
</div>









<!--BOTÃO PARA CHAMAR A MODAL NOVO COBRANÇA -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-cobranca" class="list-group-item"
  id="btn_cobranca">
</a>

<!-- MODAL NOVO COBRANÇA -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:98%" id="menu-share-cobranca">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="titulo_cob"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a id="btn-fechar-cob" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_cob" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    <!-- Valor -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="valor_cob" name="valor" placeholder="Valor" required onkeyup="mascara_valor('valor_cob')">
        <label class="color-theme ps-5">Valor</label>
      </div>
    </div>

    <!-- Parcelas + Juros Final -->
    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-stack position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="number" class="form-control rounded-xs ps-5" id="parcelas_cob" name="parcelas" placeholder="Parcelas" required value="1">
        <label class="color-theme ps-5">Parcelas</label>
      </div>
    </div>
   

    <!-- Data + Vencimento -->
    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-date position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_cob" name="data" required value="<?php echo $data_atual ?>">
        <label class="color-theme ps-5">Data</label>
      </div>
    </div>

    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-check-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_venc_cob" name="data_venc" value="<?php echo $data_atual ?>">
        <label class="color-theme ps-5">Vencimento</label>
      </div>
    </div>

    <!-- Juros % Dia + Multa -->
    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-piggy-bank position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="juros_cob" name="juros" placeholder="Juros por Dia" onkeyup="mascara_valor('juros_cob')" value="<?php echo $juros_sistema ?>">
        <label class="color-theme ps-5">Juros % Dia</label>
      </div>
    </div>

    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-exclamation-triangle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="multa_cob" name="multa" placeholder="Multa" onkeyup="mascara_valor('multa_cob')" value="<?php echo $multa_sistema ?>">
        <label class="color-theme ps-5">Multa R$</label>
      </div>
    </div>

    <!-- Frequência -->
    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-arrow-repeat position-absolute start-0 top-50 translate-middle-y ms-3"></i>

        <select class="form-select rounded-xs ps-5" name="frequencia" id="frequencia" required="">
          <option value="">Selecione</option>
          <?php 
            $query = $pdo->query("SELECT * from frequencias order by id asc");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach($res as $r){
                echo '<option value="'.$r['dias'].'">'.$r['frequencia'].'</option>';
            }
          ?>
        </select>
        <label class="color-theme ps-5">Frequência</label>
      </div>
    </div>

   
    <!-- Enviar WhatsApp -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-whatsapp position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <select name="enviar_whatsapp" id="enviar_whatsapp_emp" class="form-select rounded-xs ps-5">  
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
        <label class="color-theme ps-5">Enviar WhatsApp</label>
      </div>
    </div>

    <!-- Observações -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-chat-left-text-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5"  name="obs" placeholder="Observações">
        <label class="color-theme ps-5">Observações</label>
      </div>
    </div>


    <input type="hidden" class="form-control" id="id_cob" name="id">
    <input type="hidden" class="form-control" id="nome_cob" name="nome">

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_cobranca" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Salvar Cobrança
      </button>
    </div>

  </div>


</form>

  </div>
</div>


<!--BOTÃO PARA CHAMAR A MODAL BAIXAR -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-baixar-contas"
  class="list-group-item" id="btn_baixar" data-bs-target="#staticBackdrop">
</a>

<!-- MODAL BAIXAR -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:95%;" id="menu-share-baixar-contas">
  <div class="content ">
    <div class="d-flex pb-0">
      <div class="align-self-center">
        <h1 class="font-14 color-highlight font-700 text-uppercase" id="titulo_baixar"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m" id="btn_fechar_baixar" onclick="abrContas()"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <div class="card overflow-visible rounded-xs">
      <div class="content mb-1">
        <div class="table-responsive">
          <form id="form_baixar_contas" method="post">
            <div class="col-12 mt-2">
              <div class="form-custom form-label form-icon mb-3">
                <div class="form-custom form-label form-icon mb-3">
                  <i class="bi bi-currency-dollar font-16"></i>
                  <input type="text" name="valor_baixar" id="valor_baixar" class="form-control rounded-xs"
                    onkeyup=" calcular()" value="" required readonly="" />
                  <label class="color-theme form-label-always-active font-10 opacity-50">Valor <small><small>(Total /
                        Parcial)</small></small> </label>
                </div>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-custom form-label form-icon mb-3">
              <div class="form-custom form-label form-icon mb-3">
                <i class="bi bi-currency-dollar font-16"></i>
                <input type="text" name="juros_baixar" id="juros_baixar" class="form-control rounded-xs"
                  onkeyup=" calcular()" placeholder="Ex 15.00" value="0" />
                <label class="color-theme form-label-always-active font-10 opacity-50">Total Júros</label>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="form-custom form-label form-icon mb-3">
              <i class="bi bi-currency-dollar font-16"></i>
              <input type="text" name="multa_baixar" id="multa_baixar" class="form-control rounded-xs"
                onkeyup="calcular()" placeholder="Ex 0.15" value="0" />
              <label class="color-theme form-label-always-active font-10 opacity-50">Multa R$</label>
            </div>
          </div>
        </div>
       
      <div class="row">
       
       <div class="col-12">
            <div class="form-custom form-label form-icon mb-3">
              <div class="form-custom form-label form-icon mb-3">
                <i class="bi bi-calendar-date font-16"></i>
                <input type="date" name="data_baixa" id="data_baixa" class="form-control rounded-xs"
                   value="<?php echo $data_atual ?>" />
                <label class="color-theme form-label-always-active font-10 opacity-50">Data PGTO</label>
              </div>
            </div>
          </div>

      </div>

      <div class="row">          

           <div class="col-6">
          <div class="form-custom form-label  mb-3">
            <select name="forma_pgto" id="forma_pgto" required onchange="calcular()"
              class="form-select rounded-xs" aria-label="Floating label select example">
              <?php
              $query = $pdo->query("SELECT * from formas_pgto order by id desc");
              $res = $query->fetchAll(PDO::FETCH_ASSOC);
              $linhas = @count($res);
              if ($linhas > 0) {
                for ($i = 0; $i < $linhas; $i++) {
                  echo '<option value="' . $res[$i]['nome'] . '">' . $res[$i]['nome'] . '</option>';
                }
              } else {
                echo '<option value="0">Cadastre uma Forma de Pagamento</option>';
              }
              ?>
            </select>
            <label class="olor-theme form-label-always-active font-10 opacity-50">Forma PGTO</label>
          </div>
        </div>


          <div class="col-6">
            <div class="form-custom form-label form-icon mb-3">
              <i class="bi bi-currency-dollar font-16"></i>
              <input type="text" name="valor_final" id="valor_final" class="form-control rounded-xs"
                onkeyup="" placeholder="Ex 0.15" value="0" />
              <label class="color-theme form-label-always-active font-10 opacity-50">Valor Final</label>
            </div>
          </div>

        </div>

          <div class="row" align="right" style="font-size: 14px">
          <div class="col-md-12"> 
              <span style="margin-right: 15px">
                <input type="checkbox" class="form-checkbox" id="residuo_final" name="residuo_final" value="Sim" style="display:inline-block;">
                <label for="residuo_final" style="display:inline-block;"><small>Resíduo Final Empréstimo</small></label>
              </span>
              <br>
              <span>  
                <input type="checkbox" class="form-checkbox" id="residuo" name="residuo" value="Sim" style="display:inline-block;">
                <label for="residuo" style="display:inline-block;"><small>Resíduo Próxima Parcela</small></label>
              </span>
            </div>

            
          </div>


          <button name="btn_baixar" id="btn_baixar"
            class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
            type="submit">SALVAR <i class="fa-regular fa-circle-check"></i></button>
          <button class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
            type="button" id="btn_carregando" style="display: none">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...
          </button>
         
         <input type="hidden" class="form-control" id="id_baixar" name="id">    

          </form>
        </div>
      </div>
    </div>
  </div>
</div>






<!--BOTÃO PARA CHAMAR A MODAL ARQUIVOS -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-arquivos"
  class="list-group-item" id="btn_arquivos" data-bs-target="#staticBackdrop">
</a>

<!-- MODAL ARQUIVOS -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:70%;" id="menu-share-arquivos">
  <div class="content ">
    <div class="d-flex pb-0">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="titulo_arquivo"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <div class="card overflow-visible rounded-xs">
      <div class="content mb-1">
        <div class="table-responsive">
          <table class="table color-theme mb-4">
            <form id="form_arquivos" method="post" style="padding-bottom: 17px; border:1px solid #bdbbbb">
              <div align="center" onclick="arquivo_conta.click()" style="padding-top: 10px; padding-bottom: 10px">
                <img src="images/sem-foto.png" width="85px" id="target-arquivos"><br>
                <img src="images/icone-arquivo.png" width="85px" style="margin-top: -12px">
              </div>
              <input onchange="carregarImgArquivos()" type="file" name="arquivo_conta" id="arquivo_conta"
                hidden="hidden">

              <div class="form-floating mb-3 position-relative">
                <i class="bi bi-pencil-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
                <input type="text" class="form-control rounded-xs ps-5" id="nome-arq" name="nome_arq" placeholder=""
                  required>
                <label class="color-theme ps-5">Nome do Arquvio</label>
              </div>

             

              <button name="btn_salvar_arquivo" id="btn_salvar_arquivo"
                class="btn btn-full gradient-highlight rounded-xs text-uppercase w-100 btn-s mt-2" type="submit">Inserir
                <i class="fa-regular fa-circle-check"></i></button>
              <input type="hidden" name="id_arquivo" id="id-arquivo">
            </form>
            <div id="listar-arquivos"></div>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



<!--BOTÃO PARA CHAMAR A MODAL CONTAS -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-contas" class="list-group-item"
  id="btn_contas">
</a>

<!-- MODAL CONTAS -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:95%" id="menu-share-contas">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-14 color-highlight font-700 text-uppercase" id="titulo_contas"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#"  data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <div class="card overflow-visible">
      <div class="content mb-1">
        <div class="table-responsive">
          <table class="table color-theme mb-4">
            

            <div class="row"  id="dados_emp">
          <div style="border-bottom: 1px solid #000"><small>Empréstimos do Cliente</small></div>

          <small><div id="listar_emprestimos">
            
          </div></small>
        </div>


        <div class="row"  id="dados_cob">
          <div style="border-bottom: 1px solid #000"><small>Cobranças do Cliente</small></div>

          <small><div id="listar_cobrancas">
            
          </div></small>
        </div>



        <div class="row"  id="dados_deb">
          <div style="border-bottom: 1px solid #000"><small>Demais Débitos do Cliente</small></div>

          <small><div id="listar_contas">
            
          </div></small>
        </div>

            <input type="hidden" id="id_contas">
             <input type="hidden" id="nome_contas">
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



<!--BOTÃO PARA CHAMAR A MODAL STATUS -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-status" class="list-group-item"
  id="btn_status">
</a>

<!-- MODAL STATUS -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:30%" id="menu-share-status">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-14 color-highlight font-700 text-uppercase" id="titulo_status">Mudar Status</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <form id="form-status" method="post">
      <div class="col-12">
        <div class="form-floating mb-3 position-relative">
          <i class="bi bi-check-circle position-absolute start-0 top-50 translate-middle-y ms-3"></i>
          <select name="status" id="status" class="form-select rounded-xs ps-5 pe-5" aria-label="Selecione">
            <option value="Sim">Ativo</option>
            <option value="Não">Desativado</option>
            <option value="">Inadimplente</option>
          </select>
          <label for="status" class="color-theme ps-5">Status</label>
        </div>
      </div>


      <button id="btn_salvar_status" class="btn btn-full gradient-highlight rounded-xs text-uppercase w-100 btn-s mt-2"
        type="submit">Mudar Status<i class="fa fa-check ms-2"></i></button>
      <button class="btn btn-full gradient-highlight rounded-xs text-uppercase w-100 btn-s mt-2" type="button"
        id="btn_carregando_status" style="display: none">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...
      </button>
      <input type="hidden" name="id" id="id_status">
      <input type="hidden" class="form-control" name="id_da_os" id="id_da_os">
    </form>
  </div>
</div>






<!--BOTÃO PARA EDITAR JUROS E MULTA -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-editar" class="list-group-item"
  id="modalEditar">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:40%" id="menu-share-editar">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="titulo_cob"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="mostrarContas($('#id_contas').val(), $('#nome_contas').val())" id="btn-fechar-editar" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_empr" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    <!-- Valor -->
    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="juros_empr" name="juros" placeholder="Júros se Houver" required onkeyup="mascara_valor('juros_empr')">
        <label class="color-theme ps-5">Júros</label>
      </div>
    </div>


     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="multa_empr" name="multa" placeholder="Multa se Houver" required onkeyup="mascara_valor('multa_empr')">
        <label class="color-theme ps-5">Múlta</label>
      </div>
    </div>

   
    <input type="hidden" class="form-control" id="id_empr" name="id">

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_editar" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Editar
      </button>
    </div>

  </div>


</form>

  </div>
</div>











<!--MODAL NOVA PARCELA -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-nova_parcela" class="list-group-item"
  id="modalNovaParcela">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:70%" id="menu-share-nova_parcela">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="id_nova_parcela_cliente">Nova Parcela</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="mostrarContas($('#id_contas').val(), $('#nome_contas').val())" id="btn-fechar-nova_parcela" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_nova_parcela" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    <!-- Valor -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-chat-left-text-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="descricao_nova" name="descricao" placeholder="" required>
        <label class="color-theme ps-5">Descrição</label>
      </div>
    </div>


     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="valor_nova" name="valor" placeholder="" required onkeyup="mascara_valor('valor_nova')">
        <label class="color-theme ps-5">Valor</label>
      </div>
    </div>


      <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-date position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_venc_nova" name="data_venc" required>
        <label class="color-theme ps-5">Vencimento</label>
      </div>
    </div>

    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-exclamation-triangle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="obs_nova" name="obs" placeholder="">
        <label class="color-theme ps-5">Observações</label>
      </div>
    </div>


   
   <input type="hidden" class="form-control" id="id_nova_parcela" name="id">  
          <input type="hidden" class="form-control" id="id_nova_parcela_cliente" name="id_cliente"> 

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_nova_parcela" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Nova Parcela
      </button>
    </div>

  </div>


</form>

  </div>
</div>








<!--MODAL MOSTRAR PARCELAS -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-parcelas" class="list-group-item"
  id="modalParcelas">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:90%" id="menu-share-parcelas">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="">Parcelas</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="mostrarContas($('#id_contas').val(), $('#nome_contas').val())" id="btn-fechar-nova_parcela" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
    <small><div id="listar_parcelas" style=""></div></small>

        <input type="hidden" id="id_emprestimo">
        <input type="hidden" id="id_cobranca">  

  </div>
</div>









<!--MODAL AMORTIZAR -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-amortizar" class="list-group-item"
  id="modalAmortizar">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:70%" id="menu-share-amortizar">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="id_nova_parcela_cliente">Amortizar Valor</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="mostrarContas($('#id_contas').val(), $('#nome_contas').val())" id="btn-fechar-amortizar" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_amortizar" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    
     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="valor_amortizar" name="valor_amortizar" placeholder="" required onkeyup="mascara_valor('valor_amortizar')">
        <label class="color-theme ps-5">Valor</label>
      </div>
    </div>


      <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-date position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_amortizar" name="data_amortizar" required value="<?php echo date('Y-m-d') ?>">
        <label class="color-theme ps-5">Data</label>
      </div>
    </div>

    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-exclamation-triangle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="obs_amortizar" name="obs_amortizar" placeholder="">
        <label class="color-theme ps-5">Observações</label>
      </div>
    </div>


   
 <input type="hidden" class="form-control" id="id_amortizar" name="id"> 
          <input type="hidden" class="form-control" id="id_amortizar_cliente" name="id_cliente">  

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_amortizar" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Amortizar
      </button>
    </div>

  </div>


</form>

  </div>
</div>









<!--MODAL BAIXAR EMPRSTIMO -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-baixar_emp" class="list-group-item"
  id="modalBaixarEmprestimo">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:85%" id="menu-share-baixar_emp">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="id_nova_baixar_emp">Amortizar Valor</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="mostrarContas($('#id_contas').val(), $('#nome_contas').val())" id="btn-fechar-baixar_emp" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_baixar_emprestimo" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    
     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="valor_final_emprestimo" name="valor_final_emprestimo" placeholder="" required onkeyup="mascara_valor('valor_final_emprestimo')">
        <label class="color-theme ps-5">Valor</label>
      </div>
    </div>


     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-arrow-repeat position-absolute start-0 top-50 translate-middle-y ms-3"></i>

        <select class="form-select rounded-xs ps-5" name="status_baixa" id="status_baixa" required="">
          <option value="Finalizado">Finalizado</option>
      <option value="Perdido">Perdido</option>
        </select>
        <label class="color-theme ps-5">Status Baixa</label>
      </div>
    </div>


      <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-date position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_baixa_emprestimo" name="data_baixa_emprestimo" required value="<?php echo date('Y-m-d') ?>">
        <label class="color-theme ps-5">Data Pgto</label>
      </div>
    </div>

      <div class="col-12">
          <div class="form-custom form-label  mb-3">
            <select name="forma_pgto_emprestimo" id="forma_pgto_emprestimo" required 
              class="form-select rounded-xs" aria-label="Floating label select example">
              <?php
              $query = $pdo->query("SELECT * from formas_pgto order by id desc");
              $res = $query->fetchAll(PDO::FETCH_ASSOC);
              $linhas = @count($res);
              if ($linhas > 0) {
                for ($i = 0; $i < $linhas; $i++) {
                  echo '<option value="' . $res[$i]['nome'] . '">' . $res[$i]['nome'] . '</option>';
                }
              } else {
                echo '<option value="0">Cadastre uma Forma de Pagamento</option>';
              }
              ?>
            </select>
            <label class="olor-theme form-label-always-active font-10 opacity-50">Forma PGTO</label>
          </div>
        </div>

   
<input type="hidden" class="form-control" id="id_do_emp" name="id">   
          <input type="hidden" class="form-control" id="id_do_cliente" name="id_cliente"> 


          <br>
        <small><div align="center" style="border: 1px solid #000; font-size: 10px"><b>OBS:</b>Ao Finalizar o empréstimo, todas as parcelas que estão pendentes de pagamentos serão excluídas e o valor inserido aqui será lançado no financeiro.</div></small>

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_baixar_emprestimo" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Baixar Empréstimo
      </button>
    </div>

  </div>


</form>

  </div>
</div>








<form action="../../painel/rel/recibo_class.php" method="post" style="display:none" >
    <input type="hidden" name="id" value="<?=$id_conta;?>" id="id_conta_recibo">
    <input type="hidden" name="enviar" value="Sim">
    <button id="btn_form" type="submit"></button>
</form>





<script type="text/javascript">
  function buscar(status) {

    // Remove a classe selected de todas as abas
    document.querySelectorAll('.tabradio').forEach(tab => {
      tab.classList.remove('selected');
    });

    // Adiciona a classe selected na aba clicada
    event.target.classList.add('selected');

    // Define o valor do status e submete o formulário
    $('#ativo').val(status);
    $('#btn_filtrar_ativo').click();
  }
</script>

<script type="text/javascript">
  function mudarPessoa() {
    var pessoa = $('#tipo_pessoa').val();
    if (pessoa == 'Física') {
      $('#cpf').mask('000.000.000-00');
      $('#cpf').attr("placeholder", "Insira CPF");
    } else {
      $('#cpf').mask('00.000.000/0000-00');
      $('#cpf').attr("placeholder", "Insira CNPJ");
    }
  }
</script>


<script type="text/javascript">
  function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("#foto").files[0];
    var reader = new FileReader();
    reader.onloadend = function () {
      target.src = reader.result;
    };
    if (file) {
      reader.readAsDataURL(file);
    } else {
      target.src = "";
    }
  }
</script>



<script>
  function editar(id, nome, telefone, cpf, email, endereco, data_nasc, obs, pix, indicacao, bairro, cidade, estado, cep, pessoa, nome_sec, telefone_sec, endereco_sec, grupo, comprovante_endereco, comprovante_rg, telefone2, foto, status_cliente) {
    $('#mensagem').text('');
    $('#titulo_inserir').text('EDITAR REGISTRO');

    $('#id').val(id);
      $('#nome').val(nome);
      $('#email').val(email);
      $('#telefone').val(telefone);
      $('#endereco').val(decodeURIComponent(endereco));
      $('#cpf').val(cpf);
      $('#data_nasc').val(data_nasc);
      $('#obs').val(obs);
      $('#pix').val(pix);
      $('#indicacao').val(indicacao);
      $('#bairro').val(bairro);
      $('#cidade').val(cidade);
      $('#estado').val(estado).change();
      $('#pessoa').val(pessoa).change();
      $('#cep').val(cep);

      $('#nome_sec').val(nome_sec);
      $('#telefone_sec').val(telefone_sec);
      $('#endereco_sec').val(endereco_sec);
      $('#grupo').val(grupo);
      $('#telefone2').val(telefone2);
      $('#status_cliente').val(status_cliente).change();

      $('#target-comprovante-endereco').attr('src','../../painel/images/comprovantes/'+comprovante_endereco);
      $('#target-comprovante-rg').attr('src','../../painel/images/comprovantes/'+comprovante_rg);
      $('#target').attr('src','../../painel/images/clientes/'+foto);

    $('#btn_novo_editar').click();
  }
</script>



<script type="text/javascript">
  function mostrar(id, nome, telefone, cpf, email, endereco, data_nasc, data_cad, obs, pix, indicacao, bairro, cidade, estado, cep, total_emprestimos, total_cobrancas, pessoa, total_contas, nome_sec, telefone_sec, endereco_sec, grupo, dados_emprestimo, comprovante_endereco, comprovante_rg, tumb_comprovante_endereco, tumb_comprovante_rg, telefone2, foto) {
    const botao = document.getElementById('btn_mostrar');
    
    $('#dados_emprestimos_dados2').text(decodeURIComponent(dados_emprestimo));

    if(obs.trim() == ""){     
    $('#div_obs_dados').hide();
    }

    if(dados_emprestimo.trim() == ""){      
    $('#div_dados_emprestimos_dados').hide();
    }else{
      $('#div_dados_emprestimos_dados').show();
    }

    if(comprovante_endereco.trim() == "" || comprovante_endereco.trim() == "sem-foto.png"){     
    $('#div_link_comprovante_endereco').hide();
    }else{
      $('#div_link_comprovante_endereco').show();
    }

    if(comprovante_rg.trim() == "" || comprovante_rg.trim() == "sem-foto.png"){     
    $('#div_link_comprovante_rg').hide();
    }else{
      $('#div_link_comprovante_rg').show();
    }

    if(foto.trim() == "" || foto.trim() == "sem-foto.jpg"){     
      $('#div_foto').hide();
    }else{
      $('#div_foto').show();
    }

    if(total_emprestimos > 0){
      $('#dados_emp').show();
    }else{
      $('#dados_emp').hide();
    }

    if(total_cobrancas > 0){
      $('#dados_cob').show();
    }else{
      $('#dados_cob').hide();
    }

    if(total_contas > 0){
      $('#dados_deb').show();
    }else{
      $('#dados_deb').hide();
    }
          
      $('#titulo_dados').text(nome);
      $('#nome_dados').text(nome);
      $('#email_dados').text(email);
      $('#telefone_dados').text(telefone);
      $('#endereco_dados').text(decodeURIComponent(endereco));
      $('#cpf_dados').text(cpf);
      $('#obs_dados').text(obs);
      $('#data_nasc_dados').text(data_nasc);
      $('#data_cad_dados').text(data_cad);
      $('#pix_dados').text(pix);
      $('#indicacao_dados').text(indicacao);
      $('#bairro_dados').text(bairro);
      $('#cidade_dados').text(cidade);
      $('#estado_dados').text(estado);
      $('#cep_dados').text(cep);
      $('#pessoa_dados').text(pessoa);

      $('#nome_sec_dados').text(nome_sec);
      $('#telefone_sec_dados').text(telefone_sec);
      $('#endereco_sec_dados').text(endereco_sec);
      $('#grupo_dados').text(grupo);

      $('#telefone2_dados').text(telefone2);

      $('#cliente_baixar').val('');
      $('#status_cliente').val('').change();

      $('#id_cliente_mostrar').val(id);
          
    

      $('#link_comprovante_endereco').attr('href','../../painel/images/comprovantes/' + comprovante_endereco);
    $('#target_mostrar_comprovante_endereco').attr('src','../../painel/images/comprovantes/' + tumb_comprovante_endereco);

    $('#link_comprovante_rg').attr('href','../../painel/images/comprovantes/' + comprovante_rg);
    $('#target_mostrar_comprovante_rg').attr('src','../../painel/images/comprovantes/' + tumb_comprovante_rg);

    $('#target_mostrar_foto').attr('src','../../painel/images/clientes/' + foto);

    botao.click();
  }


  function limparCampos() {
    $('#id').val('');
    $('#nome').val('');
    $('#email').val('');
    $('#telefone').val('');
    $('#endereco').val('');
    $('#cpf').val('');
    $('#numero').val('');
    $('#bairro').val('');
    $('#cidade').val('');
    $('#estado').val('').change();
    $('#cep').val('');
    $('#cnpj').val('');
    $('#valor').val('0,00');
    $('#dominio').val('');
    $('#servidor').val('');
    $('#banco').val('');
    $('#usuario').val('');
    $('#senha').val('');
    $('#empresa').val('');
    $('#indicacao').val('');
    $('#complemento').val('');

    $('#frequencia').val('30').change();

    $('#data_emp').val('<?=$data_atual?>');
      $('#data_cob').val('<?=$data_atual?>');

    $('#valor').val('');
      $('#parcelas').val('1');
      $('#juros').val('<?=$juros_sistema?>');
      $('#multa').val('<?=$multa_sistema?>');
      $('#juros_emp').val('<?=$juros_emprestimo?>');
      $('#id_emp').val('');
      $('#frequencia').val('30').change();  

    $('#enviar_whatsapp').prop('checked', false);

  }


  function paginar(pag, busca) {
    $('#input_pagina').val(pag);
    $('#input_buscar').val(busca);
    $('#paginacao').click();
  }
</script>


<script>
  function status(id, nome, status) {
    const botao = document.getElementById('btn_status');
    $('#id_status').val(id);
    $('#status').val(status).change();
    $('#titulo_status').text(nome);
    botao.click();
  }

</script>

<script>
  function mostrarContas(id, nome) {

    listarEmprestimos(id);
    listarCobrancas(id);
    listarDebitos(id);

    const botao = document.getElementById('btn_contas');
    $('#titulo_contas').text(nome);
    $('#id_contas').val(id);
    $('#nome_contas').val(nome);
    botao.click();
    
      
  }

  function abrContas() {
    const botao = document.getElementById('btn_contas');
    botao.click();
  }



  function listarDebitos(id) {

    $.ajax({
      url: 'paginas/' + pag + "/listar_debitos.php",
      method: 'POST',
      data: { id },
      dataType: "html",

      success: function (result) {
        $("#listar_contas").html(result);
      }
    });
  }






  function formatarMoeda(valor) {
    // Converte o valor para um número
    valor = parseFloat(valor);

    // Formata o número com duas casas decimais e separador de milhar
    return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function calcularTaxa() {
    pgto = $('#saida-baixar').val();
    valor = $('#valor-baixar').val();
    $.ajax({
      url: 'paginas/receber/calcular_taxa.php',
      method: 'POST',
      data: { valor, pgto },
      dataType: "html",

      success: function (result) {
        $('#valor-taxa').val(result);
       
      }
    });


  }
</script>




<script>
  function buscarCNPJ() {
    var cnpj = $('#cnpj').val().replace(/\D/g, ''); // Remover tudo que não for número
    if (cnpj.length === 14) { // Verifica se o CNPJ tem 14 dígitos
      $.ajax({
        url: 'https://www.receitaws.com.br/v1/cnpj/' + cnpj,
        type: 'GET',
        dataType: 'jsonp', // A API retorna um JSONP para evitar CORS
        success: function (dados) {
          if (dados.status === "ERROR") {
            alertWarning("CNPJ inválido ou não encontrado!");
          } else {
            $('#nome').val(dados.nome);
            //$('#atividade_principal').html("Atividade Principal: " + dados.atividade_principal[0].text);
            $('#cep').val(dados.cep);
            $('#telefone').val(dados.telefone);
            $('#email').val(dados.email);
            $('#endereco').val(dados.logradouro);
            $('#bairro').val(dados.bairro);
            $('#numero').val(dados.numero);
            $('#cidade').val(dados.municipio);
            $('#complemento').val(dados.complemento);
            $('#estado').val(dados.uf);
            $('#cpf').val(dados.cpf);
          }
        },
        error: function () {
          alertWarning("Erro ao buscar os dados do CNPJ.");
        }
      });
    } else {
      alertCNPJ("Por favor, insira um CNPJ válido com 14 dígitos.");
    }
  }
</script>

<script>
  function limpa_formulário_cep() {
    //Limpa valores do formulário de cep.
    document.getElementById('endereco').value = ("");
    document.getElementById('bairro').value = ("");
    document.getElementById('cidade').value = ("");
    document.getElementById('estado').value = ("");
    //document.getElementById('ibge').value=("");
  }
  function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
      //Atualiza os campos com os valores.
      document.getElementById('endereco').value = (conteudo.logradouro);
      document.getElementById('bairro').value = (conteudo.bairro);
      document.getElementById('cidade').value = (conteudo.localidade);
      document.getElementById('estado').value = (conteudo.uf);
      //document.getElementById('ibge').value=(conteudo.ibge);
    } //end if.
    else {
      //CEP não Encontrado.
      limpa_formulário_cep();
      alertWarning("CEP não encontrado.");
    }
  }
  function pesquisacep(valor) {
    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');
    //Verifica se campo cep possui valor informado.
    if (cep != "") {
      //Expressão regular para validar o CEP.
      var validacep = /^[0-9]{8}$/;
      //Valida o formato do CEP.
      if (validacep.test(cep)) {
        //Preenche os campos com "..." enquanto consulta webservice.
        document.getElementById('endereco').value = "...";
        document.getElementById('bairro').value = "...";
        document.getElementById('cidade').value = "...";
        document.getElementById('estado').value = "...";
        //document.getElementById('ibge').value="...";
        //Cria um elemento javascript.
        var script = document.createElement('script');
        //Sincroniza com o callback.
        script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
        //Insere script no documento e carrega o conteúdo.
        document.body.appendChild(script);
      } //end if.
      else {
        //cep é inválido.
        limpa_formulário_cep();
        alertWarning("Formato de CEP inválido.");
      }
    } //end if.
    else {
      //cep sem valor, limpa formulário.
      limpa_formulário_cep();
    }
  };
</script>



<script>
  // Função para mostrar o placeholder ao focar no campo
  function showPlaceholder(input) {
    input.setAttribute("placeholder", "https://www.dominio.com.br"); // Adiciona o placeholder quando o campo é focado
  }

  // Função para remover o placeholder ao desfocar, caso o campo esteja vazio
  function hidePlaceholder(input) {
    if (input.value === "") {
      input.removeAttribute("placeholder"); // Remove o placeholder quando o campo está vazio
    }
  }
</script>




<script type="text/javascript">var pag = "<?= $pag ?>"</script>




<script type="text/javascript">
  function carregarImgComprovanteEndereco() {
    var target = document.getElementById('target-comprovante-endereco');
    var file = document.querySelector("#comprovante_endereco").files[0];


    var arquivo = file['name'];
    resultado = arquivo.split(".", 2);

    if(resultado[1] === 'pdf'){
      $('#target-comprovante-endereco').attr('src', "images/pdf.png");
      return;
    }

    if(resultado[1] === 'rar' || resultado[1] === 'zip'){
      $('#target-comprovante-endereco').attr('src', "images/rar.png");
      return;
    }



    var reader = new FileReader();

    reader.onloadend = function () {
      target.src = reader.result;
    };

    if (file) {
      reader.readAsDataURL(file);

    } else {
      target.src = "";
    }
  }
</script>




<script type="text/javascript">
  function carregarImgComprovanteRG() {
    var target = document.getElementById('target-comprovante-rg');
    var file = document.querySelector("#comprovante_rg").files[0];


    var arquivo = file['name'];
    resultado = arquivo.split(".", 2);

    if(resultado[1] === 'pdf'){
      $('#target-comprovante-rg').attr('src', "images/pdf.png");
      return;
    }

    if(resultado[1] === 'rar' || resultado[1] === 'zip'){
      $('#target-comprovante-rg').attr('src', "images/rar.png");
      return;
    }



    var reader = new FileReader();

    reader.onloadend = function () {
      target.src = reader.result;
    };

    if (file) {
      reader.readAsDataURL(file);

    } else {
      target.src = "";
    }
  }
</script>



<script type="text/javascript">
  function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("#foto").files[0];


    var arquivo = file['name'];
    resultado = arquivo.split(".", 2);

    var reader = new FileReader();

    reader.onloadend = function () {
      target.src = reader.result;
    };

    if (file) {
      reader.readAsDataURL(file);

    } else {
      target.src = "";
    }
  }
</script>

<script type="text/javascript">
  function mascara_pessoa(){
    var pessoa = $('#pessoa').val();
    if(pessoa == 'Física'){
      $('#cpf').mask('000.000.000-00');
      $('#cpf').attr("placeholder", "Insira CPF");
    }else{
      $('#cpf').mask('00.000.000/0000-00');
      $('#cpf').attr("placeholder", "Insira CNPJ");
    }
  }
</script>


<script>
function atualizarCorStatus() {
    var select = document.getElementById('status_cliente');
    var corSelecionada = select.options[select.selectedIndex].getAttribute('data-cor');
    var preview = document.getElementById('preview_cor_status');

    if(corSelecionada) {
        preview.style.backgroundColor = corSelecionada;
    } else {
        preview.style.backgroundColor = "#ffffff";
    }
}
</script>

<script>
function atualizarCorStatus_busca() {
    
    var select = document.getElementById('status_cliente_busca');
    var corSelecionada = select.options[select.selectedIndex].getAttribute('data-cor');
    $('#cor_status').val(corSelecionada);
    var preview = document.getElementById('preview_cor_status_busca');

    if(corSelecionada) {
        preview.style.backgroundColor = corSelecionada;
    } else {
        preview.style.backgroundColor = "#ffffff";
    }

    $('#btn_filtrar').click();
}
</script>




<script>
  const residuoFinal = document.getElementById('residuo_final');
  const residuo = document.getElementById('residuo');

  residuoFinal.addEventListener('change', function () {
    if (this.checked) {
      residuo.checked = false;
    }
  });

  residuo.addEventListener('change', function () {
    if (this.checked) {
      residuoFinal.checked = false;
    }
  });
</script>




<script type="text/javascript">
  function emprestimo(id, nome){  

  const botao = document.getElementById('btn_emprestimo');

      $('#titulo_emp').text('Empréstimo: '+nome);     
      $('#id_emp').val(id);    
      $('#nome_emprest').val(nome);     
      $('#mensagem_emp').text(''); 
      $('#frequencia').val('30').change();

      mascara_valor('juros')
      mascara_valor('multa')
      
      botao.click();
  }

  function cobranca(id, nome){   
  const botao = document.getElementById('btn_cobranca');       
      $('#titulo_cob').text('Cobrança: '+nome);     
      $('#id_cob').val(id); 
      $('#nome_cob').val(nome);        
      $('#frequencia_cob').val('30').change();
      
       botao.click();
  }



 

  function listarCobrancas(id){ 

     $.ajax({
          url: 'paginas/' + pag + "/listar_cobrancas.php",
          method: 'POST',
          data: {id},
          dataType: "html",

          success:function(result){
              $("#listar_cobrancas").html(result);
             
          }
      });

  }


  function listarEmprestimos(id){     
     $.ajax({
          url: 'paginas/' + pag + "/listar_emprestimos.php",
          method: 'POST',
          data: {id},
          dataType: "html",

          success:function(result){
              $("#listar_emprestimos").html(result);
             
          }
      });

  }

</script>






<script type="text/javascript">
  function calcular(){      
  var valor = $('#valor_baixar').val();
  var multa = $('#multa_baixar').val();
  var juros = $('#juros_baixar').val();
  var forma_pgto = $('#forma_pgto').val();  

   $.ajax({
          url: '../../painel/paginas/' + pag + "/calcular.php",
          method: 'POST',
          data: {valor, multa, juros, forma_pgto},
          dataType: "html",

          success:function(result){
               $('#valor_final').val(result);
          }
      });

  

  }
</script>
