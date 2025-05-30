<?php 
$pag = 'solicitar_emprestimo';


 ?>

<div class="main-page margin-mobile">

<div class="row">
	
<div class="col-md-12">
		<a onclick="buscar('')" type="button" class="btn btn-danger">Pendentes </a>  <a onclick="buscar('Concluída')" type="button" class="btn btn-success">Concluídas</a>
	</div>

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

<div class="bs-example widget-shadow" style="padding:15px;margin-top:0px" id="listar">

</div>

</div>

<input type="hidden" id="ids">

<!-- Modal Perfil -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
			<div class="modal-body">
				

					<div class="row">						

						<div class="col-md-4">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="Valor" required onkeyup="mascara_valor('valor')">							
						</div>

						<div class="col-md-4">							
								<label>Parcelas</label>
								<input type="text" class="form-control" id="parcelas" name="parcelas" placeholder="" required>							
						</div>

						<div class="col-md-4">							
								<label>Data</label>
								<input type="date" class="form-control" id="data" name="data" value="<?php echo $data_atual ?>" required>							
						</div>
						
					</div>


					
					<div class="row">

						<div class="col-md-12">							
								<label>Observações</label>
								<input type="text" class="form-control" id="obs" name="obs" placeholder="Observações" >							
						</div>
					</div>



					<div class="row">

						<div class="col-md-12">							
								<label>Garantia? Se necessário</label>
								<input type="text" class="form-control" id="garantia" name="garantia" placeholder="Qual Garantia?" >							
						</div>
					</div>
					


					

					<input type="hidden" class="form-control" id="id" name="id" >
					<input type="hidden" class="form-control" id="cliente" name="cliente" value="<?php echo $id_usuario ?>">					

				<br>
				<small><div id="mensagem" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>







<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>


<script type="text/javascript">
	function buscar(busca){		
		listar(busca)
	}
</script>