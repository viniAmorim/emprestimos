<?php 
$pag = 'solicitar_emprestimo';


 ?>

<div class="main-page margin-mobile">

<div class="row">
	<div class="col-md-2">
		<a onclick="inserir()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Solicitar Empréstimo</a>
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
              <input type="text" class="form-control money" id="valor" name="valor" placeholder="Valor" required>
            </div>

            <div class="col-md-4">
              <label>Valor Parcela</label>
              <input type="text" class="form-control money" id="valor_parcela" name="valor_parcela" placeholder="Valor Parcela" required>
            </div>

            <div class="col-md-4">
              <label>Tipo de Vencimento</label>
              <select class="form-control" id="tipo_vencimento" name="tipo_vencimento" required>
                <option value="" disabled selected>Selecione</option>
                <option value="diario">Diário</option>
                <option value="semanal">Semanal</option>
                <option value="quinzenal">Quinzenal</option>
                <option value="mensal">Mensal</option>
              </select>
            </div>
						
					</div>

					<div class="row">

						<div class="col-md-8">							
								<label>Observações</label>
								<input type="text" class="form-control" id="obs" name="obs" placeholder="Observações" >							
						</div>

            <div class="col-md-4">							
								<label>Data</label>
								<input type="date" class="form-control" id="data" name="data" value="<?php echo $data_atual ?>" readonly required>							
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


<script>
  // Função para formatar moeda no formato brasileiro com R$
  function formatarMoeda(valor) {
    valor = valor.replace(/\D/g, '');
    valor = (parseInt(valor, 10) / 100).toFixed(2);
    return 'R$ ' + valor
      .replace('.', ',')
      .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  const camposMoeda = document.querySelectorAll('.money');

  camposMoeda.forEach(function (campo) {
    campo.addEventListener('input', function (e) {
      let cursor = campo.selectionStart;
      let valorAntigo = campo.value;
      campo.value = formatarMoeda(campo.value);
      let diff = campo.value.length - valorAntigo.length;
      campo.setSelectionRange(cursor + diff, cursor + diff);
    });

    // Garante a formatação ao sair do campo
    campo.addEventListener('blur', function () {
      campo.value = formatarMoeda(campo.value);
    });

    // Opcional: inicializa se já houver valor salvo
    if (campo.value.trim() !== '') {
      campo.value = formatarMoeda(campo.value);
    }
  });
</script>





