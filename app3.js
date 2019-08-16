var chartb;
var datab = {
    type: "pie",
    courses: [
        {
            key: "course1",
            value: 1,
            color: "#265a88",
            title: "Curso 1"
        },
        {
            key: "course2",
            value: 2,
            color: "#419641",
            title: "Curso 2"
        },
        {
            key: "course3",
            value: 4,
            color: "#2aabd2",
            title: "Curso 3"
        },
        {
            key: "course4",
            value: 8,
            color: "#eb9316",
            title: "Curso 4"
        }
    ]
};

function processData(datab){
    columns = [];
    colors = {};
    names = {};
    for (let i = 0; i < datab.courses.length; i++) {
        const course = datab.courses[i];
        columns.push([course.key, course.value]);
        colors[course.key] =  course.color;
        names[course.key] =  course.title;
    }
    return {
        type: datab.type,
        columns,
        colors,
        names
    };
}

$(document).ready(function() {
    addChartb();
})

addChartb = function(){
    data_ = processData(datab);
    chartb = c3.generate({
        bindto: "#chart3",
        data: data_,
    })
}