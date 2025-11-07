<?php 
@session_start();
$visualizar_usuario = @$_SESSION['visualizar'];
$id_usuario = @$_SESSION['id'];
$tabela = 'clientes';
require_once("../../../conexao.php");

$data_atual = date('Y-m-d');


if($visualizar_usuario == 'Não'){
	$sql_visualizar = " and usuario = '$id_usuario' ";
}else{
	$sql_visualizar = " ";
}

$query = $pdo->query("SELECT * from $tabela where id > 0 $sql_visualizar order by nome asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Telefone</th>	
	<th class="esc">Status</th>	
	<th class="esc">Email</th>	
	<th class="esc">Data Cadastro</th>
	<th class="esc">Grupo / Local</th>	
	<th class="esc">Foto</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$telefone = $res[$i]['telefone'];
	$email = $res[$i]['email'];
	$cpf = $res[$i]['cpf'];	
	$endereco = $res[$i]['endereco'];
	$data_nasc = $res[$i]['data_nasc'];
	$data_cad = $res[$i]['data_cad'];
	$obs = $res[$i]['obs'] ?? '';
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
	$api_pgto = @$res[$i]['api_pgto'];

	$dados_emprestimoF = @rawurlencode($dados_emprestimo);

	$data_nascF = implode('/', array_reverse(explode('-', $data_nasc)));
	$data_cadF = implode('/', array_reverse(explode('-', $data_cad)));

	$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);


	$query2 = $pdo->query("SELECT * from receber where data_venc < curDate() and pago = 'Não' and cliente = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_contas_debito = @count($res2);
if($total_contas_debito == 0){
	continue;
}


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
<tr style="">
<td>

<i class="fa fa-square" style="color:{$cor}; display:{$ocultar_cor}"></i>
{$nome}
</td>
<td class="esc">{$telefone}</td>
<td class="esc"><span class="me-1 my-2 p-1" style="color:{$cor};">{$status_cliente}</span></td>
<td class="esc">{$email}</td>
<td class="esc">{$data_cadF}</td>
<td class="esc">{$grupo}</td>
<td class="esc"><img src="images/clientes/{$foto}" width="25px"></td>
<td>
	


<big><a href="#" onclick="mostrar('{$id}', '{$nome}','{$telefone}','{$cpf}','{$email}','{$enderecoF2}','{$data_nascF}', '{$data_cadF}', '{$obs}', '{$pix}', '{$indicacao}', '{$bairro}', '{$cidade}', '{$estado}', '{$cep}', '{$total_emprestimos}', '{$total_cobrancas}', '{$pessoa}', '{$total_contas}', '{$nome_sec}', '{$telefone_sec}', '{$endereco_sec}', '{$grupo}', '{$dados_emprestimoF}', '{$comprovante_endereco}', '{$comprovante_rg}', '{$tumb_comprovante_endereco}', '{$tumb_comprovante_rg}', '{$telefone2}', '{$foto}', '{$api_pgto}')" title="Mostrar Dados"><i class="fa fa-info-circle text-primary"></i></a></big>

<big><a href="#" onclick="arquivo('{$id}','{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-archive-o" style="color:#3d1002"></i></a></big>


<big><a href="#" onclick="mostrarContas('{$id}', '{$nome}')" title="Mostrar Contas"><i class="fa fa-whatsapp verde"></i></a></big>


</td>
</tr>
HTML;

}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>

</table>
<br>
<div align="right">Total Clientes: {$linhas}</div>
HTML;

}else{
	echo '<small>Nenhum Registro Encontrado!</small>';
}
?>



<script type="text/javascript">
	$(document).ready( function () {		
    $('#tabela').DataTable({
    	"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
        },
        "ordering": false,
		"stateSave": true
    });
} );
</script>

<script type="text/javascript">
	
	function mostrar(id, nome, telefone, cpf, email, endereco, data_nasc, data_cad, obs, pix, indicacao, bairro, cidade, estado, cep, total_emprestimos, total_cobrancas, pessoa, total_contas, nome_sec, telefone_sec, endereco_sec, grupo, dados_emprestimo, comprovante_endereco, comprovante_rg, tumb_comprovante_endereco, tumb_comprovante_rg, telefone2, foto, api_pgto){

		$('#dados_emprestimos_dados2').text(decodeURIComponent(dados_emprestimo));

		if(obs.trim() == ""){			
		$('#div_obs_dados').hide();
		}

		if(dados_emprestimo.trim() == ""){			
		$('#div_dados_emprestimos_dados').hide();
		}

		if(comprovante_endereco.trim() == "" || comprovante_endereco.trim() == "sem-foto.png"){			
		$('#div_link_comprovante_endereco').hide();
		}

		if(comprovante_rg.trim() == "" || comprovante_rg.trim() == "sem-foto.png"){			
		$('#div_link_comprovante_rg').hide();
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

    	$('#api_pgto_dados').text(api_pgto);

    	$('#id_cliente_mostrar').val(id);   	  	
    	

    	$('#link_comprovante_endereco').attr('href','images/comprovantes/' + comprovante_endereco);
		$('#target_mostrar_comprovante_endereco').attr('src','images/comprovantes/' + tumb_comprovante_endereco);

		$('#link_comprovante_rg').attr('href','images/comprovantes/' + comprovante_rg);
		$('#target_mostrar_comprovante_rg').attr('src','images/comprovantes/' + tumb_comprovante_rg);

		$('#target_mostrar_foto').attr('src','images/clientes/' + foto);

    	$('#modalDados').modal('show');
	}

	

	function arquivo(id, nome){		    	
    	$('#nome_arquivo').text(nome);    	
    	$('#id_arquivo').val(id);    	  	
    	$('#mensagem_arquivo').text(''); 

    	listarArquivos();
    	$('#modalArquivos').modal('show');
	}

	
	function mostrarContas(id, nome){			
    $('#titulo_contas').text(nome); 

    $('#nome_contas').val(nome); 
    $('#id_contas').val(id); 
    
    listarContas();
    $('#modalContas').modal('show');

}


function listarContas(){
	
    var id = $('#id_contas').val(); 
	$.ajax({
        url: 'paginas/clientes/mostar_contas.php',
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (mensagem) {           
           $("#listar_contas_debito").html(mensagem);
        },      

    });
}
	
</script>