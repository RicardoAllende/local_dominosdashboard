function esVacio(_elemento) {
    if (_elemento === undefined) {
        return true;
    }
    if (_elemento === null) {
        return true;
    }
    if (Array.isArray(_elemento)) {
        if (_elemento.length == 0) {
            return true;
        }
    }
    if (_elemento == "") {
        return true;
    }
    return false;
}

function obtenerNumero(val) {
    return !isNaN(parseFloat(val)) && 'undefined' !== typeof val ? parseFloat(val) : false;
}

function obtenerPorcentaje(percent, total, decimales) {
    if (decimales == undefined) {
        decimales = 2;
    }
    var per = obtenerNumero(percent),
        div = obtenerNumero(total);

    if (false === per) return 0;
    if (false === div || div === 0) return 0;

    return parseFloat((per / div * 100).toFixed(decimales));
}

function insertarTituloSeparador(div, titulo) {
    $(div).append(`
    <div class="col-sm-12 col-xl-12">
        <div class="titulog col-sm-12">
            <h1 class="text-center">${titulo}</h1>
        </div>
    </div>`);
}

function obtenerDefaultEnNull(valor, porDefault) {
    if (esVacio(porDefault)) porDefault = 0;
    if (esVacio(valor)) {
        return porDefault;
    }
    return valor;
}

function exportar_a_excel() {
    document.forms.filter_form.submit();
}

//Funcion para mostar la grafica sin informacion
function insertarGraficaSinInfo(div, mensaje) {
    if (typeof mensaje == 'undefined') {
        mensaje = "Sin información en la Base de Datos";
    }
    $(div).html(`
    <div class='col-sm-12 espacio'>
            <div class='card bg-gray border-0 m-2'>
            <div class='align-items-end'>                                        
                    <div class='fincard text-center'>
                        <a href='#' id=''></a>
                    </div>
                </div>
                <div class='card esp'>
                <div class='row espr'>
                    <div class='border-0 col-sm-4'>
                        <div class='card-body'>
                            <p class='card-text text-primary text-center txti'></p>
                            <p class='card-text text-primary text-center txtnum' id=''></p>
                        </div>
                    </div>
                    <div class='border-0 col-sm-4'>
                        <div class='card-body text-center'>
                            <p class='card-text text-warning text-center txti'></p>
                            <p class='card-text text-warning text-center txtnum' id=''></p>
                        </div>
                    </div>
                    <div class='border-0 col-sm-4'>
                        <div class='card-body text-center'>
                            <p class='card-text text-success text-center txti'></p>
                            <p class='card-text text-warning text-center txtnum' id=''></p>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class='bg-faded text-center noinfo' id=''>${mensaje}</div>
            </div>
    </div>`);
}
var comparativas = 0;

function crearGraficaDeCurso(_bindto, curso) {
    // console.log('Probable error', curso);
    switch (curso.chart) {
        case 'pie':
            _columns = [
                ['Aprobados', curso.approved_users],
                ['No Aprobados', curso.not_approved_users]
            ];
            var nombre_columnas = ["Aprobados", "No Aprobados"];
            break;
        case 'bar':
            _columns = [
                ['Inscritos', curso.enrolled_users],
                ['Aprobados', curso.approved_users],
            ];
            var nombre_columnas = ["Inscritos", "Aprobados", "No iniciaron el curso"];
            break;
        case 'gauge':
            _columns = [['Aprobados', curso.percentage]];
            var nombre_columnas = ["Aprobados"];
            break;
        case 'spline':
            _columns = [
                ['Inscritos', 30, 200, 100, 400],
                ['Aprobados', 130, 100, 140, 200]
            ];

            return c3.generate({
                data: {
                    columns: _columns,
                    type: curso.chart,
                    colors: {
                        Inscritos: '#0000ff',
                        Aprobados: '#008000',
                        'No Aprobados': '#ff0000'

                    }
                },
                bindto: _bindto,
                tooltip: {
                    format: {
                        title: function (d) { return 'Porcentaje de aprobación'; },

                    }
                }

            });

            break;
        case 'grupo':
            _columns = [
                ['Inscritos', curso.enrolled_users],
                ['Aprobados', curso.approved_users]
            ];

            return c3.generate({
                data: {
                    columns: _columns,
                    type: 'bar',
                    colors: {
                        Inscritos: '#0000ff',
                        Aprobados: '#008000',
                        'No Aprobados': '#ff0000'

                    }
                },
                bindto: _bindto,
                tooltip: {
                    format: {
                        title: function (d) { return ''; },

                    }
                }

            });
            break;

        case 'comparativa_regiones':
            imprimirComparativaFiltrosDeCurso(_bindto, curso.region_comparative);
            break;
        default:
            $(_bindto).html('');
            return;
    }
    return c3.generate({
        data: {
            columns: _columns, //[] ,//_columns,
            type: curso.chart,
            colors: {
                Inscritos: '#0000ff',
                Aprobados: '#008000',
                'No Aprobados': '#ff0000'

            }
        },
        bindto: _bindto,
        tooltip: {
            format: {
                title: function (d) {
                    if (nombre_columnas[d] !== undefined) {
                        return nombre_columnas[d];
                    } else {
                        return "_";
                    }
                },
            }
        }
    });
}

