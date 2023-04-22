/**
 * App Calendar Events
 */

'use strict';

let date = new Date();
let nextDay = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
// prettier-ignore
let nextMonth = date.getMonth() === 11 ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() + 1, 1);
// prettier-ignore
let prevMonth = date.getMonth() === 11 ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() - 1, 1);

let events = [
  {
    id: 1,
    url: '',
    title: '営業会議',
    start: date,
    end: nextDay,
    allDay: false,
    extendedProps: {
      calendar: 'Business',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'primary',
      action: '',
    }
  },
  {
    id: 2,
    url: '',
    title: '山口様FF＠大手町',
    start: new Date(date.getFullYear(), date.getMonth() + 1, -11),
    end: new Date(date.getFullYear(), date.getMonth() + 1, -10),
    allDay: true,
    extendedProps: {
      calendar: 'Business',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'secondary',
      action: '',
    }
  },
  {
    id: 3,
    url: '',
    title: '川上様P＠赤坂',
    allDay: true,
    start: new Date(date.getFullYear(), 1, 10),
    end: new Date(date.getFullYear(), 1, 11),
    extendedProps: {
      calendar: 'Personal',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'info',
      action: '',
    }
  },
  {
    id: 4,
    url: '',
    title: "川上様P＠赤坂",
    start: new Date(date.getFullYear(), date.getMonth() + 1, -11),
    end: new Date(date.getFullYear(), date.getMonth() + 1, -10),
    extendedProps: {
      calendar: 'Personal',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'warning',
      action: '',
    }
  },
  {
    id: 5,
    url: '',
    title: '本橋様OI＠丸の内',
    start: new Date(date.getFullYear(), date.getMonth() + 1, -13),
    end: new Date(date.getFullYear(), date.getMonth() + 1, -12),
    allDay: true,
    extendedProps: {
      calendar: 'ETC',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'info',
      action: '',
    }
  },
  {
    id: 6,
    url: '',
    title: '樋口様P@茅場町',
    start: new Date(date.getFullYear(), date.getMonth() + 1, -13),
    end: new Date(date.getFullYear(), date.getMonth() + 1, -12),
    allDay: true,
    extendedProps: {
      calendar: 'Personal',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'warning',
      action: '',
    }
  },
  {
    id: 7,
    url: '',
    title: '名古屋支店会議',
    start: new Date(date.getFullYear(), date.getMonth() + 1, -13),
    end: new Date(date.getFullYear(), date.getMonth() + 1, -12),
    extendedProps: {
      calendar: 'Family',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'danger',
      action: '',
    }
  },
  {
    id: 8,
    url: '',
    title: '営業会議',
    start: new Date(date.getFullYear(), date.getMonth() + 1, -13),
    end: new Date(date.getFullYear(), date.getMonth() + 1, -12),
    allDay: true,
    extendedProps: {
      calendar: 'Business',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'success',
      action: '',
    }
  },
  {
    id: 9,
    url: '',
    title: '名古屋支店会議',
    start: nextMonth,
    end: nextMonth,
    allDay: true,
    extendedProps: {
      calendar: 'Business',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'success',
      action: '',
    }
  },
  {
    id: 10,
    url: '',
    title: '阿部様　相談＠溜池山王',
    start: prevMonth,
    end: prevMonth,
    allDay: true,
    extendedProps: {
      calendar: 'Personal',
      location: '',
      customer: '',
      creator: '明石 洋次',
      create_date: new Date(),
      color: 'success',
      action: '',
    }
  }
];

// get holiday
$.get("https://holidays-jp.github.io/api/v1/date.json", function(holidays){
  
  for (const [key, value] of Object.entries(holidays)) {
    events.push(
      {
        id: 0,
        url: '',
        title: value,
        start: new Date(key),
        end: new Date(key),
        allDay: true,
        extendedProps: {
          calendar: 'Holiday',
          location: '',
          customer: '',
          creator: '明石 洋次',
          create_date: new Date(),
          color: 'danger',
          action: '',
        }
      }
    );
  }
});