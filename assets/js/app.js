/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
let $ = require('jquery');
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

require('popper.js');
require('bootstrap');
require('@fortawesome/fontawesome-free/js/all.js');
require('moment');
require('fullcalendar');
require('fullcalendar-scheduler');

$(function () {
    let events = [];
    let resources = [
        {id: 'a', title: 'Room A'},
        {id: 'b', title: 'Room B'},
        {id: 'c', title: 'Room C'},
        {id: 'd', title: 'Room D'}
    ];
    $('#calendar').fullCalendar(
        {
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            events: events,
            resources: resources,
            header: {
                left: 'title',
                center: 'month,agendaWeek,agendaDay',
                bootstrapFontAwesome: {
                    close: 'fa-times',
                    prev: 'fa-chevron-left',
                    next: 'fa-chevron-right',
                    prevYear: 'fa-angle-double-left',
                    nextYear: 'fa-angle-double-right'
                }
            },
            themeSystem: 'bootstrap4',
            height: 'auto',
            slotDuration: '00:15:00',
            weekNumbers: true,
            selectable: true,
            dayClick: function (date, jsEvent, view) {
                console.log('Clicked on: ' + date.format());
                console.log('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                console.log('Current view: ' + view.name);

                resources.push({id: 'e', title: 'Room E'});
                // change the day's background color just for fun
                $(this).css('background-color', 'red');

            },
            eventClick: function (calEvent, jsEvent, view) {
                console.log('Event: ' + calEvent.title);
                console.log('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                console.log('View: ' + view.name);

                // change the border color just for fun
                $(this).css('border-color', 'red');

            },
            views: {
                month: { // name of view
                    titleFormat: 'MMMM YYYY'
                    // other view-specific options here
                },
                week: { // name of view
                    titleFormat: 'D / MMMM / YYYY'
                    // other view-specific options here
                },
                day: { // name of view
                    titleFormat: 'D MMMM YYYY'
                    // other view-specific options here
                }
            },
            options: {
                locale: 'fr',
                isRTL: true
            }
        });
});
