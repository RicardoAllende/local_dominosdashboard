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

function crearGraficaDeCurso(_bindto, curso){
    switch(curso.chart){
        case 'pie':
        case 'bar':
            _columns = [
                ['Inscritos', curso.enrolled_users],
                ['Aprobados', curso.approved_users],
                ['No iniciaron el curso', curso.not_viewed]
            ];
            var nombre_columnas = ["Inscritos", "Aprobados", "No iniciaron el curso"];
        break;
        case 'gauge':
            _columns  = [ ['Aprobados', 30] ];
            var nombre_columnas = ["Aprobados"];
        break;
        default: 
            return;
        break;
    }
    return c3.generate({
        data: {
            columns: _columns,
            type: curso.chart,
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

function crearTarjetaParaGrafica(div, curso){
    id_para_Grafica = "chart_" + curso.id;
    $(div).append(`<div class="col-sm-12 col-xl-6">
                <div class="card bg-gray border-0 m-2" id="">

                    <div class="card-group">
                        <div class="card border-0 m-2">
                            <div class="card-body text-center">
                                <p class="card-text text-primary">Aprobados</p>
                                <p class="card-text text-primary">${curso.approved_users} (${curso.percentage} %)</p>
                            </div>
                        </div>
                        <div class="card border-0 m-2">
                            <div class="card-body text-center">
                                <p class="card-text text-warning">No Aprobados</p>
                                <p class="card-text text-warning">${curso.not_approved_users}</p>
                            </div>
                        </div>
                        <div class="card border-0 m-2">
                            <div class="card-body text-center">
                                <p class="card-text text-success">Total de usuarios inscritos</p>
                                <p class="card-text text-success">${curso.enrolled_users}</p>
                            </div>
                        </div>

                    </div>
                    <div class="chart_ bg-white m-2" id="${id_para_Grafica}"></div>
                    <div class="align-items-end">
                        <div class="fincard text-center">
                            <a href="detalle_curso?id=${curso.id}">${curso.title}</a>
                        </div>
                    </div>
                </div>
            </div>`);
    return crearGraficaDeCurso('#' + id_para_Grafica, curso);
}

function obtenerFiltros(indicator){
    informacion = $('#filter_form').serializeArray();
    dateBegining = Date.now();
    informacion.push({name: 'request_type', value: 'user_catalogues'});
    if(indicator != undefined){
        informacion.push({name: 'selected_filter', value: indicator});
    }
    peticionFiltros(informacion);
}

function generarGraficasTodosLosCursos(_bindto, response, titulo) {
    type = response.type;
    if(type == 'course_list'){
        cursos = response.result;
        if(Array.isArray(cursos)){
            var aprobados = Array();
            var nombres = Array();
            var _ideal_cobertura = Array();
            aprobados.push("Porcentaje de aprobación del curso");
            _ideal_cobertura.push('Ideal de cobertura');
            $(_bindto).html('');
            for (var i = 0; i < cursos.length; i++) {
                _ideal_cobertura.push(ideal_cobertura);
                var curso = cursos[i];
                aprobados.push(curso.percentage);
            }
            crearGraficaComparativaVariosCursos(_bindto, [aprobados, _ideal_cobertura], cursos, titulo);
            for (var i = 0; i < cursos.length; i++) {
                var curso = cursos[i];
                crearTarjetaParaGrafica(_bindto, curso);
            }
        }
    }
    if(type == 'kpi_list'){
        kpis = response.result;
        if(Array.isArray(kpis)){
            $(_bindto).html('');
            for (var i = 0; i < kpis.length; i++) {
                var aprobados = Array();
                var _ideal_cobertura = Array();
                aprobados.push("Porcentaje de aprobación del curso");
                kpi = kpis[i];
                cursos = kpi.courses;
                // insertarTituloSeparador(_bindto, kpi.name);
                switch(kpi.id){
                    case 1: // 
                        _destacado = Array();
                        _aprobado = Array();
                        _noAprobado = Array();
                        _destacado.push("Destacado");
                        _aprobado.push("Aprobado");
                        _noAprobado.push("No aprobado");
                        
                        destacado = parseInt(kpi.status["Destacado"]);
                        aprobado = parseInt(kpi.status["Aprobado"]);
                        noAprobado = parseInt(kpi.status["No aprobado"]);

                        _total = destacado + aprobado + noAprobado;
                        _aprobados = destacado + aprobado;
                        resultado = obtenerPorcentaje(_aprobados, _total);
                        __kpi = ["Aprobado-Destacado", resultado];
                        destacado = obtenerPorcentaje(destacado, _total);
                        aprobado = obtenerPorcentaje(aprobado, _total);
                        noAprobado = obtenerPorcentaje(noAprobado, _total);

                        for(var j = 0; j < cursos.length; j++){
                            curso = cursos[j];
                            aprobados.push(curso.percentage);
                            _destacado.push(destacado);
                            _aprobado.push(aprobado);
                            _noAprobado.push(noAprobado);
                        }
                        crearGraficaComparativaVariosCursos(_bindto, [aprobados, _destacado, _aprobado, _noAprobado], cursos, kpi.name, kpi.id);

                        for(var j = 0; j < cursos.length; j++){
                            curso = cursos[j];
                            crearTarjetaParaGraficakpi(_bindto, curso, __kpi, kpi.id);
                        }

                    break;
                    case 2:
                    case 3:
                        __kpi = [kpi.type, kpi.status];
                        info_kpi = Array();
                        info_kpi.push(kpi.type);
                        for(var j = 0; j < cursos.length; j++){
                            aprobados.push(curso.percentage);
                            info_kpi.push(kpi.status);
                        }
                        crearGraficaComparativaVariosCursos(_bindto, [aprobados, info_kpi], cursos, kpi.name, kpi.id);

                        for(var j = 0; j < cursos.length; j++){
                            curso = cursos[j];
                            crearTarjetaParaGraficakpi(_bindto, curso, __kpi, kpi.id);
                        }
                    break;
                }
            }
        }
    }
    return;
}

function crearGraficaComparativaVariosCursos(_bindto, info_grafica, cursos, titulo, id){
    div_id = "chart__" + $('#tab-selector').val();
    if(id != undefined){
        div_id += id;
    }
    insertarTituloSeparador(_bindto, titulo);
    $(_bindto).append(`
                <div class="col-sm-12 col-xl-12">
                    <div class="card bg-faded border-0 m-2" id="">
                        <div class="bg-white m-2" id="${div_id}"></div>
                        <div class="align-items-end">
                            <div class="fincard text-center">
                                <a href="#">Comparativa</a>
                            </div>
                        </div>
                    </div>
                </div>`);
    var chartz = c3.generate({
        data: {
            columns: info_grafica,
            type: ''
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
}

function crearTarjetaParaGraficakpi(div, curso, kpi, id){
    id_para_Grafica = "chart_" + id + '_' + curso.id;
    $(div).append(`<div class="col-sm-12 col-xl-6">
                <div class="card bg-gray border-0 m-2" id="">

                    <div class="card-group">
                        <div class="card border-0 m-2">
                            <div class="card-body text-center">
                                <p class="card-text text-primary">Aprobados</p>
                                <p class="card-text text-primary">${curso.approved_users} (${curso.percentage} %)</p>
                            </div>
                        </div>
                        <div class="card border-0 m-2">
                            <div class="card-body text-center">
                                <p class="card-text text-warning">No Aprobados</p>
                                <p class="card-text text-warning">${curso.not_approved_users}</p>
                            </div>
                        </div>
                        <div class="card border-0 m-2">
                            <div class="card-body text-center">
                                <p class="card-text text-success">Total de usuarios inscritos</p>
                                <p class="card-text text-success">${curso.enrolled_users}</p>
                            </div>
                        </div>

                    </div>
                    <div class="chart_ bg-white m-2" id="${id_para_Grafica}"></div>
                    <div class="align-items-end">
                        <div class="fincard text-center">
                            <a href="detalle_curso?id=${curso.id}">${curso.title}</a>
                        </div>
                    </div>
                </div>
            </div>`);
    return crearGraficaDeCursokpi('#' + id_para_Grafica, curso, kpi);
}

function crearGraficaDeCursokpi(_bindto, curso, kpi){
    _columns = [
        ['Porcentage de aprobación', curso.percentage],
        kpi,
    ];
    var nombre_columnas = ["Porcentaje de aprobación", "", "No iniciaron el curso", ""];
    return c3.generate({
        data: {
            columns: _columns,
            type: 'bar',
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
