<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_dominosdashboard
 * @category    dashboard
 * @copyright   2019 Subitus <contacto@subitus.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
$context_system = context_system::instance();
require_login();
require_once(__DIR__ . '/lib.php');
local_dominosdashboard_user_has_access();

global $DB;
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/administrar_KPIS.php");
$uploadKPIsurl = $CFG->wwwroot . '/local/dominosdashboard/subir_archivo.php';
$settingsurl = $CFG->wwwroot . '/admin/settings.php?section=local_dominosdashboard';
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));
echo $OUTPUT->header();
?>
<link rel="stylesheet" href="estilos.css">
<h2>Recuerde establecer  la relación entre los cursos y su KPI en las configuraciones del plugin</h2><br>
<div class="row" style="padding-bottom: 2%;">
    <div class="col-sm-4" style="text-align: right;">
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#agregarKPIModal">Agregar nuevo KPI</button>
    </div>
    <div class="col-sm-4" style="text-align: center;">
        <a class="btn btn-primary btn-lg" href="<?php echo $uploadKPIsurl; ?>">Subir KPI's</a>        
    </div>
    <div class="col-sm-4" style="text-align: left;">
        <a class="btn btn-primary btn-lg" href="<?php echo $settingsurl; ?>">Configuraciones del plugin</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover text-center">
        <thead>
            <tr>
                <th scope="col" class="text-center">Clave</th>
                <th scope="col" class="text-center">Nombre del KPI</th>
                <th scope="col" class="text-center">Tipo de valor</th>
                <th scope="col" class="text-center">Estado</th>
                <th scope="col" class="text-center">Editar</th>                
                <th scope="col" class="text-center">Eliminar</th>
            </tr>
        </thead>
        <tbody id="listado_kpis">
            <tr>
                <th scope="row">id</th>
                <td>Rotación</td>
                <td>Porcentaje</td>
                <td>Editar</td>
                <td>Habilitado</td>
                <td><button>Eliminar</button></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="agregarKPIModal" tabindex="-1" role="dialog" aria-labelledby="agregarKPIModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarKPIModalLabel">Agregar KPI</h5>
            </div>
            <div class="modal-body">
                <form id="form_kpi" name="form_kpi">
                    <div class="form-group">
                        <label for="kpi_name" class="col-form-label">Nombre del kpi:</label>
                        <input type="text" oninput="crearClave()" class="form-control" id="kpi_name" name="kpi_name">
                    </div>
                    <div class="form-group">
                        <label for="kpi_key" class="col-form-label">Clave del kpi: (esta clave tendrá que estar como cabecera en el archivo csv para agregar este KPI)</label>
                        <input type="text" class="form-control" id="kpi_key" name="kpi_key">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Tipo de dato:</label>
                        <select name="kpi_type" class="form-control">
                            <option value="Porcentaje">Porcentaje</option>
                            <option value="Número entero">Número entero</option>
                            <option value="Texto">Texto</option>
                        </select>
                    </div>
                    <input type="hidden" name="kpi_enabled" value="1">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="agregarKPI()" class="btn btn-primary">Agregar KPI</button>
            </div>
        </div>
    </div>
</div>

