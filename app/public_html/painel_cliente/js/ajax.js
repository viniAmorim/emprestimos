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
  $("#titulo_inserir").text("Novo Empréstimo");
  $("#modalForm").modal("show");
  limparCampos();
}

$("#form").submit(function () {
  event.preventDefault();
  var formData = new FormData(this);

  Swal.fire({
    title: "Enviando solicitação...",
    text: "Aguarde um momento, por favor.",
    icon: "info",
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: "paginas/" + pag + "/salvar.php",
    type: "POST",
    data: formData,

    success: function (mensagem) {
      $("#mensagem").text("");
      $("#mensagem").removeClass();

      if (mensagem.trim() == "Salvo com Sucesso") {
        Swal.fire({
          title: "Sucesso!",
          text: "Empréstimo solicitado com sucesso!",
          icon: "success",
          confirmButtonText: "OK",
        }).then((result) => {
          if (result.isConfirmed) {
            $("#btn-fechar").click();
            listar();
          }
        });
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
