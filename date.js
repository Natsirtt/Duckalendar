

Number.prototype.isBi = function () {
  if (Math.floor(this) != this) {
    throw "This n'est pas un entier";
  }
  if (this.valueOf() % 400 === 0) {
    return true;
  } else if (this.valueOf() % 4 === 0 && this.valueOf() % 100 !== 0){
    return true;
  } else {
    return false;
  }
}

Number.prototype.isInInter = function(min, max) {
  if (isNaN(min) || isNaN(max)) {
    return false;
  }
  return min <= this && this <= max;
}

function getJourMax(m,a) {
  if (m == 2 && a.isBi()) {
    return 29;
  } else if (m == 2){
    return 28;
  }
  
  if (m <= 7) {
    if (m % 2 === 0) {
      return 30;
    } else {
      return 31;
    }
  } else {
    if (m % 2 === 0) {
      return 31;
    } else {
      return 30;
    }
  }
}

function twoInt(n) {
  if (isNaN(n)) {
    throw "n n'est pas un nombre entier";
  }
  var string;
  if (n < 10) {
    string = "0";
  } else {
    string = "";
  }
  string += n.toString();
  return string;
}

function todayDate() {
  var date = new Date();
  var dateStr = "";
  dateStr += twoInt(date.getDate()) + "/";
  dateStr += twoInt(date.getMonth() + 1) + "/";
  dateStr += date.getFullYear();
  
  return dateStr;
}

function isDate(s) {
  var reg = new RegExp("[0-9]{2}\/[0-9]{2}\/[0-9]{4}");
  if (!reg.test(s)) {
    return false;
  } else {
    var day = parseInt(s.substring(0, 2), 10);
    var month = parseInt(s.substring(3, 5), 10);
    var year = parseInt(s.substring(6, 11), 10);
    return month.isInInter(1, 12) && day.isInInter(1, getJourMax(month, year));
  }
}

function getDayFromDate(d, m, y) {
  m = m - 1;
  var monthes = [1, 4, 4, 0, 2, 5, 0, 3, 6, 1, 4, 6];
  var years = [6, 4, 2, 0, 6, 4];
  var yearId = y % 100;
  yearId = yearId + (Math.floor(yearId / 4));
  var res = yearId + d;
  res = res + monthes[m];
  if (y.isBi() && m < 3) {
    res = res - 1;
  }
  yearId = (Math.floor(y / 100)) - 16;
  res = res + years[yearId];
  var day = res % 7;
  var days = [6, 7, 1, 2, 3, 4, 5];
  return days[day];
}

function numericToStrDay(d) {
  var days = [
    "lundi", 
    "mardi", 
    "mercredi", 
    "jeudi", 
    "vendredi",
    "samedi",
    "dimanche",
  ]
  return days[d - 1];
}

function numericToStrMonth(m) {
  var monthes = [
    "Janvier", 
    "Fevrier", 
    "Mars", 
    "Avril", 
    "Mai",
    "Juin",
    "Juillet",
    "Aout",
    "Septembre",
    "Octobre",
    "Novembre",
    "Decembre"
  ]
  return monthes[m];
}

function isToday(j, m, y) {
  date = new Date();
  return date.getDate() == j
      && date.getMonth() + 1 == m
      && date.getFullYear() == y;      
}

function previousMonth(m, y) {
  if (m == 1) {
    return 12;
  } else {
    return m - 1;
  }
}

function prevMonthYear(m , y) {
  if (m == 1) {
    return y - 1;
  } else {
    return y;
  }
}

function drawMonth(m, y) {
  $("#month").html(
    numericToStrMonth(m - 1)
    + " "
    + y
    + "<br />"
  );
  var i = 0;
  var dayNb = getJourMax(m, y);
  var fstJ = getDayFromDate(1, m, y);
  var j = 0;
  var calBody = $("#calendarBody");
  calBody.html("");
  var content = "";
  if (fstJ > 1) {
    content += "<tr>";
  }
  var lstMonthDMax = getJourMax (
    previousMonth(m, y),
    prevMonthYear(m, y)
  );
  while (j < fstJ - 1) {
    if (j > 4) {
      content += '<td class="noWork"></td>';
    } else {
      var num = lstMonthDMax - fstJ + j + 2;
      content += "<td class = \"noMonth\">" + num + "</td>";
    }
    j++;
  }
  while (i < dayNb) {
    if (j === 0) {
      content += "<tr>";
    }
    while (i < dayNb) {
      if (j === 0) {
        content += "<tr>";
      }
      while (j < 7 && i < dayNb){
        i++;
        var bal;
        if (isToday(i, m, y)) {
          bal = '<td class="today month';
        } else {
          bal = '<td class="month';
        }
        if (j >= 5) {
          bal += " noWork\">";
        } else {
          bal += '">';
        }
        content += bal + i + "</td>";
        j++;
      }
      var k = 0;
      while (j < 7) {
        k++;
        var bal;
        if (j > 4) {
          bal = '<td class = "noWork noMonth">'
        } else {
          bal = '<td class = "noMonth">'
        }
        content += bal + k +"</td>";
        j++;
      }
      j = 0;
      content += "</tr>";
    }
  }
  calBody.append(content);
  $(".month").click(
    function () {
      $("#testBlock").animate({right:'0%'});
      stretched = true;
      selectedDay = $(this).text();
      $("#testBlock").html(
        selectedDay
        + " "
        + numericToStrMonth(m - 1)
        + " "
        + y
      );
    }
  );
  $("#testBlock").click(
    function(){
      if (stretched) {
        $("#testBlock").animate({right: '-18%'});
        $("#testBlock").html("");
        stretched = false;
      } else {
        $("#testBlock").animate({right:'0%'});
        stretched = true;
      }
    }
  );
}

function printHour() {
  var date = new Date();
  $("#date").html(
    twoInt(date.getDate())
    + " " 
    + numericToStrMonth(date.getMonth())
    + " "
    + date.getFullYear()
    + "&nbsp;&nbsp;&nbsp;&nbsp;"
    + twoInt(date.getHours())
    + " : "
    + twoInt(date.getMinutes())
  );
  setTimeout('printHour();', '1000');
}

function monthDown() {
  if (m - 1 <= 0) {
    if (y - 1 > 1700) {
      y = y - 1;
      m = 12;
    }
  } else {
    m = m - 1;
  }
  drawMonth(m, y);
}

function monthUp() {
  if (m + 1 > 12) {
    if (y + 1 < 2200) {
      y = y + 1;
      m = 1;
    }
  } else {
    m = m + 1;
  }
  drawMonth(m, y);
}

var date = new Date();
var selectedDay = date.getDate();
var m = date.getMonth() + 1;
var y = date.getFullYear();
var stretched = false;

drawMonth(m, y);
$("#left").click(function (){monthDown();});
$("#right").click(function (){monthUp();});
window.onload = printHour();
