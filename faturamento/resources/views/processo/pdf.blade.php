<script>
    document.addEventListener("DOMContentLoaded", async function() {
        $('#btnDadosPagamento').click(function() {
            console.log('teste de click');
            $('#modalDadosPagamento').modal();
        });

        $('#upload').change(function() {
            $('#file-list').empty();
            $.each(this.files, function(index, file) {
                $('#file-list').append($('<li>').text(file.name));
            });
        });

        $('#_upload').change(function() {
            $('#file-list').empty();
            $.each(this.files, function(index, file) {
                $('#file-list').append($('<li>').text(file.name));
            });
        });

        $(document).ready(function() {
            $('#fileInput').on('change', function() {
                const errorMessages = $('.form-pagar-processo');
                errorMessages.empty();

                const allowedExtensions = ['.pdf', '.jpg', '.jpeg', '.png'];

                $.each(this.files, function(i, file) {
                    const fileName = file.name;
                    const fileExtension = fileName.slice(((fileName.lastIndexOf(
                        ".") - 1) >>> 0) + 2);

                    if (!allowedExtensions.includes('.' + fileExtension
                            .toLowerCase())) {
                        errorMessages.append('<p>O arquivo ' + fileName +
                                ' não é um arquivo de imagem ou PDF válido.</p>')
                            .show();
                    }
                });
            });

            $('.editaBanco').click(function() {
                // Ação a ser executada quando um ícone "Editar" for clicado
                let idDoBanco = $(this).parent().attr('id');
                let textBanco = $(this).parent().text().trim();
                let textBancoParts = textBanco.split("-");
                console.log(textBancoParts);
                $("input[name=banco_nome]").val(textBancoParts[0].trim());
                $("input[name=banco_agencia]").val(textBancoParts[1].trim());
                $("input[name=banco_conta]").val(textBancoParts[2].trim());
                let modalAddBank = $("#modalAddBanco .modal-body #formAdicionarBanco");
                modalAddBank.append('<input type="hidden" value="' + idDoBanco +
                    '" id="id_bank">');
                $("#modalAddBanco").modal();
            });

            // Ouvinte de evento de clique para o ícone "Excluir"
            $('.deleteBanco').click(function() {
                // Ação a ser executada quando um ícone "Excluir" for clicado
                let idDoBanco = $(this).parent().attr('id');
                let textBanco = $(this).parent().text().trim();
                console.log(textBanco);
                $("#confirmarExclusaoBanco .modal-body").text(
                    "Tem certeza que deseja desativar o banco '" + textBanco + "'?");
                $("#confirmarExclusaoBanco .modal-body").append(
                    '<input type="hidden" value="' + idDoBanco + '" id="id_bank">')
                $("#confirmarExclusaoBanco").modal("show");
            });

            $(document).on("click", "#confirmDeleteBank", function() {
                showLoader();
                let bankId = $("#id_bank").val();
                let routeText = "{{ route('bancos.destroy', ['banco' => ':id']) }}";
                let route = routeText.replace(':id', bankId);
                console.log(route);
                $.ajax({
                    url: route,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            let elementoARemover = $(`a[id="${response.id}"]`);
                            if (elementoARemover.length > 0) {
                                // Remova o elemento do DOM
                                elementoARemover.remove();
                                $('#dropdownMenuButton').text(
                                    "Selecione o banco");
                                $("#confirmarExclusaoBanco").modal("hide");
                                hideLoader();
                            } else {
                                console.log(
                                    "Elemento não encontrado com o ID especificado."
                                );
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                });
                hideLoader();
            });

        });
        @if (!empty($processo->grupos_ids))
            var grupo_processo_id = ("{{ json_encode(explode(',', $processo->grupos_ids)) }}");
            var grupo_json = grupo_processo_id.replace(/&quot;/g, '"');
            var grupo_obj = JSON.parse(grupo_json);

            for (var i = 0; i < grupo_obj.length; i++) {
                var id = grupo_obj[i];

                try {
                    var response = await getGrupoById(id);
                    //console.log(response);
                    var nome = response.id + " - " + response.nome;
                    if (response.a_processo) {
                        var buttonHtml = '<div class="row mb-3"><button id="' + response.id +
                            '" data-toogle="tooltip" title="Aprovado por ' + response.u_nome + ' em ' +
                            formatarData(response.created_at) +
                            '" class="btn btn-success" style="width: 200px;" onclick="handleButtonClick(' +
                            response.id + ')" disabled><i class="bi bi-check mr-3"></i>' + nome +
                            '</button></div>';
                    } else {
                        var arrIdGrupos =
                            "{{ json_encode(json_decode(str_replace(['"'], [''], auth()->user()->id_grupos))) }}";
                        //console.log(arrIdGrupos);
                        var button_type = arrIdGrupos.includes(response.id) ?
                            "btn-warning custom-bg-button" : "custom-bg-button btn-secondary";
                        var disabled = arrIdGrupos.includes(response.id) ? null : "disabled";
                        var pendencia = "{{ $processo->pendencia ? true : false }}";
                        if (pendencia) {
                            var buttonHtml = '<div class="row mb-3"><button ' + disabled + ' id="' +
                                response.id + '" class="btn ' + button_type +
                                '" style="width: 200px;" data-toggle="modal" data-target="#modalPendenciado"><i class="bi bi-clock mr-3"></i>' +
                                nome + '</button></div>';
                        }
                        if (!pendencia) {
                            var buttonHtml = '<div class="row mb-3"><button ' + disabled + ' id="' +
                                response.id + '" class="btn ' + button_type +
                                '" style="width: 200px;" onclick="handleButtonClick(' + response.id +
                                ')"><i class="bi bi-clock mr-3"></i>' + nome + '</button></div>';
                        }

                    }
                    document.getElementById("insert-button").innerHTML += buttonHtml;
                } catch (error) {
                    console.error(error);
                }
            }
        @endif
    });

    function getGrupoById(id) {
        return new Promise(function(resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        resolve(JSON.parse(this.responseText));
                    } else {
                        reject("Error: " + this.status);
                    }
                }
            };
            xhr.open("POST", "{{ route('autocomplete.grupo.byid') }}", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.setRequestHeader("X-CSRF-Token", "{{ csrf_token() }}");
            xhr.send("id=" + id +
                "&id_processo={{ $processo->id }}&id_processo_vencimento_valor={{ $processo->pvv_id }}");
        });
    }


    function handleButtonClick(id) {
        var button = document.querySelector('[onclick="handleButtonClick(' + id + ')"]');
        var icon = button.querySelector('i');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('processo.valida.documento') }}", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("X-CSRF-Token", "{{ csrf_token() }}");
        xhr.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        var mensagemErro = '<div class="alert alert-danger"><p>' + response.error + '</p></div>';
                        $('.mensagem-erro').html(mensagemErro).hide();
                        $('.mensagem-erro').html(mensagemErro).show();
                    }
                    if (response.success) {
                        button.classList.toggle("btn-warning");
                        button.classList.toggle("btn-success");

                        icon.classList.toggle("bi-clock");
                        icon.classList.toggle("bi-check");
                        var mensagemSuccess = '<div class="alert alert-success"><p>' + response.success +
                            '</p></div>';
                        $('.mensagem-erro').html(mensagemSuccess).hide();
                        $('.mensagem-erro').html(mensagemSuccess).show();
                        button.disabled = true;
                    }
                } else {
                    //console.log(xhr.responseText);
                }
            }
        };

        var formData = "id_grupo=" + encodeURIComponent(id) + "&id_processo=" + encodeURIComponent(
                "{{ $processo->id }}") +
            "&id_processo_vencimento_valor=" + encodeURIComponent("{{ $processo->pvv_id }}");
        xhr.send(formData);


        // Lógica adicional aqui para tratar o clique do botão
        console.log("Botão clicado com o ID:", id);
    }


    function verificarObservacao() {
        var observacao = document.getElementById('observacao').value;
        var enviarComentarioBtn = document.getElementById('enviarComentario');

        if (observacao.length >= 20) {
            enviarComentarioBtn.removeAttribute('disabled');
        } else {
            enviarComentarioBtn.setAttribute('disabled', 'disabled');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('observacao').addEventListener('input', verificarObservacao);
    });

    function loadDocumentos(documentos){
        const imageContainer = document.getElementById('image-container');
        const imageScrollWrapper = document.getElementById('image-scroll-wrapper');
        const zoomableImage = document.getElementById('zoomable-image');;
        const zoomInBtn = document.getElementById('zoom-in-btn');
        const zoomOutBtn = document.getElementById('zoom-out-btn');
        const rotateLeftButton = document.getElementById('rotateLeft');
        const rotateRightButton = document.getElementById('rotateRight');
        const currentImage = $("#zoomable-image");
        const prevButton = $("#prevButton");
        const nextButton = $("#nextButton");
        let currentZoom = 1;
        let currentIndex = 0;
        let rotation = 0;

        function abrirArquivoEmNovaAba(url) {
            // Abre a URL em uma nova aba usando window.open
            window.open(url, '_blank');
        }

        $("#viewFile").click(function() {
            $("#modalDocumentosOriginais").modal();
        });

        zoomInBtn.addEventListener('click', () => {
            currentZoom += 0.1;
            updateImageZoom();
        });

        zoomOutBtn.addEventListener('click', () => {
            currentZoom -= 0.1;
            updateImageZoom();
        });

        rotateLeftButton.addEventListener('click', () => {
            rotation -= 90;
            zoomableImage.style.transform = `rotate(${rotation}deg) scale(${currentZoom})`;
        });

        rotateRightButton.addEventListener('click', () => {
            rotation += 90;
            zoomableImage.style.transform = `rotate(${rotation}deg) scale(${currentZoom})`;
        });

        function updateImage() {
            const imagePath = "{{ url('/image') }}" + "/" + documentos[currentIndex];
            currentImage.attr("src", imagePath);
            currentZoom = 1; // Reset zoom when changing images
            rotation = 0; // Reset rotation when changing images
            zoomableImage.style.transform = `rotate(${rotation}deg) scale(${currentZoom})`;
            const imagem = new Image();
        }

        function updateImageZoom() {
            const prevScrollLeft = imageScrollWrapper.scrollLeft;
            const prevScrollTop = imageScrollWrapper.scrollTop;

            zoomableImage.style.transform = `rotate(${rotation}deg) scale(${currentZoom})`;

            const newScrollLeft = (prevScrollLeft + imageContainer.offsetWidth / 2) * (currentZoom - 1);
            const newScrollTop = (prevScrollTop + imageContainer.offsetHeight / 2) * (currentZoom - 1);

            imageScrollWrapper.scrollLeft = newScrollLeft;
            imageScrollWrapper.scrollTop = newScrollTop;
        }

        prevButton.click(function() {
            currentIndex = Math.max(currentIndex - 1, 0);
            updateImage();
            updateButtonStates()
        });

        nextButton.click(function() {
            currentIndex = Math.min(currentIndex + 1, documentos.length - 1);
            updateImage();
            updateButtonStates()
        });

        updateImage();
        updateButtonStates()

        function updateButtonStates() {
            prevButton.prop('disabled', currentIndex === 0);
            nextButton.prop('disabled', currentIndex === documentos.length - 1);
        }
        document.getElementById('enviarComentario').addEventListener('click', function() {
            var id = "{{ $processo->id }}";
            var observacao = document.getElementById('observacao').value;

            // Crie um objeto com os dados que deseja enviar ao servidor
            var formData = "observacao=" + encodeURIComponent(observacao) + "&id=" + encodeURIComponent(
                id);

            // Crie uma instância do objeto XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Configure a requisição POST para o controlador (substitua a URL pelo caminho correto)
            xhr.open('POST', "{{ route('processo.observacao') }}", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.setRequestHeader("X-CSRF-Token", "{{ csrf_token() }}");



            // Configure a função de callback para tratar a resposta do servidor
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Trate a resposta do servidor, se necessário
                        console.log(xhr.responseText);
                        inserirDadosNaTabela(JSON.parse(xhr.responseText));
                        document.getElementById('observacao').value = '';
                    } else {
                        // Trate o erro, se ocorrer algum problema na requisição
                        console.error('Erro na requisição:', xhr.status, xhr.statusText);
                    }
                }
            };

            // Envie a requisição com os dados em formato JSON
            xhr.send(formData);
        });
    }

    const maxAttempts = 3;
    const chekInterval = 5000;
    let attemptsCount = 0;
    let intervalId;

    function checkUrl(){
        showLoader();
        fetch("{{ route('pdf.miniatures', ['id' => $processo->id]) }}")
            .then(response => response.json())
            .then(data =>{
                if(data.success){
                    console.log('sucesso');
                    clearInterval(intervalId);
                    let documentos = data.files;
                    console.log(documentos);
                    let imageContainer = $('.miniaturas');
                    let imageData = '';
                    $.each(documentos, function(key, value){
                        let url = "{{ route('r2.img', ['any' => ':file']) }}";
                        let urlWithFile = url.replace(':file', value);
                        imageData += `<div class="image-mini">
                                    <img id="miniatura" style="width: 70px; height: 110px" src='${urlWithFile}' alt="Miniatura">
                                </div>
                                <div class="divider mb-3 mt-3"></div> `;
                    });
                    imageContainer.empty().append(imageData)
                    loadDocumentos(documentos);
                    hideLoader();
                } else {
                    attemptsCount++;
                    console.log('tentando de novo');
                    if(attemptsCount >= maxAttempts){
                        clearInterval(intervalId);
                       $('.mensagem-erro').show().addClass('alert alert-danger').
                       text('Não foi possível carregar as miniaturas. Tente novamente em alguns minutos');
                       hideLoader();
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao carregar as miniaturas:', error);
                if (++attemptCount >= maxAttempts) {
                    console.log('erro');
                    clearInterval(intervalId); // Para o intervalo após o número máximo de tentativas
                    console.error('Número máximo de tentativas alcançado após erro.');
                }
            });
    }
    document.addEventListener("DOMContentLoaded", function() {
        let documentosInvalidChars = "{{ json_encode($arrayFiles) }}";
        let documentosClean = documentosInvalidChars.replace(/&quot;/g, '"');
        let documentos = JSON.parse(documentosClean);
        console.log(documentos);
        if(documentos == null || documentos == ''){
            console.log('tentando carregar as imagens');
            intervalId = setInterval(checkUrl, chekInterval);
        }
        loadDocumentos(documentos)
    });

    function inserirDadosNaTabela(dados) {
        var tbody = document.querySelector("#tabelaDados tbody");
        //tbody.innerHTML = ''; // Limpa o conteúdo atual da tabela

        for (var i = 0; i < dados.length; i++) {
            var row = tbody.insertRow(0); // Cria uma nova linha na tabela

            var dataCell = row.insertCell(); // Cria a célula da data
            dataCell.textContent = formatarData(dados[i].created_at); // Insere a data na célula

            var usuarioCell = row.insertCell(); // Cria a célula do usuário
            usuarioCell.textContent = dados[i].name; // Insere o usuário na célula

            var observacaoCell = row.insertCell(); // Cria a célula da observação
            observacaoCell.textContent = dados[i].observacao; // Insere a observação na célula
        }
    }

    function formatarData(data) {
        // Converte a string da data para o objeto Date
        var date = new Date(data);

        // Obtém os valores do dia, mês e ano
        var dia = date.getDate();
        var mes = date.getMonth() + 1; // Os meses em JavaScript começam em 0, então adicionamos 1
        var ano = date.getFullYear();

        // Obtém os valores de hora, minuto e segundo
        var hora = date.getHours();
        var minuto = date.getMinutes();
        var segundo = date.getSeconds();

        // Formata a data no formato "dd/mm/yyyy hh:mm:ss"
        return dia.toString().padStart(2, '0') + '/' + mes.toString().padStart(2, '0') + '/' + ano + ' ' +
            hora.toString().padStart(2, '0') + ':' + minuto.toString().padStart(2, '0') + ':' + segundo.toString()
            .padStart(2, '0');

        // Formata a data no formato "dd/mm/yyyy"
        return dia.toString().padStart(2, '0') + '/' + mes.toString().padStart(2, '0') + '/' + ano;
    }

    function uploadFiles() {
        var id = "{{ $processo->id }}";
        var id_pvv = "{{ $processo->pvv_id }}";
        var filesInput = document.getElementById('upload');
        var files = filesInput.files;

        if (files.length === 0) {
            alert('Nenhum arquivo selecionado.');
            return;
        }

        var formData = new FormData();

        // Adiciona o ID ao FormData
        formData.append('id_processo', id);
        formData.append('id_processo_vencimento_valor', id_pvv);

        for (var i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('processo.upload') }}", true);
        xhr.setRequestHeader("X-CSRF-Token", "{{ csrf_token() }}");

        xhr.onload = function() {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                if (data.success) {
                    var mensagem = '<div class="alert alert-success"><p>' + data.success + '</p></div>';
                    $('.upload-success').html(mensagem).show();
                    $('.upload-erro').hide();
                    filesInput.value = '';
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
                if (data.error) {
                    var mensagem = '<div class="alert alert-danger"><p>' + data.error + '</p></div>';
                    $('.upload-erro').html(mensagem).show();
                    $('.upload-success').hide();
                }
                // Faça aqui o que for necessário após o upload bem-sucedido
                // Por exemplo, atualize a tabela de arquivos, se existir
            } else {
                var data = JSON.parse(xhr.responseText);
                var mensagemErro = '<div class="alert alert-danger"><p>Precisa ser arquivo .pdf</p></div>';
                $('.upload-erro').html(mensagemErro).show();
                $('.upload-success').hide();
            }
        };

        xhr.onerror = function() {
            alert('Erro ao enviar arquivos. Por favor, tente novamente mais tarde.');
        };

        xhr.send(formData);
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Ativa todos os tooltips usando o atributo "data-toggle"
        $('[data-toggle="tooltip"]').tooltip();
    });

    function pendenciar(id) {
        console.log(id);
        var emails = $("input[name='email[]']").map(function() {
            return $(this).val();
        }).get();
        $.ajax({
            url: "{{ route('processo.pendencia.documento') }}",
            type: "POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}",
                observacao: $("#observacaoPendencia").val(),
                grupo: emails
            },
            success: function(response) {
                console.log("aqui");
            }
        });
    }

    function aprovarPendenciado() {
        var observacaoPendenciado = $("#observacaoPendenciado").val();
        console.log('tamo aqui');
        if (observacaoPendenciado.length < 20) {
            alert("A observação deve ter no mínimo 20 caracteres.");
            return;
        }

        var emails = $("input[name='email[]']").map(function() {
            return $(this).val();
        }).get();

        $.ajax({
            url: "{{ route('processo.retira.pendencia') }}",
            type: "POST",
            data: {
                id_processo: "{{ $processo->id }}",
                id_processo_vencimento_valor: "{{ $processo->pvv_id }}",
                dt_vencimento: "{{ $processo->pvv_dtv }}",
                _token: "{{ csrf_token() }}",
                observacao: observacaoPendenciado,
                id_usuario_email: emails
            },
            success: function(response) {
                console.log(response)
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            },
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            const observacaoInput = $("#observacaoPendencia");
            const contadorCaracteres = $("#contadorCaracteres");
            const observacaoPendenciado = $("#observacaoPendenciado");
            const contadorCaracteresPendenciado = $("#contadorCaracteresPendenciado");
            const comentarioDelete = $("#comentarioDelete");
            const contadorCaracteresDelete = $("#contadorCaracteresDelete");
            const observacaoComentario = $("#observacao");
            const contadorObservacaoComentario = $("#contadorObservacaoComentario");

            observacaoComentario.on("input", function() {
                const caracteresDigitados = $(this).val().length;
                contadorObservacaoComentario.text(caracteresDigitados + "/500");
                if (caracteresDigitados < 20) {
                    contadorObservacaoComentario.addClass("text-danger");
                } else {
                    contadorObservacaoComentario.removeClass("text-danger");
                }
            });

            observacaoInput.on("input", function() {
                const caracteresDigitados = $(this).val().length;
                contadorCaracteres.text(caracteresDigitados + "/500");
                if (caracteresDigitados < 20) {
                    contadorCaracteres.addClass("text-danger");
                } else {
                    contadorCaracteres.removeClass("text-danger");
                }
            });

            observacaoPendenciado.on("input", function() {
                const caracteresDigitados = $(this).val().length;
                contadorCaracteresPendenciado.text(caracteresDigitados + "/500");
                if (caracteresDigitados < 20) {
                    contadorCaracteresPendenciado.addClass("text-danger");
                } else {
                    contadorCaracteresPendenciado.removeClass("text-danger");
                }
            });

            comentarioDelete.on("input", function() {
                const caracteresDigitados = $(this).val().length;
                contadorCaracteresDelete.text(caracteresDigitados + "/500");
                if (caracteresDigitados < 20) {
                    contadorCaracteresDelete.addClass("text-danger");
                } else {
                    contadorCaracteresDelete.removeClass("text-danger");
                }
            });

            $(document).on('click', '#cadBanco', function() {
                $("#modalAddBanco").modal();
                $("input[name=banco_nome]").val('');
                $("input[name=banco_agencia]").val('');
                $("input[name=banco_conta]").val('')
            });

            $("#showObservacao").on('click', function() {
                $("#modalShowObservacao").modal();
            });

            $(document).on('click', '.dropdown-banco a', function() {
                var selectedBank = $(this).text();
                $('#dropdownMenuButton').text(selectedBank);
                let idDoBanco = $(this).attr('id');
                $("input[name=id_banco]").remove();
                $(".add_banco").append(
                    `<input name="id_banco" value="${idDoBanco}" type="hidden">`);
            });

            $("#enviarBanco").on('click', function() {
                showLoader();
                $("#enviarBanco").attr("disabled", true);
                const form = $("#formAdicionarBanco")[0];
                let formData = new FormData(form);
                let formAction = form.action
                if ($("#id_bank").length) {
                    let routeText = "{{ route('bancos.update', ['banco' => ':id']) }}";
                    formAction = routeText.replace(':id', $("#id_bank").val());
                    formData.append("_method", "PUT");
                    console.log(formAction);
                }
                var result = enviaFormulario(formData, formAction)
                    .then(function(result) {
                        console.log(result);
                        if (result.success) {
                            console.log('true');
                            if ($("#id_bank").length) {
                                idRemover = $("#id_bank").val();
                                $(`a[id=${result.id}]`).remove();
                                $('#dropdownMenuButton').text("Selecione o banco");
                            }
                            $("#modalAddBanco").modal('hide');
                            let bancoNome = $("input[name=banco_nome]").val();
                            let bancoAgencia = $("input[name=banco_agencia]").val();
                            let bancoConta = $("input[name=banco_conta]").val();
                            let option = `<a id="${result.id}" class="dropdown-item" href="#">
                                    <i class="bi bi-x-circle-fill text-danger deleteBanco"></i>
                                    <i class="bi bi-pencil-fill text-warning editaBanco"></i>
                                    ${bancoNome} - ${bancoAgencia} - ${bancoConta}
                                </a>`

                            $('.dropdown-banco').find('.dropdown-divider').after(option);
                            $("#enviarBanco").attr('disabled', false);
                            hideLoader();
                        } else {
                            console.log('false');
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                        $(".form-addbanco-processo").html(error).show();
                        $("#enviarBanco").attr("disabled", false);
                        hideLoader();
                    });
            });

            function pegarBancoOrigem(id_empresa) {
                $.ajax({
                    url: "{{ route('bancos.select') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id_empresa: id_empresa,
                    },
                    success: function(result) {
                        console.log(result);
                        let newSelect = `<select id='banco' name="id_banco" class="form-control">
                                <option>Selecione o banco ou espécie</option>
                                <option value="escpecie">Espécie</option>`;
                        $.each(result, function(field, values) {
                            newSelect +=
                                `<option value=${values.id}>${values.nome} - ${values.agencia} - ${values.conta}</option>`;
                        });
                        newSelect += `</select>`;
                        $('#banco').replaceWith(newSelect);
                    },
                    error: function(xhr, status, errors) {
                        var errors = xhr.responseJSON.errors;
                        console.log(errors);
                    }
                });
            }

            function enviaFormulario(formData, formAction) {
                console.log(formAction)
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: formAction,
                        data: formData,
                        type: "POST",
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            resolve(result)
                        },
                        error: function(xhr, status, errors) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = "";
                            $.each(errors, function(field, messages) {
                                errorMessage += field + ": " + messages
                                    .join(", ") + "<b>"
                            });
                            reject(errorMessage);
                        }
                    });
                });
            }

            $("#forma_pagamento").change(function() {
                if ($(this).val() == 'especie') {
                    $("#banco").val("especie");
                } else {
                    $("#banco").prop("disabled", false);
                }
            });

            $("#valor_pago").blur(function() {
                $(this).val(formatarValor($(this).val()));
            });

            function addPagamento(formData, formAction) {
                var result = enviaFormulario(formData, formAction)
                    .then(function(result) {
                        if (result.success) {
                            console.log('true');
                            setTimeout(function() {
                                window.location.reload();
                            }, 500);
                        } else {
                            $(".form-pagar-processo").html(result.errors.mensagem).show();
                            $("#enviarPagamento").attr("disabled", false);
                        }
                    })
                    .catch(function(error) {
                        $(".form-pagar-processo").html(error).show();
                        $("#enviarPagamento").attr("disabled", false);
                    });
            }

            $("#enviarPagamentoAssimMesmo").on("click", function() {
                const form = $("#formPagarProcesso")[0];
                const formData = new FormData(form);
                const formAction = form.action;
                addPagamento(formData, formAction);
            });

            $("#cancelarEnvioPagamento").on("click", function() {
                $("#enviarPagamento").attr("disabled", false);
            });

            $(".close.enviarPagamentoAssimMesmo").on("click", function() {
                $("#enviarPagamento").attr("disabled", false);
            });

            $("#enviarPagamento").on("click", function() {
                if ($("#forma_pagamento").val() == "0") {
                    $(".form-pagar-processo").html("Por favor selecione a forma de pagamento")
                        .show();
                    return;
                }
                console.log($("input[name='id_banco']").val());
                if (!$("input[name='id_banco']").val()) {
                    $(".form-pagar-processo").html("Por favor selecione um banco de pagamento")
                        .show();
                    return;
                }

                if ($('#_upload')[0].files.length === 0) {
                    $('#avisoSemArquivo').modal('show');
                    $(this).attr("disabled", true);
                    return;
                } else {
                    $(this).attr("disabled", true);
                    const form = $("#formPagarProcesso")[0];
                    const formData = new FormData(form);
                    const formAction = form.action;
                    addPagamento(formData, formAction);
                }

                $("#modalPagamento").modal();
            });

            $("#aprovacaoFinanceiro").on("click", function() {
                $("#modalPagamento").modal();
                /*$.ajax({
                    url: "{{ route('processo.pagamento') }}",
                    type: "POST",
                    data: {
                        id_processo: "{{ $processo->id }}",
                        id_processo_vencimento_valor: "{{ $processo->pvv_id }}",
                        dt_vencimento: "{{ $processo->pvv_dtv }}",
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        console.log(response);
                    }
                });*/
            });

            $("#pendencia").on("click", function() {
                const observacao = observacaoInput.val();
                if (observacao.length < 20) {
                    alert("A observação deve ter no mínimo 20 caracteres.");
                    return;
                }

                var emails = $("input[name='email[]']").map(function() {
                    return $(this).val();
                }).get();
                $.ajax({
                    url: "{{ route('processo.pendencia.documento') }}",
                    type: "POST",
                    data: {
                        id_processo: "{{ $processo->id }}",
                        id_processo_vencimento_valor: "{{ $processo->pvv_id }}",
                        dt_vencimento: "{{ $processo->pvv_dtv }}",
                        _token: "{{ csrf_token() }}",
                        observacao: $("#observacaoPendencia").val(),
                        id_usuario_email: emails
                    },
                    success: function(response) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        console.log(errors);
                        var errorMessage = "";
                        $.each(errors, function(field, messages) {
                            errorMessage += field + ": " + messages.join(
                                ", ") + "<br>";
                        });
                        $(".pendencia-error").html(
                            "<div class='alert alert-danger'><p>" +
                            errorMessage + "</p></div>").show();
                        //$('#enviarEdicaoProcesso').attr("disabled", false);
                    }
                });

            });
        });

        $('#user-select').typeahead({
            source: function(query, process) {
                return $.post("{{ route('autocomplete.usuario') }}", {
                    term: query,
                    _token: "{{ csrf_token() }}"
                }, function(data) {
                    return process(data);
                });
            },
            afterSelect: function(item) {
                var parts = item.split('-');
                var id = parts[0].trim();
                var email = parts.slice(1).join('-').trim();

                $('#user-selecionado').tagsinput('add', email);
                $('#user-select').val('');

                // Atualizar os valores e nomes dos elementos das tags
                var inputElement = $('<input>', {
                    type: 'hidden',
                    name: 'email[]',
                    class: email,
                    value: '{"id": "' + id + '", "email": "' + email + '"}'
                });
                $('#user_hidden').append(inputElement);
            }
        });
        $('#user-select-pendenciado').typeahead({
            source: function(query, process) {
                return $.post("{{ route('autocomplete.usuario') }}", {
                    term: query,
                    _token: "{{ csrf_token() }}"
                }, function(data) {
                    return process(data);
                });
            },
            afterSelect: function(item) {
                var parts = item.split('-');
                var id = parts[0].trim();
                var email = parts.slice(1).join('-').trim();

                $('#user-selecionado-pendenciado').tagsinput('add', email);
                $('#user-select-pendenciado').val('');

                // Atualizar os valores e nomes dos elementos das tags
                var inputElement = $('<input>', {
                    type: 'hidden',
                    name: 'email[]',
                    class: email,
                    value: '{"id": "' + id + '", "email": "' + email + '"}'
                });
                $('#user_hidden').append(inputElement);
            }
        });

        $('#deletarProcesso').on('click', function() {
            console.log('clickou para deletar');
            const c = $("#comentarioDelete").val();
            if (c.length < 20) {
                alert("A observação deve ter no mínimo 20 caracteres.");
                return;
            }

            $.ajax({
                url: "{{ route('processo.destroy', ['processo' => $processo->id]) }}",
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                    observacao: $('#comentarioDelete').val()
                },
                success: function(response) {
                    var r = JSON.parse(response);
                    console.log(r.success);
                    if (r.success) {
                        $('.error-show').hide();
                        var mensagem = '<div class="alert alert-success"><p>' + r.msg +
                            '</p></div>';
                        $('.error-show').html(mensagem).show();

                        setTimeout(function() {
                            window.location.href = "{{ route('processo.index') }}";
                        }, 500);
                    }
                    if (!r.success) {
                        $('.error-show').hide();
                        var mensagem = '<div class="alert alert-danger"><p>' + r.msg +
                            '</p></div>';
                        $('.error-show').html(mensagem).show();
                    }
                },
            });
        });

        $("#enviarEdicaoProcesso").on('click', function() {
            $('#enviarEdicaoProcesso').attr("disabled", true);
            const data = $('#formEditarProcesso').serialize();
            console.log(data);
            $('#parcela').focus();
            $('#observacao').focus();
            $.ajax({
                url: "{{ route('processo.update-pvv') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    data: data,
                    id: "{{ $processo->id }}",
                },
                success: function(response) {
                    if (response.msg == 'Atualizado com sucesso') {
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    }
                },
                error: function(xhr, status, error) {
                    $('#enviarEdicaoProcesso').attr("disabled", true);
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(errors, function(field, messages) {
                        errorMessage += field + ": " + messages.join(", ") + "<br>";
                    });
                    $(".form-edit-processo-error").html(errorMessage).show();
                    $('#enviarEdicaoProcesso').attr("disabled", false);
                }
            });
            event.preventDefault();
        });

        $("#continuarEdicao").on('click', function() {
            window.location.href = "{{ route('processo.edit', ['processo' => $processo->id]) }}";
        });

        $("#editarProcesso").on('click', function() {
            event.preventDefault();
            $("#editarProcesso").attr('disabled', true);
            // Obtém todos os campos do formulário.
            var campos = $("input, select, textarea");

            // Remove o atributo "disabled" de todos os campos.
            campos.prop("disabled", false);
            $("#busca_nome").prop("disabled", true);
            $("#numero_nota").prop("disabled", true);
            $("#uploadFormEdit").prop("disabled", true);
            $("input[name='tipo_cobranca']").remove();
            $("input[name='show_tipo_cobranca']").remove();
            var inputSelect = '<div class="input-group">' +
                '<select name="tipo_cobranca" class="form-control form-select" aria-label=".form-select">';
            var tipo_cobranca = ("{{ json_encode($tipo_cobranca) }}");
            cleanTipoCobranca = tipo_cobranca.replace(/&quot;/g, '"');
            console.log(cleanTipoCobranca);
            var tipoCobranca = JSON.parse(cleanTipoCobranca);


            tipoCobranca.forEach(function(item) {
                inputSelect += '<option value="' + item.id + '">' + item.nome + '</option>';
            });
            inputSelect += '</select></div>';
            $("#select_cobranca").append(inputSelect);

            $("input[name='flow']").remove();
            $("input[name='show_flow']").remove();

            var inputSelectFlow =
                '<div class="input-group"><select name="flow" class="form-control form-select" aria-label=".form-select">' +
                '<option>Selecione o fluxo de trabalho</option>';

            var workflow = ("{{ json_encode($workflow) }}");
            var cleanWorkflow = workflow.replace(/&quot;/g, '"');
            console.log(cleanWorkflow);
            var workFlow = JSON.parse(cleanWorkflow);
            console.log(workFlow);
            workFlow.forEach(function(item) {
                inputSelectFlow += '<option value="' + item.id + '">' + item.nome + '</option>';
            });
            inputSelectFlow += '</select></div>';
            $("#select_flow").append(inputSelectFlow);
            //select.html(inputSelect);
        });

        $('#parcela').on('blur', function() {
            const date = new Date();
            var exists = document.getElementById('inserted');

            if (exists) {
                document.getElementById('inserted').remove();
            }

            const datasParcelasContainer = $('#datasParcela').val();
            datasParcelasContainer.innerHTML = '';
            const quantidadeParcelas = $('#parcela').val();

            // Verifica se a quantidade de parcelas é válida
            if (!isNaN(quantidadeParcelas) && quantidadeParcelas > 0) {
                // Cria os campos de entrada para as datas das parcelas
                html = '<div id="inserted" class="form-row">';
                let valor_total = parseFloat(converterValorFormatado($("#valor_total").val()));
                let valor_primeira_parcela = parseFloat(converterValorFormatado($(
                    "#valorPrimeiraParcela").val()));
                let valor_parcela = (valor_total - valor_primeira_parcela) / (quantidadeParcelas - 1);
                let data_primeira_parcela = $("#dataPrimeiraParcela").val();
                if (valor_parcela < 0) {
                    $(".form-processo-error").html(
                        'Parcelas estão negativas favor verificar valor total primeira parcela e qtde de parcelas'
                    ).show();
                    return;
                }
                for (let i = 1; i < quantidadeParcelas; i++) {
                    let newDate = new Date(
                        data_primeira_parcela
                        ); // Cria um novo objeto Date com a mesma data que a data original
                    newDate.setMonth(newDate.getMonth() + (
                        i)); // Adiciona o número de meses correspondente ao valor de i
                    newDate.setFullYear(date.getFullYear() + Math.floor((date.getMonth() + (i + 1)) /
                        12)); // Adiciona o número de anos correspondente ao valor de i
                    html += ' <div id="vencimento_valor" class="form-group col-md-3">' +
                        '<input name="data' + (i) +
                        '" type="date" class="form-control" placeholder="Data parcela" value="' +
                        newDate.toISOString().substr(0, 10) + '">' +
                        '</div>' +
                        '<div class="form-group col-md-3">' +
                        '<div class="input-group">' +
                        '<div class="input-group-prepend">' +
                        '<span class="input-group-text">R$</span>' +
                        '</div>' +
                        '<input name="valor' + i +
                        '" type="text" class="form-control" placeholder="Valor Parcela" value="' +
                        formatarValor(valor_parcela) + '">' +
                        '</div>' +
                        '</div>';
                }
                html += '</div>';
                $('#datasParcela').prepend(html);
            }
        });

        $("#valorPrimeiraParcela").blur(function() {
            $(this).val(formatarValor($(this).val()));
        });

        //formata valor valor_total
        $("#valor_total").blur(function() {
            $(this).val(formatarValor($(this).val()));
        });

        $("#confirmarExclusao").on("click", function() {
            $("#confirmarExclusaoModal").modal("hide");

            var button = $(this);
            var fileName = button.data("file");

            console.log("{{ csrf_token() }}");
            excluirArquivosProcesso({
                _token: "{{ csrf_token() }}",
                file: fileName,
                id_processo: "{{ $processo->id }}",
                id_processo_vencimento_valor: "{{ $processo->pvv_id }}"
            });
            console.log(fileName);
        });

        $(".excluirArquivosProcesso").on("click", function(e) {
            var button = $(this);
            var fileName = button.closest(".row").find("p").text().trim();

            // Defina o nome do arquivo na mensagem do modal de confirmação
            $("#confirmarExclusaoModal .modal-body").text("Tem certeza que deseja excluir o arquivo '" +
                fileName + "'?");

            // Defina o nome do arquivo no botão de confirmação
            $("#confirmarExclusao").data("file", fileName);

            // Exiba o modal de confirmação
            $("#confirmarExclusaoModal").modal("show");
        });

        $(".downloadfile").on("click", function() {
            var button = $(this);
            console.log(button);
            var fileName = button.closest(".row").find("p").text().trim();
            console.log(fileName);

            let array = {
                _token: '{{ csrf_token() }}',
                file: fileName,
            };
            downloadArquivosProcesso(array);

        });


    });

    function downloadArquivosProcesso(array) {
        showLoader();
        console.log(array);
        $.ajax({
            url: "{{ route('processo.baixar-arquivo') }}",
            type: "POST",
            data: array,
            xhrFields: {
                responseType: 'blob' // Define o tipo de resposta como blob (arquivo)
            },
            success: function(result) {
                hideLoader();
                var a = document.createElement('a');
                var blob = new Blob([result], {
                    type: 'application/octet-stream'
                });
                var url = window.URL.createObjectURL(blob);
                a.href = url;
                a.download = array.file; // Substitua pelo nome real do arquivo
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            },
            error: function(xhr, status, errors) {
                hideLoader();
                console.log(errors);
                $(".upload-erro").html(
                    '<div class="alert alert-danger"><p>Houve algum erro, não foi possivel fazer download do arquivo! Favor tentar novamente mais tarde</p></div>'
                ).show();

            },
        });
    }

    function excluirArquivosProcesso(array) {
        $.ajax({
            url: "{{ route('processo.excluir-arquivo') }}",
            type: "POST",
            data: array,
            //processData: false,
            //contentType: false,
            success: function(result) {
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            },
            error: function(xhr, status, errors) {
                console.log(errors);
                $(".upload-erro").html(
                    '<div class="alert alert-danger"><p>Houve algum erro, não foi possivel excluir o arquivo! Favor tentar novamente mais tarde</p></div>'
                ).show();
            },
        });
        console.log(array);
    }

    function formatPhoneNumber(phoneNumber) {
        const cleanedPhoneNumber = phoneNumber.replace(/\D/g, '');

        if (cleanedPhoneNumber.length === 10) {
            return `(${cleanedPhoneNumber.substring(0, 3)}) ${cleanedPhoneNumber.substring(3, 6)}-${cleanedPhoneNumber.substring(6)}`;
        } else if (cleanedPhoneNumber.length === 11) {
            return `(${cleanedPhoneNumber.substring(0, 2)}) ${cleanedPhoneNumber.substring(2, 7)}-${cleanedPhoneNumber.substring(7)}`;
        } else {
            return cleanedPhoneNumber;
        }
    }

    function converterValorFormatado(valorFormatado) {
        return valorFormatado.replace(/\./g, '').replace(',', '.');
    }

    function formatarValor(num) {
        if (typeof num !== 'string') {
            num = num.toString().replace(/\./g, ',');
        }
        const numero = parseFloat(num.replace(/\./g, '').replace(',', '.'));
        if (!isNaN(numero)) {
            const valorFormatado = numero.toLocaleString('pt-BR', {
                minimumFractionDigits: 2
            });
            return valorFormatado;
        }
    }
</script>
