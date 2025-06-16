$(document).ready(function () {
  listar();
});

function listar(p1, p2, p3, p4, p5, p6) {
  $.ajax({
    url: "paginas/" + pag + "/listar.php",
    method: "POST",
    data: { p1, p2, p3, p4, p5, p6 },
    dataType: "html",

    success: function (result) {
      $("#listar").html(result);
      $("#mensagem-excluir").text("");
    },
  });
}

function inserir() {
  $("#mensagem").text("");
  $("#titulo_inserir").text("Inserir Registro");
  $("#modalForm").modal("show");
  limparCampos();
}

$("#form").submit(function () {
  $("#mensagem").text("Salvando!!!");

  event.preventDefault();
  var formData = new FormData(this);

  $.ajax({
    url: "paginas/" + pag + "/salvar.php",
    type: "POST",
    data: formData,

    success: function (mensagem) {
      $("#mensagem").text("");
      $("#mensagem").removeClass();
      if (mensagem.trim() == "Salvo com Sucesso") {
        $("#btn-fechar").click();
        listar();
      } else {
        $("#mensagem").addClass("text-danger");
        $("#mensagem").text(mensagem);
      }
    },

    cache: false,
    contentType: false,
    processData: false,
  });
});

function excluir(id) {
  $("#mensagem-excluir").text("Excluindo...");

  $.ajax({
    url: "paginas/" + pag + "/excluir.php",
    method: "POST",
    data: { id },
    dataType: "html",

    success: function (mensagem) {
      if (mensagem.trim() == "Excluído com Sucesso") {
        listar();
      } else {
        $("#mensagem-excluir").addClass("text-danger");
        $("#mensagem-excluir").text(mensagem);
      }
    },
  });
}

function ativar(id, acao) {
  $.ajax({
    url: "paginas/" + pag + "/mudar-status.php",
    method: "POST",
    data: { id, acao },
    dataType: "html",

    success: function (mensagem) {
      if (mensagem.trim() == "Alterado com Sucesso") {
        listar();
      } else {
        $("#mensagem-excluir").addClass("text-danger");
        $("#mensagem-excluir").text(mensagem);
      }
    },
  });
}

function mascara_valor(valor) {
  var valorAlterado = $("#" + valor).val();
  valorAlterado = valorAlterado.replace(/\D/g, ""); // Remove todos os não dígitos
  valorAlterado = valorAlterado.replace(/(\d+)(\d{2})$/, "$1,$2"); // Adiciona a parte de centavos
  valorAlterado = valorAlterado.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // Adiciona pontos a cada três dígitos
  valorAlterado = valorAlterado;
  $("#" + valor).val(valorAlterado);
}

function validarCliente(id, nome) {
  Swal.fire({
    title: "Confirmar validação",
    text: `Deseja validar o cliente: ${nome}?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sim, validar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Validando...",
        text: "Por favor, aguarde",
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });

      $.ajax({
        url: "paginas/clientes/validar.php",
        method: "POST",
        data: { id },
        dataType: "html",
        success: function (mensagem) {
          if (mensagem.trim() === "Validado com sucesso!") {
            Swal.fire({
              title: "Sucesso!",
              text: mensagem,
              icon: "success",
              timer: 1500,
              showConfirmButton: false,
            });

            listar(); // Atualiza a tabela sem reload
          } else {
            Swal.fire({
              title: "Erro",
              text: mensagem,
              icon: "error",
            });
          }
        },
        error: function () {
          Swal.fire({
            title: "Erro de conexão",
            text: "Não foi possível validar o cliente.",
            icon: "error",
          });
        },
      });
    }
  });
}
