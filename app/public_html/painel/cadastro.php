<?php require_once("../conexao.php"); ?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo $nome_sistema ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="img/icone.png" type="image/x-icon">

	
	<!-- Bootstrap Core CSS -->
	<link href="painel/css/bootstrap.css" rel='stylesheet' type='text/css' />

	<!-- font-awesome icons CSS -->
	<link href="painel/css/font-awesome.css" rel="stylesheet"> 
	<!-- //font-awesome icons CSS-->

	<!-- side nav css file -->
	<link href='painel/css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
	<!-- //side nav css file -->

	<!-- js-->
	<script src="painel/js/jquery-1.11.1.min.js"></script>
	<script src="painel/js/modernizr.custom.js"></script>

	<!--webfonts-->
	<link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
	<!--//webfonts--> 

	<!-- chart -->
	<script src="painel/js/Chart.js"></script>
	<!-- //chart -->

  <!-- sweet alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- sweet alert -->

	</head> 
<body class="cbp-spmenu-push">
	<div class="container" style="margin-top:20px">
		<form id="form">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-5" style="margin-bottom:10px">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>							
						</div>

						<div class="col-md-4" style="margin-bottom:10px">							
								<label>Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" >							
						</div>

            <div class="col-md-3" style="margin-bottom:10px">							
								<label>Telefone</label>
								<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone" required>							
						</div>
					</div>


					<div class="row">
						<div class="col-md-3 " style="margin-bottom:10px">							
								<label>CPF</label>
								<input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" required>							
						</div>	

            <div class="col-md-3" style="margin-bottom:10px">
                <label>RG</label>
                <input type="text" class="form-control" id="rg" name="rg" placeholder="RG">
            </div>

							<div class="col-md-2 " style="margin-bottom:10px">							
								<label>Data Nascimento</label>
								<input type="text" class="form-control" id="data_nasc" name="data_nasc" placeholder="dd/mm/aaaa">							
						</div>				

							<div class="col-md-2 " style="margin-bottom:10px">							
								<label>Senha</label>
								<input type="password" class="form-control" id="senha" name="senha" required="">							
						</div>


							<div class="col-md-2 " style="margin-bottom:10px">							
								<label>Confirmar Senha</label>
								<input type="password" class="form-control" id="conf_senha" name="conf_senha" placeholder="" required="">							
						</div>

					</div>


					<div class="row">

						<div class="col-md-3 " style="margin-bottom:10px">							
								<label>CEP</label>
								<input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" onblur="pesquisacep(this.value);">							
						</div>

						<div class="col-md-9" style="margin-bottom:10px">							
								<label>Endereço</label>
								<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua e Número" >							
						</div>
					</div>


					<div class="row">
						<div class="col-md-5" style="margin-bottom:10px">							
								<label>Bairro</label>
								<input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro" >							
						</div>

						<div class="col-md-4 col-xs-7" style="margin-bottom:10px">							
								<label>Cidade</label>
								<input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" >							
						</div>

						<div class="col-md-3 col-xs-5" style="margin-bottom:10px">							
								<label>Estado</label>
								<select class="form-control" id="estado" name="estado">
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
						</div>
					</div>

          <div class="row">
            <div class="col-md-8" style="margin-bottom:10px">
              <label>Complemento</label>
              <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Complemento do endereço">
            </div>
            <div class="col-md-2" style="margin-bottom:10px">
              <label>Quadra</label>
              <input type="text" class="form-control" id="quadra" name="quadra" placeholder="Quadra">
            </div>

            <div class="col-md-2" style="margin-bottom:10px">
                <label>Lote</label>
                <input type="text" class="form-control" id="lote" name="lote" placeholder="Lote">
            </div>
					</div>

          <div class="row">
            
					</div>

          <div class="row">
          <div class="col-md-4" style="margin-bottom:10px">
            <label>Contato de Referência</label>
            <input type="text" class="form-control telefone" id="referencia_contato" name="referencia_contato" placeholder="(00) 00000-0000">
          </div>


            <div class="col-md-4" style="margin-bottom:10px">
              <label>Nome completo da referência</label>
              <input type="text" class="form-control" id="referencia_nome" name="referencia_nome" placeholder="Nome completo da referência">
            </div>

            <div class="col-md-4" style="margin-bottom:10px">
              <label>Grau de parentesco</label>
              <input type="text" class="form-control" id="referencia_parentesco" name="referencia_parentesco" placeholder="Grau de parentesco">
            </div>

					</div>

          <div class="row">
            <div class="col-md-4" style="margin-bottom:10px">							
              <label>Chave Pix em sua titularidade</label>
              <input type="text" class="form-control" id="pix" name="pix" placeholder="Chave Pix">							
            </div>

            <div class="col-md-4" style="margin-bottom:10px">							
              <label>Indicação</label>
              <input type="text" class="form-control" id="indicacao" name="indicacao" placeholder="Indicado Por">							
            </div>

            <div class="col-md-4" style="margin-bottom:10px">
              <label>Ramo de Atuação</label>
              <input type="text" class="form-control" id="ramo" name="ramo" placeholder="Ex: Vendas, Construção, Prestador de Serviço...">
            </div>
          </div>

          <!-- Campos de veículo agrupados com id para controle -->
          <div class="row" id="campos_veiculo" style="display: none;">
            <div class="col-md-4" style="margin-bottom:10px">							
              <label>Modelo veículo</label>
              <input type="text" class="form-control" id="modelo_veiculo" name="modelo_veiculo" placeholder="Modelo do veículo">							
            </div>

            <div class="col-md-3" style="margin-bottom:10px">							
              <label>Placa</label>
              <input type="text" class="form-control" id="placa" name="placa" placeholder="Placa do veículo">							
            </div>

            <div class="col-md-3" style="margin-bottom:10px">							
              <label>Status do veículo</label>
              <select class="form-control" id="status_veiculo" name="status_veiculo">
                <option value="">Selecionar</option>
                <option value="AC">Próprio</option>
                <option value="AL">Alugado</option>                  
              </select>								
            </div>

            <div class="col-md-2" style="margin-bottom:10px">							
              <label>Valor do aluguel</label>
              <input type="text" class="form-control" id="valor_aluguel" name="valor_aluguel" placeholder="Valor do aluguel">							
            </div>
          </div>

					
					<div class="row">
						<div class="col-md-4">							
								<label>Comprovante Endereço</label>
								<input type="file" class="form-control" id="comprovante_endereco" name="comprovante_endereco"  onchange="carregarImgComprovanteEndereco()">							
						</div>

						<div class="col-md-2">								
							<img src="painel/images/comprovantes/sem-foto.png"  width="70px" id="target-comprovante-endereco">								
						</div>


						<div class="col-md-4">							
								<label>Comprovante RG / CPF</label>
								<input type="file" class="form-control" id="comprovante_rg" name="comprovante_rg"  onchange="carregarImgComprovanteRG()">							
						</div>

						<div class="col-md-2">								
							<img src="painel/images/comprovantes/sem-foto.png"  width="70px" id="target-comprovante-rg">								
						</div>


					</div>

          <div class="row">
            <div class="col-md-6" style="margin-bottom:10px">
              <label>Valor desejado</label>
              <input type="text" class="form-control currency" id="valor_desejado" name="valor_desejado" placeholder="R$ 0,00">
            </div>

            <div class="col-md-6" style="margin-bottom:10px">
              <label>Valor da parcela desejada</label>
              <input type="text" class="form-control currency" id="valor_parcela_desejada" name="valor_parcela_desejada" placeholder="R$ 0,00">
            </div>
          </div>



					
					<input type="hidden" class="form-control" id="id" name="id">	
					<input type="hidden" class="form-control" id="cliente_cadastro" name="cliente_cadastro" value="Sim">					

				<br>
				<small><div id="mensagem" align="center"></div></small>

				<button type="submit" class="btn btn-primary" style="width:150px">Cadastre-se</button>
			</div>
			   
				
			
			</form>
	</div>

