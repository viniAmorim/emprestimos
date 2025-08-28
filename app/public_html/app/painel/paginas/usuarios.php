<?php
require_once("cabecalho.php");
require_once("rodape.php");
@session_start();
$id_usuario = @$_SESSION['id'];



$pag = 'usuarios';
$itens_pag = 10;


if (@$usuarios == 'ocultar') {
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


//totalizar páginas
$query2 = $pdo->query("SELECT * from $pag where (nome like '%$buscar%' or telefone like '%$buscar%' or email like '%$buscar%') order by id desc");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$linhas2 = @count($res2);

$num_paginas = ceil($linhas2 / $itens_pag);
if ($pag_proxima == $num_paginas) {
  $pag_inativa_prox = 'desabilitar_botao';
  $pag_proxima = $pagina;
} else {
  $pag_inativa_prox = '';
}


$query = $pdo->query("SELECT * from $pag where id = $id_usuario");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nivel = $res[0]['nivel'];

if ($nivel == 'administrador') {
  $mostrar_adm = 'oculta';
} else {
  $mostrar_adm = 'oculta';
}


?>



<div class="page-content header-clear-medium">


  <!-- BARRA DE PESQUISA-->
  <div class="content m-2 p-1">
    <div class="loginform">
      <form method="post">
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
      $query = $pdo->query("SELECT * from $pag where (nome like '%$buscar%' or telefone like '%$buscar%' or email like '%$buscar%') order by id desc LIMIT $limite, $itens_pag");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $linhas = @count($res);
      if ($linhas > 0) {
        for ($i = 0; $i < $linhas; $i++) {
          $id = $res[$i]['id'];
          $nome = $res[$i]['nome'];
          $telefone = $res[$i]['telefone'];
          $email = $res[$i]['email'];
          $senha = $res[$i]['senha'];
          $foto = $res[$i]['foto'];
          $nivel = $res[$i]['nivel'];
          $endereco = $res[$i]['endereco'];
          $ativo = $res[$i]['ativo'];
          $data = $res[$i]['data'];
                  

          $dataF = implode('/', array_reverse(@explode('-', $data)));
         

          if ($ativo == 'Sim') {
            $icone = 'bi bi-check-square-fill';
            $titulo_link = 'Desativar Usuário';
            $acao = 'Não';
            $classe_ativo = '';
            $foto_ativo = '';
          } else {
            $icone = 'fa-solid fa-square';
            $titulo_link = 'Ativar Usuário';
            $acao = 'Sim';
            $classe_ativo = '#c4c4c4';
            $foto_ativo = '.5';
          }

          $mostrar_adm = '';
          if ($nivel == 'Administrador') {
            $senha = '******';
            $mostrar_adm = 'ocultar';
          }

        



          echo <<<HTML


    <div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows slider-no-dots {$mostrar_adm}" id="user-slider-{$id}">
        <div class="splide__track">
          <div class="splide__list">
            <div class="splide__slide mx-3">
              <div class="d-flex">

              <div ><img src="../../painel/images/perfil/{$foto}" class="me-3 rounded-circle shadow-l" width="40" style="opacity: {$foto_ativo}"></div>
    
                <div onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}','{$ativo}','{$dataF}', '{$senha}', '{$nivel}', '{$foto}')">
                  <h5 style="color:{$classe_ativo}" class="mt-1 mb-0">{$nome}</h5>
                  <p class="font-10 mt-n2 color-blue-dark mb-0">{$telefone}</p>
                  <p class="font-10 mt-0 badge text-uppercase gradient-green  shadow-bg shadow-bg-s">{$nivel}</p>
                </div>
               <div class="ms-auto"><span class="badge bg-blue-dark mt-0 p-1 font-8 shadow-bg-s"><i class="fa fa-arrow-right"></i></span></div>
              </div>
            </div>
            <div class="splide__slide mx-3">
              <div class="d-flex">

              <div style="color:{$classe_ativo}" onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}','{$ativo}','{$dataF}', '{$senha}', '{$nivel}', '{$foto}')">
                    </div>
                <div class="ms-auto">
                  <a onclick="editar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}','{$nivel}','{$foto}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-twitter"><i class="fa fa-edit text-white"></i></a>
                  <a onclick="ativar('{$id}', '{$acao}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-phone"><big><i class="{$icone} text-white"></i></big></a>
                  
                  <a onclick="permissoes('{$id}', '{$nome}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-amarelo"><big><i class="bi bi-lock text-white"></i></big></a>
                  
                  <a onclick="excluir_reg('{$id}', '{$nome}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-google"><i class="bi bi-trash-fill text-white"></i></a>

                  <a href="https://api.whatsapp.com/send?phone=55{$telefone}&text=Olá {$nome}, tudo bem?" target="_blank" class="icon icon-xs rounded-circle shadow-l bg-whatsapp"><i class="bi bi-whatsapp text-white"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



      <div class="divider mt-3 mb-3 {$mostrar_adm}"></div>
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


<div class="fab" style="z-index: 100 !important; margin-bottom: 60px">
  <button id="btn_novo" class="main open-popup bg-highlight" data-bs-toggle="offcanvas" data-bs-target="#popupForm">
    +
  </button>
</div>



<div hidden="hidden" class="fab" style="z-index: 100 !important; margin-bottom: 60px">
  <button id="btn_novo_editar" class="main open-popup bg-highlight" data-bs-toggle="offcanvas"
    data-bs-target="#popupForm">

  </button>
</div>



<!-- MODAL USUÁRIOS -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:96%" id="popupForm">
  <div class="content mb-0">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h2 align="center" class="mb-n1 font-12 color-highlight font-700 text-uppercase pt-1" id="titulo_inserir">
          INSERIR DADOS</h2>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="limparCampos()" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <form id="form" method="post" class="demo-animation m-0">

     <div class="form-floating mb-3 position-relative">
        <i class="bi bi-person-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="nome" name="nome" placeholder="" required>
        <label for="nome" class="color-theme ps-5">Nome</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>

      <div class="form-floating mb-3 position-relative">
        <i class="fa-solid fa-at position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="email" class="form-control rounded-xs ps-5" id="email" name="email" placeholder="" required>
        <label for="email" class="color-theme ps-5">E-mail</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>

            <div class="form-floating mb-3 position-relative">
        <i class="fa-solid fa-phone position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="telefone" name="telefone" placeholder=""
          onkeyup="verificarTelefone('telefone', this.value)" required>
        <label class="color-theme ps-5">Telefone</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>

          


      <div class="form-floating mb-3 position-relative">
            <i class="bi bi-check-circle position-absolute start-0 top-50 translate-middle-y ms-3"></i>
            <select name="nivel" id="nivel" class="form-select rounded-xs ps-5 pe-5" aria-label="Selecione">
               <option>Administrador</option>
                  <option>Comum</option>
        </select>
        <label class="color-theme ps-5">Nível</label>
      </div>







      <div class="row">
        <div class="col-12">
          <div class="form-floating mb-3 position-relative">
            <i class="bi bi-geo-alt position-absolute start-0 top-50 translate-middle-y ms-3"></i>
            <input type="text" class="form-control rounded-xs ps-5" id="endereco" name="endereco" placeholder="Rua A numero x Bairro X">
            <label class="color-theme ps-5">Endereço</label>
          </div>
        </div>
       
       
      </div>
     

      <button name="btn_salvar" id="btn_salvar_usuarios"
        class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
        type="submit">Salvar</button>
      <div align="center" style="display:none" id="img_loader_usuarios"><img src="images/loader.gif"></div>
      <input type="hidden" name="id" id="id">
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
                <td style="font-size: 11px;" class="color-highlight">Telefone:</td>
                <td style="font-size: 11px;" id="telefone_dados"></td>
              </tr>
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">E-mail:</td>
                <td style="font-size: 11px;" id="email_dados"></td>
              </tr>
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Ativo:</td>
                <td style="font-size: 11px;" id="ativo_dados"></td>
              </tr>
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Cadastrado:</td>
                <td style="font-size: 11px;" id="data_dados"></td>
              </tr>
             
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Endereço:</td>
                <td style="font-size: 11px;" id="endereco_dados"></td>
              </tr>
             
            </tbody>
          </table>
          <div class="splide__slide">
            <div align="center">
              <img src="../images/sem-foto-perfil.jpg" class="me-3 rounded-circle" width="100px" id="foto_dados">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!--BOTÃO PARA CHAMAR A MODAL PERMISSÕES -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-acessos" class="list-group-item"
  id="btn_acessos">
</a>

<!-- MODAL PERMISSÕES -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:98%" id="menu-share-acessos">
  <div class="content ">
    <div class="d-flex pb-2">

      <div class="align-self-center">
        <h1 class="font-14 color-highlight font-700 text-uppercase" id="nome_permissoes">DEFINIR PERMISSÕES</h1>
      </div>

      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>

    <div class="card overflow-visible">
      <div class="content mb-1">
        <div class="table-responsive">
          <span class="page_title" id=""></span>
          <span class="page_title" style="float:right">Marcar Todos <input class="form-check-input" type="checkbox"
              id="input-todos" onchange="marcarTodos()"></span>
        </div>
        <div id="listar_permissoes" style="margin-top: 15px"></div>
        <input type="hidden" name="id" id="id_permissoes">
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
        <h1 class="font-12 color-highlight font-700 text-uppercase" id="titulo_arquivo"></h1>
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
                  <input type="text" class="form-control rounded-xs ps-5" id="nome-arq" name="nome-arq" placeholder="" required>
                  <label class="color-theme ps-5">Nome do Arquvio</label>
                </div>
              <button name="btn_salvar_arquivo" id="btn_salvar_arquivo"
                class="btn btn-full gradient-green rounded-xs text-uppercase w-100 btn-s mt-2"
                type="submit">Salva</button>
              <input type="hidden" name="id-arquivo" id="id-arquivo">
            </form>
            <div id="listar-arquivos"></div>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>




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
  function editar(id, nome, email, telefone, endereco, nivel, foto) {
    $('#mensagem').text('');
    $('#titulo_inserir').text('EDITAR REGISTRO');


    $('#id').val(id);
    $('#nome').val(nome);
    $('#email').val(email);
    $('#telefone').val(telefone);
    $('#endereco').val(endereco);
    $('#nivel').val(nivel).change();
   
    $('#target').attr("src", "../../painel/images/perfil/" + foto);
    $('#btn_novo_editar').click();
  }



</script>


<script type="text/javascript">
  function mostrar(nome, email, telefone, endereco, ativo, data, senha, nivel, foto) {
    const botao = document.getElementById('btn_mostrar');
  

    $('#titulo_dados').text(nome);
    $('#email_dados').text(email);
    $('#telefone_dados').text(telefone);
    $('#ativo_dados').text(ativo);
    $('#data_dados').text(data);
    $('#endereco_dados').text(endereco);
   
    $('#nivel_dados').text(nivel);
    $('#foto_dados').attr("src", "../../painel/images/perfil/" + foto);
    botao.click();
  }


  function limparCampos() {
    $('#id').val('');
    $('#nome').val('');
    $('#email').val('');
    $('#telefone').val('');
    $('#endereco').val('');   
    $('#nivel').val('').change();
   
  }


  function paginar(pag, busca) {
    $('#input_pagina').val(pag);
    $('#input_buscar').val(busca);
    $('#paginacao').click();
  }
</script>





<script type="text/javascript">
  function permissoes(id, nome) {
    const botao = document.getElementById('btn_acessos');
    $('#id_permissoes').val(id);
    $('#nome_permissoes').text(nome);
    botao.click();
    listarPermissoes(id);
  }


  function listarPermissoes(id) {
    $.ajax({
      url: 'paginas/' + pag + "/listar_permissoes.php",
      method: 'POST',
      data: { id },
      dataType: "html",
      success: function (result) {
        $("#listar_permissoes").html(result);
        $('#mensagem_permissao').text('');
      }
    });
  }

  function adicionarPermissao(id, usuario) {
    $.ajax({
      url: '../../painel/paginas/' + pag + "/add_permissao.php",
      method: 'POST',
      data: { id, usuario },
      dataType: "html",
      success: function (result) {
        listarPermissoes(usuario);
      }
    });
  }


  function marcarTodos() {
    let checkbox = document.getElementById('input-todos');
    var usuario = $('#id_permissoes').val();
    if (checkbox.checked) {
      adicionarPermissoes(usuario);
    } else {
      limparPermissoes(usuario);
    }
  }


  function adicionarPermissoes(id_usuario) {
    $.ajax({
      url: '../../painel/paginas/' + pag + "/add_permissoes.php",
      method: 'POST',
      data: { id_usuario },
      dataType: "html",
      success: function (result) {
        listarPermissoes(id_usuario);
      }
    });
  }


  function limparPermissoes(id_usuario) {
    $.ajax({
      url: '../../painel/paginas/' + pag + "/limpar_permissoes.php",
      method: 'POST',
      data: { id_usuario },
      dataType: "html",
      success: function (result) {
        listarPermissoes(id_usuario);
      }
    });
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
      alert("CEP não encontrado.");
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
        alert("Formato de CEP inválido.");
      }
    } //end if.
    else {
      //cep sem valor, limpa formulário.
      limpa_formulário_cep();
    }
  };
</script>


<script type="text/javascript">
  var pag = "<?= $pag ?>"
</script>