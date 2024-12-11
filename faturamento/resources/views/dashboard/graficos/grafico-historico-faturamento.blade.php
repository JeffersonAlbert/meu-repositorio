<script>
    $('document').ready(function (){
        let dataPoints = dataPointsFormat(@json($historicoReceitaAnual))
        var options = {
            animationEnabled: true,
            theme: "light2",
            height: 250,
            dataPointMaxWidth: 50,
            dataPointMinWidth: 20,
            axisX: {
                valueFormatString: "MMM"
            },
            axisY: {
                prefix: "$",
                labelFormatter: addSymbols
            },
            legend: {
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            toolTip: {
                shared: true
            },
            data: [
                {
                    // Change type to "doughnut", "line", "splineArea", etc.
                    type: "column",
                    name: "Faturamento",
                    xValueFormatString: "MMMM YYYY",
                    yValueFormatString: "$#,##0",
                    showInLegend: true,
                    color: "#1cc88a",
                    dataPoints: dataPoints,
                }
            ]
        };
        console.log(options);
        $("#invoiceHistory").CanvasJSChart(options);
    });
    function toggleDataSeries(e) {
        if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        } else {
            e.dataSeries.visible = true;
        }
        e.chart.render();
    }

    function addSymbols(e) {
        var suffixes = ["", "K", "M", "B"];
        var order = Math.max(Math.floor(Math.log(Math.abs(e.value)) / Math.log(1000)), 0);

        if (order > suffixes.length - 1)
            order = suffixes.length - 1;

        var suffix = suffixes[order];
        return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
    }

    function dataPointsFormat(d) {
        let dataPoints = [];
        $.each(d, function (index, value) {
            if(index !== "0"){
                let [y, m] = index.split('-');
                let moneyVal = unFormatMoney(formatCurrency(value))
                    .replace('.', '').replace('R$', '').trim().slice(0, -2);
                dataPoints.push({x: new Date((y/1), ((m/1)-1)), y: (moneyVal/1)});
            }
        });
        return dataPoints;
    }
</script>
