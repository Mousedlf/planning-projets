import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

  static targets = ['calendar'];


  connect() {
    const json = JSON.parse(this.data.get('json'));


const planning = new FullCalendar.Calendar(calendar, {
     initialView: 'multiMonthYear',
     editable: true,
     buttonText: {
      today:false,
     },
     multiMonthMinWidth : 200,
     weekText : "",
     navLinks : false,
     multiMonthMaxColumns: 4,
     weekends : false,
     dayHeaders : false ,
     handleWindowResize: true,
     eventBackgroundColor : '#378006',
     eventDisplay : 'background',
     events: json,
     eventClick: function(info) {
          alert('Event: ' + info.event.date);
     },

     dateClick: function(date, jsEvent, view) {  
          
          let selectedDate = date.dateStr; 
          
          
          openModal();

     }
});

planning.render();


  }


}