<script>
    // kpi_name = $('#kpi_name').val();
    // kpi_key = $('#kpi_key').val();
    // kpi_type = $('#kpi_type').val();

    document.addEventListener("DOMContentLoaded", function() {
        // document.getElementById('region-main').style.width = "100%";
        require(['jquery'], function ($) {
            cargarKPIS();
        });
    });

    function crearClave(){
        $('#kpi_key').val(string_to_slug($('#kpi_name').val()));
    }

    var listado_kpis;
    function cargarKPIS(){
        $('#listado_kpis').html(``);
        // informacion = $('#form_kpi').serializeArray();
        // informacion.push({name: 'request_type', value: 'create_kpi'});
        $.ajax({
            type: "POST",
            url: "services.php",
            data: {
                request_type: 'kpi_list'
            },
            // dataType: "json"
        })
        .done(function(data) {
            console.log(data);
            listado_kpis = JSON.parse(data);
            listado_kpis = listado_kpis.data;
            keys = Object.keys(listado_kpis);
            for (var index = 0; index < keys.length; index++) {
                var element = keys[index];
                var kpi = listado_kpis[element];
                imprimirKPI(kpi);
            }
            // data = JSON.stringify(data));
            console.log('Terminado');
            // listado_kpis = data;
            
        })
        .fail(function(error, error2) {
            console.log('Fallo', error, error2);
        });
    }

    function imprimirKPI(kpi){
        console.log(kpi);
        formname = "kpi_edit_" + kpi.id;
        $('#listado_kpis').append(`
            <tr>
            <form id='${formname}' name='${formname}'></form>
                <input type='hidden' form='${formname}' name='id' value='${kpi.id}' >
                <th scope="row" class='text-center'>
                <input type='text' form='${formname}' name='kpi_key' initialvalue='${kpi.kpi_key}' value='${kpi.kpi_key}'
                id='key_edit_${kpi.id}' class='form-control '>
                </th>
                <td><input type='text' form='${formname}' name='kpi_name' value='${kpi.name}' class='form-control'></td>
                <td>
                    <select form='${formname}' name="kpi_type" id='type_selected_${kpi.id}' class="form-control">
                        <option value="Porcentaje">Porcentaje</option>
                        <option value="Número entero">Número entero</option>
                        <option value="Texto">Texto</option>
                    </select>
                </td>
                <td>
                    <select form='${formname}' name="kpi_enabled" id='type_enabled_${kpi.id}' class="form-control">
                        <option value="0">Deshabilitado</option>
                        <option value="1">Habilitado</option>
                    </select>
                </td>
                <td><button onclick="editarKPI('#${formname}', ${kpi.id})" class='btn btn-info'>Actualizar KPI</button></td>
                <td><button onclick='eliminarKPI(${kpi.id})' class='btn btn-danger'>Eliminar</button></td>
            </tr>
        `);
        $(`#type_selected_${kpi.id}`).val(kpi.type);
        $(`#type_enabled_${kpi.id}`).val(kpi.enabled);
    }

    /*
        <td>${kpi.enabled == 0 ? `<button class='btn btn-primary' onclick="inhabilitarKPI(${kpi.id})">Habilitar KPI</button>` : `<button class='btn btn-danger' onclick="habilitarKPI(${kpi.id})">Deshabilitar KPI</button>` }</td>
     */

    function editarKPI(formname, id){
        informacion = $(formname).serializeArray();
        informacion.push({name: 'request_type', value: 'update_kpi'});
        $.ajax({
            type: "POST",
            url: "services.php",
            data: informacion,
            // dataType: "json"
        })
        .done(function(data) {
            console.log('La información obtenida es: ', data);
            if(data == 'ok'){
                cargarKPIS();
            }else{ // Se trata de un error
                var key_input = $('#key_edit_' + id)
                key_input.val(key_input.attr('initialvalue'));
                alert(data);
            }
        })
        .fail(function(error, error2) {
            alert('Por favor, inténtelo de nuevo');
        });
    }
    
    function string_to_slug (str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();
    
        // remove accents, swap ñ for n, etc
        var from = "àáäâèéëêìíïîòóöôùúüûñç·/-,:;";
        var to   = "aaaaeeeeiiiioooouuuunc______";
        for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

        return str.toUpperCase();
    }

    function ocultarModal(){
        $('#agregarKPIModal').modal('hide');
    }
    
    var informacion;
    function agregarKPI(kpi) {
        informacion = $('#form_kpi').serializeArray();
        informacion.push({name: 'request_type', value: 'create_kpi'});
        $.ajax({
            type: "POST",
            url: "services.php",
            data: informacion,
            // dataType: "json"
        })
        .done(function(data) {
            console.log('La información obtenida es: ', data);
            // return;
            if(data == 'ok'){
                cargarKPIS();
                alert('Insertado con éxito');
                ocultarModal();
                informacion = $('#form_kpi').trigger('reset');
            }else{ // Se trata de un error
                alert(data);
            }
        })
        .fail(function(error, error2) {
            alert('Por favor, inténtelo de nuevo');
            ocultarModal();
        });
    }

    function eliminarKPI(kpi) {
        if(confirm('¿Está seguro que desea eliminar este KPI?')){
            $.ajax({
                type: "POST",
                url: "services.php",
                data: {
                    request_type: 'delete_kpi',
                    id: kpi
                },
                // dataType: "json"
            })
            .done(function(data) {
                console.log('La información obtenida es: ', data);
                // return;
                if(data == 'ok'){ 
                    cargarKPIS();
                    alert('Eliminado con éxito');
                }else{ // Se trata de un error
                    alert(data);
                }
            })
            .fail(function(error, error2) {
                alert('Por favor, inténtelo de nuevo');
            });
        }
        // informacion = $('#form_kpi').serializeArray();
        // informacion.push({name: 'request_type', value: 'create_kpi'});
    }
</script>
<?php
echo $OUTPUT->footer();
