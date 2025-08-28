<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

//verificar se ele tem a permissão de estar nessa página
if(@$acessos == 'ocultar'){
    echo "<script>window.location='index.php'</script>";
    exit();
}

$pag = 'acessos';

?>

<div class="breadcrumb-header justify-content-between">
 	<div class="left-content mt-2">
 <a class="btn ripple btn-primary text-white" onclick="inserir()" type="button"><i class="fe fe-plus me-1"></i> Adicionar <?php echo ucfirst($pag); ?></a>


<!-- BOTÃO EXCLUIR SELEÇÃO -->

		<a class="btn btn-danger" href="#" onclick="deletarSel()" title="Excluir" id="btn-deletar" style="display:none"><i
				class="fe fe-trash-2"></i> Deletar</a>

</div>

</div>

<div class="row row-sm">
<div class="col-lg-12">
<div class="card custom-card">
<div class="card-body" id="listar">

</div>
</div>
</div>
</div>

<input type="hidden" id="ids">

<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				 <button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form">
			<div class="modal-body">
				

					<div class="row needs-validation was-validated">
						<div class="col-md-6 mb-2">						
								<label>Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Acesso" required>	
						</div>

						<div class="col-md-6">						
								<label>Chave</label>
								<input type="text" class="form-control" id="chave" name="chave" placeholder="Chave" required>	
						</div>
					</div>

					<div class="row">
						<div class="col-md-5 mb-2">						
								<label>Grupo</label>
								<select class="form-select" name="grupo" id="grupo">
								<option value="0">Sem Grupo</option>
								<?php 
									$query = $pdo->query("SELECT * from grupo_acessos order by id desc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$linhas = @count($res);
									if($linhas > 0){
									for($i=0; $i<$linhas; $i++){
								 ?>
								  <option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>

								<?php } } ?>
									
								</select>	
						</div>

						<div class="col-md-4 mb-2 col-8">	
							<label>Página</label>
								<select class="form-select" name="pagina" id="pagina">
								<option value="Sim">Sim</option>
								<option value="Não">Não</option>
								</select>	
						</div>

						<div class="col-md-3 col-4" style="margin-top: 22px">							
								<button id="btn_salvar" type="submit" class="btn btn-primary">Salvar<i class="fa fa-check ms-2"></i></button>
								<button class="btn btn-primary" type="button" id="btn_carregando" style="display:none">
								<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...
							</button>			
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


<script type="text/javascript">
	$(document).ready(function() {
    $('.sel2').select2({
    	dropdownParent: $('#modalForm')
    });
});
</script>



<script type="text/javascript">
			
			$('#modalForm').on('shown.bs.modal', function () {
			    $('#nome').focus();
			});
</script>