</body>
</html>





<!-- new added graphs chart js-->
	
	<script src="painel/js/Chart.bundle.js"></script>
	<script src="painel/js/utils.js"></script>
	
	
	
	<!-- Classie --><!-- for toggle left push menu script -->
	<script src="painel/js/classie.js"></script>
	
	<!--scrolling js-->
	<script src="painel/js/jquery.nicescroll.js"></script>
	<script src="painel/js/scripts.js"></script>
	<!--//scrolling js-->
	
	
	
	<!-- Bootstrap Core JavaScript -->
	<script src="painel/js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->



	<!-- Mascaras JS -->
<script type="text/javascript" src="painel/js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 





<script>
  function formatarTelefone(valor) {
    valor = valor.replace(/\D/g, ''); // Remove tudo que não for número

    if (valor.length > 11) {
      valor = valor.slice(0, 11); // Limita a 11 dígitos
    }

    // Formato celular com 9 dígitos
    if (valor.length > 10) {
      return valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    }
    // Formato fixo com 8 dígitos
    if (valor.length > 6) {
      return valor.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
    }
    if (valor.length > 2) {
      return valor.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
    }
    if (valor.length > 0) {
      return valor.replace(/^(\d{0,2})$/, '($1');
    }

    return '';
  }

  const telefoneInput = document.getElementById('referencia_contato');

  telefoneInput.addEventListener('input', function (e) {
    const input = e.target;
    const valorOriginal = input.value;
    const cursorPosition = input.selectionStart;

    // Remove tudo que não for número
    const numeros = valorOriginal.replace(/\D/g, '');
    const valorFormatado = formatarTelefone(numeros);

    input.value = valorFormatado;

    // Ajusta o cursor (de forma simplificada, sem mover de volta)
    const diff = valorFormatado.length - valorOriginal.length;
    input.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
  });

  function verificaRamoAtuacao() {
      // Pega o valor do campo "ramo", remove espaços e converte para minúsculo
      const ramo = document.getElementById('ramo').value.trim().toLowerCase();

      // Mostra ou esconde os campos do veículo com base no valor do ramo
      if (ramo === 'uber') {
        $('#campos_veiculo').slideDown();
      } else {
        $('#campos_veiculo').slideUp();
      }
    }

    // Associa a função ao evento de input no campo "ramo"
    $(document).ready(function() {
      $('#ramo').on('input', verificaRamoAtuacao);
    });
    
    function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('endereco').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('estado').value=("");
            //document.getElementById('ibge').value=("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('endereco').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('estado').value=(conteudo.uf);
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
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('endereco').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('estado').value="...";
                //document.getElementById('ibge').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

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








    $("#form").submit(function (event) {

event.preventDefault();
var formData = new FormData(this);

// Exibe alerta de carregamento
Swal.fire({
    title: 'Salvando...',
    text: 'Aguarde um instante.',
    icon: 'info',
    showConfirmButton: false,
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
        Swal.showLoading()
    }
});

$.ajax({
    url: 'painel/paginas/clientes/salvar.php',
    type: 'POST',
    data: formData,

    success: function (mensagem) {
        $('#mensagem').text('');
        $('#mensagem').removeClass();

        if (mensagem.trim() == "Salvo com Sucesso") {
            // Mostra alerta de sucesso com SweetAlert
            Swal.fire({
                title: 'Sucesso!',
                text: 'Cadastrado com sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = "acesso";
                }
            });
        } else {
            // Mostra erro com SweetAlert
            Swal.fire({
                title: 'Erro ao cadastrar!',
                text: mensagem,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    },

    cache: false,
    contentType: false,
    processData: false,
});
});


    </script>

<script type="text/javascript">
	function carregarImgComprovanteEndereco() {
		var target = document.getElementById('target-comprovante-endereco');
		var file = document.querySelector("#comprovante_endereco").files[0];


		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if(resultado[1] === 'pdf'){
			$('#target-comprovante-endereco').attr('src', "painel/images/pdf.png");
			return;
		}

		if(resultado[1] === 'rar' || resultado[1] === 'zip'){
			$('#target-comprovante-endereco').attr('src', "painel/images/rar.png");
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
			$('#target-comprovante-rg').attr('src', "painel/images/pdf.png");
			return;
		}

		if(resultado[1] === 'rar' || resultado[1] === 'zip'){
			$('#target-comprovante-rg').attr('src', "painel/images/rar.png");
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
