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

let $resources = [];

// let $events = [
//     {
//         title: 'event1',
//         start: '2019-01-27'
//     },
//     {
//         title: 'event2',
//         start: '2019-01-30',
//         end: '2019-02-01'
//     },
//     {
//         title: 'event3',
//         start: '2019-01-29T12:30:00',
//         allDay: false // will make the time show
//     }
// ];

$getTags = function () {
    let tags = [];
    $.ajax({
        headers: {
            Accept: "application/json"
        },
        url: '/api/tags',
        success: function (t) {
            $.each(t, function (index, tag) {
                tags[index] = tag;
            });
            console.log("tags from $getTags");
            console.log(tags);
            $.each(tags, function (index, tag) {
                $('#tags').append($("<option/>", {
                    value: tag.id,
                    text: tag.name
                }));
            });
            return tags;
        }
    });
};
let $tags = $getTags();

$getThings = function () {
    let things = [];
    $.ajax({
        headers: {
            Accept: "application/json"
        },
        url: '/api/things',
        success: function (t) {
            $.each(t, function (index, thing) {
                things[index] = thing;
            });
            console.log("thing from $getThings");
            console.log(things);
            $.each(things, function (index, thing) {
                $('#things').append($("<option/>", {
                    value: thing.id,
                    text: thing.brand + ' - ' + thing.model + ' - ' + thing.identificationNumber
                }));
            });
            return things;
        }
    });
};
let $things = $getThings();

$getEvents = function () {
    let events = [];
    $.ajax({
        headers: {
            Accept: "application/json"
        },
        url: "/api/events/",
        success: function (e) {
            $.each(e, function (index, event) {
                events[index] = event;
            });
            console.log("events from $getEvents");
            console.log(events);
            $.each(events, function (index, event) {
                $('#tag').append($("<option/>", {
                    value: event.id,
                    text: event.name
                }));
            });
            return events;
        }
    });
};
let $events = $getEvents();

$(function () {
    $('input[type=date]').datetimepicker();
    $('.calendar').fullCalendar(
        {
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            // eventSources: [
            //     {
            events: $events,
            //     }
            // ],
            // resources: $resources,
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
            // height: 'auto',
            slotDuration: '00:15:00',
            weekNumbers: true,
            selectable: true,
            dayClick: function (date, jsEvent, view) {
                // change the day's background color just for fun
                $(this).css('background-color', '#efefef');
                $makeBooking($(this), date.format());
            },
            eventClick: function (calEvent, jsEvent, view) {
                console.log('Event: ' + calEvent.title);
                console.log('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                console.log('View: ' + view.name);

                // change the border color just for fun
                $(this).css('border-color', 'red');

            },
            // views: {
            //     month: { // name of view
            //         titleFormat: 'MMMM Y'
            //     },
            //     week: { // name of view
            //         titleFormat: 'D / MMMM / Y'
            //     },
            //     day: { // name of view
            //         titleFormat: 'D MMMM Y'
            //     }
            // },
            options: {
                locale: 'en',
                isRTL: true
            }
        });

    $makeBooking = function (elm, date) {
        $makeBookinModal = $('#makeBooking');
        $makeBookinModal.modal('show');
        $makeBookinModal.on('show.bs.modal', function () {

        });
        $makeBookinModal.on('shown.bs.modal', function () {
            $(this).find('.modal-title').text(date);
        });
        $makeBookinModal.on('hidden.bs.modal', function () {
            elm.css('background-color', 'white');
        });
    };

    // $('#create-event-form').on('submit', function (e) {
    //     e.preventDefault();
    //     console.log("create event clicked");
    //     let dataSerialized = $('#create-event-form').serializeArray();
    //     console.log("serialized data : ", dataSerialized);
    //     let dataJson = JSON.stringify(dataSerialized);
    //     console.log("json stringify data : ", dataJson);
    //
    //     let formData = {''};
    //
    //     $.ajax({
    //         headers: {
    //             Accept: "application/json"
    //         },
    //         url: '/api/events',
    //         type: 'POST',
    //         data: dataJson,
    //         dataType: "json",
    //         contentType: "application/json",
    //         success: function (data) {
    //             console.log("submitted data", data);
    //             $('#makeBooking').modal('hide');
    //         },
    //         error: function (error) {
    //             console.log("error : ", error);
    //         }
    //     });
    //
    // });
    $('#tags').on('change', function () {
        console.log('select #tags change');
        $.ajax({
            headers: {
                Accept: "application/json"
            },
            url: '/api/things?tags.id=' + $(this).val(),
            success: function (things) {
                console.log(things);
                if (things.length > 0) {
                    $.each(things, function (index, thing) {
                        $('#things').html("");
                        $('#things').append($("<option/>", {
                            value: thing.id,
                            text: thing.brand + ' - ' + thing.model + ' - ' + thing.identificationNumber
                        }));
                    })
                } else {
                    $('#things').html("");
                    $('#things').append($("<option/>", {
                        text: "no things found for this tag"
                    }));
                }
            }
        });
    });
});