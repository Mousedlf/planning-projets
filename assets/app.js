import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridYear',
      buttonText: {
        today:false,
      },
      height: "100%",
      weekNumbers : true,
      weekText : "",
      navLinks : true,
      navLinkDayClick: function(date) {
        console.log('click', date.toISOString());
      },
      navLinkWeekClick: function() {},
      aspectRatio: 5,
      contentHeight: 'auto',
      multiMonthMaxColumns: 1,
      weekends : false,
      dayHeaders : false ,
      handleWindowResize: true,
      moreLinkClick : "popover", // ?*

    });
    
    calendar.updateSize()

    calendar.render();
  });