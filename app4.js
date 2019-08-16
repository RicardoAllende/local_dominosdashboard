$(document).ready(function() {
    addChartc();
})

function addChartc(){
    var chart = c3.generate({
        data: {
            columns: [
                ['data1', 30, 200, 100, 400, 150, 250],
                ['data2', 130, 100, 140, 200, 150, 50]
            ],
            type: 'spline'
        },
        bindto: "#chart4",
        tooltip: {
        format: {
            title: function (d) { return 'Curso ' + d; },
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }
//            value: d3.format(',') // apply this format to both y and y2
        }
    }
    });
}