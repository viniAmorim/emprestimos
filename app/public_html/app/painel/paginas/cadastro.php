<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Seu App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            width: 100%;
            max-width: 420px;
            background-color: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
        }
        .step-content {
            display: none;
        }
        .step-content.active {
            display: block;
        }
        .progress-bar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
        }
        .progress-bar-container::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            height: 4px;
            background-color: #e2e8f0;
            z-index: 0;
        }
        .progress-dot {
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background-color: #cbd5e1;
            transition: background-color 0.3s ease;
            position: relative;
            z-index: 1;
        }
        .progress-dot.active {
            background-color: #4a90e2;
        }
        .form-group label {
            font-weight: 500;
        }
        .form-group input, .form-group select {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            width: 100%;
            margin-top: 0.5rem;
            transition: border-color 0.2s;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #4a90e2;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }
        .btn-secondary {
            background-color: #e2e8f0;
            color: #4a5568;
        }
        .btn-submit {
            background: linear-gradient(to right, #97cc5e, #71a93e);
            color: white;
        }
        .text-link {
            color: #97cc5e;
            transition: color 0.2s;
        }
        .text-link:hover {
            color: #71a93e;
        }
        .hidden { display: none; }
    </style>
</head>
<body class="p-4">
    
    <div id="loading-indicator" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-80 flex items-center justify-center hidden">
        <i class="fas fa-spinner fa-spin text-indigo-500 text-8xl"></i>
    </div>

    <div class="form-container">
        <h2 class="text-center text-3xl font-bold mb-4 text-gray-800">Criar Conta</h2>
        <p class="text-center text-gray-500 mb-8">Por favor, preencha as informações para se cadastrar.</p>

        <!-- Barra de Progresso -->
        <div class="progress-bar-container">
            <div class="progress-dot active" id="dot-1"></div>
            <div class="progress-dot" id="dot-2"></div>
            <div class="progress-dot" id="dot-3"></div>
            <div class="progress-dot" id="dot-4"></div>
            <div class="progress-dot" id="dot-5"></div>
            <div class="progress-dot" id="dot-6"></div>
        </div>

        <form id="form">
            <!-- Etapa 1: Informações Pessoais -->
            <div id="step-1" class="step-content active hidden">
                <h3 class="text-xl font-semibold mb-6 text-gray-700">Etapa 1: Informações Pessoais</h3>
                
                <div class="form-group mb-4">
                    <label for="nome" class="block text-gray-700">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome completo" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="email" class="block text-gray-700">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="email@dominio.com" required>
                </div>

                <div class="form-group mb-4">
                    <label class="block text-gray-700">Celular(Whatsapp)</label>
                    <input type="text" name="telefone" id="telefone" placeholder="Telefone" class="form-input w-full" required onblur="validateField(this)">
                </div>

                <div class="form-group mb-4">
                    <label class="block text-gray-700">CPF</label>
                    <input type="text" name="cpf" id="cpf" placeholder="CPF" class="form-input w-full" required onblur="validateCPF(this)">
                </div>

                <div class="form-group mb-4">
                    <label class="block text-gray-700">RG</label>
                    <input type="text" name="rg" id="rg" placeholder="RG" class="form-input w-full" required onblur="validateField(this)">
                </div>

                <div class="form-group mb-4">
                    <label class="block text-gray-700">Data de Nascimento</label>
                    <input type="text" id="data_nasc" name="data_nasc" class="form-input w-full" placeholder="DD/MM/AAAA" required onblur="validateField(this)">
                </div>

                <div class="form-group mb-4">
                    <label class="block text-gray-700">Chave Pix em sua titularidade</label>
                    <input type="text" name="pix" id="pix" placeholder="CPF, telefone ou e-mail" class="form-input w-full" required onblur="validateField(this)">
                </div>
                
                <div class="form-group mb-4">
                  <div class="flex-1">
                    <label for="comprovante_rg" class="block text-gray-700">CNH ou RG</label>
                    <input type="file" id="comprovante_rg" name="comprovante_rg" onchange="handleFile('comprovante_rg'); validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="hidden" required>
                    
                    <button type="button" class="custom-file-upload mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="document.getElementById('comprovante_rg').click()">
                        <i class="fas fa-upload mr-2"></i> Selecionar Arquivo
                    </button>
                  </div>
                </div>

                <div class="form-group mb-4">
                  <div class="flex-1">
                      <label for="comprovante_endereco" class="block text-gray-700">Comprovante de Endereço</label>
                      <input type="file" name="comprovante_endereco" id="comprovante_endereco" onchange="handleFile('comprovante_endereco');validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="hidden" required>
                      
                      <button type="button" class="custom-file-upload mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="document.getElementById('comprovante_endereco').click()">
                          <i class="fas fa-upload mr-2"></i> Selecionar Arquivo
                      </button>
                  </div>
                </div>
                
                <div class="flex justify-end mt-6">
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Próximo</button>
                </div>
            </div>

            <!-- Etapa 2: Contato e Documentos -->
            <div id="step-2" class="step-content hidden">
                <h3 class="text-xl font-semibold mb-6 text-gray-700">Etapa 2: Login</h3>
                
                <!-- Campo de Senha com botão de visualização -->
                <div class="form-group mb-4 relative">
                    <label for="senha" class="block text-gray-700">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
                    <button type="button" class="absolute inset-y-0 right-0 top-6 px-3 flex items-center text-gray-500" onclick="togglePasswordVisibility('senha')">
                        <i class="fas fa-eye" id="toggle-senha"></i>
                    </button>
                </div>
                
                <!-- Campo de Confirmar Senha com botão de visualização -->
                <div class="form-group mb-4 relative">
                    <label for="conf_senha" class="block text-gray-700">Confirmar Senha</label>
                    <input type="password" id="conf_senha" name="conf_senha" placeholder="Confirme sua senha" required>
                    <button type="button" class="absolute inset-y-0 right-0 top-6 px-3 flex items-center text-gray-500" onclick="togglePasswordVisibility('conf_senha')">
                        <i class="fas fa-eye" id="toggle-confirmar_senha"></i>
                    </button>
                </div>

                <div class="flex flex-col items-center">
                  <video id="cameraPreview" class="hidden rounded-lg mb-4" width="320" height="240" autoplay></video>
                  <canvas id="photoCanvas" class="hidden"></canvas>

                  <img id="foto" src="../../images/sem-foto-perfil.jpg" alt="Sua Foto" class="w-48 h-48 object-cover rounded-lg mb-4">
                  
                  <input type="hidden" id="foto_usuario" name="foto_usuario" required>

                  <button type="button" onclick="tirarFoto()" class="btn btn-primary">
                      Tirar Foto
                  </button>
                </div>
                
                <div class="flex justify-between mt-6">
                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Anterior</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Próximo</button>
                </div>
            </div>

            <!-- Etapa 3: Dados Adicionais e Envio -->
            <div id="step-3" class="step-content hidden">
                <h3 class="text-xl font-semibold mb-6 text-gray-700">Etapa 3: Endereço</h3>
                
                <div class="form-group mb-4">
                    <label for="cep" class="block text-gray-700">CEP</label>
                    <input type="text" name="cep" id="cep" onblur="pesquisacep(this.value);" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="endereco" class="block text-gray-700">Endereço</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>

                <div class="form-group mb-4">
                    <label for="bairro" class="block text-gray-700">Bairro</label>
                    <input type="text" id="bairro" name="bairro" required>
                </div>

                <div class="form-group mb-4">
                    <label for="cidade" class="block text-gray-700">Cidade</label>
                    <input type="text" id="cidade" name="cidade" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="estado" class="block text-gray-700">Estado</label>
                    <select id="estado" name="estado" class="form-control rounded-lg mt-1 w-full p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="" disabled selected>Selecione o Estado</option>
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
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="quadra" class="block text-gray-700">Quadra</label>
                    <input type="text" id="quadra" name="quadra">
                </div>

                <div class="form-group mb-4">
                    <label for="lote" class="block text-gray-700">Lote</label>
                    <input type="text" id="lote" name="lote">
                </div>

                <div class="form-group mb-4">
                    <label for="numero" class="block text-gray-700">Número</label>
                    <input type="text" id="numero" name="numero">
                </div>

                <div class="form-group mb-4">
                    <label for="complemento" class="block text-gray-700">Complemento</label>
                    <input type="text" id="complemento" name="complemento" required>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Anterior</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Próximo</button>
                </div>
            </div>

            <div id="step-4" class="step-content hidden">
              <h3 class="text-xl font-semibold mb-2 text-gray-700">
                4. Referência
                <span class="block text-sm text-gray-500 font-normal mt-1">
                  (parente de primeiro grau)
                </span>
              </h3>
                
                <div class="form-group mb-4">
                    <label for="referencia_nome" class="block text-gray-700">Nome Completo</label>
                    <input type="text" id="referencia_nome" name="referencia_nome" placeholder="Nome completo" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="referencia_contato" class="block text-gray-700">Celular (Whatsapp)</label>
                    <input type="text" id="referencia_contato" name="referencia_contato" placeholder="(XX- XXXX-XXXX)" required>
                </div>

                <div class="form-group mb-4">
                    <label for="referencia_parentesco" class="block text-gray-700">Grau de parentesco</label>
                    <select id="referencia_parentesco" name="referencia_parentesco" class="form-control rounded-lg mt-1 w-full p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="" disabled selected>Selecione</option>
                        <option value="Pai">Pai</option>
                        <option value="Mãe">Mãe</option>
                        <option value="Marido">Marido</option>
                        <option value="Esposa">Esposa</option>
                        <option value="Filho">Filho</option>
                        <option value="Filha">Filha</option>
                        <option value="Irmão">Irmão</option>
                        <option value="Irmã">Irmã</option>
                        <option value="Tio">Tio</option>
                        <option value="Tia">Tia</option>
                        <option value="Avô">Avô</option>
                        <option value="Avó">Avó</option>
                        <option value="Primo">Primo</option>
                        <option value="Prima">Prima</option>
                        <option value="Sogro">Sogro</option>
                        <option value="Sogra">Sogra</option>
                    </select>
                </div>

                <h3 class="text-xl font-semibold mb-6 mt-6 text-gray-700">4.1 Quem te indicou? </h3>
                
                <div class="form-group mb-4">
                    <label for="indicacao" class="block text-gray-700">Nome completo</label>
                    <input type="text" id="indicacao" name="indicacao" placeholder="Indicado por" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="indicacao_contato" class="block text-gray-700">Celular (Whatsapp)</label>
                    <input type="text" id="indicacao_contato" name="indicacao_contato" placeholder="(XX- XXXX-XXXX)" required>
                </div>
                
                <div class="flex justify-between mt-6">
                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Anterior</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Próximo</button>
                </div>
            </div>

            <div id="step-5" class="step-content hidden">
                <h3 class="text-xl font-semibold mb-6 text-gray-700">5. Ramo de Atuação e Informações de Renda</h3>

                <div class="form-group mb-4">
                    <label for="ramo" class="block text-gray-700">Ramo de Atuação</label>
                    <select id="ramo" name="ramo" class="form-control rounded-lg mt-1 w-full p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="handleRamoChange(); validateField(this)">
                        <option value="" disabled selected>Selecione um ramo</option>
                        <option value="autonomo">Autônomo</option>
                        <option value="uber">Motorista/Entregador App</option>
                        <option value="assalariado">Assalariado</option>
                    </select>
                </div>

                <!-- Campos para Motorista/Entregador App (Uber) -->
                <div id="veiculo-campos" class="hidden mt-4 border border-yellow-400 p-4 rounded bg-yellow-100 text-black">
                    <p class="text-sm font-semibold mb-4">Preencha os campos abaixo se você for motorista ou entregador:</p>
                      <div class="form-group mb-4">
                          <label class="block text-gray-700">Modelo do Veículo</label>
                          <input type="text" name="modelo_veiculo" id="modelo_veiculo" class="form-input w-full uber-obrigatorio" required onblur="validateField(this)">
                      </div>
                      <div class="form-group mb-4">
                          <label class="block text-sm font-medium">Placa</label>
                          <input type="text" name="placa_veiculo" id="placa_veiculo" class="form-input w-full uber-obrigatorio" maxlength="7" placeholder="ABC1234" required onblur="validatePlaca(this)">
                      </div>
                  
                      <div class="form-group mb-4">
                          <label class="block text-sm font-medium">Status do Veículo</label>
                          <select name="status_veiculo" id="status_veiculo" class="form-input w-full uber-obrigatorio" required onchange="handleStatusVeiculoChange(); validateField(this)">
                              <option value="" disabled selected>Selecione</option>
                              <option value="proprio">Próprio</option>
                              <option value="alugado">Alugado</option>
                          </select>
                      </div>
                      <div class="form-group mb-4">
                          <label class="block text-sm font-medium">Valor do Aluguel</label>
                          <input type="text" name="valor_aluguel" id="valor_aluguel" class="form-input w-full uber-obrigatorio" placeholder="R$" min="0" step="0.01" onblur="validateField(this)">
                      </div>

                      <div id="frequencia_aluguel_div" class="form-group mb-4" style="display: none;">
                        <label class="block text-sm font-medium">Frequência do Aluguel</label>
                        <select name="frequencia_aluguel" id="frequencia_aluguel" class="form-input w-full uber-obrigatorio">
                            <option value="" disabled selected>Selecione a frequência</option>
                            <option value="diario">Diário</option>
                            <option value="semanal">Semanal</option>
                            <option value="quinzenal">Quinzenal</option>
                            <option value="mensal">Mensal</option>
                        </select>
                      </div>
   
                      <div class="form-group mb-4">
                          <label class="block text-sm font-medium">Print da Tela de Perfil dos Apps</label>
                          <input type="file" name="print_perfil_app" id="print_perfil_app" onchange="handleFile('print_perfil_app');validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="hidden uber-obrigatorio" required>
                          
                          <button type="button" class="custom-file-upload mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="document.getElementById('print_perfil_app').click()">
                              <i class="fas fa-upload mr-2"></i> Selecionar Arquivo
                          </button>
                      </div>

                      <div class="form-group mb-4">
                          <label class="block text-sm font-medium">Print da Tela de Veículos dos Apps</label>
                          <input type="file" name="print_veiculo_app" id="print_veiculo_app" onchange="handleFile('print_veiculo_app');validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="hidden uber-obrigatorio" required>
                          
                          <button type="button" class="custom-file-upload mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="document.getElementById('print_veiculo_app').click()">
                              <i class="fas fa-upload mr-2"></i> Selecionar Arquivo
                          </button>
                        
                      </div>

                      <div class="form-group mb-4">
                          <label class="block text-sm font-medium">Print dos Ganhos no App (Semana Atual)</label>
                          <input type="file" name="print_ganhos_hoje" id="print_ganhos_hoje" onchange="handleFile('print_ganhos_hoje');validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="hidden uber-obrigatorio" required>
                          
                          <button type="button" class="custom-file-upload mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="document.getElementById('print_ganhos_hoje').click()">
                              <i class="fas fa-upload mr-2"></i> Selecionar Arquivo
                          </button>
                      </div>

                      <div class="form-group mb-4">
                          <label class="block text-sm font-medium">Print dos Ganhos nos Apps (Últimos 30 dias)</label>
                          <input type="file" name="print_ganhos_30dias" id="print_ganhos_30dias" onchange="handleFile('print_ganhos_30dias');validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="hidden uber-obrigatorio" required>
                          
                          <button type="button" class="custom-file-upload mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="document.getElementById('print_ganhos_30dias').click()">
                              <i class="fas fa-upload mr-2"></i> Selecionar Arquivo
                          </button>
                      </div>
                </div>

                <!-- Novos Campos para Autônomo -->
                <div id="autonomo-campos" class="hidden mt-4 border border-blue-400 p-4 rounded bg-blue-100 text-black">
                  <p class="text-sm font-semibold mb-4">Preencha os campos abaixo se você for autônomo:</p>
                    <div class="form-group mb-4">
                      <label class="block text-sm font-medium">Função Exercida</label>
                      <input type="text" name="funcao_autonomo" id="funcao_autonomo" class="form-input w-full autonomo-obrigatorio" required onblur="validateField(this)">
                    </div>
                    <div class="form-group mb-4">
                        <label class="block text-sm font-medium">Nome da Empresa (se houver)</label>
                        <input type="text" name="empresa_autonomo" id="empresa_autonomo" class="form-input w-full autonomo-obrigatorio" onblur="validateField(this)">
                    </div>

                    <div class="form-group mb-4">
                      <label class="block text-sm font-medium">Extrato dos Últimos 90 Dias</label>
                      <input type="file" name="extrato_90dias" id="extrato_90dias" onchange="handleFile('extrato_90dias');validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="hidden autonomo-obrigatorio" required>
                      
                      <button type="button" class="custom-file-upload mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="document.getElementById('extrato_90dias').click()">
                          <i class="fas fa-upload mr-2"></i> Selecionar Arquivo
                      </button>
                    </div>
                    

                    <div id="campo-comprovante-extra-autonomo" class="hidden mt-4">
                      <div class="flex items-start gap-4">
                        <div class="flex-1">
                          <label class="block text-sm font-medium">Outro Comprovante (opcional)</label>
                          <input type="file" name="comprovante_extra_autonomo" id="comprovante_extra_autonomo" onchange="handleFile('comprovante_extra_autonomo');validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="form-input w-full">
                        </div>
                      </div>
                    </div>

                    <span class="block text-sm text-gray-500 mt-2">
                        Para enviar mais de um arquivo, clique em +Comprovante
                    </span>

                    <button type="button" id="btn-mostrar-comprovante-extra-autonomo" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                      + Comprovante
                    </button>
                </div>

                <!-- Novos Campos para Assalariado -->
                <div id="assalariado-campos" class="hidden mt-4 border border-green-400 p-4 rounded bg-green-100 text-black">
                  <p class="text-sm font-semibold mb-4">Preencha os campos abaixo se você for assalariado:</p>
                  
                    <div class="form-group mb-4">
                        <label class="block text-sm font-medium">Função Exercida</label>
                        <input type="text" name="funcao_assalariado" id="funcao_assalariado" class="form-input w-full assalariado-obrigatorio" required onblur="validateField(this)">
                    </div>
                    <div class="form-group mb-4">
                        <label class="block text-sm font-medium">Nome da Empresa</label>
                        <input type="text" name="empresa_assalariado" id="empresa_assalariado" class="form-input w-full assalariado-obrigatorio" required onblur="validateField(this)">
                    </div>

                    <div class="form-group mb-4">
                      <label class="block text-sm font-medium">Contracheque</label>
                      <input type="file" name="contracheque" id="contracheque" onchange="handleFile('contracheque');validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="hidden assalariado-obrigatorio" required>
                      
                      <button type="button" class="custom-file-upload mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="document.getElementById('contracheque').click()">
                          <i class="fas fa-upload mr-2"></i> Selecionar Arquivo
                      </button>
                    </div>

                    <div id="campo-comprovante-extra-assalariado" class="hidden mt-4">
                      
                      <div class="form-group mb-4">
                        <label class="block text-sm font-medium">Outro Comprovante (opcional)</label>
                        <input type="file" name="comprovante_extra_assalariado" id="comprovante_extra_assalariado" onchange="handleFile('comprovante_extra_assalariado');validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif,application/pdf" class="form-input w-full">
                      </div>
      
                    </div>

                    <span class="block text-sm text-gray-500 mt-2">
                        Para enviar mais de um arquivo, clique em +Comprovante
                    </span>

                    <button type="button" id="btn-mostrar-comprovante-extra-assalariado" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                      + Comprovante
                    </button>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Anterior</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Próximo</button>
                </div>
            </div>

            <div id="step-6" class="step-content hidden">
                <h3 class="text-xl font-semibold mb-6 text-gray-700">6. Finalização</h3>

                <div class="form-group mb-4">
                    <label for="valor_desejado" class="block text-gray-700">Valor desejado</label>
                    <input type="text" id="valor_desejado" name="valor_desejado" placeholder="Valor desejado" required>
                </div>
                
                <!-- <div class="form-group mb-4">
                    <label for="parcela_desejada" class="block text-gray-700">Valor máximo da parcela</label>
                    <input type="text" id="parcela_desejada" name="parcela_desejada" placeholder="Valor máximo da parcela" required>
                </div> -->

                <div class="flex justify-between items-center mt-6">
                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Anterior</button>
                    <button type="submit" class="btn btn-submit" id="submit-btn">Cadastrar</button>
                </div>
            </div>            
        </form>

        <div class="mt-8 text-center text-gray-500">
            Já possui cadastro? <a href="../../../app_cliente/index.php" class="text-link">Fazer Login</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>

  let stream; 
  let currentStep = 1;
  const totalSteps = 6;

  async function handleFile(inputId) {
    const input = document.getElementById(inputId);
    const file = input.files[0];

    if (!file) {
        img.src = placeholderSrc;
        input.removeAttribute('data-converted-file');
        if (typeof validateField === 'function') {
            validateField(input);
        }
        return;
    }

    // Lógica de tratamento de PDF
    if (file.type === 'application/pdf') {
       
        input.removeAttribute('data-converted-file');
        if (typeof validateField === 'function') {
            validateField(input);
        }
        Swal.fire({
            icon: 'info',
            title: 'Arquivo PDF selecionado',
            text: 'Pré-visualizações de PDF não são suportadas. O arquivo será enviado.',
            confirmButtonText: 'Ok'
        });
        return;
    }

    // Lógica de tratamento de HEIC (sem pré-visualização)
    if (file.type === 'image/heic' || file.type === 'image/heif') {
        try {
            const convertedBlob = await heic2any({
                blob: file,
                toType: 'image/jpeg',
                quality: 0.8
            });

            // Armazena a Data URL do arquivo convertido para envio posterior
            const reader = new FileReader();
            reader.onload = function(e) {
                input.setAttribute('data-converted-file', e.target.result);
                if (typeof validateField === 'function') {
                    validateField(input);
                }
            };
            reader.readAsDataURL(convertedBlob);
            console.log(`Arquivo HEIC/HEIF de ${inputId} convertido.`);
            
        } catch (error) {
            console.error(`Erro ao converter HEIC/HEIF para ${inputId}:`, error);
           
            input.removeAttribute('data-converted-file');
            if (typeof validateField === 'function') {
                validateField(input);
            }
            Swal.fire({
                icon: 'error',
                title: 'Erro no Anexo',
                text: `Não foi possível processar o arquivo HEIC/HEIF para "${input.name}".`,
                confirmButtonText: 'Ok'
            });
        }
        return;
    }

    // Para outros tipos de arquivo, apenas exibe um placeholder e valida
    console.log(`Arquivo ${inputId} selecionado.`);
    
    input.removeAttribute('data-converted-file');
    if (typeof validateField === 'function') {
        validateField(input);
    }
}
  // Função para alternar a visibilidade do campo extra
  document.getElementById('btn-mostrar-comprovante-extra-autonomo').addEventListener('click', function() {
      const campoExtra = document.getElementById('campo-comprovante-extra-autonomo');
      campoExtra.classList.toggle('hidden'); // Alterna a classe 'hidden'
  });

  // Função para alternar a visibilidade do campo extra
  document.getElementById('btn-mostrar-comprovante-extra-assalariado').addEventListener('click', function() {
      const campoExtra = document.getElementById('campo-comprovante-extra-assalariado');
      campoExtra.classList.toggle('hidden'); // Alterna a classe 'hidden'
  });

  async function tirarFoto() {
      const cameraPreview = document.getElementById('cameraPreview');
      const photoCanvas = document.getElementById('photoCanvas');
      const photoDisplay = document.getElementById('foto');
      const fotoUsuarioInput = document.getElementById('foto_usuario');

      if (!stream) { // Se a câmera não estiver ativa, solicita acesso
          try {
              // Solicita acesso à câmera do usuário
              stream = await navigator.mediaDevices.getUserMedia({ video: true });
              cameraPreview.srcObject = stream;
              cameraPreview.style.display = 'block'; // Mostra o vídeo da câmera
          } catch (err) {
              console.error("Erro ao acessar a câmera:", err);
              alert("Não foi possível acessar a câmera. Verifique as permissões.");
              return;
          }
      } else { // Se a câmera já estiver ativa, tira a foto
          const context = photoCanvas.getContext('2d');
          photoCanvas.width = cameraPreview.videoWidth;
          photoCanvas.height = cameraPreview.videoHeight;
          context.drawImage(cameraPreview, 0, 0, photoCanvas.width, photoCanvas.height);

          // Converte a imagem do canvas para Base64
          const imageDataUrl = photoCanvas.toDataURL('image/png');
          
          // Exibe a imagem capturada
          photoDisplay.src = imageDataUrl;
          
          // Armazena a imagem Base64 no input hidden
          fotoUsuarioInput.value = imageDataUrl;

          // Opcional: Para a câmera após tirar a foto
          if (stream) {
              stream.getTracks().forEach(track => track.stop());
              stream = null;
              cameraPreview.srcObject = null;
              cameraPreview.style.display = 'none'; // Esconde o vídeo da câmera
          }

          // Chama a função de validação, se houver
          validateField(fotoUsuarioInput);
      }
  }

  // --- FUNÇÕES DE MÁSCARAS ---
  function setupMasks() {
      $('#cpf').mask('000.000.000-00');
      $('#telefone').mask('(00) 00000-0000');
      $('#cep').mask('00000-000');
      $('#data_nasc').mask('00/00/0000');
      $('#referencia_contato').mask('(00) 00000-0000');
      $('#indicacao_contato').mask('(00) 00000-0000');

      // Múltiplas máscaras para a placa (Mercosul e Antiga)
      var plateMasks = ['AAA-0000', 'AAA0A00'];
      $('#placa_veiculo').mask(plateMasks);

      $('#valor_desejado').mask('000.000.000.000.000,00', {reverse: true});
      // $('#parcela_desejada').mask('000.000.000.000.000,00', {reverse: true});
      $('#valor_aluguel').mask('000.000.000.000.000,00', {reverse: true});
  }

  // Inicializa as máscaras quando o documento estiver pronto
  $(document).ready(function() {
      setupMasks();
      showStep(currentStep);
      handleRamoChange();
      handleStatusVeiculoChange();
  });

  // --- FUNÇÕES DE VALIDAÇÃO ---
  function validateEmail(email) {
      const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(String(email).toLowerCase());
  }

  function validateCPF(input) {
      const cpf = input.value.replace(/\D/g, '');
      if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
          markInvalid(input, 'CPF inválido.');
          return false;
      }

      let sum = 0;
      let remainder;

      for (let i = 1; i <= 9; i++) {
          sum = sum + parseInt(cpf.substring(i - 1, i)) * (11 - i);
      }
      remainder = (sum * 10) % 11;

      if ((remainder === 10) || (remainder === 11)) {
          remainder = 0;
      }
      if (remainder !== parseInt(cpf.substring(9, 10))) {
          markInvalid(input, 'CPF inválido.');
          return false;
      }

      sum = 0;
      for (let i = 1; i <= 10; i++) {
          sum = sum + parseInt(cpf.substring(i - 1, i)) * (12 - i);
      }
      remainder = (sum * 10) % 11;

      if ((remainder === 10) || (remainder === 11)) {
          remainder = 0;
      }
      if (remainder !== parseInt(cpf.substring(10, 11))) {
          markInvalid(input, 'CPF inválido.');
          return false;
      }

      markValid(input);
      return true;
  }

  // NOVA FUNÇÃO DE VALIDAÇÃO DE DATA DE NASCIMENTO
  function validateDataNascimento(input) {
      const dateString = input.value;
      const regex = /^\d{2}\/\d{2}\/\d{4}$/;

      if (!regex.test(dateString)) {
          markInvalid(input, 'Data inválida. Use o formato DD/MM/AAAA.');
          return false;
      }

      const parts = dateString.split('/');
      const day = parseInt(parts[0], 10);
      const month = parseInt(parts[1], 10);
      const year = parseInt(parts[2], 10);

      // Validações básicas de intervalo
      if (day < 1 || day > 31 || month < 1 || month > 12 || year < 1900 || year > new Date().getFullYear()) {
          markInvalid(input, 'Data inválida. Verifique dia, mês ou ano.');
          return false;
      }

      const date = new Date(year, month - 1, day); // Mês é 0-indexed (0=Jan, 11=Dez)
      // Verifica se a data é realmente uma data válida (ex: 31/02/2023 não é válida)
      if (date.getFullYear() !== year || date.getMonth() + 1 !== month || date.getDate() !== day) {
          markInvalid(input, 'Data inválida. Verifique o dia/mês/ano.');
          return false;
      }

      // Validação de idade mínima (ex: mínimo 18 anos)
      const today = new Date();
      let age = today.getFullYear() - year;
      const m = today.getMonth() - (month - 1); // Mês atual - (mês de nasc - 1)
      if (m < 0 || (m === 0 && today.getDate() < day)) {
          age--;
      }
      if (age < 18) { // Defina a idade mínima necessária
          markInvalid(input, 'Você deve ter pelo menos 18 anos.');
          return false;
      }

      markValid(input);
      return true;
  }

  function validatePassword(input) {
      const senha = $('#senha').val();
      const confSenha = $('#conf_senha').val();

      if (senha === '' || confSenha === '') {
          markInvalid(input, 'Por favor, preencha ambos os campos de senha.');
          return false;
      }
      if (senha.length < 6) {
          markInvalid($('#senha')[0], 'A senha deve ter no mínimo 6 caracteres.');
          return false;
      }
      if (senha !== confSenha) {
          markInvalid($('#conf_senha')[0], 'As senhas não coincidem.');
          return false;
      }
      markValid($('#senha')[0]);
      markValid($('#conf_senha')[0]);
      return true;
  }

  function validatePlaca(input) {
      const placa = input.value.toUpperCase();
      const re = /^[A-Z]{3}\d{4}$|^[A-Z]{3}\d[A-Z]\d{2}$/;
      if (!re.test(placa)) {
          markInvalid(input, 'Formato de placa inválido (ex: ABC1234 ou ABC1A23).');
          return false;
      }
      markValid(input);
      return true;
  }

  // Função para marcar um campo como inválido e exibir mensagem de erro
  function markInvalid(input, message = '') {
      input.classList.add('invalid');
      input.style.borderColor = '#fc8181';

      let errorMessageElement = input.nextElementSibling;
      // Verifica se o próximo irmão é um elemento de mensagem de erro, ou cria um
      if (!errorMessageElement || !errorMessageElement.classList.contains('error-message')) {
          errorMessageElement = document.createElement('p');
          errorMessageElement.classList.add('error-message', 'text-red-400', 'text-sm', 'mt-1');
          input.parentNode.insertBefore(errorMessageElement, input.nextSibling);
      }
      errorMessageElement.textContent = message;
      errorMessageElement.classList.remove('hidden');
  }

  // Função para marcar um campo como válido e ocultar mensagem de erro
  function markValid(input) {
      input.classList.remove('invalid');
      input.style.borderColor = '#4a5568'; // Borda padrão

      let errorMessageElement = input.nextElementSibling;
      if (errorMessageElement && errorMessageElement.classList.contains('error-message')) {
          errorMessageElement.classList.add('hidden');
          errorMessageElement.textContent = '';
      }
  }

  // Validação genérica de campo
  function validateField(input) {
    const type = input.type;
    const id = input.id;
    let isValid = true;
    let errorMessage = '';

    const statusVeiculoSelect = document.getElementById('status_veiculo');
    
    // VERIFICAÇÃO ADICIONADA: Campos de aluguel só são validados se o status for "alugado"
    if (statusVeiculoSelect && statusVeiculoSelect.value !== 'alugado' && (id === 'valor_aluguel' || id === 'frequencia_aluguel')) {
        markValid(input);
        return true;
    }

    // Verifica campos obrigatórios primeiro
    if (input.hasAttribute('required') && input.value.trim() === '') {
        isValid = false;
        errorMessage = 'Este campo é obrigatório.';
    } else {
        // Validações específicas
        if (id === 'email') {
            if (!validateEmail(input.value)) {
                isValid = false;
                errorMessage = 'Email inválido.';
            }
        } else if (id === 'cpf') {
            isValid = validateCPF(input);
            if (!isValid) errorMessage = input.getAttribute('data-invalid');
        } else if (id === 'data_nasc') {
            isValid = validateDataNascimento(input);
            if (!isValid) {
                let errorMessageElement = input.nextElementSibling;
                if (errorMessageElement && errorMessageElement.classList.contains('error-message')) {
                    errorMessage = errorMessageElement.textContent;
                } else {
                    errorMessage = 'Data de Nascimento inválida.';
                }
            }
        } else if (id === 'telefone' || id === 'referencia_contato') {
            if (input.value.replace(/\D/g, '').length < 11) {
                isValid = false;
                errorMessage = 'Telefone inválido (mínimo 11 dígitos incluindo DDD).';
            }
        } else if (id === 'senha' || id === 'conf_senha') {
            isValid = validatePassword(input);
            if (!isValid) errorMessage = input.getAttribute('data-invalid');
        } else if (id === 'placa_veiculo') {
            isValid = validatePlaca(input);
            if (!isValid) errorMessage = input.getAttribute('data-invalid');
        } else if (type === 'file') {
            if (input.hasAttribute('required') && input.files.length === 0) {
                isValid = false;
                errorMessage = 'Por favor, anexe o arquivo.';
            }
        } else if (type === 'number') {
            if (input.hasAttribute('required') && input.value.trim() === '') {
                isValid = false;
                errorMessage = 'Este campo é obrigatório.';
            } else if (input.min && parseFloat(input.value) < parseFloat(input.min)) {
                isValid = false;
                errorMessage = `O valor mínimo é ${input.min}.`;
            }
        } else if (input.tagName === 'SELECT' && input.value === '') {
            isValid = false;
            errorMessage = 'Por favor, selecione uma opção.';
        }
    }

    if (isValid) {
        markValid(input);
    } else {
        markInvalid(input, errorMessage);
    }
    return isValid;
}

  // Validação da etapa atual antes de prosseguir
  function validateCurrentStep() {
    console.log(`--- Validando Etapa ${currentStep} ---`);
    const currentStepElement = document.getElementById(`step-${currentStep}`);
    const inputs = currentStepElement.querySelectorAll('[required], .uber-obrigatorio, .autonomo-obrigatorio, .assalariado-obrigatorio');
    let allValid = true;
    let firstInvalidField = null;
    let invalidMessages = [];

    const ramoSelect = document.getElementById('ramo');
    const selectedRamo = ramoSelect ? ramoSelect.value : '';
    const statusVeiculoSelect = document.getElementById('status_veiculo');
    const selectedStatusVeiculo = statusVeiculoSelect ? statusVeiculoSelect.value : '';

    inputs.forEach(input => {
        let shouldValidate = true;

        const isUberField = input.classList.contains('uber-obrigatorio');
        const isAutonomoField = input.classList.contains('autonomo-obrigatorio');
        const isAssalariadoField = input.classList.contains('assalariado-obrigatorio');
        // VERIFICAÇÃO ADICIONADA: Lógica condicional para campos de aluguel
        const isAluguelField = (input.id === 'valor_aluguel' || input.id === 'frequencia_aluguel');

        if (isUberField && selectedRamo !== 'uber') {
            shouldValidate = false;
        } else if (isAutonomoField && selectedRamo !== 'autonomo') {
            shouldValidate = false;
        } else if (isAssalariadoField && selectedRamo !== 'assalariado') {
            shouldValidate = false;
        } else if (isAluguelField && selectedStatusVeiculo !== 'alugado') {
            shouldValidate = false;
        }

        if (!shouldValidate) {
            markValid(input);
            console.log(`Pulando validação para (ramo diferente): ${input.id || input.name}`);
            return;
        }

        const isValidField = validateField(input);
        if (!isValidField) {
            allValid = false;
            const message = input.getAttribute('data-invalid') || 'Campo inválido ou vazio.';
            let fieldName = input.previousElementSibling ? input.previousElementSibling.textContent.replace(':', '').trim() : input.placeholder || input.id || input.name;
            invalidMessages.push(`${fieldName}: ${message}`);
            if (!firstInvalidField) {
                firstInvalidField = input;
            }
        }
    });

    // Validação específica para o Step 2 (confirmação de senha)
    if (currentStep === 2) {
        const senha = document.getElementById('senha');
        const confSenha = document.getElementById('conf_senha');
        if (senha.value !== confSenha.value) {
            markInvalid(confSenha, 'As senhas não coincidem.');
            invalidMessages.push('Confirmação de Senha: As senhas não coincidem.');
            allValid = false;
            if (!firstInvalidField) firstInvalidField = confSenha;
        } else {
            markValid(confSenha);
        }
    }

    if (!allValid) {
        let errorMessageHTML = invalidMessages.map(msg => `<li>${msg}</li>`).join('');
        Swal.fire({
            icon: 'error',
            title: 'Erro de Validação!',
            html: `Por favor, corrija os seguintes campos antes de prosseguir:<ul class="text-left mt-2">${errorMessageHTML}</ul>`,
            confirmButtonText: 'Ok',
            customClass: {
                popup: 'swal2-responsive-popup'
            }
        });
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
        console.log("Validação falhou para a etapa atual. Campos inválidos:", invalidMessages);
        return false;
    }
    console.log("Validação bem-sucedida para a etapa atual.");
    return true;
}


  // --- NAVEGAÇÃO ENTRE ETAPAS ---
// Function to show a specific step
function showStep(stepNumber) {
    console.log(`Tentando mostrar a etapa: ${stepNumber}`);

    // Hide all steps first
    const steps = document.querySelectorAll('.step-content');
    steps.forEach(step => {
        step.classList.remove('active');
    });

    // Show the desired step
    const stepToShow = document.getElementById(`step-${stepNumber}`);
    if (stepToShow) {
        stepToShow.classList.add('active');
        console.log(`Removendo 'hidden' e adicionando 'active' para a etapa ${stepNumber}.`);
    }

    // Update progress dots
    const dots = document.querySelectorAll('.progress-dot');
    dots.forEach((dot, index) => {
        if (index + 1 === stepNumber) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });

    currentStep = stepNumber;
}

// Function to go to the next step
function nextStep() {
    const currentStepDiv = document.getElementById(`step-${currentStep}`);
    const inputs = currentStepDiv.querySelectorAll('input[required], select[required]');
    let allValid = true;
    const invalidFields = [];

    // Validation logic (simplified for brevity)
    inputs.forEach(input => {
        let isValid = false;
        if (input.type === 'file') {
            isValid = input.files.length > 0;
        } else if (input.type === 'email') {
            isValid = validateEmail(input.value);
        } else {
            isValid = input.value.trim() !== '';
        }

        if (!isValid) {
            allValid = false;
            invalidFields.push(input.name);
        }
    });

    if (allValid) {
        console.log(`Validação bem-sucedida para a etapa atual.`);
        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        }
    } else {
        console.log(`Validação falhou para a etapa atual. Campos inválidos: ${invalidFields.join(', ')}`);
        Swal.fire({
            icon: 'error',
            title: 'Erro na Validação',
            html: `Por favor, preencha todos os campos obrigatórios:<br><b>${invalidFields.join(', ')}</b>`,
            confirmButtonText: 'Ok'
        });
    }
}

  function prevStep() {
      if (currentStep > 1) {
          currentStep--;
          showStep(currentStep);
      }
  }


  // --- FUNÇÃO DE PESQUISA DE CEP ---
  function pesquisacep(valor) {
      var cep = valor.replace(/\D/g, '');

      if (cep != "") {
          var validacep = /^[0-9]{8}$/;

          if (validacep.test(cep)) {
              document.getElementById('endereco').value = "...";
              document.getElementById('bairro').value = "...";
              document.getElementById('cidade').value = "...";
              document.getElementById('estado').value = "...";

              var script = document.createElement('script');
              script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=callbackMeuCep';
              document.head.appendChild(script);

          } else {
              markInvalid(document.getElementById('cep'), 'Formato de CEP inválido.');
              limpa_formulário_cep();
          }
      } else {
          markValid(document.getElementById('cep'));
          limpa_formulário_cep();
      }
  }

  function limpa_formulário_cep() {
      document.getElementById('endereco').value = "";
      document.getElementById('bairro').value = "";
      document.getElementById('cidade').value = "";
      document.getElementById('estado').value = "";
      markValid(document.getElementById('endereco'));
      markValid(document.getElementById('bairro'));
      markValid(document.getElementById('cidade'));
      markValid(document.getElementById('estado'));
  }

  function callbackMeuCep(conteudo) {
      if (!("erro" in conteudo)) {
          document.getElementById('endereco').value = (conteudo.logradouro);
          document.getElementById('bairro').value = (conteudo.bairro);
          document.getElementById('cidade').value = (conteudo.localidade);
          document.getElementById('estado').value = (conteudo.uf);
          markValid(document.getElementById('cep'));
          markValid(document.getElementById('endereco'));
          markValid(document.getElementById('bairro'));
          markValid(document.getElementById('cidade'));
          markValid(document.getElementById('estado'));
      } else {
          markInvalid(document.getElementById('cep'), 'CEP não encontrado.');
          limpa_formulário_cep();
      }
  }

  // --- LÓGICA CONDICIONAL PARA CAMPOS DE RAMO DE ATUAÇÃO ---
  function handleRamoChange() {
      const ramoSelect = document.getElementById('ramo');
      const selectedRamo = ramoSelect.value;

      const veiculoCampos = document.getElementById('veiculo-campos');
      const autonomoCampos = document.getElementById('autonomo-campos');
      const assalariadoCampos = document.getElementById('assalariado-campos');

      // Seleciona todos os campos que podem ser condicionalmente obrigatórios
      const allConditionalInputs = document.querySelectorAll('.uber-obrigatorio, .autonomo-obrigatorio, .assalariado-obrigatorio');

      // Esconde todos os blocos e remove 'required' de todos os campos condicionais
      veiculoCampos.classList.add('hidden');
      autonomoCampos.classList.add('hidden');
      assalariadoCampos.classList.add('hidden');

      allConditionalInputs.forEach(input => {
          input.removeAttribute('required');
          input.value = ''; // Limpa o valor
          markValid(input); // Marca como válido
      });

      // Mostra o bloco correto e define 'required' para seus campos
      if (selectedRamo === 'uber') {
          veiculoCampos.classList.remove('hidden');
          veiculoCampos.querySelectorAll('.uber-obrigatorio').forEach(input => {
              input.setAttribute('required', 'required');
              validateField(input);
          });
          handleStatusVeiculoChange(); // Garante que o campo de aluguel esteja correto
      } else if (selectedRamo === 'autonomo') {
          autonomoCampos.classList.remove('hidden');
          autonomoCampos.querySelectorAll('.autonomo-obrigatorio').forEach(input => {
              input.setAttribute('required', 'required');
              validateField(input);
          });
      } else if (selectedRamo === 'assalariado') {
          assalariadoCampos.classList.remove('hidden');
          assalariadoCampos.querySelectorAll('.assalariado-obrigatorio').forEach(input => {
              input.setAttribute('required', 'required');
              validateField(input);
          });
      }
  }

  function handleStatusVeiculoChange() {
    const statusVeiculoSelect = document.getElementById('status_veiculo');
    const valorAluguelInput = document.getElementById('valor_aluguel');
    const frequenciaAluguelSelect = document.getElementById('frequencia_aluguel');

    // É importante garantir que os elementos existem antes de manipulá-los
    if (!statusVeiculoSelect || !valorAluguelInput || !frequenciaAluguelSelect) {
        return;
    }

    const valorAluguelDiv = valorAluguelInput.closest('div.form-group');
    const frequenciaAluguelDiv = frequenciaAluguelSelect.closest('div.form-group');

    if (statusVeiculoSelect.value === 'alugado') {
        valorAluguelDiv.style.display = 'block';
        frequenciaAluguelDiv.style.display = 'block'; // Mostra o novo campo
        valorAluguelInput.setAttribute('required', 'required');
        frequenciaAluguelSelect.setAttribute('required', 'required'); // Torna o novo campo obrigatório
    } else {
        valorAluguelDiv.style.display = 'none';
        frequenciaAluguelDiv.style.display = 'none'; // Esconde o novo campo
        valorAluguelInput.removeAttribute('required');
        frequenciaAluguelSelect.removeAttribute('required'); // Remove a obrigatoriedade
        valorAluguelInput.value = '';
        frequenciaAluguelSelect.value = ''; // Limpa o valor do novo campo
        // Garante que o estado de validação seja limpo
        markValid(valorAluguelInput);
        markValid(frequenciaAluguelSelect);
    }
}

  // --- TOGGLE VISIBILIDADE DA SENHA ---
  function togglePasswordVisibility(fieldId, buttonId) {
      const passwordField = document.getElementById(fieldId);
      const toggleButton = document.getElementById(buttonId);

      if (passwordField.type === 'password') {
          passwordField.type = 'text';
          toggleButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500"><path stroke-linecap="round" stroke-linejoin="round" d="M3.988 5.862a1.01 1.01 0 0 0 .6-.967V4.774A.75.75 0 0 1 5.373 4h13.254a.75.75 0 0 1 .715.774v.121a1.01 1.01 0 0 0 .6.967l1.353.451c.328.109.52.433.52.779V9.5a2.25 2.25 0 0 1-2.25 2.25h-5.467A.75.75 0 0 0 12 12.75h-.008a.75.75 0 0 0-.75.75v.375c0 .414-.336.75-.75.75H10.5a.75.75 0 0 1-.75-.75v-.375a.75.75 0 0 0-.75-.75h-.008a.75.75 0 0 0-.75.75v.375c0 .414-.336.75-.75.75H6.75A2.25 2.25 0 0 1 4.5 9.5V7.162c0-.346.192-.67.52-.779l1.353-.451Zm-.611 11.233a1.01 1.01 0 0 0 .6-.967V17.274A.75.75 0 0 1 5.373 16h13.254a.75.75 0 0 1 .715.774v.121a1.01 1.01 0 0 0 .6.967l1.353.451c.328.109.52.433.52.779V21.5a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 21.5v-2.338c0-.346.192-.67.52-.779l1.353-.451Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>`;
      } else {
          passwordField.type = 'password';
          toggleButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>`;
      }
  }

  // --- SUBMISSÃO DO FORMULÁRIO (AJAX) ---
  $('#form').submit(function(event) {
  event.preventDefault(); // Impede o envio padrão do formulário

  if (!validateCurrentStep()) {
      return; // Se a validação da última etapa falhar, não prossegue.
  }

  // Se tudo validado, prepare os dados do formulário
  var formData = new FormData(this);
  var $submitBtn = $('#submit-btn');

  $.ajax({
      url: '../../../painel/paginas/clientes/salvar.php', 
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',

      beforeSend: function() {
        // Exibe a sobreposição de loading e desabilita o botão
        $('#loading-indicator').css('display', 'flex');
        $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
      },
      
      complete: function() {
        // Esconde a sobreposição de loading e habilita o botão
        $('#loading-indicator').css('display', 'none');
        $submitBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
      },
      
      success: function(response) {
        if (response.success) { 
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: response.message, 
                confirmButtonText: 'Ok'
            }).then(() => {
                
                window.location.href = '/'; 
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro no Cadastro!',
                text: response.message, 
                confirmButtonText: 'Ok'
            });
        }
      },
      error: function(xhr, status, error) {
          // Este bloco 'error' é importante para depurar problemas de comunicação HTTP
          console.error("Erro AJAX:", xhr.responseText); // xhr.responseText conterá a resposta bruta (HTML, JSON de erro, etc.)
          Swal.fire({
              icon: 'error',
              title: 'Erro de Comunicação!',
              text: 'Não foi possível conectar ao servidor ou houve um erro inesperado. Detalhes: ' + xhr.responseText,
              confirmButtonText: 'Ok'
          });
      }
  });
  });
  </script>
</body>
</html>
