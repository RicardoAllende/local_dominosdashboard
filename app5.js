$(document).ready(function() {
    addCharte("#chart5");
    addCharte("#chart7");
    addCharte("#chart6");
})

/*
KPI
    Destacado   2
    Aprobado    1
    No aprobado 0
*/
function addCharte(bindto_){
    var chart = c3.generate({
        data: {
            columns: [
                ['Porcentaje de aprobación', 30, 200, 100, 400, 150, 250], // Serie de cursos
                ['KPI', 130, 100, 140, 200, 150, 100],
                ['Ideal de kpi', 95, 95, 95, 95, 95, 95]
            ],            
            type: 'area'
        },
        bindto: bindto_,
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

