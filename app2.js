$(document).ready(function() {

    addCharta();// Aprobados-Pestaña 1
    addChartb();//No aprobados-Pestaña 1   
    addChartc();//Programa 0-90 vs % Rotación   
    addChartd();//Ranking  
    addCharte();//Ranking  
})

//-----------------------------------------------------------------------------------------------------------------------------------------------------------PESTAÑA 1

//-------------------------------------------------------------------------------------------------Aprobados

function addCharta(){
    var charta = c3.generate({
        data: {
            columns: [
                ['Aprobados', 50]

            ],
            colors: {
            Aprobados: '#0e4bef'
        },
            type: 'gauge'
        },
        bindto: "#chart3",
        tooltip: {
        format: {
            title: function (d) { return 'Aprobados ';},



        }
    }
    });
}

//-----------------------------------------------------------------------------------------------------------No aprobados
function addChartb(){
    var chartb = c3.generate({
        data: {
            columns: [
                ['No_Aprobados', 50]

            ],
            colors: {
            No_Aprobados: '#ffff00'
        },
            type: 'gauge'
        },
        bindto: "#chart4",
        tooltip: {
        format: {
            title: function (d) { return 'Aprobados ';},       
            


        }
    }
    });
}

//-------------------------------------------------------------------------------Gráficas de CRUCE DE INDICADORES-----------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------Programa 0-90 vs % Rotación (Graficap)
function addChartc(){
    var chartc = c3.generate({
        data: {
            columns: [
                ['Aprobado', 40],
                ['No Aprobado', 130],
                ['Destacado', 140]
            ],
            type: 'pie'
        },
        bindto: "#chart",
        tooltip: {
        format: {
            title: function (d) { return 'Curso '; },
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('');
                return format(value);
            }

        }
    }
    });
}

function addChartd(){
    var chartc = c3.generate({
        data: {
            columns: [
                ['Programa 0-90', 140],
                ['Staff certificado', 130],
                ['Curso Norma 251', 100]
            ],
            type: 'bar'
        },
        bindto: "#chart2",
        tooltip: {
        format: {
            title: function (d) { return 'Ranking '; },
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('');
                return format(value);
        }

        }
        },
        axis: {
            rotated: true
        },
    });
}

function addCharte(){
    var chartc = c3.generate({
        data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            
        ],
        types: {
            data1: 'bar',
        }
    },
    bindto: "#chart5",
    axis: {
        rotated: true
    }
});
}