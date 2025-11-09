<?php 
$pag = 'clientes';

if(@$clientes == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
    exit();
}

if($verificar_pagamentos != 'Não'){
	//verificar se tem conta paga
	$query = $pdo->query("SELECT * from receber where pago != 'Sim' and ref_pix != ''");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$linhas = @count($res);
	if($linhas > 0){
		for($i=0; $i<$linhas; $i++){
		$ref_pix = $res[$i]['ref_pix'];	
	        require('pagamentos/consultar_pagamento.php');      
		}
	}
}

 ?>

<!DOCTYPE html>
<html>
  <head>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-x3j6s5Fp6zG1y6eT3d3xW8V5g8K9wK7/j7T0fP1j/E6L3/eI0/2y9z1/N8aH3i7N4yO5Q=" crossorigin="anonymous">

    <style>
      .ocultar {
    display: none !important;
}
.alerta-item {
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}
.alerta-item:last-child {
    border-bottom: none;
}
.alerta-tipo {
    font-weight: bold;
}
.alerta-valor {
    color: #555;
    font-style: italic;
}
.alerta-data {
    font-size: 0.8em;
    color: #999;
}


    </style>
  </head>
  <body>
    
<div class="main-page margin-mobile">
<!-- <a onclick="inserir()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Cliente</a> -->



<li class="dropdown head-dpdn2" style="display: inline-block;">		
		<a href="#" data-toggle="dropdown"  class="btn btn-danger dropdown-toggle" id="btn-deletar" style="display:none"><span class="fa fa-trash-o"></span> Deletar</a>

		<ul class="dropdown-menu">
		<li>
		<div class="notification_desc2">
		<p>Excluir Selecionados? <a href="#" onclick="deletarSel()"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
</li>


<li class="dropdown head-dpdn2" style="display: inline-block;">		
		<a href="#" data-toggle="dropdown"  class="btn btn-success dropdown-toggle" id="btn-cobrar" style="display:none"><span class="fa fa-usd"></span> Mensagem</a>

		<ul class="dropdown-menu">
		<li>
		<div class="notification_desc2">
		<p>Mensagem Selecionados? <a href="#" onclick="cobrarSel()"><span class="text-success">Sim</span></a></p>
		</div>
		</li>										
		</ul>


</li>

<li class="" style="display: inline-block;">		
		<input class="form-control" type="text" name="mensagem_whats" id="mensagem_whats"  style="display:none; width:600px !important" value="">
</li>

<li class="" style="display: inline-block;">
	 <div id="preview_cor_status_busca" style="width: 17px; height: 17px; border: 1px solid #ccc; border-radius: 4px;"></div>
</li>
<li class="" style="display: inline-block;">
	<select class="form-control mt-2" name="status_cliente_busca" id="status_cliente_busca" onchange="atualizarCorStatus_busca(); buscar()">
		<option value="" data-cor="">Filtrar Por Status</option>				
		<?php 
		$query = $pdo->query("SELECT * from status_clientes order by id asc");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$linhas = @count($res);
		if($linhas > 0){
			for($i=0; $i<$linhas; $i++){
				?>
				<option value="<?php echo $res[$i]['nome'] ?>" data-cor="<?php echo $res[$i]['cor'] ?>">
					<?php echo $res[$i]['nome'] ?>
				</option>
			<?php } } ?>
		</select>	
	</li>

  <li class="" style="display: inline-block;">
    <select class="form-control mt-2" name="estagio_cliente_busca" id="estagio_cliente_busca" onchange="buscar()">
      <option value="">Filtrar por Estágio</option>
      <option value="Aprovado">Aprovado</option>
      <option value="Reprovado">Reprovado</option>
      <option value="Pendente">Pendente</option>
      <option value="Nao Validado">Não Validado</option>
      <option value="Ativo">Ativo</option>
      <option value="Inativo">Inativo</option>
      <option value="Em Atraso">Em Atraso</option>
      <option value="Negativado">Negativado</option>
      <option value="Em Analise">Em Análise</option>
      <option value="Nao Tem Interesse">Não tem Interesse</option>
      </select>
  </li>

  <li class="" style="display: inline-block;">
    <select class="form-control mt-2" name="alerta_busca" id="alerta_busca" onchange="buscar()">
      <option value="">Filtrar por alertas</option>
      <option value="ComAlerta">Apenas com Alertas</option>
      <option value="SemAlerta">Sem Alertas</option>
      <option value="ApenasIgnorados">Alertas Ignorados</option>
    </select>
  </li>


<button type="button" class="btn btn-success" data-toggle="modal" data-target="#importarXlsModal" style="margin-left: 25px">
  Importar Arquivo XLS
</button>

<button class="btn btn-primary" onclick="exportarClientes()">Exportar Excel</button>

<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>

</div>

<input type="hidden" id="ids">

