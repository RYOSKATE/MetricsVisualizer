
    //data[0][0]   ["defact_num"]/[group_name]/ [file_num]/[file_num]/[loc]/[date] ;
    //    1-4[日付分]


    AmCharts.ready(function () 
    {
        if(typeof(data) != "undefined")
        {
            var chartData = generateChartData();
            createStockChart(chartData);
        }
    });
    
    function generateChartData() 
    {
        var chartData = new Array();
        for(var i = 0;i<data.length;++i)
        {
            if(0<data[i].length)
            {
                var chartDataTemp = [];
                for(var j =0;j<data[i].length;++j)
                {
                    var dataTemp = data[i][j];
                    chartDataTemp.push({
                        date: dataTemp['date'],
                        value: dataTemp['defact_num'],
                        volume: 0
                    });
                }
                chartData.push(chartDataTemp);
            }
        }
        return chartData;
    }

    function createStockChart(chartData) 
    {
        var chart = new AmCharts.AmStockChart();
        chart.pathToImages = "http://www.amcharts.com/lib/3/images/";///ファイルパスの設定要確認
        // DATASETS //////////////////////////////////////////
        // create data sets first
        var dataSet = new Array();
        for(var i =0;i<chartData.length;++i)
        {
            var dataSetTemp = new AmCharts.DataSet();
            dataSetTemp.title = modelName[i+1];//選択されたモデル名に変更する必要あり
            dataSetTemp.fieldMappings = [{
                    fromField: "value",
                    toField: "value"
                }, {
                    fromField: "volume",
                    toField: "volume"
            }];
            dataSetTemp.dataProvider = chartData[i];
            dataSetTemp.categoryField = "date";
            dataSet.push(dataSetTemp);
        }

        // set data sets to the chart
        chart.dataSets = dataSet;

        // PANELS ///////////////////////////////////////////
        // first stock panel
        var stockPanel1 = new AmCharts.StockPanel();
        stockPanel1.showCategoryAxis = false;
        stockPanel1.title = "Value";
        stockPanel1.percentHeight = 70;

        // graph of first stock panel
        var graph1 = new AmCharts.StockGraph();
        graph1.valueField = "value";
        graph1.comparable = true;
        graph1.compareField = "value";
        graph1.bullet = "round";
        graph1.bulletBorderColor = "#FFFFFF";
        graph1.bulletBorderAlpha = 1;
        graph1.balloonText = "[[title]]:<b>[[value]]</b>";
        graph1.compareGraphBalloonText = "[[title]]:<b>[[value]]</b>";
        graph1.compareGraphBullet = "round";
        graph1.compareGraphBulletBorderColor = "#FFFFFF";
        graph1.compareGraphBulletBorderAlpha = 1;
        stockPanel1.addStockGraph(graph1);

        // create stock legend
        var stockLegend1 = new AmCharts.StockLegend();
        stockLegend1.periodValueTextComparing = "[[percents.value.close]]%";
        stockLegend1.periodValueTextRegular = "[[value.close]]";
        stockPanel1.stockLegend = stockLegend1;

        // set panels to the chart
        chart.panels = [stockPanel1];


        // OTHER SETTINGS ////////////////////////////////////
        var sbsettings = new AmCharts.ChartScrollbarSettings();
        sbsettings.graph = graph1;
        chart.chartScrollbarSettings = sbsettings;

        // CURSOR
        var cursorSettings = new AmCharts.ChartCursorSettings();
        cursorSettings.valueBalloonsEnabled = true;
        chart.chartCursorSettings = cursorSettings;


        // PERIOD SELECTOR ///////////////////////////////////
        var periodSelector = new AmCharts.PeriodSelector();
        periodSelector.position = "left";
        periodSelector.periods = [{
            period: "DD",
            count: 10,
            label: "10 days"
        }, {
            period: "MM",
            selected: true,
            count: 1,
            label: "1 month"
        }, {
            period: "YYYY",
            count: 1,
            label: "1 year"
        }, {
            period: "YTD",
            label: "YTD"
        }, {
            period: "MAX",
            label: "MAX"
        }];
        chart.periodSelector = periodSelector;


        // DATA SET SELECTOR
        var dataSetSelector = new AmCharts.DataSetSelector();
        dataSetSelector.position = "left";
        chart.dataSetSelector = dataSetSelector;
        chart.write('chartdiv');
    }