function crearTarjetaParaGrafica(div, curso, claseDiv) {
    if (typeof claseDiv !== 'string') {
        claseDiv = "col-sm-4";
    }
    id_para_Grafica = "chart_" + curso.id;
    if (typeof currentTab != 'undefined') {
        id_para_Grafica += '_' + currentTab;
    }
    if (typeof mostrarEnlaces != 'undefined') {
        enlace = "detalle_curso_iframe.php?id=" + curso.id;
    } else {
        enlace = '#';
    }

    var a = curso.percentage;
    var b = 100 - a;

    $(div).append(`<div class="${claseDiv} espacio">
                <div class="card bg-gray border-0 m-2">
                <div class="align-items-end">
                        <div class="fincard text-center">
                            <a href="${enlace}">${curso.title}</a>
                        </div>
                    </div>
                    <div class="card esp">
                    <div class="row espr">
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text txti_aprobados">Aprobados</p>
                                <p class="card-text txtnum_aprobados">${curso.approved_users} (${curso.percentage} %)</p>
                            </div>
                        </div>
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text txti_noaprobados">No Aprobados</p>
                                <p class="card-text txtnum_noaprobados">${curso.not_approved_users} (${b.toFixed(2)} %)</p>
                            </div>
                        </div>
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text txti_inscritos">Total de usuarios inscritos</p>
                                <p class="card-text txtnum_inscritos">${curso.enrolled_users}</p>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="chart_ bg-faded m-2" id="${id_para_Grafica}"></div>                    
                </div>
            </div>`);
    return crearGraficaDeCurso('#' + id_para_Grafica, curso);
}

function obtenerFiltros(indicator) {
    informacion = $('#filter_form').serializeArray();
    informacion.push({ name: 'request_type', value: 'user_catalogues' });
    if (indicator != undefined) {
        informacion.push({ name: 'selected_filter', value: indicator });
    }
    peticionFiltros(informacion);
}


function generarGraficasTodosLosCursos(_bindto, response, titulo) {
    type = response.type;
    $(_bindto).html('');
    if (type == 'course_list') {
        cursos = response.result;
        if (Array.isArray(cursos)) {
            var aprobados = Array();
            var no_aprobados = Array();
            var nombres = Array();
            var _ideal_cobertura = Array();
            nombres.push('x');
            aprobados.push("Porcentaje de aprobación del curso");
            no_aprobados.push("Porcentaje de no aprobados");
            _ideal_cobertura.push('Ideal de cobertura');
            for (var i = 0; i < cursos.length; i++) {
                _ideal_cobertura.push(ideal_cobertura);
                var curso = cursos[i];
                aprobados.push(curso.percentage);
                var resta = 100 - curso.percentage;
                var resu = resta.toFixed(2);
                no_aprobados.push(resu);
                nombres.push(curso.title);
            }
            crearGraficaComparativaVariosCursos(_bindto, [nombres, aprobados, no_aprobados, _ideal_cobertura], cursos, titulo);


            /**
             * Sección d, adaptar
             */
            // grupoDeCursos = Array();
            listadoDeCursos = array();
            var aprobados_ = Array();
            var no_aprobados_ = Array();
            var nombres_ = Array();
            var inscritos = Array();
            // var _ideal_cobertura_ = Array();
            nombres_.push('x');
            inscritos.push('Inscritos');
            aprobados_.push("Aprobados");
            no_aprobados_.push("No Aprobados");
            // _ideal_cobertura_.push('Ideal de cobertura');
            for (var i = 0; i < cursos.length; i++) {
                var curso = cursos[i];
                chart = curso.chart;
                // if (chart.indexOf('grupo_cursos') !== -1) { // Se hará una comparativa entre estos cursos, primero creamos un arreglo con esos cursos
                // grupoDeCursos.push(curso);
                var curso_ = cursos[i];
                // _ideal_cobertura_.push(ideal_cobertura);
                aprobados_.push(curso_.approved_users);
                inscritos.push(curso.enrolled_users);
                no_aprobados_.push(curso_.not_approved_users);
                nombres_.push(curso_.title);
                // } else { // Creación estándar donde se hace una gráfica por curso (gauge, bar, pie, comparativa_regiones, ...)
                //     crearTarjetaParaGrafica(_bindto, curso);
                // }
            }
            // console.log('Grupo de cursos', grupoDeCursos);
            dashboardCrearGraficaComparativaGrupoDeCursos(_bindto, [nombres_, inscritos, aprobados_, no_aprobados_], listadoDeCursos, "Programas de entrenamiento temporal"); // Se puede modificar sin problemas
            /**
             * Termina sección d
             */
        }
    }
    if (type == 'kpi_list') {
        kpis = response.result;
        if (Array.isArray(kpis)) {
            for (var i = 0; i < kpis.length; i++) {
                var aprobados = Array();
                var _ideal_cobertura = Array();
                aprobados.push("Porcentaje de aprobación del curso");
                kpi = kpis[i];
                cursos = kpi.courses;
                if (esVacio(kpi.status)) { // No hay kpi devuelto
                    div_id = "chart__";
                    if (typeof currentTab != 'undefined') {
                        div_id += '_';
                        div_id += currentTab;
                    }
                    if (typeof id != "undefined") {
                        div_id += id;
                    }
                    div_id = div_id + i;
                    insertarTituloSeparador(_bindto, titulo);
                    $(_bindto).append(`
                        <div class="col-sm-12 col-xl-12">
                            <div class="card bg-faded border-0 m-2" id="">
                                    
                                <div class="bg-white m-2" id="${div_id}"></div>                        
                            </div>
                        </div>`);
                    div_id = "#" + div_id;
                    insertarGraficaSinInfo(div_id);
                    continue;
                }



                switch (kpi.type) {
                    case "Texto": // Nombre
                        valores = Array();

                        _keys = Object.keys(kpi.status);
                        info_grafica = Array();
                        info_grafica.aprobados = Array();
                        info_grafica.aprobados.push('Porcentaje de aprobación');
                        nombre_cursos = Array();
                        nombre_cursos.push('x');

                        for (var t = 0; t < _keys.length; t++) {
                            _current_key = _keys[t];
                            info_grafica[_current_key] = Array();
                            info_grafica[_current_key].push(_current_key);
                        }
                        for (var j = 0; j < cursos.length; j++) {
                            curso = cursos[j];
                            nombre_cursos.push(curso.title);
                            info_grafica.aprobados.push(curso.percentage);
                            for (var r = 0; r < _keys.length; r++) {
                                _current_key = _keys[r];
                                info_grafica[_current_key].push(kpi.status[_current_key]);
                            }
                        }

                        _keys = Object.keys(info_grafica);
                        info_procesada_grafica = Array();
                        for (var r = 0; r < _keys.length; r++) {
                            _current_key = _keys[r];
                            info_procesada_grafica.push(info_grafica[_current_key]);
                            // info_grafica[_current_key] = Array();
                            // info_grafica[_current_key].push(kpi.status[_current_key]);
                        }
                        info_procesada_grafica.push(nombre_cursos);

                        div_id = "chart__";
                        if (typeof currentTab != 'undefined') {
                            div_id += '_';
                            div_id += currentTab;
                        }
                        div_id += kpi.type;
                        insertarTituloSeparador(_bindto, kpi.name);
                        $(_bindto).append(`
                                    <div class="col-sm-12">
                                        <div class="card bg-faded border-0 m-2" id="">
                                        <div class="align-items-end">
                                                <div class="fincard text-center">
                                                    <a href="#">${kpi.name}</a>
                                                </div>
                                            </div>
                                            <div class="bg-white m-2" id="${div_id}"></div>
                                        </div>
                                    </div>`);

                        console.log('Información de la gráfica spline', info_grafica);
                        c3.generate({
                            data: {
                                x: 'x',
                                columns: info_procesada_grafica,
                                type: 'spline'
                            },
                            bindto: '#' + div_id,
                            axis: {
                                x: {
                                    type: 'category' // this needed to load string x value
                                }
                            },
                        });
                        // crearGraficaComparativaVariosCursos(_bindto, [aprobados, _destacado, _aprobado, _noAprobado], cursos, kpi.name, kpi.id);


                        _keys = Object.keys(kpi.status);
                        for (var j = 0; j < cursos.length; j++) {
                            curso = cursos[j];

                            info_grafica_kpi = Array();
                            info_grafica_kpi.push(['Porcentaje de aprobación', curso.percentage]);
                            for (var r = 0; r < _keys.length; r++) {
                                ck = _keys[r];
                                info_grafica_kpi.push([ck, kpi.status[ck]]);
                            }

                            // crearTarjetaParaGraficakpi(_bindto, curso, __kpi, kpi.id);

                            id_para_Grafica = "chart_" + kpi.id + '_' + curso.id;
                            if (typeof mostrarEnlaces != 'undefined') {
                                enlace = "detalle_curso_iframe.php?id=" + curso.id;
                            } else {
                                enlace = '#';
                            }
                            $(_bindto).append(`<div class="col-sm-12 col-xl-6 espacio">
                                        <div class="card bg-gray border-0 m-2" id="">
                                        <div class="align-items-end">
                                                <div class="fincard text-center">
                                                    <a href="${enlace}">${curso.title}</a>
                                                </div>
                                            </div>
                                            <div class="card esp">
                                            <div class="row espr">
                                                <div class="border-0 col-sm-4">
                                                    <div class="card-body text-center">
                                                        <p class="card-text txti_aprobados">Aprobados</p>
                                                        <p class="card-text txtnum_aprobados">${curso.approved_users} (${curso.percentage} %)</p>
                                                    </div>
                                                </div>
                                                <div class="border-0 col-sm-4">
                                                    <div class="card-body text-center">
                                                        <p class="card-text txti_noaprobados">No Aprobados</p>
                                                        <p class="card-text txtnum_noaprobados">${curso.not_approved_users}</p>
                                                    </div>
                                                </div>
                                                <div class="border-0 col-sm-4">
                                                    <div class="card-body text-center">
                                                        <p class="card-text txti_inscritos">Total de usuarios inscritos</p>
                                                        <p class="card-text txtnum_inscritos">${curso.enrolled_users}</p>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="chart_ bg-faded m-2" id="${id_para_Grafica}"></div>                    
                                        </div>
                                    </div>`);
                            id_para_Grafica = '#' + id_para_Grafica;
                            console.log('info_grafica_kpi', info_grafica_kpi);
                            c3.generate({
                                data: {
                                    columns: info_grafica_kpi,
                                    type: 'bar',
                                },
                                bindto: id_para_Grafica,
                            });
                        }


                        break;
                    case 'Número entero':
                    case 'Porcentaje':
                        __kpi = [kpi.name, kpi.status];
                        info_kpi = Array();
                        nombres = Array();
                        nombres.push('x');
                        info_kpi.push(kpi.name);
                        for (var j = 0; j < cursos.length; j++) {
                            curso = cursos[j];
                            aprobados.push(curso.percentage);
                            info_kpi.push(kpi.status);
                            nombres.push(curso.title);
                        }
                        crearGraficaComparativaVariosCursos(_bindto, [nombres, aprobados, info_kpi], cursos, kpi.name, kpi.id);

                        for (var j = 0; j < cursos.length; j++) {
                            curso = cursos[j];
                            crearTarjetaParaGraficakpi(_bindto, curso, __kpi, kpi.id);
                        }
                        break;
                }
            }
        }
    }
    return true;
}

