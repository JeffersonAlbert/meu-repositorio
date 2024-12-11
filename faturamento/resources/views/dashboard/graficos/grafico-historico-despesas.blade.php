<script>
    $('document').ready(function (){
        console.log(@json($historicoDespesasAnual));
        let dataPoints = dataPointsFormat(@json($historicoDespesasAnual))
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
                    name: "Despesa",
                    xValueFormatString: "MMMM YYYY",
                    yValueFormatString: "$#,##0",
                    showInLegend: true,
                    color: "#e74a3b",
                    dataPoints: dataPoints,
                }
            ]
        };
        $("#expensesHistory").CanvasJSChart(options);
    });
</script>
