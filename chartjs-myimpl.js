var angleChart;
var temperatureChart;

function showCharts() {
    var chartUrl;
    console.log(window.location.hash);
    if(window.location.hash === "#mock") {
        chartUrl = "backend/read.json"
    } else {
        var hash = window.location.hash;
        if(hash && hash.length > 1) {
            chartUrl = "backend/backups/" + hash.substr(1);
        } else {
            chartUrl = "backend/read.php?token=*kraeftspindel1&fields=angle,temperature,battery";
        }
    }
    fetch(chartUrl)
    .then(response => response.json())
    .then(json => {
    const values = document.getElementById("values");
        while (values.firstChild) {
            values.removeChild(values.firstChild);
        }
        if(angleChart) {
            angleChart.destroy();
        }
        if(temperatureChart) {
            temperatureChart.destroy();
        }

        const angles = [];
        const temperatures = [];
        for(let i = 0; i < json.length; i++) {
            let dateString = json[i]["datetime"];
            let dateInt = Date.parse(dateString);
            angles.push({
                "x": dateInt,
                "y": json[i]["angle"]
            });
            temperatures.push({
                "x": dateInt,
                "y": json[i]["temperature"]
            });
        }

        const lastMeasuring = json[json.length - 1];

        // last update
        addData("last update (UTC)", formatDate(lastMeasuring["datetime"]), new Date().getTime() - new Date(lastMeasuring["datetime"]).getTime() > 90 * 60 * 1000);

        // duration
        const diff = (new Date(lastMeasuring["datetime"]).getTime() - new Date(json[0]["datetime"]).getTime()) / 1000;
        const h = (diff - diff%3600) / 3600;
        const d = (h - h%24) / 24;
        addData("duration", d + "d " + h%24 + "h"), d < 0 || h < 0 || d > 28 || h > 23;

        // temperature
        addData("temperature", lastMeasuring["temperature"], lastMeasuring["temperature"] < 9.0 || lastMeasuring["temperature"] > 25.0);

        // angle
        addData("angle", lastMeasuring["angle"], lastMeasuring["angle"] < 0.0 || lastMeasuring["angle"] > 90.0);

        // battery
        addData("battery", lastMeasuring["battery"], lastMeasuring["battery"] < 3.0);

        const myOptions = {
            scales: {
                x: {
                    ticks: {
                        callback: function(value, index, values) {
                            return formatDate(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        callback: function(value, index, values) {
                            return value + '°';
                        }
                    }
                }
            }
        }
        angleChart = new Chart(document.getElementById('angleChart'), {
            type: 'scatter',
            data: {
                datasets: [{
                    label: "Angle",
                    data: angles,
                    showLine: true,
                    pointRadius: 1,
                    lineTension: 0
                }]
            },
            options: myOptions
        });
        temperatureChart = new Chart(document.getElementById('temperatureChart'), {
            type: 'scatter',
            data: {
                datasets: [{
                    label: "Temperature",
                    data: temperatures,
                    showLine: true,
                    pointRadius: 1,
                    lineTension: 0
                }]
            },
            options: myOptions
        });
    });
}

function formatDate(dateInt) {
    const date = new Date(dateInt);
    let dateString = date.getDate() + "."
        + (date.getMonth() + 1) + "."
        + date.getFullYear()%100 + " "
        + date.getHours() + ":";
    if(date.getMinutes() < 10) {
        dateString += "0";
    }
    dateString += date.getMinutes();
    return dateString;
}

function addData(title, value, doWarn = false) {
    let span1 = document.createElement("span");
    span1.innerText = title;
    let span2 = document.createElement("h2");
    span2.innerText = value;

    let val = document.createElement("div");
    val.appendChild(span1);
    val.appendChild(span2);
    if(doWarn)
        val.style.backgroundColor = "#faa";

    document.getElementById("values").appendChild(val);
}