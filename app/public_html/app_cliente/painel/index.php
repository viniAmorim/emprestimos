<?php
@session_start();
require_once("../../conexao.php");
$pag_inicial = 'home';
$data_page = 'index';

$id_usuario = @$_SESSION['id'];

$query = $pdo->query("SELECT * from receber where pago = 'Não' and data_venc = curDate()");
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  $total_vencendo_msg = @count($res);

    $query = $pdo->query("SELECT * from receber where pago = 'Não' and data_venc < curDate()");
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  $total_vencidas_msg = @count($res);

if (@$_SESSION['aut_token_portalapp'] != 'portalapp2024') {
  echo "<script>localStorage.setItem('id_usu', '')</script>";
  unset($_SESSION['id'], $_SESSION['nome'], $_SESSION['nivel']);
  $_SESSION['msg'] = "";
  echo '<script>window.location="../"</script>';
  exit();
}



$buscar = @$_POST['buscar'];

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$pago = @$_POST['pago'];

$data_hoje = date('Y-m-d');
$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01";
$data_inicio_ano = $ano_atual . "-01-01";
$data_mes = $ano_atual."-".$mes_atual."-01";

$data_ontem = date('Y-m-d', strtotime("-1 days", strtotime($data_atual)));
$data_amanha = date('Y-m-d', strtotime("+1 days", strtotime($data_atual)));


if ($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '09' || $mes_atual == '11') {
  $data_final_mes = $ano_atual . '-' . $mes_atual . '-30';
} else if ($mes_atual == '02') {
  $bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));
  if ($bissexto == 1) {
    $data_final_mes = $ano_atual . '-' . $mes_atual . '-29';
  } else {
    $data_final_mes = $ano_atual . '-' . $mes_atual . '-28';
  }

} else {
  $data_final_mes = $ano_atual . '-' . $mes_atual . '-31';
}


if (@$_GET['pagina'] != "") {
  $pagina = @$_GET['pagina'];


} else {
  $pagina = $pag_inicial;
}



$query = $pdo->query("SELECT * from clientes where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
  $nome_usuario = $res[0]['nome'];
  $telefone_usuario = $res[0]['telefone'];
  $email_usuario = $res[0]['email'];
  $cpf_usuario = $res[0]['cpf'];  
  $endereco_usuario = $res[0]['endereco'];
  $data_nasc_usuario = $res[0]['data_nasc'];    
  $pix_usuario = $res[0]['pix'];
  $indicacao_usuario = $res[0]['indicacao'];
  $bairro_usuario = $res[0]['bairro'];
  $cidade_usuario = $res[0]['cidade'];
  $estado_usuario = $res[0]['estado'];
  $cep_usuario = $res[0]['cep'];
  $foto_usuario = $res[0]['foto'];



}

require_once("cabecalho.php");
require_once("rodape.php");
require_once("alertas.php");

echo "<script>localStorage.setItem('pagina', '$pagina')</script>";
require_once("paginas/" . $pagina . ".php");

?>





<!-- MODAL PERFIL-->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:98%" id="menu-perfil">
  <div class="content mb-0">
    <div class="d-flex pb-2">
      <div class="align-self-center text-uppercase">
        <span style="margin-left: 150px !important" class="font-14 color-highlight font-700 ">EDITAR PERFIL</span>
      </div>
      <div class="align-self-center ms-auto">
        <button style="border: none; background: transparent; margin-right: 12px" data-bs-dismiss="offcanvas"
          id="btn-fechar-perfil" aria-label="Close" data-bs-dismiss="modal" type="button"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i>
        </button>
      </div>
    </div><br>
    <form id="form-perfil" method="post">

    <div class="form-floating mb-3 position-relative">
        <i class="bi bi-person-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="nome" name="nome" placeholder="" required value="<?php echo $nome_usuario ?>">
        <label class="color-theme ps-5">Seu Nome</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>

      <div class="form-floating mb-3 position-relative">
        <i class="fa-solid fa-at position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="email" class="form-control rounded-xs ps-5" id="email" name="email" placeholder="" value="<?php echo $email_usuario ?>" >
        <label class="color-theme ps-5">Seu E-mail</label>
       
      </div>

<div class="form-floating mb-3 position-relative">
        <i class="fa-solid fa-phone position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="telefone" name="telefone" placeholder=""
          onkeyup="verificarTelefone('telefone_perfil', this.value)" required value="<?php echo $telefone_usuario ?>">
  <label class="color-theme ps-5">Telefone</label>
  <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
    style="font-size: 9px;">(Obrigatório)</small>
</div>

<!-- CPF / CNPJ -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-file-earmark-person-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input value="<?php echo $cpf_usuario ?>" type="text" class="form-control rounded-xs ps-5" id="cpf_perfil" name="cpf" placeholder="" required>
  <label class="color-theme ps-5">CPF / CNPJ</label>
</div>


<!-- Nascimento -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-calendar-event-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input value="<?php echo $data_nasc_usuario ?>" type="text" class="form-control rounded-xs ps-5" id="data_nasc_usuario" name="data_nasc" placeholder="dd/mm/aaaa">
  <label class="color-theme ps-5">Nascimento</label>
