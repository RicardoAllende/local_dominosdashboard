$(document).ready(function() {

    addCharta();// Aprobados-Pestaña 1
    addChartb();//No aprobados-Pestaña 1
    //-----------------------------------
    addCharta1();// Aprobados-Pestaña 2
    addChartb2();//No aprobados-Pestaña 2
    //-----------------------------------------------------------------------------CRUCE DE INDICADORES
    addChartc();//Programa 0-90 vs % Rotación
    addChartz();//Programa 0-90 vs % Rotación
    addChartd();//Programa 0-90 vs Quejas de servicio
    addCharte();//Programa 0-90 vs Quejas de servicio
    addChartf();//Staff certificado vs % Venta de tiendas cubiertas
    addChartg();//Staff certificado vs % Venta de tiendas cubiertas
    addCharth();//Curso Norma 251 Certificado curso ICA Champion
    addCharti();//Curso Norma 251 Certificado curso ICA Champion
    addChartj();//% Cubrimiento campaña de servicio KPI satisfacción del cliente
    addChartk();//% Cubrimiento campaña de servicio KPI satisfacción del cliente
    //------------------------------------------------------------------------------PROGRAMAS DE ENTRENAMIENTO
    addChartl();//Entrenamiento nuevos ingresos induccion
    addChartm();//Entrevista sentido de pertenencia
    addChartn();//Certificacion PPP
    addCharto();//Ruta Domino´s
    addChartp();//Certificación ERS
    addChartq();//Certificación RSC
    addChartr();//Entrenamiento Gerencial Formación de Supervisor
    addCharts();//Entrevista básico Norma 251
    addChartt();//Formación Subgerente
    addChartu();//Seguridad y salud
    addChartv();//Formación gerente
    addChartw();//Entrenamiento franquicias Staff general
    //------------------------------------------------------------------------------LANZAMIENTO Y CAMPAÑAS
    addChartaa();//Campañas seguridad y salud
})

//-----------------------------------------------------------------------------------------------------------------------------------------------------------PESTAÑA 1

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

//-----------------------------------------------------------------------------------------------------------------------------------------------------------PESTAÑA 2

//-------------------------------------------------------------------------------------------------Aprobados
function addCharta1(){
    var charta = c3.generate({
        data: {
            columns: [

                ['Aprobados', 80]
                                

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

                ['No_Aprobados', 20]
                                

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

//-------------------------------------------------------------------------------Gráficas de CRUCE DE INDICADORES-----------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------Programa 0-90 vs % Rotación (Grafica)
function addChartc(){
    var chartc = c3.generate({
        data: {
            columns: [
                ['Aprobado', 40],
                ['No Aprobado', 130],
                ['Destacado', 140]
            ],
            type: 'bar'
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
                var format = id === 'data1' ? d3.format(',') : d3.format('');
                return format(value);
            }

        }
    }
    });
}

//----------------------------------------------------------------------------------Programa 0-90 vs Quejas de servicio (Grafica2)
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
                var format = id === 'data1' ? d3.format(',') : d3.format('');
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
                var format = id === 'data1' ? d3.format(',') : d3.format('');
                return format(value);
            }

        }
    }
    });
}

//----------------------------------------------------------------------------------------Staff certificado vs % Venta de tiendas cubiertas (Grafica3)

function addChartf(){
    var chartf = c3.generate({
        data: {
            columns: [

                ['Ventas', 30],
                ['Staff certificado', 130]                

            ],
            type: 'bar'
        },
        bindto: "#chart7",
        tooltip: {
        format: {
            title: function (d) { return 'Quejas de servicio';},
            value: function (value, ratio, id) {
                var format = id === 'data1' ? d3.format(',') : d3.format('');
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
                var format = id === 'data1' ? d3.format(',') : d3.format('');
                return format(value);
            }

        }
    }
    });
}

//---------------------------------------------------------------------------------------------Curso Norma 251 Certificado curso ICA Champion (Grafica4)

function addCharth(){
    var charth = c3.generate({
        data: {
            columns: [
                ['Aprobado', 50],
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
                var format = id === 'data1' ? d3.format(',') : d3.format('');
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
                var format = id === 'data1' ? d3.format(',') : d3.format('');
                return format(value);
            }

        }
    }
    });
}

//--------------------------------------------------------------------------------------% Cubrimiento campaña de servicio KPI satisfacción del cliente (Grafica5)

function addChartj(){
    var chartj = c3.generate({
        data: {
            columns: [
                ['Aprobado', 50],
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
                var format = id === 'data1' ? d3.format(',') : d3.format('');
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
                var format = id === 'data1' ? d3.format(',') : d3.format('');
                return format(value);
            }

        }
    }
    });
}

//-------------------------------------------------------------------------------Gráficas de PROGRAMAS DE ENTRENAMIENTO-----------------------------------------------------------------------------------------------------

//------------------------------------------------------Entrenamiento nuevos ingresos induccion (Grafica6)

function addChartl(){
    var chartj = c3.generate({
        data: {
            columns: [
                ['Aprobado', 30],
                ['No Aprobado', 100],
                ['No hecho', 60]                
            ],
            type: 'pie'
        },
        bindto: "#chart13",        
    });
}

