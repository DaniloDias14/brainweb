<?php
include_once('lconfig.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Exibir os dados para depuração
    echo "Nome: $nome, Email: $email"; // Para verificar se os dados estão sendo recebidos

    if ($nome && $email && $senha) {
        // Prepara a consulta SQL
        $stmt = $conexao->prepare("INSERT INTO registrar (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha);
        
        if ($stmt->execute()) {
            echo "Conta criada com sucesso!";
        } else {
            echo "Erro: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}

// Puxar estados
function getUfs() {
    $url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Cidades de acordo com o estado
function getCidades($uf) {
    $url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $uf . '/municipios';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Função para puxar cidades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uf'])) {
    $uf = $_POST['uf'];
    $cidades = getCidades($uf);
    echo json_encode($cidades);
    exit;
}

$ufs = getUfs();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <script>
        async function carregarCidades() {
            const ufSelect = document.getElementById('uf');
            const uf = ufSelect.value;

            const response = await fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    uf: uf
                })
            });

            const cidades = await response.json();
            const cidadeSelect = document.getElementById('cidade');
            cidadeSelect.innerHTML = '';

            cidades.forEach(function(cidade) {
                const option = document.createElement('option');
                option.value = cidade.nome;
                option.textContent = cidade.nome;
                cidadeSelect.appendChild(option);
            });
        }

        function toggleForms() {
            const signInForm = document.querySelector('.login');
            const registerForm = document.querySelector('.register');
            const isSignIn = document.getElementById('SignIn').checked;

            signInForm.style.display = isSignIn ? 'block' : 'none';
            registerForm.style.display = isSignIn ? 'none' : 'block';
        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(String(email).toLowerCase());
        }

        function validateAge(dateOfBirth) {
            const today = new Date();
            const birthDate = new Date(dateOfBirth);
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age >= 18;
        }

        function validateLogin() {
            const email = document.getElementById('emailLogin').value;
            const password = document.getElementById('passwordLogin').value;

            if (!validateEmail(email)) {
                alert('Por favor, insira um e-mail válido.');
                return;
            }

            if (password.length < 8) {
                alert('A senha deve ter pelo menos 8 caracteres.');
                return;
            }

            if (email && password) {
                window.location.href = ''; // Adicione a URL de redirecionamento aqui
            } else {
                alert('Por favor, preencha todos os campos.');
            }
        }

        function validateRegister() {
            const name = document.getElementById('name').value;
            const date = document.getElementById('date').value;
            const email = document.getElementById('emailRegister').value;
            const telephone = document.getElementById('telephone').value;
            const whatsapp = document.getElementById('whatsapp').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const uf = document.getElementById('uf').value;
            const cidade = document.getElementById('cidade').value;

            if (!validateEmail(email)) {
                alert('Por favor, insira um e-mail válido.');
                return;
            }

            if (!validateAge(date)) {
                alert('Você deve ter 18 anos ou mais para se registrar.');
                return;
            }

            if (password.length < 8) {
                alert('A senha deve ter pelo menos 8 caracteres.');
                return;
            }

            if (password !== confirmPassword) {
                alert('As senhas não coincidem. Por favor, tente novamente.');
                return;
            }

            if (name && date && email && telephone && whatsapp && password && confirmPassword && uf && cidade) {
                window.location.href = ''; // Adicione a URL de redirecionamento aqui
            } else {
                alert('Por favor, preencha todos os campos.');
            }
        }

        function formatPhone(input) {
            input.value = input.value
                .replace(/\D/g, '') // Remove caracteres não numéricos
                .replace(/^(\d{2})(\d)/, '($1) $2') // Formata código de área
                .replace(/(\d)(\d{4})$/, '$1 $2') // Formata parte do número
                .replace(/(\d{5})(\d)/, '$1-$2'); // Formata parte final do número
        }

        window.onload = function() {
            toggleForms(); // Chama a função ao carregar a página

            // Define a data máxima de nascimento
            const today = new Date();
            const maxDate = new Date(today.setFullYear(today.getFullYear() - 18));
            const dateInput = document.getElementById('date');
            dateInput.max = maxDate.toISOString().split('T')[0]; // Formato YYYY-MM-DD
        };
    </script>
    <link rel="stylesheet" href="login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <form id="authForm" class="wrapper" action="login.php" method="POST">
        <div class="InUp">
            <input id="SignIn" type="radio" name="tab" checked onclick="toggleForms()">
            <label for="SignIn">Conecte-se</label>

            <input id="SignUp" type="radio" name="tab" onclick="toggleForms()">
            <label for="SignUp">Registrar</label>
        </div>

        <div class="login">
            <div>
                <img width="25" src="image/login.png" alt="">
                <input id="emailLogin" type="email" name="emailLogin" placeholder="E-mail" required>
            </div>
            <div>
                <img width="25" src="image/padlock.png" alt="">
                <input id="passwordLogin" type="password" name="passwordLogin" placeholder="Senha" required minlength="8">
                <img width="25" src="image/password.png" alt="">
                <button type="button" onclick="validateLogin()">Entrar</button>
            </div>
        </div>

        <div class="register" style="display: none;">
            <div class="name">
                <label for="name">Nome completo</label>
                <input type="text" id="name" name="nome" required>
            </div>
            <div class="date">
                <label for="date">Data de nascimento</label>
                <input type="date" id="date" max="" required>
            </div>
            <div class="emailRegister">
                <label for="emailRegister">E-mail</label>
                <input id="emailRegister" type="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="telephone">
                <label for="telephone">Telefone</label>
                <input type="text" id="telephone" placeholder="(00) 0 0000-0000" minlength="15" maxlength="15"
                    oninput="formatPhone(this)" required>
            </div>
            <div>
                <label for="whatsapp">WhatsApp</label>
                <input type="text" id="whatsapp" placeholder="(00) 0 0000-0000" minlength="15" maxlength="15"
                    oninput="formatPhone(this)" required>
            </div>

            <div>
                <label for="password">Senha</label>
                <input type="password" id="password" name="senha" required minlength="8">
            </div>
            <div>
                <label for="confirmPassword">Confirme a senha</label>
                <input type="password" id="confirmPassword" required minlength="8">
            </div>
            <section>
                <label for="uf">Estado</label>
                <select id="uf" onchange="carregarCidades()" required>
                    <option></option>
                    <?php foreach ($ufs as $uf): ?>
                        <option value="<?= $uf['sigla'] ?>"><?= $uf['sigla'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="cidade">Cidade</label>
                <select id="cidade" required>
                    <option value=""></option>
                </select>
            </section>
            <div>
                <button type="submit" name="submit">Criar conta</button>
            </div>
        </div>
    </form>
</body>
</html>
