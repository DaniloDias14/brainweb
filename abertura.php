
<!-- <?php include 'verifica_sessao.php';?> -->
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abertura de Chamados - TI</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="amem.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <h1>Abertura de Chamados</h1>
        <form id="chamadoForm">
            <div class="label-container d-flex row">
                <div class="col-md-4">
                    <label for="setor">Setor</label>
                    <select id="setor" required>
                        <option value="">--Selecione um setor--</option>
                        <option value="suporte">Suporte Técnico</option>
                        <option value="rede">Rede</option>
                        <option value="desenvolvimento">Desenvolvimento</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="nome">Nome</label>
                    <select id="nome" required>
                        <option value="">--Selecione um nome--</option>
                        <option value="usuario1">Usuário 1</option>
                        <option value="usuario2">Usuário 2</option>
                        <option value="usuario3">Usuário 3</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" required oninput="formatPhone(this)">
                </div>
            </div>
            <div class="button-container">
                <button type="button" onclick="openPopup()" class="btn btn-outline-success">Novo Usuário</button>
            </div>

            <div class="d-flex row p-3">
                <label for="descricao">Descrição do Problema</label>
                <textarea id="descricao" required></textarea>
    
                <label for="anexo">Anexar arquivos</label>
                <input id="anexo" class="form-control" type="file" id="formFile" accept="image/*">
                <!-- <input type="file" id="anexo" accept="image/*" multiple> -->
                <!-- <div id="imagePreview" class="image-preview"></div> -->
            </div><br>
            <div class="p-1">
                <center><button type="submit" id="enviaChamado" class="btn btn-outline-success">Enviar Chamado</button></center>
            </div>
        </form>
    </div>

    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2>Cadastrar Novo Usuário</h2>
            <form id="usuarioForm">
                <fieldset>
                    <label for="novoSetor">Setor</label>
                    <select id="novoSetor" class="input-field" required>
                        <option value="">--Selecione um setor--</option>
                        <option value="suporte">Suporte Técnico</option>
                        <option value="rede">Rede</option>
                        <option value="desenvolvimento">Desenvolvimento</option>
                    </select>

                    <label for="novoNome">Nome</label>
                    <input type="text" id="novoNome" class="input-field" required>

                    <label for="novoTelefone">Telefone</label>
                    <input type="tel" id="novoTelefone" required oninput="formatPhone(this)">
                </fieldset>
                <button type="button" onclick="registerUser()" class="btn btn-outline-success">Cadastrar Novo Usuário</button>
            </form>
        </div>
    </div>
    <script src="script.js">
    </script>
    <script>
        function openPopup() {
            document.getElementById("popup").style.display = "block";
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }

        document.getElementById("chamadoForm").onsubmit = async function (event) {
            const telefone = document.getElementById("telefone").value;
            const telefoneRegex = /^\(\d{2}\) \d{5}-\d{4}$/;
            event.preventDefault();

            if (!telefoneRegex.test(telefone)) {
                alert("Por favor, preencha o telefone no formato (00) 00000-0000.");
                return;
            }

            let resultadoAjax = await ajax(
                'POST',
                'insereChamado.php',
                {
                    descricao: $('#descricao').val(),
                    img: btoa($('#anexo').val())
                }
            );

            alert(resultadoAjax['message']);
            window.location.href = 'chamado.php';

            // alert("Chamado enviado!");
        };

        function registerUser() {
            const novoSetor = document.getElementById("novoSetor").value;
            const novoNome = document.getElementById("novoNome").value;
            const novoTelefone = document.getElementById("novoTelefone").value;

            if (!novoSetor || !novoNome || !novoTelefone) {
                alert("Por favor, preencha todos os campos.");
                return;
            }

            const telefoneRegex = /^\(\d{2}\) \d{5}-\d{4}$/;
            if (!telefoneRegex.test(novoTelefone)) {
                alert("Por favor, preencha o telefone no formato (00) 00000-0000.");
                return;
            }

            alert("Usuário cadastrado!");
            closePopup();
        }

        function formatPhone(input) {
            let value = input.value.replace(/\D/g, '');

            if (value.length > 11) {
                value = value.slice(0, 11);
            }

            if (value.length > 6) {
                input.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7, 11)}`;
            } else if (value.length > 2) {
                input.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
            } else if (value.length > 0) {
                input.value = `(${value}`;
            } else {
                input.value = '';
            }
        }

        document.getElementById("anexo").addEventListener("change", function (event) {
            const imagePreview = document.getElementById("imagePreview");
            const files = Array.from(event.target.files);

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgContainer = document.createElement("div");
                    imgContainer.className = "img-container";

                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.alt = file.name;

                    const removeBtn = document.createElement("span");
                    removeBtn.innerHTML = "&times;";
                    removeBtn.className = "remove-btn";
                    removeBtn.onclick = function () {
                        imgContainer.remove();
                    };

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    imagePreview.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</body>

</html>
