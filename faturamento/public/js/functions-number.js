function formatCurrency(value) {
    // Remover todos os pontos de milhares e substituir vírgulas por pontos
    //value = value.replace(/\./g, '').replace(',', '.');

    // Formatar o número como moeda com 2 casas decimais
    const formattedValue = parseFloat(value).toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL",
    });

    return formattedValue;
}

function formatDate(inputDate) {
    const dateParts = inputDate.split(" ")[0].split("-");
    const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
    return formattedDate;
}

function formatDateDb(value) {
    console.log(value);
    const dateParts = value.split("/");
    if (dateParts.length === 3) {
        const [day, month, year] = dateParts;
        return `${year}-${month.padStart(2, "0")}-${day.padStart(2, "0")}`;
    } else {
        const months = {
            jan: "01",
            feb: "02",
            mar: "03",
            apr: "04",
            mai: "05",
            jun: "06",
            jul: "07",
            aug: "08",
            sep: "09",
            out: "10",
            nov: "11",
            dec: "12",
        };
        const datePartsAlt = value.split(" ");
        if (datePartsAlt.length === 3) {
            const [day, month, year] = datePartsAlt;
            const monthNumber = months[month.toLowerCase()];
            return `${year}-${monthNumber}-${day.padStart(2, "0")}`;
        }
    }
    return null;
}

function convertDateRange(dateRange) {
    const dateRanges = dateRange.split(" a ");
    console.log(dateRanges);
    if (dateRanges.length === 2) {
        console.log("estamos aqui");
        const startDate = formatDateDb(dateRanges[0].trim());
        const endDate = formatDateDb(dateRanges[1].trim());

        console.log(startDate + "" + endDate);
        return {
            startDate: startDate,
            endDate: endDate,
        };
    }
    if (dateRanges.length === 1) {
        const startDate = formatDateDb(dateRanges[0]);
        const endDate = formatDateDb(dateRanges[0]);
        return {
            startDate: startDate,
            endDate: endDate,
        };
    }
    return null;
}

function formatDateNumber(inputDate) {
    let data = new Date(inputDate);
    var dia = data.getDate();
    var mes = data.toLocaleString("default", { month: "long" });
    var ano = data.getFullYear();
    return `${dia} ${mes.substring(0, 3)} ${ano}`;
}

function formatHourNumber(dataHoraString) {
    const dataHora = new Date(dataHoraString);
    const options = { hour: "numeric", minute: "numeric", second: "numeric" };
    return dataHora.toLocaleString("pt-BR", options);
}

function unFormatMoney(num) {
    return num.replace(/\./g, "").replace(",", ".");
}

function formatarValor(num) {
    if (typeof num !== "string") {
        num = num.toString().replace(/\./g, ",");
    }

    console.log(num);

    const numero = parseFloat(num.replace(/\./g, "").replace(",", "."));
    console.log("lalalal " + numero);

    if (!isNaN(numero)) {
        const valorFormatado = numero.toLocaleString("pt-BR", {
            minimumFractionDigits: 2,
        });
        return valorFormatado;
    }
}

//desformata valor que formatamos antes para poder fazer conta
function converterValorFormatado(valorFormatado) {
    return valorFormatado.replace(/\./g, "").replace(",", ".");
}

function formatPhoneNumber(phoneNumber) {
    const cleanedPhoneNumber = phoneNumber.replace(/\D/g, "");

    if (cleanedPhoneNumber.length === 10) {
        return `(${cleanedPhoneNumber.substring(0, 3)}) ${cleanedPhoneNumber.substring(3, 6)}-${cleanedPhoneNumber.substring(6)}`;
    } else if (cleanedPhoneNumber.length === 11) {
        return `(${cleanedPhoneNumber.substring(0, 2)}) ${cleanedPhoneNumber.substring(2, 7)}-${cleanedPhoneNumber.substring(7)}`;
    } else {
        return cleanedPhoneNumber;
    }
}

function validateArrayLength(array1, array2) {
    const maxLength = Math.max(array1.length, array2.length);

    // Preencher os arrays com zeros para ter o mesmo comprimento
    const filledArray1 = Array.from(
        { length: maxLength },
        (_, i) => array1[i - (maxLength - array1.length)] || "0.0000",
    );
    const filledArray2 = Array.from(
        { length: maxLength },
        (_, i) => array2[i - (maxLength - array2.length)] || "0.0000",
    );

    return { array1: filledArray1, array2: filledArray2 };
}

