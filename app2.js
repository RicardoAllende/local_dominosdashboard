/*var chartd;
var datad = {
    type: "donut",
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

function processData(datad){
    columns = [];
    colors = {};
    names = {};
    for (let i = 0; i < datad.courses.length; i++) {
        const course = datad.courses[i];
        columns.push([course.key, course.value]);
        colors[course.key] =  course.color;
        names[course.key] =  course.title;
    }
    return {
        type: datad.type,
        columns,
        colors,
        names
    };
}

$(document).ready(function() {
    addChartd();
})

addChartd = function(){
    data_ = processData(datad);
    chartd = c3.generate({
        bindto: "#chart2",
        data: data_,
    })
}*/