//--------------------------------------------------------Entrevista sentido de pertenencia (Grafica7)

function addChartm(){
    var chartj = c3.generate({
        data: {
            columns: [
                ['Aprobado', 40],
                ['No Aprobado', 100],
                ['No hecho', 60]                
            ],
            type: 'pie'
        },
        bindto: "#chart14",        
    });
}

//----------------------------------------------------------------Certificacion PPP (Grafica8)

function addChartn(){
    var chartj = c3.generate({
        data: {
            columns: [
                ['Aprobado', 40],
                ['No Aprobado', 100],
                ['No hecho', 40]                
            ],
            type: 'pie'
        },
        bindto: "#chart15",        
    });
}

//----------------------------------------------------------------Ruta Domino´s (Grafica9)

function addCharto(){
    var chartj = c3.generate({
        data: {
            columns: [
                ['Aprobado', 40],
                ['No Aprobado', 100],
                ['No hecho', 10]                
            ],
            type: 'pie'
        },
        bindto: "#chart16",        
    });
}

//----------------------------------------------------------------Certificación ERS (Grafica10)

function addChartp(){
    var chartj = c3.generate({
       data: {
            columns: [
                ['Aprobado', 40],
                ['No Aprobado', 30],
                ['No hecho', 10]                
            ],
            type: 'pie'
        },
        bindto: "#chart17",        
    });
}

//----------------------------------------------------------------Certificación RSC (Grafica11)

function addChartq(){
    var chartj = c3.generate({
        /*size: {
        height: 440,
        width: 1080
    },*/
        data: {
            columns: [
                ['Aprobado', 40],
                ['No Aprobado', 30],
                ['No hecho', 90]                
            ],
            type: 'pie'
        },
        bindto: "#chart18",        
    });
}

//----------------------------------------------------------------Entrenamiento Gerencial Formación de Supervisor (Grafica12)

function addChartr(){
    var chartj = c3.generate({
        /*size: {
        height: 440,
        width: 1080
    },*/
        data: {
            columns: [
                ['Aprobado', 40],
                ['No Aprobado', 30],
                ['No hecho', 40]                
            ],
            type: 'pie'
        },
        bindto: "#chart19",        
    });
}

//----------------------------------------------------------------Entrevista básico Norma 251 (Grafica13)

function addCharts(){
    var chartj = c3.generate({
        /*size: {
        height: 440,
        width: 1080
    },*/
        data: {
            columns: [
                ['Aprobado', 40],
                ['No Aprobado', 40],
                ['No hecho', 40]                
            ],
            type: 'pie'
        },
        bindto: "#chart20",        
    });
}

//----------------------------------------------------------------Formación Subgerente (Grafica14)

function addChartt(){
    var chartj = c3.generate({
        /*size: {
        height: 440,
        width: 1080
    },*/
        data: {
            columns: [
                ['Aprobado', 10],
                ['No Aprobado', 40],
                ['No hecho', 40]                
            ],
            type: 'pie'
        },
        bindto: "#chart21",        
    });
}

//----------------------------------------------------------------Seguridad y salud (Grafica15)

function addChartu(){
    var chartj = c3.generate({
        /*size: {
        height: 440,
        width: 1080
    },*/
        data: {
            columns: [
                ['Aprobado', 10],
                ['No Aprobado', 20],
                ['No hecho', 40]                
            ],
            type: 'pie'
        },
        bindto: "#chart22",        
    });
}

//----------------------------------------------------------------Formación gerente (Grafica16)

function addChartv(){
    var chartj = c3.generate({
        /*size: {
        height: 440,
        width: 1080
    },*/
        data: {
            columns: [
                ['Aprobado', 10],
                ['No Aprobado', 60],
                ['No hecho', 40]                
            ],
            type: 'pie'
        },
        bindto: "#chart23",        
    });
}

//----------------------------------------------------------------Entrenamiento franquicias Staff general (Grafica17)

function addChartw(){
    var chartj = c3.generate({
        /*size: {
        height: 440,
        width: 1080
    },*/
        data: {
            columns: [
                ['Aprobado', 30],
                ['No Aprobado', 60],
                ['No hecho', 80]                
            ],
            type: 'pie'
        },
        bindto: "#chart24",        
    });
}

//-----------------------------------------------------------------------------------------Graficas de LANZAMIENTO Y CAMPAÑAS-----------------------------------------------------------------------------------------------

//------------------------------------------------------------Campañas seguridad y salud (Grafica18)
function addChartaa(){
    var chartj = c3.generate({
        /*size: {
        height: 440,
        width: 1080
    },*/
        data: {
            columns: [
                ['Aprobado', 90],
                ['No Aprobado', 60],
                ['No hecho', 80]                
            ],
            type: 'pie'
        },
        bindto: "#chart25",        
    });
}


/*function chargePE(){
    setTimeout(function(){
        addCharta1();
        addChartb2();
        addCharth();
        addCharti();
    }, 500);
}-->*/