function buscarEnderecoPorCEP(cep, callback) {
    cep = cep.replace(/\D/g, "");

    if (cep.length !== 8) {
        console.error("CEP inválido");
        callback(null);
        return;
    }

    const url = `https://viacep.com.br/ws/${cep}/json/`;

    fetch(url)
        .then((response) => response.json())
        .then((data) => {
            if (data.erro) {
                console.error("CEP não encontrado");
                callback(null);
            } else {
                console.log("Endereço encontrado:", data);
                callback(data);
            }
        })
        .catch((error) => {
            console.error("Erro na requisição:", error);
            callback(null);
        });
}

function calcularDataRetroativa(dias) {
    var hoje = new Date(); // Obtém a data de hoje
    var dataRetroativa = new Date(); // Cria um novo objeto de data

    // Subtrai o número de dias da data atual
    dataRetroativa.setDate(hoje.getDate() - dias);

    // Formata a data retroativa para o formato "yyyy-MM-dd"
    var hojeFormatado = hoje.toISOString().slice(0, 10);
    var retroDateFormatada = dataRetroativa.toISOString().slice(0, 10);

    var retorno = { hoje: hojeFormatado, retroDate: retroDateFormatada };

    return retorno; // Retorna um objeto com as datas formatadas
}

function converteAcoesRapidasParaData(val) {
    console.log("teste " + val);
    let hoje = new Date();

    if (val == "hoje") {
        let dataFormatada = formatDate(hoje.toISOString().slice(0, 10));
        $("#button-pai").text(dataFormatada);
        return;
    }

    if (val == "semana") {
        var semana = converteAcoesRapidasParaDataSemana();
        $("#button-pai").text(semana.segunda + " a " + semana.domingo);
        return;
    }

    if (val == "mes") {
        var mes = converteAcoesRapidasParaDataMes();
        $("#button-pai").text(mes.data_inicial + " a " + mes.data_final);
        return;
    }

    if (val == "ano") {
        var ano = converteAcoesRapidasParaDataAno();
        $("#button-pai").text(ano.primeiro_dia + " a " + ano.ultimo_dia);
        return;
    }

    if (val == "ultimos_12_meses") {
        console.log("aqui");
        var ultimos = converteAcoesRapidasUltimosMeses();
        $("#button-pai").text(
            ultimos.primeiro_dia + " a " + ultimos.ultimo_dia,
        );
        return;
    }
}

function converteAcoesRapidasUltimosMeses() {
    var today = new Date();

    // Define a data de início dos últimos 12 meses
    var startDate = new Date(
        today.getFullYear() - 1,
        today.getMonth(),
        today.getDate(),
    );

    // Define a data de fim dos últimos 12 meses
    var endDate = today;

    var primeiroDia = formatDateNumber(startDate);
    var ultimoDia = formatDateNumber(endDate);

    return { primeiro_dia: primeiroDia, ultimo_dia: ultimoDia };
}

function converteAcoesRapidasParaDataAno() {
    var today = new Date();

    // Define a primeira data do ano
    var firstDayYear = new Date(today.getFullYear(), 0, 1);

    // Define a última data do ano
    var lastDayYear = new Date(today.getFullYear(), 11, 31);

    var primeiroDiaDoAno = formatDateNumber(firstDayYear);
    var ultimoDiaDoAno = formatDateNumber(lastDayYear);

    return { primeiro_dia: primeiroDiaDoAno, ultimo_dia: ultimoDiaDoAno };
}

function converteAcoesRapidasParaDataMes() {
    // Obtém a data atual
    var today = new Date();

    // Define a primeira data do mês
    var firstDate = new Date(today.getFullYear(), today.getMonth(), 1);

    // Define a última data do mês
    var lastDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    var iniDate = formatDateNumber(firstDate);
    var fimDate = formatDateNumber(lastDate);

    return { data_inicial: iniDate, data_final: fimDate };
}

function converteAcoesRapidasParaDataSemana() {
    var today = new Date();
    var dayOfWeek = today.getDay();
    var daysToMonday = dayOfWeek === 0 ? 1 : -dayOfWeek + 1;
    var daysToSunday = dayOfWeek === 0 ? 0 : 7 - dayOfWeek;
    var monday = new Date(today);
    monday.setDate(today.getDate() + daysToMonday);
    let segunda = formatDate(monday.toISOString().slice(0, 10));
    var sunday = new Date(today);
    sunday.setDate(today.getDate() + daysToSunday);
    let domingo = formatDate(sunday.toISOString().slice(0, 10));
    return { segunda: segunda, domingo: domingo };
}

function database2jsonFormat(val) {
    console.log(val);
    let data = val.replace(/&quot;/g, '"');
    console.log(data);
    dataObj = JSON.parse(data);
    console.log(dataObj);
    return dataObj;
}

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
}

