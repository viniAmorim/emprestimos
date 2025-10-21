<?php 
$pag = 'emprestimos';

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

<div class="main-page margin-mobile">

<div class="row align-items-end" style="margin-bottom:10px;">
  <!-- Botão Empréstimo -->
  <div class="col-md-2 col-sm-12" style="padding:0; margin-bottom: 10px;">
    <a href="index.php?pagina=clientes" class="btn btn-primary w-100">
      <span class="fa fa-plus"></span> Empréstimo
    </a>
  </div>

  <form method="POST" action="rel/emprestimos_class.php" target="_blank" class="col-md-10 col-sm-12 row m-0 p-0">

    <!-- Cliente -->
    <div class="col-md-4 col-sm-12 mb-2">
      <select class="sel5" id="clientes" name="cliente" onchange="buscar()" style="width: 100%;">
        <option value="">Selecionar Cliente para Filtrar</option>
        <?php 
          $query = $pdo->query("SELECT * from clientes order by nome asc");
          $res = $query->fetchAll(PDO::FETCH_ASSOC);
          $linhas = @count($res);
          if($linhas > 0){
            for($i=0; $i<$linhas; $i++){
        ?>
          <option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>
        <?php } } ?>			
      </select>
    </div>

    <!-- Status -->
    <div class="col-md-2 col-sm-12 mb-2">
      <select class="sel5" id="status" name="status" onchange="buscar()" style="width: 100%;">
        <option value="Ativos">Ativos</option>
        <option value="Finalizado">Finalizados</option>
        <option value="Perdido">Perdidos</option>	
      </select>
    </div>

    <!-- Datas: SEMPRE lado a lado -->
    <div class="col-md-5 col-sm-12 mb-2">
      <div style="display: flex; flex-wrap: nowrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 5px;">
          <i class="fa fa-calendar-o text-primary"></i>
          <input type="date" name="dataInicial" id="dataInicial" value="" 
                 style="width: 130px; height: 30px; font-size: 14px; border: 1px solid #ccc; background: transparent;">
        </div>
        <div style="display: flex; align-items: center; gap: 5px;">
          <i class="fa fa-calendar-o text-primary"></i>
          <input type="date" name="dataFinal" id="dataFinal" value="" 
                 style="width: 130px; height: 30px; font-size: 14px; border: 1px solid #ccc; background: transparent;">
        </div>
      </div>
    </div>

    <!-- Botão Relatório -->
    <div class="col-md-1 col-sm-12 mb-2 text-md-end">
      <button type="submit" class="btn btn-success w-100">
        <span class="fa fa-file-pdf-o"></span>
      </button>
    </div>

  </form>
</div>


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

<div class="bs-example widget-shadow" style="padding:15px; margin-top:0px" id="listar">

</div>

</div>

<input type="hidden" id="ids">




<!-- Modal Dados -->
<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_dados"></span></h4>
				<button id="btn-fechar-dados" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<div class="row" style="margin-top: 0px">
					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Telefone: </b></span><span id="telefone_dados"></span>
					</div>

					
					<div class="col-md-12" style="margin-bottom: 5px">
						<span><b>Email: </b></span><span id="email_dados"></span>
					</div>

					

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>CPF: </b></span><span id="cpf_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Nascimento: </b></span><span id="data_nasc_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Data Cadastro: </b></span><span id="data_cad_dados"></span>
					</div>

					<div class="col-md-12" style="margin-bottom: 5px">
						<span><b>Endereço: </b></span><span id="endereco_dados"></span>
					</div>

					<div class="col-md-12" style="margin-bottom: 5px">
						<span><b>Obs: </b></span><span id="obs_dados"></span>
					</div>

				</div>
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



<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>


<form action="rel/recibo_class.php" method="post" style="display:none" target="_blank">
    <input type="hidden" name="id" value="<?=$id_conta;?>" id="id_conta_recibo">
    <input type="hidden" name="enviar" value="Sim">
    <button id="btn_form" type="submit"></button>
</form>


<script type="text/javascript">
	$(document).ready( function () {
		buscar()
			$('.sel5').select2({

		

		});

	});
</script>



<script type="text/javascript">
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
	function buscar(){		
		var cliente = $('#clientes').val();
		var status = $('#status').val();
		listar(cliente, status)
	}
</script>



<script type="text/javascript">
	function calcular(){			
	var valor = $('#valor_baixar').val();
	var multa = $('#multa_baixar').val();
	var juros = $('#juros_baixar').val();
	var forma_pgto = $('#forma_pgto').val();	

	 $.ajax({
	        url: 'paginas/clientes/calcular.php',
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
    event.preventDefault();
    var formData = new FormData(this);
     $('#mensagem_baixar').text('Carregando!!');
      $('#btn_salvar_baixar').hide();

    $.ajax({
        url: 'paginas/clientes/baixar.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
        	var split = mensagem.split("*");
            $('#mensagem_baixar').text('');
            $('#mensagem_baixar').removeClass()
            if (split[0].trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-baixar').click();
                mostrarParcelas(id_emp);

                 $('#id_conta_recibo').val(split[1]);
                $("#btn_form").click();
                      

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
        url: 'paginas/clientes/baixar_emprestimo.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
        	var split = mensagem.split("*");
            $('#mensagem_baixar_emprestimo').text('');
            $('#mensagem_baixar_emprestimo').removeClass()
            if (split[0].trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-baixar_emprestimo').click();
                if(id_emp != ""){
                	 mostrarParcelas(id_emp)
                }

                buscar()


                $('#id_conta_recibo').val(split[1]);
                $("#btn_form").click();                
             
                   	   

            } else {

            	$('#btn-fechar-baixar_emprestimo').click();              

                buscar()

                $('#mensagem_baixar_emprestimo').addClass('text-danger')
                $('#mensagem_baixar_emprestimo').text(split[0])
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
	

$("#form_empr").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/clientes/editar_emp.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {  
                 
            if (mensagem.trim() == "Editado com Sucesso") {   
            $('#btn-fechar-empr').click()	
            buscar();
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
	

$("#form_amortizar").submit(function () {

	var id_cliente = $('#id_amortizar_cliente').val();
	

	$('#mensagem_amortizar').text('Carregando');
	$('#btn_amortizar').hide();

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/clientes/amortizar.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_amortizar').text('');
            $('#mensagem_amortizar').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-amortizar').click();
                buscar();     

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
	

$("#form_lancar").submit(function () {

	var id_cliente = $('#id_lancar_cliente').val();
	

	$('#mensagem_lancar').text('Carregando');
	$('#btn_lancar').hide();

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/clientes/lancar_valor.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_lancar').text('');
            $('#mensagem_lancar').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
            	
                $('#btn-fechar-lancar').click();
                buscar();     

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