function crearGraficaComparativaVariosCursos(_bindto, info_grafica, cursos, titulo, id) {
    div_id = "chart__";
    if (typeof currentTab != 'undefined') {
        div_id += '_';
        div_id += currentTab;
    }
    if (typeof id != "undefined") {
        div_id += id;
    }
    insertarTituloSeparador(_bindto, titulo);
    $(_bindto).append(`
                <div class="col-sm-12">
                    <div class="card bg-faded border-0 m-2" id="">
                    <div class="align-items-end">
                            <div class="fincard text-center">
                                <a href="#">Comparativas</a>
                            </div>
                        </div>
                        <div class="bg-white m-2" id="${div_id}"></div>                        
                    </div>
                </div>`);
    // Gráfica de líneas rectas
    // var chartz = c3.generate({
    //     data: {
    //         columns: info_grafica,
    //         type: ''
    //     },        
    //     bindto: '#' + div_id,
    //     tooltip: {
    //         format: {
    //             title: function (d) { return cursos[d].title; },
    //             value: function (value, ratio, id) {
    //                 return value + " %";
    //             }
    //         }
    //     }
    // });

    // Gráfica de curvas
    // var chartz = c3.generate({
    //     data: {
    //         columns: info_grafica,
    //         type: 'spline'
    //     },        
    //     bindto: '#' + div_id,
    //     tooltip: {
    //         format: {
    //             title: function (d) { return cursos[d].title; },
    //             value: function (value, ratio, id) {
    //                 return value + " %";
    //             }
    //         }
    //     }
    // });


    // var titulos_cursos=['x'];
    // var arr_grafica = [];
    // console.log("arr_grafica");

    // for(i=0; i<cursos.length; i++){
    //     titulos_cursos.push(cursos[i].title)
    // }

    // for(i=0; i<info_grafica.length; i++){
    //     arr_grafica.push(info_grafica[i])
    // }

    // console.log(arr_grafica);

    //Gráfica de barra agrupada
    var chartz = c3.generate({
        data: {
            x: 'x',
            columns: info_grafica,
            // columns: [titulos_cursos, arr_grafica],
            type: 'bar',
            colors: {
                'Ideal de cobertura': '#8a7e7e',
            }
        },
        axis: {
            x: {
                type: 'category' // this needed to load string x value
            }
        },
        bindto: '#' + div_id,
        tooltip: {
            format: {
                title: function (d) { return cursos[d].title; },
                value: function (value, ratio, id) {
                    return value + " %";
                }
            }
        }
    });



    console.log('INFORMACION TABLA');
    console.log(cursos);

    // if(currentTab != 3){
    //     $(_bindto).append(`        
    //         <div class="col-sm-12">
    //         <table frame="void" id="tabla_comparativa${currentTab}" rules="rows" style="width:100%;text-align: center;">

    //             <tr class="rankingt${currentTab}">
    //                 <th>Nombre del curso</th>
    //                 <th class="txt_tabla_aprobados">Aprobados</th>
    //                 <th class="txt_tabla_no_aprobados">No Aprobados</th>
    //                 <th class="txt_tabla_inscritos">Total de usuarios inscritos</th>                    
    //                 <th class="txt_tabla_porcentaje_aprobacion">Porcentaje de Aprobación del curso</th>                        
    //             </tr>                                       
    //         </table>
    //         <br>
    //         </div>        
    //     `);   
    //     for(var j = 0; j < cursos.length; j++){                  
    //         $(`#tabla_comparativa${currentTab}`).append(`<tr>        
    //             <td>${cursos[j].title}</td>
    //             <td class="txt_tabla_aprobados">${cursos[j].approved_users}</td>
    //             <td class="txt_tabla_no_aprobados">${cursos[j].not_approved_users}</td>
    //             <td class="txt_tabla_inscritos">${cursos[j].enrolled_users}</td>                
    //             <td class="txt_tabla_porcentaje_aprobacion">${cursos[j].percentage} %</td>
    //             </tr> 
    //         `);                
    //     }
    // }
}

