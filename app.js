$(document).ready(function() {
    addCharta();// Aprobados
    addChartb();//No aprobados
    //addCharta1();// Aprobados
    //addChartb2();//No aprobados
    addChartc();//Programa 0-90 vs % Rotación
    addChartz();//Programa 0-90 vs % Rotación
    addChartd();//Programa 0-90 vs Quejas de servicio
    addCharte();//Programa 0-90 vs Quejas de servicio
    addChartf();//Staff certificado vs % Venta de tiendas cubiertas
    addChartg();//Staff certificado vs % Venta de tiendas cubiertas
    //addCharth();//Curso Norma 251 Certificado curso ICA Champion
    //addCharti();//Curso Norma 251 Certificado curso ICA Champion
    addChartj();//% Cubrimiento campaña de servicio KPI satisfacción del cliente
    addChartk();//% Cubrimiento campaña de servicio KPI satisfacción del cliente
})

//-----------------------------------------------------------------------------------------------------RANKING--------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------Aprobados
function addCharta(){
    var charta = c3.generate({
        data: {
            columns: [
                ['Aprobados', 30]

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
                ['No_Aprobados', 70]

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

//--------------------------------------------------------------------------------Programa 0-90 vs % Rotación
function addChartc(){
    var chartc = c3.generate({
        data: {
            columns: [
                ['Aprobado', 30],
                ['No Aprobado', 130],
                ['Destacado', 140]
            ],
            type: 'bar'
        },
        bindto: "#chart",
        tooltip: {
        format: {
            title: function (d) { return 'Curso ' + d; },
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

function addChartz(){
    var chartz = c3.generate({
        data: {
            columns: [
                ['Aprobado', 30, 200, 100, 400, 150, 250],
                ['No Aprobado', 100, 100, 140, 200, 150, 50],
                ['Destacado', 140, 500, 240, 100, 350, 90]
            ],
            type: ''
        },
        bindto: "#chart2",
        tooltip: {
        format: {
            title: function (d) { return 'Curso ' + d; },
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

//----------------------------------------------------------------------------------Programa 0-90 vs Quejas de servicio
function addChartd(){
    var chartd = c3.generate({
        data: {
            columns: [
                ['Quejas', 30],
                ['Porcentaje', 130]
            ],
            type: 'bar'
        },
        bindto: "#chart5",
        tooltip: {
        format: {
            title: function (d) { return 'Quejas de servicio';},
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

function addCharte(){
    var charte = c3.generate({
        data: {
            columns: [
                ['Quejas', 30, 200, 100, 400, 150, 250],
                ['Porcentaje', 100, 100, 140, 200, 150, 50]
            ],
            type: ''
        },
        bindto: "#chart6",
        tooltip: {
        format: {
            title: function (d) { return 'Quejas de servicio';},
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

//----------------------------------------------------------------------------------------Staff certificado vs % Venta de tiendas cubiertas

function addChartf(){
    var chartf = c3.generate({
        data: {
            columns: [
                ['Quejas', 30],
                ['Porcentaje', 130]
            ],
            type: 'bar'
        },
        bindto: "#chart7",
        tooltip: {
        format: {
            title: function (d) { return 'Quejas de servicio';},
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

function addChartg(){
    var chartg = c3.generate({
        data: {
            columns: [
                ['Ideal', 94, 94, 94, 94, 94, 94],
                ['% Rotacion', 10, 100, 40, 20, 15, 50],
                ['% Capacitacion', 30, 60, 50, 20, 15, 35]
            ],
            type: ''
        },
        bindto: "#chart8",
        tooltip: {
        format: {
            title: function (d) { return 'Quejas de servicio';},
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

//---------------------------------------------------------------------------------------------Curso Norma 251 Certificado curso ICA Champion

function addCharth(){
    var charth = c3.generate({
        data: {
            columns: [
                ['Aprobado', 30],
                ['No Aprobado', 100],
                ['Destacado', 60]
            ],
            type: 'bar'
        },
        bindto: "#chart9",
        tooltip: {
        format: {
            title: function (d) { return 'Quejas de servicio';},
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

function addCharti(){
    var charti = c3.generate({
        data: {
            columns: [
                ['Aprobado', 94, 94, 94, 94, 94, 94],
                ['No Aprobado', 10, 100, 40, 20, 15, 50],
                ['Destacado', 30, 60, 50, 20, 15, 35]
            ],
            type: ''
        },
        bindto: "#chart10",
        tooltip: {
        format: {
            title: function (d) { return 'Quejas de servicio';},
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

//--------------------------------------------------------------------------------------% Cubrimiento campaña de servicio KPI satisfacción del cliente

function addChartj(){
    var chartj = c3.generate({
        data: {
            columns: [
                ['Aprobado', 30],
                ['No Aprobado', 100],
                ['Destacado', 60]
            ],
            type: 'bar'
        },
        bindto: "#chart11",
        tooltip: {
        format: {
            title: function (d) { return 'Quejas de servicio';},
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

function addChartk(){
    var chartk = c3.generate({
        data: {
            columns: [
                ['Aprobado', 94, 94, 94, 94, 94, 94],
                ['No Aprobado', 10, 100, 40, 20, 15, 50],
                ['Destacado', 30, 60, 50, 20, 15, 35]
            ],
            type: ''
        },
        bindto: "#chart12",
        tooltip: {
        format: {
            title: function (d) { return 'Quejas de servicio';},
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('$');
                return format(value);
            }

        }
    }
    });
}

//-------------------------------------------------------------------------------Gráficas de PROGRAMAS DE ENTRENAMIENTO-----------------------------------------------------------------------------------------------------







function addCharta1(){
    var charta = c3.generate({
        data: {
            columns: [
                ['Aprobados', 30]

            ],
            colors: {
            Aprobados: '#0e4bef'
        },
            type: 'gauge'
        },
        bindto: "#chart30",
        tooltip: {
        format: {
            title: function (d) { return 'Aprobados ';},



        }
    }
    });
}

//-----------------------------------------------------------------------------------------------------------No aprobados
function addChartb2(){
    var chartb = c3.generate({
        data: {
            columns: [
                ['No_Aprobados', 70]

            ],
            colors: {
            No_Aprobados: '#ffff00'
        },
            type: 'gauge'
        },
        bindto: "#chart40",
        tooltip: {
        format: {
            title: function (d) { return 'Aprobados ';},



        }
    }
    });
}

function chargePE(){

  setTimeout(function(){
    addCharta1();
    addChartb2();
    addCharth();
    addCharti();
 }, 500);


}
