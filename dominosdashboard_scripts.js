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