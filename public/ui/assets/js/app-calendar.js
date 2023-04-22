/**
 * App Calendar
 */

/**
 * ! If both start and end dates are same Full calendar will nullify the end date value.
 * ! Full calendar will end the event on a day before at 12:00:00AM thus, event won't extend to the end date.
 * ! We are getting events from a separate file named app-calendar-events.js. You can add or remove events from there.
 *
 **/

'use strict';

let direction = 'ltr';

if (isRtl) {
  direction = 'rtl';
}

document.addEventListener('DOMContentLoaded', function () {
  (function () {
    flatpickr.l10ns.default.firstDayOfWeek = 1; // Monday
    
    const calendarEl = document.getElementById('calendar'),
      appCalendarSidebar = document.querySelector('.app-calendar-sidebar'),
      addEventSidebar = document.getElementById('addEventSidebar'),
      appOverlay = document.querySelector('.app-overlay'),
      calendarsColor = {
        Business: 'primary',
        Holiday: 'success',
        Personal: 'danger',
        Family: 'warning',
        ETC: 'info',
        primary: 'primary',
        success: 'success',
        danger: 'danger',
        warning: 'warning',
        info: 'info',
        secondary: 'secondary',
      },
      offcanvasTitle = document.querySelector('.offcanvas-title'),
      btnToggleSidebar = document.querySelector('.btn-toggle-sidebar'),
      btnSubmit = document.querySelector('#btn_event_submit'), // document.querySelector('button[type="submit"]'),
      btnDeleteEvent = document.querySelector('.btn-delete-event'),
      btnCopyEvent = document.querySelector('.btn-copy-event'),
      btnCancel = document.querySelector('.btn-cancel'),
      eventTitle = document.querySelector('#eventTitle'),
      eventStartDate = document.querySelector('#eventStartDate'),
      eventEndDate = document.querySelector('#eventEndDate'),
      eventRepeat = $('#eventRepeat'), 
      eventHidden = document.querySelector('#eventHidden'),
      eventEditPermission = $('#eventEditPermission'), 
      eventGroups = $('#eventGroups'), 
      eventCustomer = $('#eventCustomer'), 
      eventFacilities = $('#eventFacilities'), 
      eventUrl = document.querySelector('#eventURL'), // 主催者
      eventLabel = $('#eventLabel'), // ! Using jquery vars due to select2 jQuery dependency // 活動項目
      eventGuests = $('#eventGuests'), // ! Using jquery vars due to select2 jQuery dependency // 参加者
      eventActions = $('#eventActions'), // ! Using jquery vars due to select2 jQuery dependency // 活動項目
      eventColor = $('#eventColor'),
      eventLocation = document.querySelector('#eventLocation'),
      eventDescription = document.querySelector('#eventDescription'),
      allDaySwitch = document.querySelector('.allDay-switch'),
      selectAll = document.querySelector('.select-all'),
      filterInput = [].slice.call(document.querySelectorAll('.input-filter')),
      inlineCalendar = document.querySelector('.inline-calendar');

    const calendarTitle = document.querySelector('#calendarTitle'),
      calendarPrev = document.querySelector('#calendarPrev'),
      calendarNext = document.querySelector('#calendarNext'),
      calendarToday = document.querySelector('#calendarToday'),
      selectCalendarView = document.querySelector('#selectCalendarView');

    let eventToUpdate,
      currentEvents = events, // Assign app-calendar-events.js file events (assume events from API) to currentEvents (browser store/object) to manage and update calender events
      isFormValid = false,
      inlineCalInstance;

    // Init event Offcanvas
    const bsAddEventSidebar = new bootstrap.Offcanvas(addEventSidebar);

    setTimeout(select2_init, 500);
    function select2_init() {
      if (eventColor.length) {
        function renderColorBadges(option) {
          if (!option.id) {
            return option.text;
          }
          var $badge = "<span class='badge bg-" + $(option.element).data('label') + " m-2'>&nbsp;</span>" + option.text;
  
          return $badge;
        }
  
        eventColor.wrap('<div class="position-relative"></div>').select2({
          placeholder: '選択してください',
          dropdownParent: eventColor.parent(),
          templateResult: renderColorBadges,
          templateSelection: renderColorBadges,
          minimumResultsForSearch: -1,
          escapeMarkup: function (es) {
            return es;
          }
        });
      }
  
      if (eventCustomer.length) {
        eventCustomer.select2({
          placeholder: '選択してください',
          dropdownParent: eventColor.parent(),
          escapeMarkup: function(markup) {
            return markup;
          },
          templateResult: function(option) {
            if (!option.id) {
              return option.text;
            }
            var $html = option.text + '<br/><span>' + $(option.element).data('address')+ '</span>';
    
            return $html;
          },
          templateSelection: function(option) {
            return option.text;
          },
          minimumResultsForSearch: -1,
        });
      }
  
      // //! TODO: Update Event label and guest code to JS once select removes jQuery dependency
      // // Event Label (select2)
      // if (eventLabel.length) {
      //   function renderBadges(option) {
      //     if (!option.id) {
      //       return option.text;
      //     }
      //     var $badge =
      //       "<span class='badge badge-dot bg-" + $(option.element).data('label') + " me-2'> " + '</span>' + option.text;
  
      //     return $badge;
      //   }
      //   eventLabel.wrap('<div class="position-relative"></div>').select2({
      //     placeholder: '選択してください',
      //     dropdownParent: eventLabel.parent(),
      //     templateResult: renderBadges,
      //     templateSelection: renderBadges,
      //     minimumResultsForSearch: -1,
      //     escapeMarkup: function (es) {
      //       return es;
      //     }
      //   });
      // }
      
      // // Event Guests (select2)
      // if (eventGuests.length) {
      //   function renderGuestAvatar(option) {
      //     if (!option.id) {
      //       return option.text;
      //     }
      //     var $avatar =
      //       "<div class='d-flex flex-wrap align-items-center'>" +
      //       "<div class='avatar avatar-xs me-2'>" +
      //       "<img src='" +
      //       assetsPath +
      //       'img/avatars/' +
      //       $(option.element).data('avatar') +
      //       "' alt='avatar' class='rounded-circle' />" +
      //       '</div>' +
      //       option.text +
      //       '</div>';

      //     return $avatar;
      //   }
      //   eventGuests.wrap('<div class="position-relative"></div>').select2({
      //     placeholder: '選択してください',
      //     dropdownParent: eventGuests.parent(),
      //     closeOnSelect: false,
      //     templateResult: renderGuestAvatar,
      //     templateSelection: renderGuestAvatar,
      //     escapeMarkup: function (es) {
      //       return es;
      //     }
      //   });
      // }
    }


    // Event start (flatpicker)
    if (eventStartDate) {
      var start = eventStartDate.flatpickr({
        enableTime: true,
        altFormat: 'Y-m-dTH:i:S',
        locale : 'ja',
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            instance.mobileInput.setAttribute('step', null);
          }
        }
      });
    }

    // Event end (flatpicker)
    if (eventEndDate) {
      var end = eventEndDate.flatpickr({
        enableTime: true,
        altFormat: 'Y-m-dTH:i:S',
        locale : 'ja',
        onReady: function (selectedDates, dateStr, instance) {
          if (instance.isMobile) {
            instance.mobileInput.setAttribute('step', null);
          }
        }
      });
    }

    // Inline sidebar calendar (flatpicker)
    if (inlineCalendar) {
      inlineCalInstance = inlineCalendar.flatpickr({
        monthSelectorType: 'static',
        inline: true,
        locale: 'ja'
      });
    }

    // Event click function
    function eventClick(info) {
      eventToUpdate = info.event;
      if(eventToUpdate.id == 0) { return; }

      if (eventToUpdate.url) {
        info.jsEvent.preventDefault();
        window.open(eventToUpdate.url, '_blank');
      }
      bsAddEventSidebar.show();
      // For update event set offcanvas title text: Update Event
      if (offcanvasTitle) {
        offcanvasTitle.innerHTML = '予定を編集';
      }
      btnSubmit.innerHTML = '更新';
      btnSubmit.classList.add('btn-update-event');
      btnSubmit.classList.remove('btn-add-event');
      btnDeleteEvent.classList.remove('d-none');
      btnCopyEvent.classList.remove('d-none');

      eventTitle.value = eventToUpdate.title;
      start.setDate(eventToUpdate.start, true, 'Y-m-d');
      eventToUpdate.allDay === true ? (allDaySwitch.checked = true) : (allDaySwitch.checked = false);
      eventToUpdate.end !== null
        ? end.setDate(eventToUpdate.end, true, 'Y-m-d')
        : end.setDate(eventToUpdate.start, true, 'Y-m-d');
      eventLabel.val(eventToUpdate.extendedProps.calendar).trigger('change');
      eventToUpdate.extendedProps.location !== undefined
        ? (eventLocation.value = eventToUpdate.extendedProps.location)
        : null;
      eventToUpdate.extendedProps.guests !== undefined
        ? eventGuests.val(eventToUpdate.extendedProps.guests).trigger('change')
        : null;
      eventToUpdate.extendedProps.action !== undefined
        ? eventActions.val(eventToUpdate.extendedProps.action).trigger('change')
        : null;
      eventToUpdate.extendedProps.description !== undefined
        ? (eventDescription.value = eventToUpdate.extendedProps.description)
        : null;

      eventColor.val(eventToUpdate.extendedProps.color).trigger('change');

      // // Call removeEvent function
      // btnDeleteEvent.addEventListener('click', e => {
      //   removeEvent(parseInt(eventToUpdate.id));
      //   // eventToUpdate.remove();
      //   bsAddEventSidebar.hide();
      // });
    }

    // Modify sidebar toggler
    function modifyToggler() {
      const fcSidebarToggleButton = document.querySelector('.fc-sidebarToggle-button');
      fcSidebarToggleButton.classList.remove('fc-button-primary');
      fcSidebarToggleButton.classList.add('d-lg-none', 'd-inline-block', 'ps-0');
      while (fcSidebarToggleButton.firstChild) {
        fcSidebarToggleButton.firstChild.remove();
      }
      fcSidebarToggleButton.setAttribute('data-bs-toggle', 'sidebar');
      fcSidebarToggleButton.setAttribute('data-overlay', '');
      fcSidebarToggleButton.setAttribute('data-target', '#app-calendar-sidebar');
      fcSidebarToggleButton.insertAdjacentHTML('beforeend', '<i class="ti ti-menu-2 ti-sm"></i>');

      if(calendar.view.type == "timeGridWeek") {
        calendarTitle.innerHTML = moment(calendar.view.currentStart).format('M月D日') + calendar.view.dateEnv.defaultSeparator + moment(calendar.view.currentEnd).format('M月D日');
      } else {
        calendarTitle.innerHTML = calendar.view.title;
      }
      
    }

    // modify header toolbar
    function modifyHeaderToolbar(calendar) {
      calendarPrev.addEventListener('click', event => {
        calendar.prev();
        inlineCalInstance.setDate(calendar.getDate());
      });
      calendarNext.addEventListener('click', event => {
        calendar.next();
        inlineCalInstance.setDate(calendar.getDate());
      });
      calendarToday.addEventListener('click', event => {
        calendar.today();
        inlineCalInstance.setDate(calendar.today());
      });
      
      selectCalendarView.addEventListener('change', event => {
        let view_type= 'dayGridMonth';
        switch (event.target.value) {
          case 'month':
            view_type= 'dayGridMonth';
            break
          case 'week':
            view_type= 'timeGridWeek';
            break
          case 'day':
            view_type= 'timeGridDay'; // 'resourceTimeGridDay'
            break
          case 'list':
            view_type= 'listMonth';
            break
        }
        calendar.changeView(view_type);
      });
    }

    // Filter events by calender
    function selectedCalendars() {
      let selected = [],
        filterInputChecked = [].slice.call(document.querySelectorAll('.input-filter:checked'));

      filterInputChecked.forEach(item => {
        selected.push(item.getAttribute('data-value'));
      });

      return selected;
    }

    // --------------------------------------------------------------------------------------------------
    // AXIOS: fetchEvents
    // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
    // --------------------------------------------------------------------------------------------------
    function fetchEvents(info, successCallback) {
      // Fetch Events from API endpoint reference
      /* $.ajax(
        {
          url: '../../../app-assets/data/app-calendar-events.js',
          type: 'GET',
          success: function (result) {
            // Get requested calendars as Array
            var calendars = selectedCalendars();

            return [result.events.filter(event => calendars.includes(event.extendedProps.calendar))];
          },
          error: function (error) {
            console.log(error);
          }
        }
      ); */

      let calendars = selectedCalendars();
      // We are reading event object from app-calendar-events.js file directly by including that file above app-calendar file.
      // You should make an API call, look into above commented API call for reference
      let selectedEvents = currentEvents.filter(function (event) {
        // console.log(event.extendedProps.calendar.toLowerCase());
        return calendars.includes(event.extendedProps.calendar.toLowerCase());
      });
      // if (selectedEvents.length > 0) {
      successCallback(selectedEvents);
      // }
    }

    // Init FullCalendar
    // ------------------------------------------------
    let calendar = new Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale : 'ja',
      firstDay: 1,
      events: fetchEvents,
      plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin], // , resourceTimeGridPlugin
      editable: true,
      dragScroll: true,
      dayMaxEvents: 2,
      eventResizableFromStart: true,
      // customButtons: {
      //   sidebarToggle: {
      //     text: 'Sidebar'
      //   }
      // },
      headerToolbar: {
        start: null, //'sidebarToggle, today, prev, next, title',
        end: null // 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      buttonText : {
        today:    '今日',
        month:    '月',
        week:     '週',
        day:      '日',
        list:     'リスト',
        /*prev: '&nbsp;&#9668;&nbsp;',
        next: '&nbsp;&#9658;&nbsp;',
        prevYear: '&nbsp;&lt;&lt;&nbsp;',
        nextYear: '&nbsp;&gt;&gt;&nbsp;',*/
      },
      allDayText : '終日',
      viewHint: function(buttonText, buttonName) {
        return '';
        // if (buttonName.match(/^dayGrid/)) { // matches "dayGridWeek"
        //   return buttonText + " list view" // results in "week list view"
        // } else {
        //   return buttonText + " view" // results in "week view"
        // }
      },
      buttonHints : function(buttonText, buttonName) {
        return '';
      },
      navLinkHint: function(navText, navLinkName) {
        return '';
      },
      moreLinkHint: function(moreText, moreLinkName) {
        return '';
      },
      timeHint: function(timeText, TimeName) {
        return '';
      },
      eventHint: function(eventText, eventName) {
        return '';
      },
      direction: direction,
      initialDate: new Date(),
      navLinks: true, // can click day/week names to navigate views
      navLinkDayClick: function(date, jsEvent) {
        $(selectCalendarView).selectpicker('val', 'day');
        calendar.changeView("timeGridDay", date);
      },
      moreLinkClick : function( info ) { // 'timeGridDay',
        $(selectCalendarView).selectpicker('val', 'day');
        calendar.changeView("timeGridDay", info.date);
      },
      eventClassNames: function ({ event: calendarEvent }) {
        const colorName = calendarsColor[calendarEvent._def.extendedProps.color]; // calendarsColor[calendarEvent._def.extendedProps.calendar];
        // Background Color
        return ['fc-event-' + colorName];
      },
      dateClick: function (info) {
        let date = moment(info.date).format('YYYY-MM-DD');
        resetValues();
        bsAddEventSidebar.show();

        // For new event set offcanvas title text: Add Event
        if (offcanvasTitle) {
          offcanvasTitle.innerHTML = '予定を追加';
        }
        btnSubmit.innerHTML = '追加';
        btnSubmit.classList.remove('btn-update-event');
        btnSubmit.classList.add('btn-add-event');
        btnDeleteEvent.classList.add('d-none');
        btnCopyEvent.classList.add('d-none');
        eventStartDate.value = date;
        eventEndDate.value = date;
      },
      eventClick: function (info) {
        eventClick(info);
      },
      eventDidMount: function(info) {
        let event = info.event,
          tooltip_body = "";

        if(event.id == 0) {
          tooltip_body += "タイトル: " + event.title;
        } else {
          let event_time = event.allDay ? '終日' : moment(event.start).format('HH:mm') + "~" + moment(event.end).format('HH:mm');
          tooltip_body += "時間: " + event_time;
          tooltip_body += "<br/>タイトル: " + event.title;
          tooltip_body += "<br/>場所: " + event.extendedProps.location;
          tooltip_body += "<br/>顧客名: " + event.extendedProps.customer;
          tooltip_body += "<br/>作成者: " + event.extendedProps.creator;
          tooltip_body += "<br/>作成日: " + moment(event.extendedProps.create_date).format('YYYY年MM月DD日 HH:mm');
        }

        $(info.el).tooltip({ 
          title : tooltip_body,
          html: true,
          trigger: 'hover',
          boundary: 'window',
          template : '<div class="tooltip event-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner text-start fs-tiny"></div></div>'
        });
        /*
        var tooltip = new Tooltip(info.el, {
          title: tooltip_body,
          placement: 'top',
          trigger: 'hover',
          container: 'body'
        });*/
      },
      datesSet: function () {
        modifyToggler();
      },
      viewDidMount: function (viewobj) {
        modifyToggler();
      },
    });

    // Render calendar
    calendar.render();
    // Modify sidebar toggler
    modifyToggler();
    // Modify header toolbar
    modifyHeaderToolbar(calendar);

    const eventForm = document.getElementById('eventForm');
    const fv = FormValidation.formValidation(eventForm, {
      fields: {
        eventTitle: {
          validators: {
            notEmpty: {
              message: '必須項目です。'
            }
          }
        },
        eventStartDate: {
          validators: {
            notEmpty: {
              message: '必須項目です。'
            }
          }
        },
        eventEndDate: {
          validators: {
            notEmpty: {
              message: '必須項目です。'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          eleValidClass: '',
          rowSelector: function (field, ele) {
            // field is the field name & ele is the field element
            return '.mb-3';
          }
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        // Submit the form when all fields are valid
        // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    })
      .on('core.form.valid', function () {
        // Jump to the next step when all fields in the current step are valid
        isFormValid = true;
      })
      .on('core.form.invalid', function () {
        // if fields are invalid
        isFormValid = false;
      });

    // Sidebar Toggle Btn
    if (btnToggleSidebar) {
      btnToggleSidebar.addEventListener('click', e => {
        btnCancel.classList.remove('d-none');
      });
    }

    // Add Event
    // ------------------------------------------------
    function addEvent(eventData) {
      // ? Add new event data to current events object and refetch it to display on calender
      // ? You can write below code to AJAX call success response

      currentEvents.push(eventData);
      calendar.refetchEvents();

      // ? To add event directly to calender (won't update currentEvents object)
      // calendar.addEvent(eventData);
    }

    // Update Event
    // ------------------------------------------------
    function updateEvent(eventData) {
      // ? Update existing event data to current events object and refetch it to display on calender
      // ? You can write below code to AJAX call success response
      eventData.id = parseInt(eventData.id);
      currentEvents[currentEvents.findIndex(el => el.id === eventData.id)] = eventData; // Update event by id
      calendar.refetchEvents();

      // ? To update event directly to calender (won't update currentEvents object)
      // let propsToUpdate = ['id', 'title', 'url'];
      // let extendedPropsToUpdate = ['calendar', 'guests', 'location', 'description'];

      // updateEventInCalendar(eventData, propsToUpdate, extendedPropsToUpdate);
    }

    // Remove Event
    // ------------------------------------------------

    function removeEvent(eventId) {
      // ? Delete existing event data to current events object and refetch it to display on calender
      // ? You can write below code to AJAX call success response
      currentEvents = currentEvents.filter(function (event) {
        return event.id != eventId;
      });
      calendar.refetchEvents();

      // ? To delete event directly to calender (won't update currentEvents object)
      // removeEventInCalendar(eventId);
    }

    // (Update Event In Calendar (UI Only)
    // ------------------------------------------------
    const updateEventInCalendar = (updatedEventData, propsToUpdate, extendedPropsToUpdate) => {
      const existingEvent = calendar.getEventById(updatedEventData.id);

      // --- Set event properties except date related ----- //
      // ? Docs: https://fullcalendar.io/docs/Event-setProp
      // dateRelatedProps => ['start', 'end', 'allDay']
      // eslint-disable-next-line no-plusplus
      for (var index = 0; index < propsToUpdate.length; index++) {
        var propName = propsToUpdate[index];
        existingEvent.setProp(propName, updatedEventData[propName]);
      }

      // --- Set date related props ----- //
      // ? Docs: https://fullcalendar.io/docs/Event-setDates
      existingEvent.setDates(updatedEventData.start, updatedEventData.end, {
        allDay: updatedEventData.allDay
      });

      // --- Set event's extendedProps ----- //
      // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
      // eslint-disable-next-line no-plusplus
      for (var index = 0; index < extendedPropsToUpdate.length; index++) {
        var propName = extendedPropsToUpdate[index];
        existingEvent.setExtendedProp(propName, updatedEventData.extendedProps[propName]);
      }
    };

    // Remove Event In Calendar (UI Only)
    // ------------------------------------------------
    function removeEventInCalendar(eventId) {
      calendar.getEventById(eventId).remove();
    }

    // Add new event
    // ------------------------------------------------
    btnSubmit.addEventListener('click', e => {
      if (btnSubmit.classList.contains('btn-add-event')) {
        if (isFormValid) {
          let newEvent = {
            id: calendar.getEvents().length + 1,
            title: eventTitle.value,
            start: eventStartDate.value,
            end: eventEndDate.value,
            startStr: eventStartDate.value,
            endStr: eventEndDate.value,
            display: 'block',
            extendedProps: {
              location: eventLocation.value,
              guests: eventGuests.val(),
              calendar: eventLabel.val(),
              description: eventDescription.value,
              customer: eventCustomer.val(),
              creator: '明石 洋次',
              create_date: new Date(),
              color: eventColor.val(),
              action: eventActions.val(),
            }
          };
          // if (eventUrl.value) {
          //   newEvent.url = eventUrl.value;
          // }
          if (allDaySwitch.checked) {
            newEvent.allDay = true;
          }
          addEvent(newEvent);
          bsAddEventSidebar.hide();
        }
      } else {
        // Update event
        // ------------------------------------------------
        if (isFormValid) {
          let eventData = {
            id: eventToUpdate.id,
            title: eventTitle.value,
            start: eventStartDate.value,
            end: eventEndDate.value,
            // url: eventUrl.value,
            extendedProps: {
              location: eventLocation.value,
              guests: eventGuests.val(),
              calendar: eventLabel.val(),
              description: eventDescription.value,
              customer: eventCustomer.val(),
              creator: '明石 洋次',
              create_date: new Date(),
              color: eventColor.val(),
              action: eventActions.val(),
            },
            display: 'block',
            allDay: allDaySwitch.checked ? true : false
          };

          updateEvent(eventData);
          bsAddEventSidebar.hide();
        }
      }
    });

    // Call removeEvent function
    btnDeleteEvent.addEventListener('click', e => {
      removeEvent(parseInt(eventToUpdate.id));
      // eventToUpdate.remove();
      bsAddEventSidebar.hide();
    });

    // Call copyEvent function
    btnCopyEvent.addEventListener('click', e => {

      bsAddEventSidebar.hide();
    });

    // Reset event form inputs values
    // ------------------------------------------------
    function resetValues() {
      eventEndDate.value = '';
      eventUrl.value = '';
      eventStartDate.value = '';
      eventTitle.value = '';
      eventLocation.value = '';
      allDaySwitch.checked = false;
      eventGuests.val('').trigger('change');
      eventActions.val('').trigger('change');
      eventDescription.value = '';
    }

    // When modal hides reset input values
    addEventSidebar.addEventListener('hidden.bs.offcanvas', function () {
      resetValues();
    });

    // Hide left sidebar if the right sidebar is open
    btnToggleSidebar.addEventListener('click', e => {
      if (offcanvasTitle) {
        offcanvasTitle.innerHTML = '予定を追加';
      }
      btnSubmit.innerHTML = '追加';
      btnSubmit.classList.remove('btn-update-event');
      btnSubmit.classList.add('btn-add-event');
      btnDeleteEvent.classList.add('d-none');
      btnCopyEvent.classList.add('d-none');
      appCalendarSidebar.classList.remove('show');
      appOverlay.classList.remove('show');
    });

    // Calender filter functionality
    // ------------------------------------------------
    if (selectAll) {
      selectAll.addEventListener('click', e => {
        if (e.currentTarget.checked) {
          document.querySelectorAll('.input-filter').forEach(c => (c.checked = 1));
        } else {
          document.querySelectorAll('.input-filter').forEach(c => (c.checked = 0));
        }
        calendar.refetchEvents();
      });
    }

    if (filterInput) {
      filterInput.forEach(item => {
        item.addEventListener('click', () => {
          // document.querySelectorAll('.input-filter:checked').length < document.querySelectorAll('.input-filter').length
          //   ? (selectAll.checked = false)
          //   : (selectAll.checked = true);

          calendar.refetchEvents();
        });
      });
    }

    // Jump to date on sidebar(inline) calendar change
    inlineCalInstance.config.onChange.push(function (date) {
      calendar.changeView(calendar.view.type, moment(date[0]).format('YYYY-MM-DD'));
      modifyToggler();
      appCalendarSidebar.classList.remove('show');
      appOverlay.classList.remove('show');
    });

    // custom script    
    const noticeRepeater = $('.notice-repeater');
    if (noticeRepeater.length) {
      var row = 2;
      var col = 1;

      noticeRepeater.repeater({
        show: function () {
          var fromControl = $(this).find('.form-control, .form-select');
  
          fromControl.each(function (i) {
            var id = 'notice-repeater-' + row + '-' + col;
            $(fromControl[i]).attr('id', id);
            col++;
          });
  
          row++;
  
          $(this).slideDown();
        },
        hide: function (e) {
          // confirm('Are you sure you want to delete this element?') && $(this).slideUp(e);
          $(this).slideUp(e);
        }
      });
    }
  })();
});