<!-- Modal Perfil -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">
                    <span id="titulo_inserir"></span>
                </h4>
                <button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 form-group"> <label>Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
                        </div>

                        <div class="col-md-3 form-group"> <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                        </div>

                        <div class="col-md-2 form-group"> <label>RG</label>
                            <input type="text" class="form-control" id="rg" name="rg" placeholder="RG">
                        </div>

                        <div class="col-md-3 form-group"> 
                          <div class="d-flex align-items-center mb-2"> 
                            <label class="mb-0 mr-2">Status Cliente</label> 
                            <div style="display: none;" id="preview_cor_status"></div> 
                          </div>
                            <select class="form-control" name="status_cliente" id="status_cliente" onchange="atualizarCorStatus()"> <option value="" data-cor="">Selecionar Status</option>
                                <?php
                                // PHP code remains the same
                                $query = $pdo->query("SELECT * from status_clientes order by id asc");
                                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                $linhas = @count($res);
                                if ($linhas > 0) {
                                    for ($i = 0; $i < $linhas; $i++) {
                                ?>
                                        <option value="<?php echo $res[$i]['nome'] ?>" data-cor="<?php echo $res[$i]['cor'] ?>">
                                            <?php echo $res[$i]['nome'] ?>
                                        </option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Telefone2</label>
                            <input type="text" class="form-control" id="telefone2" name="telefone2" placeholder="Outro Telefone">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Tipo Pessoa</label>
                            <select class="form-control" name="pessoa" id="pessoa" onchange="mascara_pessoa()">
                                <option value="Física">Física</option>
                                <option value="Jurídica">Jurídica</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>CPF / CNPJ</label>
                            <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Contato de Referência</label>
                            <input type="text" class="form-control" id="referencia_contato" name="referencia_contato" placeholder="Contato de referência">
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Nome completo da referência</label>
                            <input type="text" class="form-control" id="referencia_nome" name="referencia_nome" placeholder="Nome completo da referência">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Grau de parentesco</label>
                            <input type="text" class="form-control" id="referencia_parentesco" name="referencia_parentesco" placeholder="Grau de parentesco">
                        </div>

                        <div class="col-md-2 form-group">
                            <label>Atuação</label>
                            <input type="text" class="form-control" id="ramo" name="ramo" placeholder="Ex: Vendas, Construção...">
                        </div>
                    </div>

                    <div class="row" id="campos_veiculo" style="display: none;">
                        <div class="col-md-4 form-group">
                            <label>Modelo veículo</label>
                            <input type="text" class="form-control" id="modelo_veiculo" name="modelo_veiculo" placeholder="Modelo do veículo">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Placa</label>
                            <input type="text" class="form-control" id="placa" name="placa" placeholder="Placa do veículo">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Status do veículo</label>
                            <select class="form-control" id="status_veiculo" name="status_veiculo">
                                <option value="">Selecionar</option>
                                <option value="AC">Próprio</option>
                                <option value="AL">Alugado</option>
                            </select>
                        </div>

                        <div class="col-md-2 form-group">
                            <label>Valor do aluguel</label>
                            <input type="text" class="form-control" id="valor_aluguel" name="valor_aluguel" placeholder="Valor do aluguel">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Nascimento</label>
                            <input type="text" class="form-control" id="data_nasc" name="data_nasc" placeholder="dd/mm/aaaa">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" onblur="pesquisacep(this.value);">
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Endereço</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua e Número">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 form-group">
                            <label>Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro">
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade">
                        </div>

                        <div class="col-md-3 form-group">
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
                        <div class="col-md-4 form-group">
                            <label>Chave Pix / Conta Bancária</label>
                            <input type="text" class="form-control" id="pix" name="pix" placeholder="Chave Pix">
                        </div>

                        <div class="col-md-5 form-group">
                            <label>Indicação</label>
                            <input type="text" class="form-control" id="indicacao" name="indicacao" placeholder="Indicado Por">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                                <option value="Alerta">Alerta</option>
                                <option value="Atenção">Atenção</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px">
                      <div class="col-6 ">
                      <!-- Status -->
                      <div class="form-floating mb-3 position-relative">
                        <i class="bi bi-shield-fill-check position-absolute start-0 top-50 translate-middle-y ms-3"></i>
                        <select class="form-select rounded-xs ps-5 pe-5" id="api_pgto" name="api_pgto">
                        <option value="">Nenhuma</option>
                            <option value="Mercado Pago">Mercado Pago</option>
                            <option value="Asaas">Asaas</option>
                        </select>
                        <label class="color-theme ps-5">Api de Pagamento</label>
                      </div>

                    </div>

                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label>Nome Secundário</label>
                            <input type="text" class="form-control" id="nome_sec" name="nome_sec" placeholder="Nome Secundário">
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Telefone Secundário</label>
                            <input type="text" class="form-control" id="telefone_sec" name="telefone_sec" placeholder="Telefone Secundário">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label>Endereço Secundário</label>
                            <input type="text" class="form-control" id="endereco_sec" name="endereco_sec" placeholder="Endereço Secundário">
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Grupo (Empresa, outro)</label>
                            <input type="text" class="form-control" id="grupo" name="grupo" placeholder="Local de Identificação do Cliente">
                        </div>
                    </div>

                    <div class="row align-items-end"> <div class="col-md-4 form-group">
                            <label>Comprovante Endereço</label>
                            <input type="file" style="width: 100px;" class="form-control" id="comprovante_endereco" name="comprovante_endereco" onchange="carregarImgComprovanteEndereco()">
                        </div>

                        <div class="col-md-2 mb-2"> <img style="width: 100px;" src="images/comprovantes/sem-foto.png" id="target-comprovante-endereco"> </div>

                        <div class="col-md-4 form-group">
                            <label>Comprovante RG / CPF</label>
                            <input type="file" class="form-control" id="comprovante_rg" name="comprovante_rg" onchange="carregarImgComprovanteRG()">
                        </div>

                        <div class="col-md-2 mb-2"> <img style="width: 100px;" src="images/comprovantes/sem-foto.png" id="target-comprovante-rg"> </div>
                    </div>

                    <div class="row align-items-end"> <div class="col-md-4 form-group">
                            <label>Foto Cliente</label>
                            <input type="file" class="form-control" id="foto" name="foto" onchange="carregarImg()">
                        </div>

                        <div class="col-md-2 mb-2"> <img src="images/clientes/sem-foto.jpg" id="target"> </div>
                    </div>

                    <input type="hidden" class="form-control" id="id" name="id">

                    <div id="mensagem" align="center"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Dados -->
<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="overflow: scroll; height:100%; scrollbar-width: thin;">
	<div class="modal-dialog">
		<div class="modal-content" >
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_dados"></span></h4>
				<button id="btn-fechar-dados" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<small>
				<div class="row" style="margin-top: 0px">

          <div class="col-md-12" id="div_foto" style="text-align: center; margin-bottom: 15px;">
            <img 
              id="target_mostrar_foto"
              src=""
              alt="Foto do Cliente"
              style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 2px solid #ccc;">
          </div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Telefone: </b></span><span id="telefone_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Telefone2: </b></span><span id="telefone2_dados"></span>
					</div>
					
					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Email: </b></span><span id="email_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Pessoa: </b></span><span id="pessoa_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>CPF / CNPJ: </b></span><span id="cpf_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Nascimento: </b></span><span id="data_nasc_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Data Cadastro: </b></span><span id="data_cad_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Chave Pix: </b></span><span id="pix_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Indicado Por: </b></span><span id="indicacao_dados"></span>
					</div>


					<div class="col-md-12" style="margin-bottom: 5px">
						<span><b>Endereço: </b></span><span id="endereco_dados"></span>
					</div>

          <div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Api Pgto: </b></span><span id="api_pgto_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Bairro: </b></span><span id="bairro_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Cidade: </b></span><span id="cidade_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Estado: </b></span><span id="estado_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>CEP: </b></span><span id="cep_dados"></span>
					</div>


					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Nome Secundário: </b></span><span id="nome_sec_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Telefone Secundário: </b></span><span id="telefone_sec_dados"></span>
					</div>


					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Endereço Secundário: </b></span><span id="endereco_sec_dados"></span>
					</div>


					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Grupo / Empresa: </b></span><span id="grupo_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px" id="div_link_comprovante_endereco">	
						<span><b>Comprovante Endereço: </b></span>
						<a id="link_comprovante_endereco" target="_blank" title="Clique para abrir o arquivo!">	
							<img width="75px" id="target_mostrar_comprovante_endereco">
						</a>	
					</div>	


					<div class="col-md-6" style="margin-bottom: 5px" id="div_link_comprovante_rg">
						<span><b>Comprovante RG: </b></span>
						<a id="link_comprovante_rg" target="_blank" title="Clique para abrir o arquivo!">	
							<img width="75px" id="target_mostrar_comprovante_rg">
						</a>	
					</div>	


          <div class="col-md-12" style="margin-top: 10px; text-align: center;" id="div_botao_validar">
          
          </div>

				</div>
			</small>

				<div class="row" style="overflow: scroll; height:200px; scrollbar-width: thin;" id="dados_emp">
					<div style="border-bottom: 1px solid #000"><small>Empréstimos do Cliente</small></div>

					<small><div id="listar_emprestimos">
						
					</div></small>
				</div>


				<div class="row" style="overflow: scroll; height:200px; scrollbar-width: thin;" id="dados_cob">
					<div style="border-bottom: 1px solid #000"><small>Cobranças do Cliente</small></div>

					<small><div id="listar_cobrancas">
						
					</div></small>
				</div>



				<div class="row" style="overflow: scroll; height:200px; scrollbar-width: thin;" id="dados_deb">
					<div style="border-bottom: 1px solid #000"><small>Demais Débitos do Cliente</small></div>

					<small><div id="listar_debitos">
						
					</div></small>
				</div>


				<input type="hidden" id="id_cliente_mostrar">
        
        

			</div>
					
		</div>
	</div>
</div>

<div class="modal fade" id="modalAlertas" tabindex="-1" role="dialog" aria-labelledby="modalAlertasLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAlertasLabel">Alertas de Duplicidade (<span id="nome-cliente-alerta"></span>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="lista-alertas">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>



	<!-- Modal Arquivos -->
	<div class="modal fade" id="modalArquivos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="tituloModal">Gestão de Arquivos - <span id="nome_arquivo"> </span></h4>
					<button id="btn-fechar-arquivos" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="form-arquivos" method="post">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-8">						
								<div class="form-group"> 
									<label>Arquivo</label> 
									<input class="form-control" type="file" name="arquivo_conta" onChange="carregarImgArquivos();" id="arquivo_conta">
								</div>	
							</div>
							<div class="col-md-4" style="margin-top:-10px">	
								<div id="divImgArquivos">
									<img src="images/arquivos/sem-foto.png"  width="60px" id="target-arquivos">									
								</div>					
							</div>




						</div>

						<div class="row" style="margin-top:-40px">
							<div class="col-md-8">
								<input type="text" class="form-control" name="nome_arq"  id="nome_arq" placeholder="Nome do Arquivo * " required>
							</div>

							<div class="col-md-4">										 
								<button type="submit" class="btn btn-primary">Inserir</button>
							</div>
						</div>

						<hr>

						<small><div id="listar_arquivos"></div></small>

						<br>
						<small><div align="center" id="mensagem_arquivo"></div></small>

						<input type="hidden" class="form-control" name="id_arquivo"  id="id_arquivo">


					</div>
				</form>
			</div>
		</div>
</div>

	<!-- Modal Arquivos Conta-->
	<div class="modal fade" id="modalArquivos_conta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 2000">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="tituloModal">Gestão de Arquivos - <span id="nome_arquivo_conta"> </span></h4>
					<button id="btn-fechar-arquivos_conta" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="form-arquivos_conta" method="post">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-8">						
								<div class="form-group"> 
									<label>Arquivo</label> 
									<input class="form-control" type="file" name="arquivo_conta" onChange="carregarImgArquivos_conta();" id="arquivo_conta_conta">
								</div>	
							</div>
							<div class="col-md-4" style="margin-top:-10px">	
								<div id="divImgArquivos">
									<img src="images/arquivos/sem-foto.png"  width="60px" id="target-arquivos_conta">									
								</div>					
							</div>

						</div>

						<div class="row" style="margin-top:-40px">
							<div class="col-md-8">
								<input type="text" class="form-control" name="nome_arq"  id="nome_arq_conta" placeholder="Nome do Arquivo * " required>
							</div>

							<div class="col-md-4">										 
								<button type="submit" class="btn btn-primary">Inserir</button>
							</div>
						</div>

						<hr>

						<small><div id="listar_arquivos_conta"></div></small>

						<br>
						<small><div align="center" id="mensagem_arquivo_conta"></div></small>

						<input type="hidden" class="form-control" name="id_arquivo"  id="id_arquivo_conta">


					</div>
				</form>
			</div>
		</div>
</div>

<!-- Modal Empréstimo -->
<div class="modal fade" id="modalEmprestimo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_emp"></span></h4>
				<button id="btn-fechar-emp" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_emp">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-3">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="Valor" required onkeyup="mascara_valor('valor')">							
						</div>

						<div class="col-md-2">							
								<label>Parcelas</label>
								<input type="number" class="form-control" id="parcelas" name="parcelas" placeholder="Parcelas" required value="1">							
						</div>


						<div class="col-md-3">							
								<label>Júros Final %</label>
								<input type="text" class="form-control" id="juros_emp" name="juros_emp" placeholder="Júros Total %" value="<?php echo $juros_emprestimo ?>" required>							
						</div>

						<div class="col-md-4">							
								<label>Data</label>
								<input type="date" class="form-control" id="data_emp" name="data"  value="<?php echo $data_atual ?>" required>							
						</div>
					</div>

				
					<div class="row">

						<div class="col-md-3">							
								<label>Júros % Dia</label>
								<input type="text" class="form-control" id="juros" name="juros" placeholder="Júros se Houver" value="<?php echo $juros_sistema ?>" onkeyup="mascara_valor('juros')">							
						</div>


						<div class="col-md-3">							
								<label>Multa R$</label>
								<input type="text" class="form-control" id="multa" name="multa" placeholder="Multa se Houver" value="<?php echo $multa_sistema ?>" onkeyup="mascara_valor('multa')">							
						</div>

            <div class="col-md-6">							
								<label>Descrição</label>
								<input type="text" class="form-control" id="descricao_cobranca" name="descricao_cobranca" placeholder="Ex: Aluguel, IPTV, etc" value="" required="">							
						</div>


						<div class="col-md-4">							
								<label>Vencimento</label>
								<input type="date" class="form-control" id="data_venc" name="data_venc" placeholder="Data de Vencimento" value="<?php echo $data_atual ?>">							
						</div>

						
					</div>


					<div class="row">

						<div class="col-md-4">						
								<label>Frequência</label>
								<select class="form-control" name="frequencia" id="frequencia">								
								<?php 
									$query = $pdo->query("SELECT * from frequencias order by id asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$linhas = @count($res);
									if($linhas > 0){
									for($i=0; $i<$linhas; $i++){
								 ?>
								  <option value="<?php echo $res[$i]['dias'] ?>"><?php echo $res[$i]['frequencia'] ?></option>

								<?php } } ?>
									
								</select>	
						</div>

						<div class="col-md-4">							
								<label>Tipo Júros 

									<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-info-circle text-primary "></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-150px;">
		<li>
		<div class="notification_desc2" style="width:450px">
		<p>
		<span><b>Júros Padrão:</b> <br><small>É o júros básico para empréstimo, onde o cliente pegando 1000 reais em 10 parcelas com 10 % de juros vai pagar 110 reais por mês, pagando assim 100 reais a mais no montante final!</small></span><br><br>

		<b>Júros Simples Price JS:</b> <br><small>É o júros simples com aumento do valor da parcela mês a mês, sempre aumentando os 10% (valor dos júros aplicado no empréstimo com base na simulação anterior) a cada mês, começando com a primeira parcela em 110, passando para a segunda em 120 até chegar na ultima de que seria de 200 reais.</small></span><br><br>

		<b>Júros Composto Comum:</b> <br><small>É o júros calculado com base no débito do cliente, todas as parcelas fixas com o mesmo valor, a fórmula usada foi a M = C ( 1+i)t. Ou seja, a fórmula é: montante é igual ao capital, vezes a taxa de juros mais um, elevado ao tempo, ainda seguindo o mesmo exemplo dado nos juros básico onde seria 10% ao mes em 10 meses a parcela seria de 259,37 reais.</small></span><br><br>


		<b>Júros Composto Banco Price JS:</b> <br><small>É o júros aplicado nos empréstimos bancários, todas as parcelas fixas com o mesmo valor, a fórmula usada foi a M = C { taxa / (1 - (1 + taxa) ^ (-t) ) }. Ainda seguindo o mesmo exemplo dado anteriormente onde seria 10% ao mes em 10 meses a parcela seria de 162,75 reais.</small></span><br><br>


		<b>Júros Prefixados:</b> <br><small>É o júros aplicado em cada uma das parcelas do empréstimo, no caso um empréstimo de 1000 reais a 10% de juros e 5 parcelas vai gerar cada uma das parcelas no valor de 300 reais, pois seria os 10% de 1000 reais em cada uma das parcelas.</small></span><br><br>


		<b>Somente Júros:</b> <br><small>Será cobrado somente os júros, não há parcelas definidas, ficando em aberto o valor total emprestado, no caso um empréstimo de 1000 reais a 10% de juros, vai gerar uma parcela mensal no valor de 100 reais, e ela se mantém sendo gerada até que o empréstimo seja quitado.</small></span><br><br>

		<small><b>OBS:</b>Consultar alguém da área juridica que entenda de júros e empréstimos, pois existem encargos e impostos como IOF que são aplicados ao empréstimo e você pode acescenta-los as parcelas da forma correta se necessário.</small>
		
		</p>
		</div>
		</li>										
		</ul>
</li>

								</label>
								<select name="tipo_juros" id="tipo_juros" class="form-control">
									
									<option value="Padrão">Júros Padrão (Básico)</option>
									<option value="Simples">Júros Simples (Price JS)</option>
									<option value="Composto_Price">Júros Composto Banco </option>
									<option value="Composto">Júros Composto Comum </option>
									<option value="Prefixado">Júros Prefixados</option>
									<option value="Somente Júros">Somente Júros</option>
									<option value="Sem Júros">Sem Júros</option>

								</select>						
						</div>

						<div class="col-md-4">							
								<label>Enviar Whatsapp</label>
								<select name="enviar_whatsapp" id="enviar_whatsapp_emp" class="form-control">	
									<option value="Sim">Sim</option>
									<option value="Não">Não</option>
								</select>						
						</div>

					</div>	

          <div class="row">
            <div class="col-md-12">       
              <label>Observações</label>
              <textarea class="form-control" id="obs" name="obs" placeholder="Observações sobre o Empréstimo"></textarea>
            </div>
          </div>


					<input type="hidden" class="form-control" id="id_emp" name="id">					

				<br>
				<small><div id="mensagem_emp" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button id="btn_emprestimo" type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Cobranca -->
<div class="modal fade" id="modalCobranca" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_cob"></span></h4>
				<button id="btn-fechar-cob" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_cob">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-3">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor_cob" name="valor" placeholder="Valor" required onkeyup="mascara_valor('valor_cob')">							
						</div>

						<div class="col-md-5">							
								<label>Parcelas <small><small>(Não Obrigatória)</small></small></label>
								<input type="number" class="form-control" id="parcelas_cob" name="parcelas" placeholder="Parcelas" value="1">							
						</div>

							<div class="col-md-4">							
								<label>Data</label>
								<input type="date" class="form-control" id="data_cob" name="data"  value="<?php echo $data_atual ?>" required>							
						</div>	
						
					</div>

				

					<div class="row">

							<div class="col-md-4">							
								<label>Vencimento</label>
								<input type="date" class="form-control" id="data_venc_cob" name="data_venc" placeholder="Data de Vencimento" value="<?php echo $data_atual ?>">							
						</div>

						<div class="col-md-4">						
								<label>Frequência</label>
								<select class="form-control" name="frequencia" id="frequencia_cob">								
								<?php 
									$query = $pdo->query("SELECT * from frequencias where dias > 0 order by id asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$linhas = @count($res);
									if($linhas > 0){
									for($i=0; $i<$linhas; $i++){
								 ?>
								  <option value="<?php echo $res[$i]['dias'] ?>"><?php echo $res[$i]['frequencia'] ?></option>

								<?php } } ?>
									
								</select>	
						</div>

							<div class="col-md-4">							
								<label>Enviar Whatsapp</label>
								<select name="enviar_whatsapp" id="enviar_whatsapp_cob" class="form-control">	
									<option value="Sim">Sim</option>
									<option value="Não">Não</option>
								</select>						
						</div>
						
					</div>

					<div class="row">
						<div class="col-md-4">							
								<label>Júros % Dia</label>
								<input type="text" class="form-control" id="juros_cobranca" name="juros" placeholder="Júros se Houver" value="" onkeyup="mascara_valor('juros_cobranca')">							
						</div>


						<div class="col-md-4">							
								<label>Multa R$</label>
								<input type="text" class="form-control" id="multa_cobranca" name="multa" placeholder="Multa se Houver" value="" onkeyup="mascara_valor('multa_cobranca')">							
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">							
								<label>Observações</label>
								<input type="text" class="form-control" id="obs_cob" name="obs" placeholder="Observações" >							
						</div>
					</div>

					<input type="hidden" class="form-control" id="id_cob" name="id">					

				<br>
				<small><div id="mensagem_cob" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button id="btn_cobranca" type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Parcelas -->
<div class="modal fade" id="modalParcelas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">	
		<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_baixar">Parcelas</span></h4>
				<button id="btn-fechar-parcelas" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>			
			<div class="modal-body">
						
				<small><div id="listar_parcelas" style="overflow: scroll; height:380px; scrollbar-width: thin;"></div></small>

				<input type="hidden" id="id_emprestimo">
				<input type="hidden" id="id_cobranca">						
			</div>
			
		</div>
	</div>
</div>

<!-- Modal Baixar -->
<div class="modal fade" id="modalBaixar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_baixar">Baixar Parcela</span></h4>
				<button id="btn-fechar-baixar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_baixar">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-4">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor_baixar" name="valor_baixar" placeholder="Valor" readonly="">							
						</div>						
					

							<div class="col-md-4">							
								<label>Total Júros</label>
								<input type="text" class="form-control" id="juros_baixar" name="juros_baixar" placeholder="Júros se Houver" onkeyup="calcular()">							
						</div>

						<div class="col-md-4">							
								<label>Multa R$</label>
								<input type="text" class="form-control" id="multa_baixar" name="multa_baixar" placeholder="Multa se Houver" onkeyup="calcular()" >							
						</div>
						
					</div>

				
					<div class="row">

						<div class="col-md-4">							
								<label>Data Pgto</label>
								<input type="date" class="form-control" id="data_baixa" name="data_baixa" placeholder="Data de Pagamento" value="<?php echo $data_atual ?>">							
						</div>

						<div class="col-md-4">							
								<label>Forma Pgto</label>
								<select name="forma_pgto" id="forma_pgto" class="form-control" onchange="calcular()" >
									<option value="">Selecionar Forma PGTO </option>
									<?php 
										$query = $pdo->query("SELECT * from formas_pgto order by id asc");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$linhas = @count($res);
						for($i=0; $i<$linhas; $i++){
							echo '<option value="'.$res[$i]['nome'].'">'.$res[$i]['nome'].'</option>';		
						}

									 ?>
								</select>
															
						</div>

						<div class="col-md-4">							
								<label>Valor Final</label>
								<input type="text" class="form-control" id="valor_final" name="valor_final" placeholder="Total Pago" required onkeyup="mascara_valor('valor_final')">							

						</div>	
										
					</div>

					<div class="row" align="right">
					  <div class="col-md-12">	

              <span style="margin-right: 15px">
                <input type="checkbox" class="form-checkbox" id="residuo_parcela" name="residuo_parcela" value="Sim" style="display:inline-block;">
                <label for="residuo_final" style="display:inline-block;"><small>Resíduo mesma Parcela</small></label>
              </span>
						  <span style="margin-right: 15px">
						    <input type="checkbox" class="form-checkbox" id="residuo_final" name="residuo_final" value="Sim" style="display:inline-block;">
						    <label for="residuo_final" style="display:inline-block;"><small>Resíduo Final</small></label>
						  </span>

						  <span>	
						    <input type="checkbox" class="form-checkbox" id="residuo" name="residuo" value="Sim" style="display:inline-block;">
						    <label for="residuo" style="display:inline-block;"><small>Resíduo Próxima Parcela</small></label>
						  </span>
						</div>
						
					</div>
					<input type="hidden" class="form-control" id="id_baixar" name="id">					

				<br>
				<small><div id="mensagem_baixar" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button id="btn_salvar_baixar" type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Baixar -->
<div class="modal fade" id="modalBaixarEmprestimo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_baixar_emp">Baixar Empréstimo</span></h4>
				<button id="btn-fechar-baixar_emprestimo" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_baixar_emprestimo">
			

			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-5">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor_final_emprestimo" name="valor_final_emprestimo" placeholder="Valor" onkeyup="mascara_valor('valor_final_emprestimo')">							
						</div>	

						<div class="col-md-7">	
						<label>Finalizado / Perdido</label>	
						<select class="form-control" id="status_baixa" name="status_baixa">
			<option value="Finalizado">Finalizado</option>
			<option value="Perdido">Perdido</option>	
		</select>

		</div>
		</div>

		<div class="row">			
					

						<div class="col-md-5">							
								<label>Data Pgto</label>
								<input type="date" class="form-control" id="data_baixa_emprestimo" name="data_baixa_emprestimo" placeholder="Data de Pagamento" value="<?php echo $data_atual ?>">							
						</div>


						<div class="col-md-7">							
								<label>Forma Pgto</label>
								<select name="forma_pgto_emprestimo" id="forma_pgto_emprestimo" class="form-control" required>
									<option value="">Selecionar Forma PGTO </option>
									<?php 
										$query = $pdo->query("SELECT * from formas_pgto order by id asc");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$linhas = @count($res);
						for($i=0; $i<$linhas; $i++){
							echo '<option value="'.$res[$i]['nome'].'">'.$res[$i]['nome'].'</option>';		
						}

									 ?>
								</select>
															
						</div>


						
					</div>
					<input type="hidden" class="form-control" id="id_do_emp" name="id">		
					<input type="hidden" class="form-control" id="id_do_cliente" name="id_cliente">					

				<br>
				<small><div id="mensagem_baixar_emprestimo" align="center"></div></small>


				<br>
				<small><div align="center" style="border: 1px solid #000"><b>OBS:</b>Ao Finalizar o empréstimo, todas as parcelas que estão pendentes de pagamentos serão excluídas e o valor inserido aqui será lançado no financeiro.</div></small>

			</div>

			<div class="modal-footer">       
				<button id="btn_salvar_baixar_emprestimo" type="submit" class="btn btn-primary">Baixar</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Nova Parcela -->
<div class="modal fade" id="modalNovaParcela" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="">Nova Parcela</span></h4>
				<button id="btn-fechar-nova" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_nova_parcela">
			<div class="modal-body">

				<div class="row">
						<div class="col-md-12">							
								<label>Descrição</label>
								<input type="text" class="form-control" id="descricao_nova" name="descricao" placeholder="Descrição">							
						</div>	

											
					</div>
				

					<div class="row">
						<div class="col-md-6">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor_nova" name="valor" placeholder="Valor">							
						</div>	

						<div class="col-md-6">							
								<label>Vencimento</label>
								<input type="date" class="form-control" id="data_venc_nova" name="data_venc" placeholder="Data de venc" value="<?php echo $data_atual ?>">							
						</div>	
						
					</div>


					<div class="row">
						<div class="col-md-12">							
								<label>OBS</label>
								<input type="text" class="form-control" id="obs_nova" name="obs" placeholder="Observações">							
						</div>	

											
					</div>
					<input type="hidden" class="form-control" id="id_nova_parcela" name="id">	
					<input type="hidden" class="form-control" id="id_nova_parcela_cliente" name="id_cliente">	
									

				<br>
				<small><div id="mensagem_nova_parcela" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button id="btn_nova_parcela" type="submit" class="btn btn-primary">Criar</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Amortizar -->
<div class="modal fade" id="modalAmortizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="">Amortizar Valor</span></h4>
				<button id="btn-fechar-amortizar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_amortizar">
			<div class="modal-body">							

					<div class="row">
						<div class="col-md-6">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor_amortizar" name="valor_amortizar" placeholder="Valor">							
						</div>	

						<div class="col-md-6">							
								<label>Data</label>
								<input type="date" class="form-control" id="data_amortizar" name="data_amortizar" placeholder="Data Pagamento" value="<?php echo $data_atual ?>">							
						</div>	
						
					</div>

          <div class="row">
						<div class="col-md-12">							
								<label>Forma Pgto</label>
								<select name="forma_pgto_amortizar" id="forma_pgto_amortizar" class="form-control"  >									
									<?php 
										$query = $pdo->query("SELECT * from formas_pgto order by id asc");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$linhas = @count($res);
						for($i=0; $i<$linhas; $i++){
							echo '<option value="'.$res[$i]['nome'].'">'.$res[$i]['nome'].'</option>';		
						}

									 ?>
								</select>
															
						</div>
					</div>


					<div class="row">
						<div class="col-md-12">							
								<label>OBS</label>
								<input type="text" class="form-control" id="obs_amortizar" name="obs_amortizar" placeholder="Observações">							
						</div>	

											
					</div>

					<input type="hidden" class="form-control" id="id_amortizar" name="id">	
					<input type="hidden" class="form-control" id="id_amortizar_cliente" name="id_cliente">	
									

				<br>
				<small><div id="mensagem_amortizar" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button id="btn_amortizar" type="submit" class="btn btn-primary">Criar</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Ediitar Emp -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_empr"></span></h4>
				<button id="btn-fechar-empr" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_empr">
			<div class="modal-body">
				

				<div class="row">

						<div class="col-md-4">							
								<label>Júros % Dia</label>
								<input type="text" class="form-control" id="juros_empr" name="juros" placeholder="Júros se Houver"  onkeyup="mascara_valor('juros_empr')">							
						</div>


						<div class="col-md-4">							
								<label>Multa R$</label>
								<input type="text" class="form-control" id="multa_empr" name="multa" placeholder="Multa se Houver"  onkeyup="mascara_valor('multa_empr')">							
						</div>
						
					</div>

					<input type="hidden" class="form-control" id="id_empr" name="id">					

				<br>
				<small><div id="mensagem_empr" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Lançar Valor -->
<div class="modal fade" id="modalLancar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="">Lançar Valor</span></h4>
				<button id="btn-fechar-lancar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_lancar">
			<div class="modal-body">							

					<div class="row">
						<div class="col-md-6">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor_lancar" name="valor_lancar" placeholder="Valor">							
						</div>	

						<div class="col-md-6">							
								<label>Data</label>
								<input type="date" class="form-control" id="data_lancar" name="data_lancar" placeholder="Data Rececimento" value="<?php echo $data_atual ?>">							
						</div>	
						
					</div>


					<div class="row">
						<div class="col-md-12">							
								<label>Forma Pgto</label>
								<select name="forma_pgto_lancar" id="forma_pgto_lancar" class="form-control"  >									
									<?php 
										$query = $pdo->query("SELECT * from formas_pgto order by id asc");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$linhas = @count($res);
						for($i=0; $i<$linhas; $i++){
							echo '<option value="'.$res[$i]['nome'].'">'.$res[$i]['nome'].'</option>';		
						}

									 ?>
								</select>
															
						</div>
					</div>


					<div class="row">
						<div class="col-md-12">							
								<label>OBS</label>
								<input type="text" class="form-control" id="obs_lancar" name="obs_lancar" placeholder="Observações">							
						</div>	

											
					</div>

				
								


					<input type="hidden" class="form-control" id="id_lancar" name="id">	
					<input type="hidden" class="form-control" id="id_lancar_cliente" name="id_cliente">	
									

				<br>
				<small><div id="mensagem_lancar" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button id="btn_lancar" type="submit" class="btn btn-primary">Criar</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="importarXlsModal" tabindex="-1" role="dialog" aria-labelledby="importarXlsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="importarXlsModalLabel">Importar Arquivo Excel</h5>
        <button id="btn-fechar-excel" type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">

      	<form id="form_excel">
      	<div class="row">
      		<div class="col-md-9">
      			<!-- Input de arquivo -->
        <div class="form-group">
          <label for="arquivoXls">Selecione o arquivo .xls</label>
          <input type="file" class="form-control" id="arquivoXls" name="arquivo" accept=".xls,.xlsx">
        </div>
      		</div>
      		<div class="col-md-3" style="margin-top: 23px">
      			 <button type="submit" class="btn btn-success">Importar</button>
      		</div>
      	</div>

      	</form>
        
      	<div class="row">
						<label>Use uma linha acima com o nome das colunas dessa forma  (<i class="fa fa-file-excel-o text-success"></i> <a target="_blank" href="images/modelo.xlsx" style="color:blue">Modelo Anexo</a>)</label> <br>
						<img src="images/modelo_clientes.jpg" width="100%"><br><br>
						<label>Caso não tenha todas as colunas, deixe as que não tiver com dados vazio, mas preencha o cabeçalho delas, conforme o modelo acima ou modelo em anexo, as datas precisam estar no modelo americano (ano-mês-dia)</label>
					</div>
</div>

    </div>
  </div>
</div>




<form action="rel/recibo_class.php" method="post" style="display:none" target="_blank">
    <input type="hidden" name="id" value="<?=$id_conta;?>" id="id_conta_recibo">
    <input type="hidden" name="enviar" value="Sim">
    <button id="btn_form" type="submit"></button>
</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawR5R2tGv6FvE7aA9qI5T7F/2y9v8C8N/P8=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RMR1Y1R6K4/p7TsaWc5u4wM8j/E6L3/eI0/2y9z1/N8aH3i7N4yO5Q=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-Bz6Q9w1/k1D/B8mF6rFvM3n2y9z1/N8aH3i7N4yO5Q=" crossorigin="anonymous"></script>

    <script type="text/javascript" src="js/ajax.js"></script>
  </body>
</html>

<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
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

      $(document).ready(function() {
        $('#ramo').on('input', verificaRamoAtuacao);
      });

			function carregarImgArquivos() {
				var target = document.getElementById('target-arquivos');
				var file = document.querySelector("#arquivo_conta").files[0];

				var arquivo = file['name'];
				resultado = arquivo.split(".", 2);

				if(resultado[1] === 'pdf'){
					$('#target-arquivos').attr('src', "images/pdf.png");
					return;
				}

				if(resultado[1] === 'rar' || resultado[1] === 'zip'){
					$('#target-arquivos').attr('src', "images/rar.png");
					return;
				}

				if(resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt'){
					$('#target-arquivos').attr('src', "images/word.png");
					return;
				}


				if(resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls'){
					$('#target-arquivos').attr('src', "images/excel.png");
					return;
				}


				if(resultado[1] === 'xml'){
					$('#target-arquivos').attr('src', "images/xml.png");
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
	function listarArquivos(){
		var id = $('#id_arquivo').val(); 

		 $.ajax({
	        url: 'paginas/' + pag + "/listar_arquivos.php",
	        method: 'POST',
	        data: {id},
	        dataType: "html",

	        success:function(result){
	            $("#listar_arquivos").html(result);
	           
	        }
	    });

	}
</script>

<script type="text/javascript">
	

$("#form-arquivos").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/arquivos.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_arquivo').text('');
            $('#mensagem_arquivo').removeClass()
            if (mensagem.trim() == "Inserido com Sucesso") {

            	$('#nome_arq').val('');
				$('#arquivo_conta').val('');
				$('#target-arquivos').attr('src','images/arquivos/sem-foto.png');
                //$('#btn-fechar-arquivos').click();
                listarArquivos();          

            } else {

                $('#mensagem_arquivo').addClass('text-danger')
                $('#mensagem_arquivo').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>

<script type="text/javascript">
	
$("#form_emp").submit(function () {

	$('#mensagem_emp').text('Carregando');
	$('#btn_emprestimo').hide();

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/emprestimo.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_emp').text('');
            $('#mensagem_emp').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-emp').click();
                limparCampos();
                      

            } else {

                $('#mensagem_emp').addClass('text-danger')
                $('#mensagem_emp').text(mensagem)
            }

            $('#btn_emprestimo').show();

        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>


<script type="text/javascript">
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
	        url: 'paginas/' + pag + "/calcular.php",
	        method: 'POST',
	        data: {valor, multa, juros, forma_pgto},
	        dataType: "html",

	        success:function(result){
	             $('#valor_final').val(result);
	        }
	    });

	

	}
</script>

<script type="text/javascript">

$("#form_baixar").submit(function () {

	var id_emp = $('#id_emprestimo').val();
	var id_cob = $('#id_cobranca').val();
	var cliente = $('#cliente_baixar').val();

    event.preventDefault();
    var formData = new FormData(this);

    $('#mensagem_baixar').text('Carregando!!');
      $('#btn_salvar_baixar').hide();
  

    $.ajax({
        url: 'paginas/' + pag + "/baixar.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
        	var split = mensagem.split("*");
            $('#mensagem_baixar').text('');
            $('#mensagem_baixar').removeClass()
            if (split[0].trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-baixar').click();
                if(id_emp != ""){
                	 mostrarParcelasEmp(id_emp)
                }

                if(id_cob != ""){
                	 mostrarParcelas(id_cob);
                }

                if(id_emp == "" && id_cob == ""){
                	listarDebitos(cliente);
                }

                $('#id_conta_recibo').val(split[1]);
                $("#btn_form").click();
                
               limparCampos();
                   	   

            } else {

                $('#mensagem_baixar').addClass('text-danger')
                $('#mensagem_baixar').text(split[0])
            }

              $('#btn_salvar_baixar').show();


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>

<script type="text/javascript">

$("#form_baixar_emprestimo").submit(function () {

	var id_emp = $('#id_do_emp').val();
	var id_cliente = $('#id_do_cliente').val();
	
    event.preventDefault();
    var formData = new FormData(this);

    $('#mensagem_baixar_emprestimo').text('Carregando!!');
      $('#btn_salvar_baixar_emprestimo').hide();
  

    $.ajax({
        url: 'paginas/' + pag + "/baixar_emprestimo.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
        	var split = mensagem.split("*");
            $('#mensagem_baixar_emprestimo').text('');
            $('#mensagem_baixar_emprestimo').removeClass()
            if (split[0].trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-baixar_emprestimo').click();
                if(id_emp != ""){
                	 mostrarParcelasEmp(id_emp)
                }

                listarEmprestimos(id_cliente)


                $('#id_conta_recibo').val(split[1]);
                $("#btn_form").click();
                
               limparCampos();
                   	   

            } else {

                $('#mensagem_baixar_emprestimo').addClass('text-danger')
                $('#mensagem_baixar_emprestimo').text(split[0]);


                $('#btn-fechar-baixar_emprestimo').click();
                listarEmprestimos(id_cliente)
            }

              $('#btn_salvar_baixar_emprestimo').show();


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>

<script type="text/javascript">
	

$("#form_cob").submit(function () {

	$('#mensagem_cob').text('Carregando');
	$('#btn_cobranca').hide();

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/recorrencia.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_cob').text('');
            $('#mensagem_cob').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-cob').click();
                limparCampos();
                      

            } else {

                $('#mensagem_cob').addClass('text-danger')
                $('#mensagem_cob').text(mensagem)
            }

            $('#btn_cobranca').show();

        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>


<script type="text/javascript">
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
</script>

<script>
    
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

    </script>

<script type="text/javascript">
	

$("#form_nova_parcela").submit(function () {

	var id_emp = $('#id_nova_parcela_cliente').val();
	

	$('#mensagem_nova_parcela').text('Carregando');
	$('#btn_nova_parcela').hide();

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/nova_parcela.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_nova_parcela').text('');
            $('#mensagem_nova_parcela').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-nova').click();
                limparCampos();
                listarEmprestimos(id_emp);      

            } else {

                $('#mensagem_nova_parcela').addClass('text-danger')
                $('#mensagem_nova_parcela').text(mensagem)
            }

            $('#btn_nova_parcela').show();

        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
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

<script type="text/javascript">
	function listarDebitos(id){			
		 $.ajax({
	        url: 'paginas/' + pag + "/listar_debitos.php",
	        method: 'POST',
	        data: {id},
	        dataType: "html",

	        success:function(result){
	            $("#listar_debitos").html(result);
	           
	        }
	    });

	}


	function mensagem_w(id, mensagem){			
		 $.ajax({
	        url: 'paginas/' + pag + "/mensagem.php",
	        method: 'POST',
	        data: {id, mensagem},
	        dataType: "html",

	        success:function(result){	           
	           
	        }
	    });

	}
</script>

<script type="text/javascript">
			function carregarImgArquivos_conta() {
				var target = document.getElementById('target-arquivos_conta');
				var file = document.querySelector("#arquivo_conta_conta").files[0];

				var arquivo = file['name'];
				resultado = arquivo.split(".", 2);

				if(resultado[1] === 'pdf'){
					$('#target-arquivos_conta').attr('src', "images/pdf.png");
					return;
				}

				if(resultado[1] === 'rar' || resultado[1] === 'zip'){
					$('#target-arquivos_conta').attr('src', "images/rar.png");
					return;
				}

				if(resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt'){
					$('#target-arquivos_conta').attr('src', "images/word.png");
					return;
				}


				if(resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls'){
					$('#target-arquivos_conta').attr('src', "images/excel.png");
					return;
				}


				if(resultado[1] === 'xml'){
					$('#target-arquivos_conta').attr('src', "images/xml.png");
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
	function listarArquivosConta(){
		var id = $('#id_arquivo_conta').val(); 

		 $.ajax({
	        url: 'paginas/receber/listar_arquivos.php',
	        method: 'POST',
	        data: {id},
	        dataType: "html",

	        success:function(result){
	            $("#listar_arquivos_conta").html(result);
	           
	        }
	    });

	}
</script>

<script type="text/javascript">
	

$("#form-arquivos_conta").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/receber/arquivos.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_arquivo_conta').text('');
            $('#mensagem_arquivo_conta').removeClass()
            if (mensagem.trim() == "Inserido com Sucesso") {

            	$('#nome_arq_conta').val('');
				$('#arquivo_conta_conta').val('');
				$('#target-arquivos_conta').attr('src','images/arquivos/sem-foto.png');
                //$('#btn-fechar-arquivos').click();
                listarArquivosConta();          

            } else {

                $('#mensagem_arquivo_conta').addClass('text-danger')
                $('#mensagem_arquivo_conta').text(mensagem)
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
	

$("#form_amortizar").submit(function () {

	var id_cliente = $('#id_amortizar_cliente').val();
	

	$('#mensagem_amortizar').text('Carregando');
	$('#btn_amortizar').hide();

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/amortizar.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_amortizar').text('');
            $('#mensagem_amortizar').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-amortizar').click();
                limparCampos();
                listarEmprestimos(id_cliente);      

            } else {

                $('#mensagem_amortizar').addClass('text-danger')
                $('#mensagem_amortizar').text(mensagem)
            }

            $('#btn_amortizar').show();

        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
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
	

$("#form_empr").submit(function () {

	var id_cliente = $('#id_cliente_mostrar').val();

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/clientes/editar_emp.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {  
                 
            if (mensagem.trim() == "Editado com Sucesso") {   
            $('#btn-fechar-empr').click()	
            listarEmprestimos(id_cliente); 
            alert(mensagem)         	

            } else {

                $('#mensagem_empr').addClass('text-danger')
                $('#mensagem_empr').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
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
    var preview = document.getElementById('preview_cor_status_busca');

    if(corSelecionada) {
        preview.style.backgroundColor = corSelecionada;
    } else {
        preview.style.backgroundColor = "#ffffff";
    }
}
</script>


<script type="text/javascript">
	function buscar(){
		var status = $('#status_cliente_busca').val();
    var estagio = $('#estagio_cliente_busca').val();    
    var alerta = $('#alerta_busca').val();
		listar(status, estagio, alerta);
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
	

$("#form_excel").submit(function () {		
	
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/importar_excel.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {           
            if (mensagem.trim() == "Importado com Sucesso") {
            	
                $('#btn-fechar-excel').click();
                buscar();  

            } else {               
                alertWarning(mensagem)
            }          

        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>

<script>
function exportarClientes() {
  window.open('http://localhost/painel/paginas/clientes/exportar_excel.php', '_blank');
}
</script>


<script type="text/javascript">
	

$("#form_lancar").submit(function () {

	var id_cliente = $('#id_lancar_cliente').val();
	

	$('#mensagem_lancar').text('Carregando');
	$('#btn_lancar').hide();

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/lancar_valor.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_lancar').text('');
            $('#mensagem_lancar').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-lancar').click();
                limparCampos();
                listarEmprestimos(id_cliente);      

            } else {

                $('#mensagem_lancar').addClass('text-danger')
                $('#mensagem_lancar').text(mensagem)
            }

            $('#btn_lancar').show();

        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>

