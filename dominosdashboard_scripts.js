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