function dashboardCrearGraficaComparativaGrupoDeCursos(_bindto, info_grafica, cursos, titulo, id) {
    div_id = "course_group_";
    if (typeof currentTab != 'undefined') {
        div_id += '_';
        div_id += currentTab;
    }
    if (typeof id != "undefined") {
        div_id += id;
    }
    insertarTituloSeparador(_bindto, titulo);
    $(_bindto).append(`
                <div class="col-sm-12">
                    <div class="card bg-faded border-0 m-2" id="">
                    <div class="align-items-end">
                            <div class="fincard text-center">
                                <a href="#">${titulo}</a>
                            </div>
                        </div>
                        <div class="bg-white m-2" id="${div_id}"></div>                        
                    </div>
                </div>`);

    return c3.generate({
        data: {
            x: 'x',
            columns: info_grafica,
            type: 'bar',
            colors: {
                Inscritos: '#a5a3a4',
                Aprobados: '#016392',
                'No Aprobados': '#d70c20'
            },
            groups: [
                ['Aprobados', 'No Aprobados']
            ]
        },
        bindto: '#' + div_id,
        axis: {
            x: {
                type: 'category' // this needed to load string x value
            }
        },
    });
}

function crearTarjetaParaGraficakpi(div, curso, kpi, id) {
    id_para_Grafica = "chart_" + id + '_' + curso.id;
    if (typeof mostrarEnlaces != 'undefined') {
        enlace = "detalle_curso_iframe.php?id=" + curso.id;
    } else {
        enlace = '#';
    }
    $(div).append(`<div class="col-sm-12 col-xl-6 espacio">
                <div class="card bg-gray border-0 m-2" id="">
                <div class="align-items-end">
                        <div class="fincard text-center">
                            <a href="${enlace}">${curso.title}</a>
                        </div>
                    </div>
                    <div class="card esp">
                    <div class="row espr">
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text txti_aprobados">Aprobados</p>
                                <p class="card-text txtnum_aprobados">${curso.approved_users} (${curso.percentage} %)</p>
                            </div>
                        </div>
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text txti_noaprobados">No Aprobados</p>
                                <p class="card-text txtnum_noaprobados">${curso.not_approved_users}</p>
                            </div>
                        </div>
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text txti_inscritos">Total de usuarios inscritos</p>
                                <p class="card-text txtnum_inscritos">${curso.enrolled_users}</p>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="chart_ bg-faded m-2" id="${id_para_Grafica}"></div>                    
                </div>
            </div>`);
    return crearGraficaDeCursokpi('#' + id_para_Grafica, curso, kpi);
}

function crearGraficaDeCursokpi(_bindto, curso, kpi) {
    _columns = [
        ['Porcentaje de aprobación', curso.percentage],
        kpi,
    ];
    var nombre_columnas = ["Porcentaje de aprobación", "", "No iniciaron el curso", ""];
    return c3.generate({
        data: {
            columns: _columns,
            type: 'bar',
            colors: {

            }

        },
        bindto: _bindto,
        tooltip: {
            format: {
                title: function (d) {
                    if (nombre_columnas[d] !== undefined) {
                        return nombre_columnas[d];
                    } else {
                        return "_";
                    }
                },
            }
        }
    });
}