function lcFirst(str) {
    return str.charAt(0).toLowerCase() + str.slice(1);
}

function paginateNumber(ajaxResult, tipo) {
    var paginacao = `<nav>` + `<ul class="pagination">`;
    $.each(ajaxResult.links, function (index, links) {
        var active = links.active == true ? "active" : "";
        paginacao +=
            `<li class="page-item ${active}">` +
            `<a class="page-link ${tipo}" href="${links.url}">${links.label}</a>` +
            `</li>`;
    });
    paginacao += "</ul></nav>";
    return paginacao.replace("Previous", "").replace("Next", "");
}
//ajaxReasult = retorno do ajax
//dataType = {'trace_code': 'string',
//'f_name': 'string',
//'filial_nome': 'string',
//'tipo_cobranca': 'string',
//'nome_contrato': 'string',
//'pvv_dtv': 'date',
//'vparcela': 'currency',
//'produto': 'string',
//'status': 'string',}
//depois preciso retornar os dados formatados
function alterGridNumber(ajaxResult, dataType, tagReplace = null) {
    return new Promise((resolve, reject) => {
        try {
            console.log(ajaxResult);
            let formattedData = [];

            $.each(ajaxResult.data, function (index, value) {
                let row = {};

                $.each(dataType, function (key, options) {
                    const { type, alternative, nullValue, trueValue } = options;
                    let fieldValue = value[key];

                    if (
                        (fieldValue === null || fieldValue === undefined) &&
                        alternative
                    ) {
                        fieldValue = value[alternative];
                    }

                    if (type === "string") {
                        row[key] =
                            fieldValue !== null && fieldValue !== undefined
                                ? fieldValue
                                : "";
                    }
                    if (type === "date") {
                        row[key] = formatDate(fieldValue);
                    }
                    if (type === "currency") {
                        row[key] = formatCurrency(fieldValue);
                    }
                    if (type === "boolean") {
                        if (fieldValue === null || fieldValue === false) {
                            row[key] = nullValue; // Usar valor passado no array
                        } else {
                            row[key] = trueValue; // Usar valor passado no array
                        }
                    }
                });

                formattedData.push(row);
            });

            let grid = "";
            $.each(formattedData, function (key, value) {
                grid += `<tr>`;
                $.each(value, function (index, val) {
                    grid += `<td class="text-center td-grid-font align-middle">${val}</td>`;
                });
                grid += `</tr>`;
            });
            $(tagReplace).empty().append(grid);
            resolve(formattedData);
        } catch (error) {
            console.log(error);
            reject(error);
        }
    });
}

function getNextPageNumber(
    nextPage,
    tipo = null,
    tagReplace = null,
    fieldReplace = null,
) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: nextPage,
            type: "GET",
            data: {
                tipo: tipo,
            },
            success: function (response) {
                resolve(response);
            },
            error: function (error) {
                console.log(error);
                reject(error);
            },
        });
    });
}

function formatMonthYear(monthYear){
    const [month, year] = monthYear.split("/");
    return `${year}-${month.padStart(2, "0")}`;
}

function addBtnToDropdown(className, text, modalId) {
    var flg = 0;
    console.log(flg);
    $(`.${className}`).on("select2:open", function () {
        flg++;
    $(".select2-results__option.add-button").remove();

        let $this_html = `
                    <div class='select2-results__option add-button text-center mt-1 mb-1'>
                    <hr style="margin:2px">
                    <a href="javascript:void(0)" class="btn-appended  btn btn-sm btn-success w-100" onclick="javascript:$('#${modalId}').modal('show'),$('.${className}').select2('close');">
                        ${text}
                    </a>
                    </div>`;
    $('.select2-dropdown').append($this_html);
    });
}

function dataPointsFormat(d) {
    let dataPoints = [];
    $.each(d, function (index, value) {
        if(index !== "0"){
            let [y, m] = index.split('-');
            let moneyVal = unFormatMoney(formatCurrency(value))
                .replace('R$', '').trim();
            //console.log(moneyVal);
            dataPoints.push({x: new Date((y/1), ((m/1)-1)), y: (moneyVal/1)});
        }
    });
    return dataPoints;
}

function addSymbols(e) {
    var suffixes = ["", "K", "M", "B"];
    var order = Math.max(Math.floor(Math.log(Math.abs(e.value)) / Math.log(1000)), 0);

    if (order > suffixes.length - 1)
        order = suffixes.length - 1;

    var suffix = suffixes[order];
    return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
}

function toggleDataSeries(e) {
    if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        e.dataSeries.visible = false;
    } else {
        e.dataSeries.visible = true;
    }
    e.chart.render();
}
