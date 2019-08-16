var elements = "";
var dev = "";
var courseid = "";
try{
    require(['jquery'], function($) {
        // elements = $(":select[name^='local_dominosdashboard_course_completion']")

        console.log(window.location.href);
        selects = document.getElementsByTagName("select");
        // id_s_local_dominosdashboard_course_completion_30
        for (var index = 0; index < selects.length; index++) {
            var select = selects[index];
            if(typeof select.id == "string"){
                if(select.id.indexOf('local_dominosdashboard_course_completion') != -1 && select.id.indexOf('id_s_local_dominosdashboard_course_completion_activity_') == -1){
                    prepareCompletionTime(select.id);
                    select.addEventListener("change", function(_element){
                        element = _element.target;
                        console.log(element.id);
                        prepareCompletionTime(element.id);
                    });
                    console.log(select);
                }
            }
        }
    });
}catch(error){
    console.log(error);
}

function prepareCompletionTime(elementid){
    console.log("Ejecutando función con el id " + elementid);
    // dev = element;
    console.log('elementid', elementid);
    var jquerySelector = "#" + elementid;
    // console.log('jquerySelector', jquerySelector);
    var jqueryObject = $(jquerySelector);
    // console.log('jqueryObject', jqueryObject);
    // console.log('jqueryObject.val()', jqueryObject.val());
    var id_parts = elementid.split('_');
    courseid = id_parts[id_parts.length - 1];
    var course_completion                   = "#admin-course_completion_" + courseid;
    var course_grade_activity_completion    = "#admin-course_grade_activity_completion_" + courseid;
    var course_minimum_score                = "#admin-course_minimum_score_" + courseid;
    var course_completion_activity          = "#admin-course_completion_activity_" + courseid;
    var badge_completion                    = "#admin-badge_completion_" + courseid;
    var course_kpi                          = "#admin-course_kpi_" + courseid;
    
    /* 
        course_completion
        course_grade_activity_completion // only gradable item
        course_minimum_score
        badge_completion
        course_completion_activity
        course_kpi
    */
    switch(jqueryObject.val()){
        case "1": // COMPLETION_DEFAULT
            $(course_grade_activity_completion).hide();
            $(course_minimum_score).hide();
            $(course_completion_activity).hide();
            $(badge_completion).hide();
            $(course_kpi).show();
            break;
        case "2": // COMPLETION_DEFAULT_AND_GRADE
            $(course_grade_activity_completion).hide();
            $(course_minimum_score).hide();
            $(course_completion_activity).hide();
            $(badge_completion).hide();
            $(course_kpi).show();
            break;
        case "3": // COMPLETION_BY_GRADE
            $(course_grade_activity_completion).show();
            $(course_minimum_score).show();
            $(course_completion_activity).hide();
            $(badge_completion).hide();
            $(course_kpi).show();
            break;
        case "4": // COMPLETION_BY_BADGE
            $(course_grade_activity_completion).hide();
            $(course_minimum_score).hide();
            $(course_completion_activity).hide();
            $(badge_completion).show();
            $(course_kpi).show();
            break;
        case "5": // COMPLETION_BY_ACTIVITY
            $(course_grade_activity_completion).show();
            $(course_minimum_score).hide();
            $(course_completion_activity).hide();
            $(badge_completion).hide();
            $(course_kpi).show();
            break;
        // case "6: // COMPLETION_BY_AVG
        //     $(course_completion).show();
        //     $(course_grade_activity_completion).show();
        //     $(course_minimum_score).show();
        //     $(course_completion_activity).show();
        //     $(course_kpi).show();
        //     break;
        default:
            $(course_completion).show();
            $(course_grade_activity_completion).show();
            $(course_minimum_score).show();
            $(course_completion_activity).show();
            $(badge_completion).hide();
            $(course_kpi).show();
            break;
    }
}