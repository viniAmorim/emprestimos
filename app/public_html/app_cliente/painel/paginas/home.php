<style>
  .card-container {
    background: linear-gradient(to bottom, #e8f5e9, #c8e6c9); /* verde bem suave */
    min-height: 100vh;
    padding: 20px;
    margin-top: 70px;
  }

  .card-menu {
    background-color: #388e3c; /* verde elegante */
    border-radius: 14px;
    color: #fff;
    padding: 30px 10px;
    text-align: center;
    transition: all 0.3s ease;
    min-height: 130px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .card-menu:hover {
    background-color: #2e7d32; /* escurece no hover */
    transform: translateY(-5px);
  }

  .card-menu i {
    font-size: 2.2rem;
    margin-bottom: 12px;
    display: block;
  }

  .card-menu div {
    font-weight: 500;
    font-size: 0.95rem;
  }
</style>

<div class="container-fluid card-container">
  <div class="row g-4">

    <!-- Empréstimos -->
    <?php if($recursos != "Cobranças"){ ?>
    <div class="col-6">
      <a href="emprestimos" onclick="navigateToPage(event, 'emprestimos')" class="text-decoration-none">
        <div class="card-menu">
          <i class="bi bi-currency-dollar"></i>
          <div>Empréstimos</div>
        </div>
      </a>
    </div>
    <?php } ?>

    <!-- Cobranças -->
    <?php if($recursos != "Empréstimos"){ ?>
    <div class="col-6">
      <a href="cobrancas" onclick="navigateToPage(event, 'cobrancas')" class="text-decoration-none">
        <div class="card-menu">
          <i class="bi bi-cash"></i>
          <div>Cobranças</div>
        </div>
      </a>
    </div>
    <?php } ?>

    <!-- Minhas Contas -->
    <div class="col-6">
      <a href="receber" onclick="navigateToPage(event, 'receber')" class="text-decoration-none">
        <div class="card-menu">
          <i class="bi bi-currency-exchange"></i>
          <div>Minhas Contas</div>
        </div>
      </a>
    </div>

    <!-- Solicitar Empréstimos -->
    <?php if($recursos != "Cobranças"){ ?>
    <div class="col-6">
      <a href="solicitar_emprestimo" onclick="navigateToPage(event, 'solicitar_emprestimo')" class="text-decoration-none">
        <div class="card-menu">
          <i class="fa-solid fa-list-check"></i>
          <div>Solicitar</div>
        </div>
      </a>
    </div>
    <?php } ?>

  </div>
</div>
