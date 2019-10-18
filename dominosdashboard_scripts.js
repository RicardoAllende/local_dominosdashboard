function esVacio(_elemento){
    if(_elemento === undefined){
        return true;
    }
    if(_elemento === null){
        return true;
    }
    if(Array.isArray(_elemento)){
        if(_elemento.length == 0){
            return true;
        }
    }
    if(_elemento == ""){
        return true;
    }
    return false;
}

function obtenerNumero(val) {
    return !isNaN(parseFloat(val)) && 'undefined' !== typeof val ? parseFloat(val) : false;
}

function obtenerPorcentaje(percent, total, decimales) {
    if(decimales == undefined){
        decimales = 2;
    }
    var per = obtenerNumero(percent),
        div = obtenerNumero(total);

    if (false === per) return 0;
    if (false === div || div === 0) return 0;

    return parseFloat((per / div * 100).toFixed(decimales));
}

function insertarTituloSeparador(div, titulo){
    $(div).append(`
    <div class="col-sm-12 col-xl-12">
        <div class="titulog col-sm-12">
            <h1 class="text-center">${titulo}</h1>
        </div>
    </div>`);
}

function obtenerDefaultEnNull(valor, porDefault){
    if(esVacio(porDefault)) porDefault = 0;
    if(esVacio(valor)){
        return porDefault;
    }
    return valor;
}

function exportar_a_excel(){
    document.forms.filter_form.submit();
}

//Funcion para mostar la grafica sin informacion
function insertarGraficaSinInfo(div, mensaje){
    if(typeof mensaje == 'undefined'){
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

function crearGraficaDeCurso(_bindto, curso){
    // console.log('Probable error', curso);
    switch(curso.chart){
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
            _columns  = [ ['Aprobados', curso.percentage] ];
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
        
        case 'comparativa_regiones' :
                imprimirComparativaFiltrosDeCurso(_bindto, curso.region_comparative);
                break;
        default:
            $(_bindto).html('');
            return;
    }
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
                title: function (d) {
                    if(nombre_columnas[d] !== undefined){
                        return nombre_columnas[d];
                    }else{
                        return "_";
                    }
                },
            }
        }
    });
}

