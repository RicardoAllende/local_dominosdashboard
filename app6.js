var chart;
var data = {
    type: "bar",
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

function processData(data){
    columns = [];
    colors = {};
    names = {};
    for (let i = 0; i < data.courses.length; i++) {
        const course = data.courses[i];
        columns.push([course.key, course.value]);
        colors[course.key] =  course.color;
        names[course.key] =  course.title;
    }
    return {
        type: data.type,
        columns,
        colors,
        names
    };
}

$(document).ready(function() {
    addChart();
})

addChart = function(){
    data_ = processData(data);
    chart = c3.generate({
        bindto: "#chart6",
        data: data_,
    })
}