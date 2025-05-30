<?php 
$pag = 'status_clientes';

if(@$status_clientes == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
	exit();
}

?>

<div class="main-page margin-mobile">

	<a onclick="inserir()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Novo Status</a>



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

	<div class="bs-example widget-shadow" style="padding:15px" id="listar">

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
						<div class="col-md-5">							
							<label>Nome do Status</label>
							<input type="text" class="form-control" id="nome" name="nome" placeholder="Bom, Regular, Ótimo, Péssimo" required>							
						</div>

						<div class="col-md-4">
							<div style="display: flex; align-items: center; gap: 10px;">
								<label for="cor_status" style="margin: 0;">Cor do Status:</label>
								<div id="preview_cor" style="width: 17px; height: 17px; border: 1px solid #ccc; border-radius: 4px;"></div>
							</div>
							<select id="cor" name="cor" class="form-control mt-2" onchange="mostrarCorSelecionada()" required="">
								<option value="">Selecione</option>
								<option value="#28a745">Verde</option>
								<option value="#ffc107">Amarelo</option>
								<option value="#dc3545">Vermelho</option>
								<option value="#17a2b8">Azul</option>
								<option value="#6c757d">Cinza</option>
								<option value="#6610f2">Roxo</option>
								<option value="#fd7e14">Laranja</option>
								<option value="#20c997">Turquesa</option>
							</select>
						</div>

						<div class="col-md-3" style="margin-top: 22px;">							
							<button type="submit" class="btn btn-primary">Salvar</button>					
						</div>
					</div>





					<input type="hidden" class="form-control" id="id" name="id">					

					<br>
					<small><div id="mensagem" align="center"></div></small>
				</div>

			</form>
		</div>
	</div>
</div>





<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>



<script>
	function mostrarCorSelecionada() {
		var corSelecionada = document.getElementById('cor').value;
		var preview = document.getElementById('preview_cor');

		if(corSelecionada) {
			preview.style.backgroundColor = corSelecionada;
		} else {
			preview.style.backgroundColor = "#ffffff";
		}
	}
</script>