function peticionFiltros(info) {
    // if(isFilterLoading){
    //     console.log('Cargando contenido de cursos, no debe procesar más peticiones por el momento');
    //     return;
    // }
    // isFilterLoading = !isFilterLoading;
    dateBeginingFiltros = Date.now();
    // if (typeof muestraComparativas != 'boolean') {
    //     muestraComparativas = false;
    // }
    muestraComparativas = true; // Se mostrarán comparativas a todos los cursos por defecto
    $.ajax({
        type: "POST",
        url: "services.php",
        data: info,
        dataType: "json"
    })
        .done(function (data) {
            isFilterLoading = false;
            console.log('Filtros devueltos', data);
            keys = Object.keys(data.data);
            // div_selector = '#indicator_section_' + clave;
            div_selector = '#contenedor_filtros';
            crearElementos = esVacio($(div_selector).html());
            for (var index = 0; index < keys.length; index++) {
                clave = keys[index]
                var catalogo = data.data[clave];
                heading_id = "indicatorheading" + clave;
                collapse_id = "collapse_" + clave;
                subfiltro_id = 'subfilter_id_' + clave;
                if (crearElementos) {
                    $(div_selector).append(`
                    <div class="card">
                        <div class="card-header cuerpo-filtro" id="${heading_id}">
                            <h5 class="mb-0">
                                <span class="btn btn-link collapsed texto-filtro"
                                    data-toggle="collapse" style="color: white;text-transform: uppercase;font-size: 0.8rem;" data-target="#${collapse_id}" aria-expanded="false"
                                    aria-controls="${collapse_id}">
                                    ${clave}
                                </span>
                                ${muestraComparativas ? `<span class="btn btn-link text-right texto-filtro" onclick="compararFiltros('${clave}')" style="color: white;font-size: 0.8rem;">Comparar</span>` : ``}
                            </h5>
                        </div>
                        <div id="${collapse_id}" class="collapse" aria-labelledby="${heading_id}" data-parent="#contenedor_filtros">
                            <div class="card-body subgrupo-filtro" id='${subfiltro_id}'></div>
                        </div>
                    </div>`);
                    // }else{
                }
                subfiltro_id = "#" + subfiltro_id;
                $(subfiltro_id).html('');
                // console.log($(subfiltro_id));
                _claves = Object.keys(catalogo);
                console.log(clave, _claves.length);
                if (clave == 'ccosto') {
                    _claves = _claves.sort();
                }
                for (var j = 0; j < _claves.length; j++) {
                    var valor_elemento = _claves[j];
                    var elementoDeCatalogo = catalogo[valor_elemento];
                    if (clave == 'ccosto') {
                        $(subfiltro_id).append(`
                                <label class="text-uppercase subfiltro"><input type="checkbox" name="${clave}[]"
                                class="indicator_option text-uppercase indicator_${clave}\" onclick="loaderFiltro(),obtenerInformacion('${clave}')"
                                data-indicator=\"${clave}\" value=\"${elementoDeCatalogo}\"
                                >
                                 ${esVacio(valor_elemento) ? " (Vacío)" : ' (' + elementoDeCatalogo + ')' + valor_elemento}</label><br>
                    `);
                    } else {
                        $(subfiltro_id).append(`
                                <label class="text-uppercase subfiltro"><input type="checkbox" name="${clave}[]"
                                class="indicator_option text-uppercase indicator_${clave}\" onclick="loaderFiltro(),obtenerInformacion('${clave}')"
                                data-indicator=\"${clave}\" value=\"${valor_elemento}\"
                                >
                                 ${esVacio(elementoDeCatalogo) ? " (Vacío)" : valor_elemento}</label><br>
                    `);
                    }
                }
            }
            dateEnding = Date.now();
            console.log(`Tiempo de respuesta al obtener filtros de API ${dateEnding - dateBegining} ms`);
            setTimeout(function () {
                showPage_filtro("contenido_dashboard");
            }, 1000)
            showPage_comparar("ldm_comparativas");

        })
        .fail(function (error, error2) {
            isFilterLoading = false;
            console.log(error);
            console.log(error2);
        });
}
function obtenerFiltros(indicator) {
    // console.log("Obteniendo filtros");
    info = $('#filter_form').serializeArray();
    dateBegining = Date.now();
    info.push({ name: 'request_type', value: 'user_catalogues' });
    if (indicator != undefined) {
        info.push({ name: 'selected_filter', value: indicator });
    }
    peticionFiltros(info);
}

//Funcion de loader general
var myVar;

function loaderGeneral() {
    myVar = setTimeout(showPage, 50);
}

function showPage(id_div) {
    document.getElementById("loader").style.display = "none";
    //document.getElementById(id_div).style.display = "block";
}

function mostrarLoader() {
    document.getElementById("loader").style.display = "block";
}

function ocultarLoader() {
    document.getElementById("loader").style.display = "none";
}

function hidePage(id_div) {
    document.getElementById("loader").style.display = "block";
    //   document.getElementById(id_div).style.display = "none";
}

//Funcion para cargar el loader con los filtros
var variable_filtro;

function loaderFiltro() {
    variable_filtro = setTimeout(hidePage_filtro, 100);
}

function showPage_filtro() {
    document.getElementById("loader").style.display = "none";
    //document.getElementById("ldm_tab_" + currentTab).style.display = "block";

}

function hidePage_filtro() {
    document.getElementById("loader").style.display = "block";
    //   document.getElementById("ldm_tab_" + currentTab).style.display = "none";

}

//Funcion para cargar el loader a la fecha en detalle de curso
var variable_fecha;

function loaderFecha() {
    variable_fecha = setTimeout(hidePage_fecha, 100);
}

function showPage_fecha() {
    document.getElementById("loader").style.display = "none";
    document.getElementById("contenido_dashboard").style.display = "block";

}

function hidePage_fecha() {
    document.getElementById("loader").style.display = "block";
    //   document.getElementById("contenido_dashboard").style.display = "none";

}

//------

//Funcion para cargar el loader a comparar en detalle de curso
var variable_comparar;

function loaderComparar() {
    variable_comparar = setTimeout(hidePage_comparar, 100);
}

