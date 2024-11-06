<?php
// include 'lconfig.php';;
//  include 'verifica_sessao.php';

session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($nome && $email && $senha) {
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

function getUfs() {
    $url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function getCidades($uf) {
    $url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $uf . '/municipios';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Página Inicial</title>

    <link rel="stylesheet" href="login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>

    <center><h1 class="pt-5">Bem vindo ao sistema de abertura de chamados</h1></center>
    <form id="authForm" class="wrapper pt-5" action="login.php" method="POST">
        <div class="InUp">
            <input id="SignIn" type="radio" name="tab" checked onclick="toggleForms()">
            <label for="SignIn">Conecte-se</label>

            <input id="SignUp" type="radio" name="tab" onclick="toggleForms()">
            <label for="SignUp">Registrar</label>
        </div>

        <div class="login">
            <div>
                <span class="mx-5"><b>Login</b></span>
                <div class="d-flex col">
                    <img style="max-width: 50px !important; max-height: 50px !important;" src="image/login.png" alt="" class="p-2">
                    <input id="emailLogin" type="email" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <!-- <input id="emailLogin" type="email" name="emailLogin" placeholder="E-mail" required> -->
            </div>

            <div>
                <span class="mx-5"><b>Senha</b></span><br>
                <div class="d-flex col">
                    <img  style="max-width: 50px !important; max-height: 50px !important;" src="image/padlock.png" alt="" class="p-2">
                    <input id="passwordLogin" type="password" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">

                </div>
                <!-- <input id="passwordLogin" type="password" name="passwordLogin" placeholder="Senha" required minlength="8"> -->
                <!-- <img width="25" src="image/password.png" alt=""> -->
            </div>

            <div>
                <button type="button" class="btn btn-outline-success" onclick="validateLogin()">Entrar</button>
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
                <button class="btn btn-outline-success" type="submit" name="submit">Criar conta</button>
            </div>
        </div>
    </form>
</body>
</html>

<script>
    async function carregarCidades() {
        const uf = $('#uf').val();

        const response = await fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ uf: uf })
        });

        const cidades = await response.json();
        const cidadeSelect = $('#cidade');
        cidadeSelect.empty();

        cidades.forEach(function(cidade) {
            const option = $('<option></option>').val(cidade.nome).text(cidade.nome);
            cidadeSelect.append(option);
        });
    }

    function toggleForms() {
        const isSignIn = $('#SignIn').is(':checked');
        $('.login').toggle(isSignIn);
        $('.register').toggle(!isSignIn);
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
        const email = $('#emailLogin').val();
        const password = $('#passwordLogin').val();

        if (!validateEmail(email)) {
            alert('Por favor, insira um e-mail válido.');
            return;
        }

        if (password.length < 8) {
            alert('A senha deve ter pelo menos 8 caracteres.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'api.php',
            data: { email: email, password: password },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = 'abertura.php'; 
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); 
                alert('Erro ao processar a requisição: ' + error);
            }
        });
    }



    function validateRegister() {
        const name = $('#name').val();
        const date = $('#date').val();
        const email = $('#emailRegister').val();
        const telephone = $('#telephone').val();
        const whatsapp = $('#whatsapp').val();
        const password = $('#password').val();
        const confirmPassword = $('#confirmPassword').val();
        const uf = $('#uf').val();
        const cidade = $('#cidade').val();

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
            window.location.href = '';
        } else {
            alert('Por favor, preencha todos os campos.');
        }
    }

    function formatPhone(input) {
        const val = input.value.replace(/\D/g, '')
            .replace(/^(\d{2})(\d)/, '($1) $2')
            .replace(/(\d)(\d{4})$/, '$1 $2')
            .replace(/(\d{5})(\d)/, '$1-$2');
        $(input).val(val);
    }

    $(document).ready(function() {
        toggleForms();

        const today = new Date();
        const maxDate = new Date(today.setFullYear(today.getFullYear() - 18));
        $('#date').attr('max', maxDate.toISOString().split('T')[0]);
    });

    
</script>