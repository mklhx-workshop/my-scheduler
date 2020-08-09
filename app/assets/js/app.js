/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = (jQuery = require("jquery"));
console.log("Hello Webpack Encore! Edit me in assets/js/app.js");

require("popper.js");
require("bootstrap");
require("@fortawesome/fontawesome-free/js/all.js");
require("moment");


// $("input[type=date]").datetimepicker();

//   $(".calendar").fullCalendar({
//     schedulerLicenseKey: "GPL-My-Project-Is-Open-Source",
//     // eventSources: [
//     //     {
//     events: $events,
//     //     }
//     // ],
//     // resources: $resources,
//     header: {
//       left: "title",
//       center: "month,agendaWeek,agendaDay",
//       bootstrapFontAwesome: {
//         close: "fa-times",
//         prev: "fa-chevron-left",
//         next: "fa-chevron-right",
//         prevYear: "fa-angle-double-left",
//         nextYear: "fa-angle-double-right"
//       }
//     },
//     themeSystem: "bootstrap4",
//     // height: 'auto',
//     slotDuration: "00:15:00",
//     weekNumbers: true,
//     selectable: true,
//     dayClick: (date, jsEvent, view) => {
//       // change the day's background color just for fun
//       $(this).css("background-color", "#efefef");
//       $makeBooking($(this), date.format());
//     },
//     eventClick: (calEvent, jsEvent, view) => {
//       console.log("Event: " + calEvent.title);
//       console.log("Coordinates: " + jsEvent.pageX + "," + jsEvent.pageY);
//       console.log("View: " + view.name);

//       // change the border color just for fun
//       $(this).css("border-color", "red");
//     },
//     // views: {
//     //     month: { // name of view
//     //         titleFormat: 'MMMM Y'
//     //     },
//     //     week: { // name of view
//     //         titleFormat: 'D / MMMM / Y'
//     //     },
//     //     day: { // name of view
//     //         titleFormat: 'D MMMM Y'
//     //     }
//     // },
//     options: {
//       locale: "en",
//       isRTL: true
//     }
//   });

//   $makeBooking = (elm, date) => {
//     $makeBookinModal = $("#makeBooking");
//     $makeBookinModal.modal("show");
//     $makeBookinModal.on("show.bs.modal", function() {});
//     $makeBookinModal.on("shown.bs.modal", function() {
//       $(this)
//         .find(".modal-title")
//         .text(date);
//     });
//     $makeBookinModal.on("hidden.bs.modal", function() {
//       elm.css("background-color", "white");
//     });
//   };

// $('#create-event-form').on('submit', function (e) {
//     e.preventDefault();
//     console.log("create event clicked");
//     let dataSerialized = $('#create-event-form').serializeArray();
//     console.log("serialized data : ", dataSerialized);
//     let dataJson = JSON.stringify(dataSerialized);
//     console.log("json stringify data : ", dataJson);

//     let formData = {''};

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

// });
//   $("#tags").on("change", () => {
//     console.log("select #tags change");
//     $.ajax({
//       headers: {
//         Accept: "application/json"
//       },
//       url: "/api/things?tags.id=" + $(this).val(),
//       success: things => {
//         console.log(things);
//         if (things.length > 0) {
//           $.each(things, (index, thing) => {
//             $("#things").html("");
//             $("#things").append(
//               $("<option/>", {
//                 value: thing.id,
//                 text:
//                   thing.brand +
//                   " - " +
//                   thing.model +
//                   " - " +
//                   thing.identificationNumber
//               })
//             );
//           });
//         } else {
//           $("#things").html("");
//           $("#things").append(
//             $("<option/>", {
//               text: "no things found for this tag"
//             })
//           );
//         }
//       }
//     });
//   });