function showPage_comparar() {
    document.getElementById("loader").style.display = "none";
    comp = document.getElementById("ldm_comparativas");
    if (comp != null) {
        comp.style.display = "block";
    }

}

function hidePage_comparar() {
    document.getElementById("loader").style.display = "block";
    //   document.getElementById("contenido_dashboard").style.display = "none";

}

//------

function imprimirRanking(div, info) {
    var colorTop = "#29B6F6";
    var colorBottom = "#FF6F00";
    ranking_top = "#dominosdashboard-ranking-top";
    ranking_bottom = "#dominosdashboard-ranking-bottom";
    ranking_top_body = "#tbody-ranking-top";
    ranking_bottom_body = "#tbody-ranking-bottom";
    ranking_clase = ".dominosdashboard-ranking";
    ranking_titulo = "#dominosdashboard-ranking-title";
    $(div).html('');
    if (Array.isArray(info.activities)) {
        activities = info.activities;
        num_activities = info.activities.length;
        enrolled_users = parseInt(info.enrolled_users);
        if (enrolled_users < 1) {
            return false;
        }
        insertarTituloSeparador(div, 'Ranking de actividades');
        if (num_activities >= 6) { // Se muestran 2 rankings
            $(div).append(`
                    <div class="col-sm-6 dominosdashboard-ranking" id="dominosdashboard-ranking-top">
                        <table frame="void" rules="rows" style="width:100%">
                            <tr class="rankingt">
                                <th>#</th>
                                <th>Actividades</th>
                                <th>Aprobados</th>
                            </tr>
                            <tbody id="tbody-ranking-top"></tbody>
                        </table>
                    </div>
                    <div class="col-sm-6 dominosdashboard-ranking" id="dominosdashboard-ranking-bottom">
                        <table frame="void" rules="rows" style="width:100%">
                            <tr class="rankingt">
                                <th>#</th>
                                <th>Actividades</th>
                                <th>No Aprobados</th>
                            </tr>
                            <tbody id="tbody-ranking-bottom"></tbody>
                        </table>
                    </div>
                `);
            var contenido = "";
            for (var i = 0; i < 3; i++) {
                var elemento = activities[i];
                percentage = Math.floor(elemento.completed / enrolled_users * 100);
                contenido += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${elemento.title}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" style="width: ${percentage}%; background: ${colorTop}; color: #525252; border-radius: 5px; text-align: center;">${percentage}%</div>
                        </div>
                    </td>
                </tr>`;
            }
            $(ranking_top_body).html(contenido);
            $(ranking_bottom_body).html('');
            var contenido = "";
            for (var i = 1; i <= 3; i++) {
                var elemento = activities[num_activities - i];
                notCompleted = enrolled_users - elemento.completed;
                percentage = Math.floor(notCompleted / enrolled_users * 100);
                contenido += `
                <tr>
                    <td>${i}</td>
                    <td>${elemento.title}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" style="width: ${percentage}%; background: ${colorBottom}; color: #525252;border-radius: 5px; text-align: center;">${percentage}%</div>
                        </div>
                    </td>
                </tr>`;
            }
            $(ranking_bottom_body).html(contenido);
            return;
        } else if (num_activities > 0) { // Sólo se muestra un ranking
            $(div).append(`
                    <div class="col-sm-8 offset-sm-4 dominosdashboard-ranking" id="dominosdashboard-ranking-top">
                        <table frame="void" rules="rows" style="width:100%">
                            <tr class="rankingt">
                                <th>#</th>
                                <th>Actividades</th>
                                <th>Aprobados</th>
                            </tr>
                            <tbody id="tbody-ranking-top"></tbody>
                        </table>
                    </div>
                `);
            var contenido = "";
            for (var i = 0; i < 3; i++) {
                if (activities[i] == undefined) {
                    continue;
                }
                var elemento = activities[i];
                percentage = Math.floor(elemento.completed / enrolled_users * 100);
                contenido += `
                <tr>
                    <td>${i}</td>
                    <td>${elemento.title}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" style="width: ${percentage}%; background: ${colorTop}; color: #525252; border-radius: 5px; text-align: center;">${percentage}%</div>
                        </div>
                    </td>
                </tr>`;
            }
            $(ranking_top_body).html(contenido);
            return;
        }
        return;
    }
}

function imprimirDIV(contenido) {
    var ficha = document.getElementById(contenido);
    var ventanaImpresion = window.open(' ', 'popUp');
    ventanaImpresion.document.write('<link href="libs/c3.css" rel="stylesheet"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous"><link href="estilos.css" rel="stylesheet">' + ficha.innerHTML);
    ventanaImpresion.document.close();
    ventanaImpresion.print();
    ventanaImpresion.close();
}

//Función donde se compara el avance de cada region
function seccion_a_imprimirGraficaComparativaCursos(container, respuesta) {

    var nombre_curso = [];
    var arrRegions = [];
    var arrGraph = [];
    nombre_curso.push('x');


    respuesta = respuesta.sections.seccion_a;
    cursos = respuesta.courses;
    for (var i = 0; i < cursos.length; i++) {
        var curso = cursos[i];
        nombre_curso.push(curso.title);
        // nombre_curso.push(curso.region_comparative.title);
        comparativa = curso.region_comparative.comparative;

        for (var j = 0; j < comparativa.length; j++) {
            comparativa_actual = comparativa[j];
            var region_percentage = comparativa_actual.percentage;
            saveRegionCourse(comparativa_actual.name, j, region_percentage, arrRegions);

           
        }
    }
    

    arrGraph.push(nombre_curso);
    for (i = 0; i < arrRegions.length; i++) {
        arrGraph.push(arrRegions[i]);
    }
   
    createCardGrahp_comparative(container, respuesta.name, arrGraph, i);
    
}

function saveRegionCourse(region, id, percentage, arrRegions) {
    if (arrRegions[id] != undefined) {
        //console.log(arrRegions[id]);
        arrRegions[id].push(parseInt(percentage))
    } else {
        arrRegions[id] = [region, parseInt(percentage)];
    }

     
}

function createCardGrahp_comparative(container, title, arrGraph, id) {
    
    var cardKPIRegion = "<div class='col-sm-12 espacio'>" +
        "<div class='card bg-gray border-0 m-2'>" +
            "<div class='align-items-end'>" +
                "<div class='fincard text-center'>" +
                    "<a href=''>" + title + "</a>" +
                "</div>" +
            "</div>" +
            "<div class='card esp'>" +
                "<div class='row espr'>" +
                "</div>" +
            "</div>" +
            "<div class='chart_ bg-faded m-2' id='grafica_a_kpi" + id + "'></div>"
        "</div>" +
        "</div>";

    $(container).append(cardKPIRegion);

    console.log('arrGraph');
    console.log(arrGraph);
    return c3.generate({
        data: {
            x: 'x',
            columns: arrGraph,
            type: 'spline'
        },
        axis: {
            x: {
                type: 'category' // this needed to load string x value
            }
        },
        bindto: "#grafica_a_kpi" + id,
    });
}


//Función para imprimir el porcentaje de aprobación de cada curso
function seccion_b_imprimirGraficaComparativaCursos(container, respuesta) {
    for (var i = 0; i < respuesta.sections.seccion_b.courses.length; i++) {
        var c_aprobados = [];
        c_aprobados.push('Porcentaje Aprobados');
        var percentage_aprobados = respuesta.sections.seccion_b.courses[i];
        c_aprobados.push(percentage_aprobados.percentage);
        createCardGrahp_gauge(container, respuesta.sections.seccion_b.courses[i].title, c_aprobados, i)
    }
    
}
function createCardGrahp_gauge(container, title, c_aprobados, id) {

    var card_gauge = "<div class='col-sm-6 espacio'>" +
        "<div class='card bg-gray border-0 m-2'>" +
            "<div class='align-items-end'>" +
                "<div class='fincard text-center'>" +
                    "<a href='detalle_curso_iframe.php?id='>" + title + "</a>" +
                "</div>" +
            "</div>" +
            "<div class='card esp'>" +
                "<div class='row espr'>" +
                "</div>" +
            "</div>" +
            "<div class='chart_  bg-faded m-2' id='grafica_gauge" + id + "'></div>"
        "</div>" +
        "</div>";

    $(container).append(card_gauge);

    return c3.generate({
        data: {
            columns: [
                c_aprobados
            ],
            type: 'gauge',
            colors: {
                'Porcentaje Aprobados': '#ed403c',

            },
        },
        bindto: "#grafica_gauge" + id,
    });
}

function seccion_c_imprimirGraficaComparativaCursos(container, respuesta) {
    r_seccionc = respuesta.sections.seccion_c;
    for (var i = 0; i < r_seccionc.courses.length; i++) {
        var c_percentage_region = Array();
        var regiones = r_seccionc.courses[i];
        for (var j = 0; j < regiones.region_comparative.comparative.length; j++) {
            c_percentage_region.push([regiones.region_comparative.comparative[j].name, parseInt(regiones.region_comparative.comparative[j].percentage)]);
        }
        createCardGrahp_horizontalBar(container, r_seccionc.name, c_percentage_region, i);
    }    
}

function createCardGrahp_horizontalBar(container, title, c_percentage_region, id) {

    var card_horizontal = "<div class='col-sm-12 espacio'>" +
        "<div class='card bg-gray border-0 m-2'>" +
            "<div class='align-items-end'>" +
                "<div class='fincard text-center'>" +
                    "<a href=''>" + title + "</a>" +
                "</div>" +
            "</div>" +
            "<div class='card esp'>" +
                "<div class='row espr'>" +
                "</div>" +
            "</div>" +
            "<div class='chart_ bg-faded m-2' id='grafica_horizontal" + id + "'></div>"
        "</div>" +
        "</div>";
   
    $(container).append(card_horizontal);

    return c3.generate({
        data: {
            columns:
                c_percentage_region,

            type: 'bar',            
        },
        axis: {
            rotated: true
        },
        bindto: "#grafica_horizontal" + id,
        grid: {
            y: {
                lines: [{ value: 0 }]
            }
        }
    });
}

//Función para pintar una card donde se comparen los cursos, en la primera pestaña
function seccion_d_imprimirGraficaComparativaCursos(container, respuesta) {
    r_secciond = respuesta.sections.seccion_d;
    var cursos_d = [];
    cursos_d.push('x');
    var inscritos_d = [];
    var aprobados_d = [];
    var no_aprobados_d = [];
    inscritos_d.push('Inscritos');
    aprobados_d.push('Aprobados');
    no_aprobados_d.push('No Aprobados');
    for (i = 0; i < r_secciond.courses.length; i++) {
        var curso_name = r_secciond.courses[i];
        cursos_d.push(curso_name.title);
        inscritos_d.push(curso_name.enrolled_users);
        aprobados_d.push(curso_name.approved_users);
        no_aprobados_d.push(curso_name.not_approved_users);
    }
    createCardGrahp_group(container, r_secciond.name, cursos_d, inscritos_d, aprobados_d, no_aprobados_d);
    

}

function createCardGrahp_group(container, title, cursos_d, inscritos_d, aprobados_d, no_aprobados_d) {

    var card_group = "<div class='col-sm-12 espacio'>" +
        "<div class='card bg-gray border-0 m-2'>" +
            "<div class='align-items-end'>" +
                "<div class='fincard text-center'>" +
                    "<a href=''>" + title + "</a>" +
                "</div>" +
            "</div>" +
            "<div class='card esp'>" +
                "<div class='row espr'>" +
                "</div>" +
            "</div>" +
            "<div class='chart_ bg-faded m-2' id='grafica_group'></div>"
        "</div>" +
        "</div>";

    $(container).append(card_group);

    return c3.generate({
        data: {
            x: 'x',
            columns: [
                cursos_d,
                inscritos_d,
                aprobados_d,
                no_aprobados_d
            ],
            groups: [
                ['Aprobados', 'No Aprobados']
            ],
            type: 'bar',
            colors: {
                Inscritos: '#a5a3a4',
                Aprobados: '#016392',
                'No Aprobados': '#d70c20'

            },
        },
        axis: {
            x: {
                type: 'category' // this needed to load string x value
            }
        },
        bindto: "#grafica_group",
        
    });
}



//Funcion de kpi por region en la pestaña 3
function kpi_region(container, respuesta) {

    console.log("Respuesta ");
    console.log(respuesta);



    for (var i = 0; i < respuesta.length; i++) {
        var nombre_region = [];
        var region_avance = [];
        var kpi_comparative = [];
        nombre_region.push('x');
        region_avance.push('Avance');
        kpi_comparative.push('KPI');
        

        for (var j = 0; j < respuesta[i].course_information.region_comparative.comparative.length; j++) {
            var kpi_name_region = respuesta[i].course_information.region_comparative.comparative[j];
            nombre_region.push(kpi_name_region.name);
            region_avance.push(kpi_name_region.percentage);
            kpi_comparative.push(respuesta[i].kpi.status);
        }
       
        createCardGrahp(container, respuesta[i].kpi_name + " vs " + respuesta[i].course_name.toUpperCase(), region_avance, nombre_region, kpi_comparative, i)
    }
}

function createCardGrahp(container, title, region_avance, nombre_region, kpi_comparative, id) {
    
    var cardKPIRegion = "<div class='col-sm-12 espacio'>" +
        "<div class='card bg-gray border-0 m-2'>" +
            "<div class='align-items-end'>" +
                "<div class='fincard text-center'>" +
                    "<a href=''>" + title + "</a>" +
                "</div>" +
            "</div>" +
            "<div class='card esp'>" +
                "<div class='row espr'>" +
                "</div>" +
            "</div>" +
            "<div class='chart_ bg-faded m-2' id='grafica_a_kpi" + id + "'></div>"
        "</div>" +
        "</div>";

    $(container).append(cardKPIRegion);

    
    return c3.generate({
        data: {
            x: 'x',
            columns: [
                nombre_region,
                region_avance,
                kpi_comparative
                
            ],
            type: 'spline'
        },
        axis: {
            x: {
                type: 'category' // this needed to load string x value
            }
        },
        bindto: "#grafica_a_kpi" + id,
    });
}

function detalles_entrenamiento(container, respuesta) {
    for (i = 0; i < respuesta.length; i++) {
        var cursos_entrenamiento = [];
        cursos_entrenamiento.push('x');
        var inscritos_entrenamiento = [];
        inscritos_entrenamiento.push('Inscritos');
        var aprobados_entrenamiento = [];
        aprobados_entrenamiento.push('Aprobados');
        var no_aprobados_entrenamiento = [];
        no_aprobados_entrenamiento.push('No Aprobados');
        cursos_entrenamiento.push(respuesta[i].title);
        inscritos_entrenamiento.push(respuesta[i].enrolled_users);
        aprobados_entrenamiento.push(respuesta[i].approved_users);
        no_aprobados_entrenamiento.push(respuesta[i].not_approved_users);
        createCardGrahp_entrenamiento(container, respuesta[i].title, respuesta[i].approved_users, respuesta[i].not_approved_users, respuesta[i].enrolled_users, cursos_entrenamiento, inscritos_entrenamiento, aprobados_entrenamiento, no_aprobados_entrenamiento, i);
    }
}

function createCardGrahp_entrenamiento(container, title, aprobados, no_aprobados, inscritos, cursos_entrenamiento, inscritos_entrenamiento, aprobados_entrenamiento, no_aprobados_entrenamiento, id) {
    
    var cardEntrenamiento = "<div class='col-sm-6 espacio'>" +
        "<div class='card bg-gray border-0 m-2'>" +
            "<div class='align-items-end'>" +
                "<div class='fincard text-center'>" +
                    "<a href=''>" + title + "</a>" +
                "</div>" +
            "</div>" +
                "<div class='card esp'>" +
                    "<div class='row espr'>" +
                        "<div class='border-0 col-sm-4'>"+
                            "<div class='card-body'>"+
                                "<p class='card-text text-center txti_aprobados'>Aprobados</p>"+
                                "<p class='card-text text-center txti_aprobados' id='apro2'>"+aprobados+"</p>"+
                            "</div>"+
                        "</div>"+
                        "<div class='border-0 col-sm-4'>"+
                            "<div class='card-body text-center'>"+
                                "<p class='card-text text-center txti_noaprobados'>No Aprobados</p>"+
                                "<p class='card-text text-center txti_noaprobados' id='no_apro2'>"+no_aprobados+"</p>"+
                            "</div>"+
                        "</div>"+
                        "<div class='border-0 col-sm-4'>"+
                            "<div class='card-body text-center'>"+
                                "<p class='card-text text-center txti_inscritos'>Inscritos</p>"+
                                "<p class='card-text text-center txti_inscritos' id='tusuario2'>"+inscritos+"</p>"+
                            "</div>"+
                        "</div>"+ 
                    "</div>" +
                "</div>" +
            "<div class='chart_ bg-faded m-2' id='graph_entrenamiento" + id + "'></div>"
        "</div>" +
        "</div>";

    $(container).append(cardEntrenamiento);

    
    return c3.generate({
        data: {
                    x: 'x',
                    columns: [
                        cursos_entrenamiento,
                        inscritos_entrenamiento,
                        aprobados_entrenamiento,
                        no_aprobados_entrenamiento
                    ],
                    groups: [
                        ['Aprobados', 'No Aprobados']
                    ],
                    type: 'bar',
                    colors: {
                        Inscritos: '#a5a3a4',
                        Aprobados: '#016392',
                        'No Aprobados': '#d70c20'
        
                    },
                },
                axis: {
                    x: {
                        type: 'category' // this needed to load string x value
                    }
                },
                bindto: "#graph_entrenamiento" + id,
                grid: {
                    y: {
                        lines: [{ value: 0 }]
                    }
                }
    });
}