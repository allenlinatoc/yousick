function getData($url) {
    return $.ajax({
        dataType: 'json',
        url: $url
    });
}

function getIndex(array, name)
{
    var length = array.length;
    for(var i = 0; i < length; i++)
    {
        if (array[i].name == name)
            return i;
    }
    return -1;
}

$('#report1-nav-item').click(function() {
    var $data = getData(window.BASE_URL + '/rest/reports/monthly');

    $data.success(function (data) {
        if (data.success)
        {
            var options = {
                chart: {
                    type: 'line'
                },
                title: {
                    text: $('#report1-nav-item').text()
                },
                xAxis: {
                    categories: []
                },
                yAxis: {
                    title: {
                        text: 'Total Sick Leaves Filed'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                series: []
            };

            var length = data.data.collection.length;
            var sl_count = [];

            $.each(data.data.collection, function (index, monthly_data) {
                options.xAxis.categories.push(monthly_data.year+"-"+monthly_data.month);
                sl_count.push(parseInt(monthly_data.count));

                if (length == index+1)
                {
                    options.series.push({
                        name: 'All',
                        data: sl_count
                    })
                    $('#chart-container').highcharts(options);
                }
            });
        }
    });
});

$('#report2-nav-item').click(function() {
    var $data = getData(window.BASE_URL + '/rest/reports/monthly?category=department');

    $data.success(function (data) {
        if (data.success)
        {
            var options = {
                chart: {
                    type: 'column'
                },
                title: {
                    text: $('#report2-nav-item').text()
                },
                xAxis: {
                    categories: []
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Sick Leaves Filed'
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                        }
                    }
                },
                legend: {
                    align: 'right',
                    x: -30,
                    verticalAlign: 'top',
                    y: 25,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    formatter: function () {
                        return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': ' + this.y + '<br/>' +
                            'Total: ' + this.point.stackTotal;
                    }
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                            style: {
                                textShadow: '0 0 3px black'
                            }
                        }
                    }
                },
                series: []
            };

            var length = data.data.collection.length;
            var sl_count = [];

            $.each(data.data.collection, function (index, dept_data) {
                var xIndex = $.inArray(dept_data.yearmonth, options.xAxis.categories);
                if (xIndex == -1)
                {
                    options.xAxis.categories.push(dept_data.yearmonth);
                    xIndex = options.xAxis.categories.length-1;
                }

                var dept = dept_data.department;
                var seriesIndex = getIndex(options.series, dept);
                if (seriesIndex == -1)
                {
                    var seriesData = [];
                    for(var i = 0; i < xIndex; i++)
                    {
                        seriesData.push(0);
                    }
                    options.series.push({ name: dept, data: seriesData});

                    seriesIndex = options.series.length-1;
                }
                else {
                    if (options.series[seriesIndex].data.length < xIndex)
                    for (var i = 0; i < xIndex; i++)
                    {
                        if (options.series[seriesIndex].data[i] == null)
                            options.series[seriesIndex].data.push(0);
                    }
                }
                options.series[seriesIndex].data.push(parseInt(dept_data.count));

                if (length == index+1)
                    $('#chart-container').highcharts(options);
            });
        }
    });
});

$('#report3-nav-item').click(function() {
    var $data = getData(window.BASE_URL + '/rest/reports/monthly?category=individual');

    $data.success(function (data) {
        if (data.success)
        {
            var options = {
                chart: {
                    type: 'line'
                },
                title: {
                    text: $('#report3-nav-item').text()
                },
                xAxis: {
                    categories: []
                },
                yAxis: {
                    title: {
                        text: 'Total Filed Sick Leave'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                series: []
            };

            var length = data.data.collection.length;
            var sl_count = [];

            $.each(data.data.collection, function (index, ind_data) {
                var xIndex = $.inArray(ind_data.yearmonth, options.xAxis.categories);
                if (xIndex == -1)
                {
                    options.xAxis.categories.push(ind_data.yearmonth);
                    xIndex = options.xAxis.categories.length-1;
                }

                var username = ind_data.username;
                var seriesIndex = getIndex(options.series, username);
                if (seriesIndex == -1)
                {
                    var seriesData = [];
                    for(var i = 0; i < xIndex; i++)
                    {
                        seriesData.push(0);
                    }
                    options.series.push({ name: username, data: seriesData});

                    seriesIndex = options.series.length-1;
                }
                else {
                    if (options.series[seriesIndex].data.length < xIndex)
                    for (var i = 0; i < xIndex; i++)
                    {
                        if (options.series[seriesIndex].data[i] == null)
                            options.series[seriesIndex].data.push(0);
                    }
                }
                options.series[seriesIndex].data.push(parseInt(ind_data.count));

                if (length == index+1)
                    $('#chart-container').highcharts(options);
            });
        }
    });
});
