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

//Funcion para mostar la grafica sin informacion
function insertarGraficaSinInfo(div){
    $(div).append(`
    <div class='col-sm-6 espacio'>
            <div class='card bg-gray border-0 m-2'>
                <div class='card esp'>
                <div class='row espr'>
                    <div class='border-0 col-sm-4'>
                        <div class='card-body'>
                            <p class='card-text text-primary text-center txti'>Aprobados</p>
                            <p class='card-text text-primary text-center txtnum' id=''></p>
                        </div>
                    </div>
                    <div class='border-0 col-sm-4'>
                        <div class='card-body text-center'>
                            <p class='card-text text-warning text-center txti'>No Aprobados</p>
                            <p class='card-text text-warning text-center txtnum' id=''></p>
                        </div>
                    </div>
                    <div class='border-0 col-sm-4'>
                        <div class='card-body text-center'>
                            <p class='card-text text-success text-center txti'>Total de usuarios</p>
                            <p class='card-text text-warning text-center txtnum' id=''></p>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class='bg-faded text-center noinfo' id=''>Sin información en la Base de Datos</div>                                   
                    <div class='align-items-end'>                                        
                    <div class='fincard text-center'>
                        <a href='' id=''></a>
                    </div>
                </div>
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
            $(_bindto).html('');
            return;
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

function crearTarjetaParaGrafica(div, curso, claseDiv){
    if(typeof claseDiv !== 'string'){
        claseDiv = "col-sm-12 col-xl-6";
    }
    id_para_Grafica = "chart_" + curso.id;
    if(typeof currentTab != 'undefined'){
        id_para_Grafica += '_' + currentTab;
    }
    console.log('id_para_Grafica', id_para_Grafica);
    $(div).append(`<div class="${claseDiv} espacio">
                <div class="card bg-gray border-0 m-2">
                    <div class="card esp">
                    <div class="row espr">
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text text-primary txti">Aprobados</p>
                                <p class="card-text text-primary txtnum">${curso.approved_users} (${curso.percentage} %)</p>
                            </div>
                        </div>
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text text-warning txti">No Aprobados</p>
                                <p class="card-text text-warning txtnum">${curso.not_approved_users}</p>
                            </div>
                        </div>
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text text-success txti">Total de usuarios inscritos</p>
                                <p class="card-text text-success txtnum">${curso.enrolled_users}</p>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="chart_ bg-faded m-2" id="${id_para_Grafica}"></div>
                    <div class="align-items-end">
                        <div class="fincard text-center">
                            <a href="detalle_curso_iframe.php?id=${curso.id}">${curso.title}</a>
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
    div_id = "chart__";
    if(typeof currentTab != 'undefined'){
        div_id += '_';
        div_id += currentTab;
    }
    console.log('El id de los cursos varios es ', div_id);
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
    $(div).append(`<div class="col-sm-12 col-xl-6 espacio">
                <div class="card bg-gray border-0 m-2" id="">

                    <div class="card esp">
                    <div class="row espr">
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text text-primary txti">Aprobados</p>
                                <p class="card-text text-primary txtnum">${curso.approved_users} (${curso.percentage} %)</p>
                            </div>
                        </div>
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text text-warning txti">No Aprobados</p>
                                <p class="card-text text-warning txtnum">${curso.not_approved_users}</p>
                            </div>
                        </div>
                        <div class="border-0 col-sm-4">
                            <div class="card-body text-center">
                                <p class="card-text text-success txti">Total de usuarios inscritos</p>
                                <p class="card-text text-success txtnum">${curso.enrolled_users}</p>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="chart_ bg-faded m-2" id="${id_para_Grafica}"></div>
                    <div class="align-items-end">
                        <div class="fincard text-center">
                            <a href="detalle_curso_iframe.php?id=${curso.id}">${curso.title}</a>
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

function peticionFiltros(info){
    $.ajax({
        type: "POST",
        url: "services.php",
        data: info,
        dataType: "json"
    })
    .done(function(data) {
        console.log(data);
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
            console.log(clave, catalogo.length);
            if(crearElementos){
                $(div_selector).append(`
                    <div class="card">
                        <div class="card-header cuerpo-filtro" id="${heading_id}">
                            <h5 class="mb-0">
                                <span class="btn btn-link collapsed texto-filtro"
                                    data-toggle="collapse" style="color: white;" data-target="#${collapse_id}" aria-expanded="false"
                                    aria-controls="${collapse_id}">
                                    ${clave}
                                </span>
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
            for(var j = 0; j < catalogo.length; j++){
                var elementoDeCatalogo = catalogo[j];
                $(subfiltro_id).append(`
                            <label class="subfiltro"><input type="checkbox" name="${clave}[]"
                            class="indicator_option indicator_${clave}\" onclick="obtenerInformacion('${clave}')" 
                            data-indicator=\"${clave}\" value=\"${elementoDeCatalogo}\"
                            >
                             ${esVacio(elementoDeCatalogo) ? " (Vacío)" : elementoDeCatalogo}</label><br>
                `);
            }
        }
        dateEnding = Date.now();
        console.log(`Tiempo de respuesta al obtener filtros de API ${dateEnding - dateBegining} ms`);
    })
    .fail(function(error, error2) {
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
        if(num_activities >= 6){ // Se muestran 2 rankings
            $(div).append(`
                    <div class="titulog col-sm-12 dominosdashboard-ranking" id="dominosdashboard-ranking-title">
                        <h1 style="text-align: center;">Ranking de actividades</h1>
                    </div>
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
                            <div class="progress-bar" style="width: ${percentage}%; background: ${colorTop}; color: white; border-radius: 5px; text-align: center;">${percentage}%</div>
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
                            <div class="progress-bar" style="width: ${percentage}%; background: ${colorBottom}; color: white;border-radius: 5px; text-align: center;">${percentage}%</div>
                        </div>
                    </td>
                </tr>`;
            }
            $(ranking_bottom_body).html(contenido);
            return;
        }else if(num_activities > 0){ // Sólo se muestra un ranking
            $(div).append(`
                    <div class="titulog col-sm-12 dominosdashboard-ranking" id="dominosdashboard-ranking-title">
                        <h1 style="text-align: center;">Ranking de actividades</h1>
                    </div>

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
                            <div class="progress-bar" style="width: ${percentage}%; background: ${colorTop}; color: white; border-radius: 5px; text-align: center;">${percentage}%</div>
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