<script>
    $('document').ready(function (){
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
                    name: "Venda por cliente",
                    xValueFormatString: "MMMM YYYY",
                    yValueFormatString: "$#,##0",
                    showInLegend: true,
                    color: "#1cc88a",
                    dataPoints: [
                        { x: new Date(2017, 0), y: 20000 },
                        { x: new Date(2017, 1), y: 25000 },
                        { x: new Date(2017, 2), y: 30000 },
                        { x: new Date(2017, 3), y: 70000, indexLabel: "Maior Faturamento" },
                        { x: new Date(2017, 4), y: 40000 },
                        { x: new Date(2017, 5), y: 60000 },
                        { x: new Date(2017, 6), y: 55000 },
                        { x: new Date(2017, 7), y: 33000 },
                        { x: new Date(2017, 8), y: 45000 },
                        { x: new Date(2017, 9), y: 30000 },
                        { x: new Date(2017, 10), y: 50000 },
                        { x: new Date(2017, 11), y: 35000 }
                    ]
                }
            ]
        };
        $("#buyPerClient").CanvasJSChart(options);
    });
</script>