</div>



      <div class="form-floating mb-3 position-relative">
        <i class="bi bi-person-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="password" class="form-control rounded-xs ps-5" id="senha" name="senha" placeholder="" required value="<?php echo @$senha_usuario ?>">
        <label class="color-theme ps-5">Senha</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>

       <div class="form-floating mb-3 position-relative">
        <i class="bi bi-person-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="password" class="form-control rounded-xs ps-5" id="conf_senha" name="conf_senha" placeholder="" required>
            <label class="color-theme ps-5">Confirmar Senha</label>
            <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
    style="font-size: 9px;">(Obrigatório)</small>
          </div>

    
     <!-- CEP -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-geo-alt-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input value="<?php echo $cep_usuario ?>" type="text" class="form-control rounded-xs ps-5" id="cep_perfil" name="cep" placeholder="" onblur="pesquisacep(this.value);">
  <label class="color-theme ps-5">CEP</label>
</div>

<!-- Endereço -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-house-door-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input value="<?php echo $endereco_usuario ?>" type="text" class="form-control rounded-xs ps-5" id="endereco" name="endereco" placeholder="">
  <label class="color-theme ps-5">Endereço</label>
</div>

<!-- Bairro -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-signpost-2-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input value="<?php echo $bairro_usuario ?>" type="text" class="form-control rounded-xs ps-5" id="bairro" name="bairro" placeholder="">
  <label class="color-theme ps-5">Bairro</label>
</div>

<!-- Cidade -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-building position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input value="<?php echo $cidade_usuario ?>" type="text" class="form-control rounded-xs ps-5" id="cidade" name="cidade" placeholder="">
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


<!-- Chave Pix -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-person-plus-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input value="<?php echo $pix_usuario ?>" type="text" class="form-control rounded-xs ps-5" id="pix_usuario" name="pix" placeholder="">
  <label class="color-theme ps-5">Chave Pix</label>
</div>

<!-- Indicação -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-person-plus-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input value="<?php echo $indicacao_usuario ?>" type="text" class="form-control rounded-xs ps-5" id="indicacao" name="indicacao" placeholder="">
  <label class="color-theme ps-5">Indicação</label>
</div>
     



     

      <div class="form_row" align="center" onclick="foto_perfil.click()">
        <img src="../../painel/images/clientes/<?php echo $foto_usuario ?>" width="100px" id="target_perfil"><br>
        <img src="../images/icone-arquivo.png" width="100px" style="margin-top: -12px">
      </div>
      <input onChange="carregarImgPerfil();" type="file" name="foto" id="foto_perfil" style="display:none">
      <button name="btn_salvar" id="btn_salvar_perfil"
        class="btn btn-full  gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
        type="submit">SALVAR</button>
      <input type="hidden" name="id_usuario" value="<?php echo $id_usuario ?>">
    </form>
  </div>
</div>







<!--FIM DA PAGINA-->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/swiper.min.js"></script>
<script src="js/ajax.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>



<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

<script type="text/javascript">
  $(document).ready(function () {
    $('#estado').val("<?=$estado_usuario?>");

    $('.sel_nulo').select2({
    });
    $('.sel2').select2({
      dropdownParent: $('#popupForm')
    });
    $('.sel3').select2({
      dropdownParent: $('#popupForm')
    });
    $('.sel4').select2({
      dropdownParent: $('#popupForm')
    });
    $('.sel5').select2({
      dropdownParent: $('#popupForm')
    });
    $('.sel11').select2({
      dropdownParent: $('#popupForm')
    });
  });
</script>

<script>
  function navigateToPage(event, page) {
    event.preventDefault(); // Impede o comportamento padrão do link
    window.location.href = page; // Redireciona para a página especificada

    // Após um pequeno atraso, redireciona para a página correspondente
    setTimeout(function () {
      location.reload(); // Atualiza a página atual
    }, 100); // 100 milissegundos de atraso
  }
</script>


<style type="text/css">
  .select2-selection__rendered {
    line-height: 40px !important;
    font-size: 12px !important;
    color: #666666 !important;
    margin-top: 3px !important;
  }

  .select2-selection {
    height: 50px !important;
    font-size: 10px !important;
    color: #666666 !important;
    border-radius: 10px !important;
    border: 2px solid #e8e8e8 !important;
  }
</style>


<script>
  $("#form-perfil").submit(function () {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      url: "../../painel_cliente/editar-perfil.php",
      type: 'POST',
      data: formData,
      success: function (mensagem) {
        //ARMAZENAR O RETORNO PARA A MSG DE SUCESSO
			$('#toast-message').text(mensagem.trim());
        if (mensagem.trim() == "Editado com Sucesso") {
          //toast(mensagem, 'verde')
          $('#not_salvar').click();
          $('#btn-fechar-perfil').click();
          //location.reload();
          setTimeout(function () {
            location.reload();
          }, 1000); // 2000 milissegundos = 2 segundos
          
        } else {
          //$('#msg-perfil').addClass('text-danger')
          toast(mensagem, 'vermelha')
        }
      },
      cache: false,
      contentType: false,
      processData: false,
    });
  });


</script>



<script type="text/javascript">
  function carregarImgPerfil() {
    var target = document.getElementById('target_perfil');
    var file = document.querySelector("#foto_perfil").files[0];

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


