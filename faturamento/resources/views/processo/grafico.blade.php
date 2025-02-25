<script>
document.addEventListener('DOMContentLoaded', function() {
    const dadosDoBackendPago = "{{ ($vencimentosPagoProcesso) }}";
    const dadosDoBackendAberto = "{{ $vencimentosAbertoProcesso }}";
    const mesesBackEnd = "{{ $meses }}";
    var dadosArray = JSON.parse(dadosDoBackendPago.replace(/&quot;/g, '"'));
    var dadosArrayAberto = JSON.parse(dadosDoBackendAberto.replace(/&quot;/g, '"'));
    var mesesJson = JSON.parse(mesesBackEnd.replace(/&quot;/g, '"'));
    const g = document.getElementById('barChart').getContext('2d');
    const labels = Object.values(mesesJson);
    const data = Object.values(dadosArray);
    const dataAberto = Object.values(dadosArrayAberto);
    const arrayTeste = validateArrayLength(data, dataAberto);
    console.log(labels);
    console.log(data);
    console.log(dataAberto);
    console.log(arrayTeste.array1);
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
    }

    // Bar Chart Example
    var ctx = document.getElementById("barChart");
    var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: "Pago",
            backgroundColor: "#4e73df",
            hoverBackgroundColor: "#2a2e59",
            borderColor: "#4e73df",
            data: data,
        },{
            label: "Aberto",
            backgroundColor: "#e74a3b",
            hoverBackgroundColor: "#970c1a",
            borderColor: "#e74a3b",
            data: dataAberto,
        }],
    },
    options: {
        maintainAspectRatio: false,
        layout: {
        padding: {
            left: 10,
            right: 25,
            top: 25,
            bottom: 0
        }
        },
        scales: {
        xAxes: [{
            time: {
            unit: 'month'
            },
            gridLines: {
            display: false,
            drawBorder: false
            },
            ticks: {
            maxTicksLimit: 6
            },
            //maxBarThickness: 25,
        }],
        yAxes: [{
            ticks: {
            min: 0,
            max: "{{ ceil($maiorValor) }}",
            maxTicksLimit: 10,
            padding: 10,
            // Include a dollar sign in the ticks
            callback: function(value, index, values) {
                return 'R$ ' + number_format(value);
            }
            },
            gridLines: {
            color: "#000",
            zeroLineColor: "#000",
            drawBorder: false,
            borderDash: [2],
            zeroLineBorderDash: [2]
            }
        }],
        },
        legend: {
        display: false
        },
        tooltips: {
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
        callbacks: {
            label: function(tooltipItem, chart) {
            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
            return datasetLabel + ': R$' + number_format(tooltipItem.yLabel);
            }
        }
        },
    }
    });
});
</script>