function crearTarjetaParaGrafica(div, curso, claseDiv){
    if(typeof claseDiv !== 'string'){
        claseDiv = "col-sm-4";
    }
    id_para_Grafica = "chart_" + curso.id;
    if(typeof currentTab != 'undefined'){
        id_para_Grafica += '_' + currentTab;
    }
    if(typeof mostrarEnlaces != 'undefined'){
        enlace = "detalle_curso_iframe.php?id=" + curso.id;
    }else{
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

function obtenerFiltros(indicator){
    informacion = $('#filter_form').serializeArray();
    informacion.push({name: 'request_type', value: 'user_catalogues'});
    if(indicator != undefined){
        informacion.push({name: 'selected_filter', value: indicator});
    }
    peticionFiltros(informacion);
}


function generarGraficasTodosLosCursos(_bindto, response, titulo) {
    type = response.type;
    $(_bindto).html('');
    if(type == 'course_list'){
        cursos = response.result;
        if(Array.isArray(cursos)){
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
            for (var i = 0; i < cursos.length; i++) {
                var curso = cursos[i];
                crearTarjetaParaGrafica(_bindto, curso);
            }
        }
    }
    if(type == 'kpi_list'){
        kpis = response.result;
        if(Array.isArray(kpis)){
            for (var i = 0; i < kpis.length; i++) {
                var aprobados = Array();
                var _ideal_cobertura = Array();
                aprobados.push("Porcentaje de aprobación del curso");
                kpi = kpis[i];
                cursos = kpi.courses;
                if(esVacio(kpi.status)){ // No hay kpi devuelto
                    div_id = "chart__";
                    if(typeof currentTab != 'undefined'){
                        div_id += '_';
                        div_id += currentTab;
                    }
                    if(typeof id != "undefined"){
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

               
               
                switch(kpi.type){
                    case "Texto": // Nombre
                        valores = Array();

                        _keys = Object.keys(kpi.status);
                        info_grafica = Array();
                        info_grafica.aprobados = Array();
                        info_grafica.aprobados.push('Porcentaje de aprobación');
                        nombre_cursos = Array();
                        nombre_cursos.push('x');

                        for(var t = 0; t < _keys.length; t++){
                            _current_key = _keys[t];
                            info_grafica[_current_key] = Array();
                            info_grafica[_current_key].push(_current_key);
                        }
                        for(var j = 0; j < cursos.length; j++){
                            curso = cursos[j];
                            nombre_cursos.push(curso.title);
                            info_grafica.aprobados.push(curso.percentage);
                            for(var r = 0; r < _keys.length; r++){
                                _current_key = _keys[r];
                                info_grafica[_current_key].push(kpi.status[_current_key]);
                            }
                        }

                        _keys = Object.keys(info_grafica);
                        info_procesada_grafica = Array();
                        for(var r = 0; r < _keys.length; r++){
                            _current_key = _keys[r];
                            info_procesada_grafica.push(info_grafica[_current_key]);
                            // info_grafica[_current_key] = Array();
                            // info_grafica[_current_key].push(kpi.status[_current_key]);
                        }
                        info_procesada_grafica.push(nombre_cursos);

                        div_id = "chart__";
                        if(typeof currentTab != 'undefined'){
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
                        for(var j = 0; j < cursos.length; j++){
                            curso = cursos[j];

                            info_grafica_kpi = Array();
                            info_grafica_kpi.push(['Porcentaje de aprobación', curso.percentage]);
                            for(var r = 0; r < _keys.length; r++){
                                ck = _keys[r];
                                info_grafica_kpi.push([ck, kpi.status[ck]]);
                            }

                            // crearTarjetaParaGraficakpi(_bindto, curso, __kpi, kpi.id);

                            id_para_Grafica = "chart_" + kpi.id + '_' + curso.id;
                            if(typeof mostrarEnlaces != 'undefined'){
                                enlace = "detalle_curso_iframe.php?id=" + curso.id;
                            }else{
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
                        for(var j = 0; j < cursos.length; j++){
                            curso = cursos[j];
                            aprobados.push(curso.percentage);
                            info_kpi.push(kpi.status);
                            nombres.push(curso.title);
                        }
                        crearGraficaComparativaVariosCursos(_bindto, [nombres, aprobados, info_kpi], cursos, kpi.name, kpi.id);

                        for(var j = 0; j < cursos.length; j++){
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

function crearGraficaComparativaVariosCursos(_bindto, info_grafica, cursos, titulo, id){
    div_id = "chart__";
    if(typeof currentTab != 'undefined'){
        div_id += '_';
        div_id += currentTab;
    }
    if(typeof id != "undefined"){
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

function crearTarjetaParaGraficakpi(div, curso, kpi, id){
    id_para_Grafica = "chart_" + id + '_' + curso.id;
    if(typeof mostrarEnlaces != 'undefined'){
        enlace = "detalle_curso_iframe.php?id=" + curso.id;
    }else{
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

function crearGraficaDeCursokpi(_bindto, curso, kpi){
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
                    if(nombre_columnas[d] !== undefined){
                        return nombre_columnas[d];
                    }else{
                        return "_";
                    }
                },
            }
        }
    });
}

function peticionFiltros(info){
    // if(isFilterLoading){
    //     console.log('Cargando contenido de cursos, no debe procesar más peticiones por el momento');
    //     return;
    // }
    // isFilterLoading = !isFilterLoading;
    dateBeginingFiltros = Date.now();
    if(typeof muestraComparativas != 'boolean'){
        muestraComparativas = false;
    }
    $.ajax({
        type: "POST",
        url: "services.php",
        data: info,
        dataType: "json"
    })
    .done(function(data) {
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
            if(crearElementos){
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
            if(clave == 'ccosto'){
                _claves = _claves.sort();
            }
            for(var j = 0; j < _claves.length; j++){
                var valor_elemento = _claves[j];
                var elementoDeCatalogo = catalogo[valor_elemento];
                if(clave == 'ccosto'){
                    $(subfiltro_id).append(`
                                <label class="text-uppercase subfiltro"><input type="checkbox" name="${clave}[]"
                                class="indicator_option text-uppercase indicator_${clave}\" onclick="loaderFiltro(),obtenerInformacion('${clave}')"
                                data-indicator=\"${clave}\" value=\"${elementoDeCatalogo}\"
                                >
                                 ${esVacio(valor_elemento) ? " (Vacío)" : ' (' + elementoDeCatalogo + ')' + valor_elemento }</label><br>
                    `);
                }else{
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
        setTimeout(function(){            
            showPage_filtro("contenido_dashboard");            
        },1000)
        showPage_comparar("ldm_comparativas");
        
    })
    .fail(function(error, error2) {
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

function mostrarLoader(){
    document.getElementById("loader").style.display = "block";
}

function ocultarLoader(){
    document.getElementById("loader").style.display = "none";
}

function hidePage(id_div){
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

function hidePage_filtro(){
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

function hidePage_fecha(){
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
    comp =  document.getElementById("ldm_comparativas");
    if(comp != null){
        comp.style.display = "block";
    }
  
}

function hidePage_comparar(){
  document.getElementById("loader").style.display = "block";
//   document.getElementById("contenido_dashboard").style.display = "none";
  
}

//------

function imprimirRanking(div, info) {
    var colorTop = "#29B6F6";
    var colorBottom = "#FF6F00";
    ranking_top         = "#dominosdashboard-ranking-top";
    ranking_bottom      = "#dominosdashboard-ranking-bottom";
    ranking_top_body    = "#tbody-ranking-top";
    ranking_bottom_body = "#tbody-ranking-bottom";
    ranking_clase       = ".dominosdashboard-ranking";
    ranking_titulo      = "#dominosdashboard-ranking-title";
    $(div).html('');
    if(Array.isArray(info.activities)){
        activities = info.activities;
        num_activities = info.activities.length;
        enrolled_users = parseInt(info.enrolled_users);
        if(enrolled_users < 1){
            return false;
        }
        insertarTituloSeparador(div, 'Ranking de actividades');
        if(num_activities >= 6){ // Se muestran 2 rankings
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
            for(var i = 0; i < 3; i++){
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
            for(var i = 1; i <= 3; i++){
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
        }else if(num_activities > 0){ // Sólo se muestra un ranking
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
            for(var i = 0; i < 3; i++){
                if(activities[i] == undefined){
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

//Función para pintar una card donde se comparen los cursos, en la primera pestaña
function imprimirGraficaComparativaCursos(){
    document.getElementById("grafica_comparativa").innerHTML = "<div class='col-sm-6 espacio'>"+
    "<div class='card bg-gray border-0 m-2'>"+
    "<div class='align-items-end'>"+
            "<div class='fincard text-center'>"+
                "<a href=''>Comparativa de cursos</a>"+
            "</div>"+
        "</div>"+
        "<div class='card esp'>"+
        "<div class='row espr'>"+           
            
            "</div>"+
        "</div>"+
        "<div class='chart_ bg-faded m-2' id='grafica_historico'></div>"                    
    "</div>"+
    "</div>";

            return c3.generate({
                data: {
                    columns: [
                        ['Aprobados', 30, 200, 200, 400, 150, 250],
                        ['No Aprobados', 130, 100, 100, 200, 150, 50],
                        ['Inscritos', 230, 200, 200, 300, 250, 250]
                    ],
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
                bindto: "#grafica_historico",
                grid: {
                    y: {
                        lines: [{value:0}]
                    }
                }
            });

}

/**
         * @param _bindto string selector con sintaxis jquery donde se imprimirán las gráficas
         */
        function imprimirComparativaFiltrosDeCurso(_bindto, informacion){
            $(_bindto).html('');
            if(!esVacio(informacion.comparative)){
                comparative = informacion.comparative;
                columns = Array();
                comparativas++;
                id_para_Grafica = 'ldm_comparativa_' + comparativas + '_' + informacion.key;
                //insertarTituloSeparador(_bindto, 'Comparativa ' + informacion.filter);
                $(_bindto).append(`<div class='col-sm-6 espacio'>
                <div class='card bg-gray border-0 m-2'>
                <div class='align-items-end'>
                <div class='fincard text-center'>
                <a href=''>Comparativa</a>
                </div>
                </div>
                <div class='card esp'>
                <div class='row espr'>
                </div>
                </div>
                <div class='chart_ bg-faded m-2' id='${id_para_Grafica}'></div>
                </div>
                </div>`);
                // $(_bindto).append(`<div><h4 style="text-transform: uppercase;">Comparativa ${informacion.filter}</h4><div id="${id_para_Grafica}"></div></div>`);
                id_para_Grafica = '#' + id_para_Grafica;
                for(var j = 0; j < comparative.length; j++){
                    datos_a_comparar = comparative[j];
                    columns.push([datos_a_comparar.name, datos_a_comparar.percentage]);
                }
                data = { columns: columns, type: 'bar'};
                var chart = c3.generate({
                    data: data,
                    axis: {
                        rotated: true
                    },
                    tooltip: {
                        format: {
                            title: function (d) { return 'Porcentaje de aprobación'; },
                        }               
                    },   
                    bindto: id_para_Grafica,
                });
            }else{
                console.log('Error de imprimirComparativaFiltrosDeCurso', informacion);
                $(_bindto).html('');
            }
        }