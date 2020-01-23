/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (typeof google != 'undefined') {
    window.onload = function () {
        var data = google.visualization.arrayToDataTable([
            //['Rating', 'Reviews'],
            [rating_text, review_text],
            ['5 ' + star_text, Number(rating_chart_data['5_star'])],
            ['4 ' + star_text, Number(rating_chart_data['4_star'])],
            ['3 ' + star_text, Number(rating_chart_data['3_star'])],
            ['2 ' + star_text, Number(rating_chart_data['2_star'])],
            ['1 ' + star_text, Number(rating_chart_data['1_star'])]
        ]);

        var options = {
            //title: 'Rating Chart'
            title: chart_text
        };

        var chart = new google.visualization.BarChart(document.getElementById('velsofincentive_bar_graph'));

        chart.draw(data, options);

    }
    }