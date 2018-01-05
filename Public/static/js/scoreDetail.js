/**
 * Created by Mr.liang on 2017/7/22.
 */

var myChart1 = echarts.init(document.getElementById('chart1'));
//var myChart2 = echarts.init(document.getElementById('chart2'));
option1 = {
    title: {
        text: '分数段统计（单位:人数）',
        subtext: '来源于统计中心',
    },
    tooltip : {
        trigger: 'axis',
        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
        },
        formatter: function (params) {
            var tar = params[1];
            return tar.name + '<br/>' + tar.seriesName + ' : ' + tar.value;
        }
    },
    grid: {
        width: 'auto',
        //bottom: '3%',
        containLabel: false
    },
    xAxis: {
        type : 'category',
        splitLine: {show:false},
        data : ['40分以下','40 - 50','50 - 60','60 - 70','70 - 80','80 - 90','90 - 100'],
        name: '分数段',
        nameGap: '11'
    },
    yAxis: [
        {
            type : 'value',
            name: '人数',
            nameLocation: 'start',
            nameGap: '18'
        },
    ],
    series: [
        {
            name: '辅助',
            type: 'bar',
            stack:  '总计',
            itemStyle: {
                normal: {
                    barBorderColor: 'rgba(0,0,0,0)',
                    color: 'rgba(0,0,0,0)'
                },
                emphasis: {
                    barBorderColor: 'rgba(0,0,0,0)',
                    color: 'rgba(0,0,0,0)'
                }
            },
            data: [0, 0, 0, 0, 0, 0, 0]
        },
        {
            name: '数量',
            type: 'bar',
            stack: '值',
            barWidth: '80%',
            label: {
                normal: {
                    show: true,
                    position: 'inside'
                }
            },
            data:[{$score_segment}],
        }
        ],
    color:['#3498db']
};
myChart1.setOption(option1);
