var myChart = echarts.init(document.getElementById('echarts-stats'));


option = {
    title: {
        text: '系统访问次数'
    },
    tooltip: {},
    legend: {
        data:['次']
    },
    xAxis: {
        data: ["1","2","3","3","4","5"]
    },
    yAxis: {},
    series: [{
        name: '销量',
        type: 'bar',
        data: [5, 20, 36, 10, 10, 20]
    }]
};
myChart.setOption(option);

