//Fonctions pour les cookies

function getCookie(cookieName) {
    var cookContent = document.cookie;
    var cookieName2 = cookieName + "=";
    var contentLength = cookContent.length;

    for (var i = 0; i < contentLength; i++) {
        var length = i + cookieName2.length;
        if (cookContent.substring(i, length) == cookieName2) {
            var cookEnd = cookContent.indexOf(";", length);
            if (cookEnd == -1) {
                cookEnd = contentLength;
            }
            return cookContent.substring(length, cookEnd);
        }
    }
    return null;
}

/**
 * Une fonction qui créée un objet Date avec en paramètre le jour le mois et 
 * l'année. Le mois est indéxé sur 1 ce qui est plus logique que l'indexation
 * sur 0 de Javascript.
 * @param {int} j Le jour
 * @param {int} m le mois
 * @param {int} y l'année
 * @return {Date}
 */
function newDate(j, m, y) {
    return new Date(y, m - 1, j);
}

/**
 * Une fonction qui créé un objet Date avec en paramètre une chaine de caractère
 * formatée comme ceci : "yyyy-mm-dd". Cependant le mois est indéxé sur 1.
 * @param {string} str La chaine de caractère représentant la date
 * @returns {Date} L'objet Date
 */
function newDateFromStr(str) {
    var splitedStr = str.split("-");
    return newDate(parseInt(splitedStr[0]), parseInt(splitedStr[1]), parseInt(splitedStr[2]));
}

/**
 * Une fonction qui créé un objet Date avec en paramètre une chaine de caractère
 * formatée comme ceci : "hh:mm:ss". L'objet Date n'est construit et utilisable
 * qu'en temps qu'une représentation de l'heure.
 * @param {string} str La chaine de caracètre représentant l'heure.
 * @return {Date}
 */
function newDateTimeFromStr(str) {
    var splitedStr = str.split(":");
    return new Date(0, 0, 0, splitedStr[0], splitedStr[1], splitedStr[2]);
}


//Ajout de comparaisons au protorype des dates

/**
 * Compare les heures et les minutes de this et date2. Renvoie 0 si ce sont
 * les mêmes, -1 si this < date2 (seulement pour les heures et minutes) et
 * retourne 1 si this > date2.
 * @param {Date} date2 La date dont les heures et les minutes sont à comparer
 * avec la cible de l'appel.
 */
function hoursAndMinutesCompare(date2) {
    var thisHours = this.getHours();
    var date2Hours = date2.getHours();
    var thisMinutes = this.getMinutes();
    var date2Minutes = date2.getMinutes;

    if ((thisHours < date2Hours)
            || ((thisHours == date2Hours) && (thisMinutes < date2Minutes))) {
        return -1;
    }

    if ((thisHours == date2Hours) && (thisMinutes == date2Minutes)) {
        return 0;
    }

    return 1;
}

/**
 * Compare la date this et la date2. Renvoie 0 si ce sont les mêmes,
 * -1 si this < date2 et 1 si date2 > this.
 * Seul la date, je mois et l'année sont pris en compte.
 * @param {Date} date2 La date à comparer avec la cible de l'appel.
 */
function dateCompare(date2) {
    var thisDate = this.getDate();
    var date2Date = date2.getDate();
    var thisMonth = this.getMonth();
    var date2Month = date2.getMonth();
    var thisYear = this.getFullYear();
    var date2Year = date2.getFullYear();

    if ((thisYear < date2Year)
            || ((thisYear == date2Year) && (thisMonth < date2Month))
            || ((thisYear == date2Year) && (thisMonth == date2Month)
            && (thisDate < date2Date))) {
        return -1;
    }

    if ((thisYear > date2Year)
            || ((thisYear == date2Year) && (thisMonth > date2Month))
            || ((thisYear == date2Year) && (thisMonth == date2Month)
            && (thisDate > date2Date))) {
        return 1;
    }

    return 0;
}

Date.prototype.compareHoursAndMinutes = hoursAndMinutesCompare;
Date.prototype.compare = dateCompare;

//Ajout d'une méthode retournant la date au format "yyyy-mm-dd"

/**
 * Permet de récupérer la date au format d'une chaîne de caractères
 * formatée comme suit : "yyyy-mm-dd".
 * @returns {String} La chaine de caractère formatée.
 */
function YMDDateFormat() {
    var str = this.getFullYear() + "-" + (this.getMonth() + 1) + "-" + this.getDate();
    return str;
}

Date.prototype.YMDFormat = YMDDateFormat;

//Le calendrier

/**
 * Permet d'afficher le message passé en paramètre dans
 * l'emplacement de notification.
 * @param {string} msg Le message à afficher.
 */
function setNotification(msg) {
    $("#notification").html('<p id="notificationMsg">' + msg + '. Coin.</p>');
}

/**
 * Affiche dans le container tout un évènement, permettant de le modifier
 * et de le supprimer.
 */
function displayOneEvent(container, eventName, date) {
    
}

/**
 * Affiche un formulaire de modification d'évènement dans le container.
 */
function displayModifForm(container, eventName, date) {
    alert()
    container.html("");
}

/**
 * Affiche dans le container le formulaire d'ajout.
 * @param {} container L'élément html dans lequel sera affiché le formulaire.
 */
function displayAddEventForm(container) {
    var dateStr = selectedDate.YMDFormat();
    container.html(
            '<form action="" method="post">'
            + '<p>Nom de l\'évènement :</p>'
            + '<input type="text" id="eventNameInput" maxlength="25" size="26" />'
            + '<p>Date de fin :</p>'
            + '<input type="date" min="' + dateStr + '" value="' + dateStr + '" id="endDateInput" />'
            + '<p>Heure de début :</p>'
            + '<input type="time" value="08:00" id="beginTimeInput" />'
            + '<p>Heure de fin :</p>'
            + '<input type="time" value="09:00" id="endTimeInput" />'
            + '<p>Description de l\'évènement :</p>'
            + '<textarea id="descInput" rows="10" cols="30">Facultatif...</textarea>'
            + '</form>'
            );
    //Affichage du bouton d'enregistrement
    $("#newEventSubmit").css({display: "block"});

    //Effacement du contenu de la textarea en cas de 1er clic
    $("#descInput").click(function() {
        if ($(this).val() == "Facultatif...") {
            $(this).val("");
        }
    });

    //Traitement asynchrone du formulaire
    $("#addEventForm").submit(function() {
        var newEventStatus = $("#newEventStatus");
        newEventStatus.css({display: "block"});
        var eventNameValue = $("#eventNameInput").val();
        var endDateValue = $("#endDateInput").val();
        var beginTimeValue = $("#beginTimeInput").val();
        var endTimeValue = $("#endTimeInput").val();
        var descValue = $("#descInput").val();
        if (descValue == "Facultatif...") {
            descValue = "";
        }
        var beginTimeDate = newDateTimeFromStr(beginTimeValue + ":00");
        var endTimeDate = newDateTimeFromStr(endTimeValue + ":00");

        //Les champs doivent être remplis & valides
        if (eventNameValue == "" || endDateValue == ""
                || beginTimeValue == "" || endTimeValue == "") {
            setNotification("Veuillez correctement remplir tout les champs");
        } else {
            //On empêche de mettre une date de fin antérieure à la date de début
            if (selectedDate.compare(new Date(endDateValue)) == 1) {
                setNotification("Veuilez entrer une date de fin posterieure ou identique à celle de début");
            } else if ((selectedDate.compare(new Date(endDateValue)) == 0)
                    && (beginTimeDate.compareHoursAndMinutes(endTimeDate) != -1)) {
                //Si la date de début et de fin et la même, l'heure de fin doit être
                //strictement supérieure à celle de début
                setNotification("Veuillez entrer une horaire de fin posterieure à celle de début");

            } else {
                //On peut traiter le formulaire
                $.ajax({
                    url: 'eventManager.ajax.php',
                    type: 'POST',
                    data: {
                        req: 'add',
                        eventName: eventNameValue,
                        beginDate: selectedDate.YMDFormat(),
                        endDate: endDateValue,
                        beginTime: beginTimeValue,
                        endTime: endTimeValue,
                        desc: descValue
                    },
                    error: function(j, textStatus, errorThrown) {
                        setNotification("Erreur lors de la requête asynchrone");
                    },
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result.status == "bddConnectionFailed") {
                            setNotification("Erreur de connexion à la base de données");
                        } else if (result.status == "inputsError") {
                            setNotification("Erreur sur les formulaires");
                        } else if (result.status == "existsAlready") {
                            setNotification("L'évènement " + eventNameValue + " éxiste déjà à cette date");
                        } else if (result.status == "insertionFailed") {
                            setNotification("L'insertion de l'évènement en base de données à échoué");
                        } else if (result.status == "unknownReq") {
                            setNotification("Requête inconnue par le serveur");
                        } else if (result.status == "ok") {
                            setNotification("Evènement ajouté avec succès");
                            displayListEvents($("#innerRightPanel"), selectedDate);
                            $("#newEventSubmit").css({display: "none"});
                        } else {
                            setNotification("Réponse du serveur inconnue");
                        }
                    }
                });
            }
        }

        newEventStatus.css({display: "none"});
        return false;
    });
}

/**
 * Affiche dans le conteneur html la liste des évènements du jour.
 * @param {} container Le conteneur html
 * @param {Date} selectedDate La date dont les évènements sont à afficher
 */
function displayListEvents(container, selectedDate) {
    $.ajax({
        url: 'eventsList.ajax.php',
        type: 'POST',
        data: {
            date: selectedDate.YMDFormat()
        },
        error: function(j, textStatus, errorThrown) {
            setNotification("Erreur lors de la requête asynchrone");
        },
        success: function(data) {
            var result = JSON.parse(data);
            if (result.status == "userUnidentified") {
                setNotification("Identifiez-vous afin de pouvoir utiliser Duckalendar");
            } else if (result.status == "inputError") {
                setNotification("Erreur de données en entrée du serveur");
            } else if (result.status == "bddConnectionError") {
                setNotification("Erreur de connexion à la base de données. Liste des évènements du jour indisponible");
            } else if (result.status == "ok") {
                //Ok
                var newHtml = "<dl>";
                for (var i = 0; i < result.eventsNb; i++) {
                    var event = result.eventsArray[i];
                    newHtml += '<dt id="' + event.name + '">[' + event.beginTime + "] " + event.name + "</dt>";
                    if (event.desc != "") {
                        newHtml += '<dd class="eventDesc">' + event.desc + '</dd>';
                    }
                    
                    $("#" + event.name).click(function() {
                        displayModifForm(container,
                                         event.name,
                                         selectedDate);
                    });
                }
                newHtml += "</dl>";
                container.html(newHtml);
            } else {
                setNotification("Réponse du serveur inconnue");
            }
        }
    });
}

/**
 * Insère une classe html aux jours du calendrier qui possède un ou
 * plusieurs évènement(s) en base de données.
 */
function displayDateEvents(month, year) {

}

Number.prototype.isBi = function() {
    if (Math.floor(this) != this) {
        throw "This n'est pas un entier";
    }
    if (this.valueOf() % 400 === 0) {
        return true;
    } else if (this.valueOf() % 4 === 0 && this.valueOf() % 100 !== 0) {
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

function getJourMax(m, a) {
    if (m == 2 && a.isBi()) {
        return 29;
    } else if (m == 2) {
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
        "dimanche"
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
    /*date = new Date();
     return date.getDate() == j
     && date.getMonth() + 1 == m
     && date.getFullYear() == y;*/
    var paramDate = new Date(y, m - 1, j);
    var todayDate = new Date();
    return todayDate.compare(paramDate) == 0;
}

function previousMonth(m, y) {
    if (m == 1) {
        return 12;
    } else {
        return m - 1;
    }
}

function prevMonthYear(m, y) {
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
    var lstMonthDMax = getJourMax(
            previousMonth(m, y),
            prevMonthYear(m, y)
            );
    while (j < fstJ - 1) {
        if (j > 4) {
            num++;
            content += '<td class="noWork noMonth monthDown">' + num + '</td>';
        } else {
            var num = lstMonthDMax - fstJ + j + 2;
            content += '<td class = "noMonth monthDown">' + num + '</td>';
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
            while (j < 7 && i < dayNb) {
                i++;
                var bal;
                if (isToday(i, m, y)) {
                    bal = '<td class="today month';
                } else {
                    bal = '<td class="month';
                }
                if (j >= 5) {
                    bal += ' noWork">';
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
                    bal = '<td class = "noWork noMonth monthUp">';
                } else {
                    bal = '<td class = "noMonth monthUp">';
                }
                content += bal + k + "</td>";
                j++;
            }
            j = 0;
            content += "</tr>";
        }
    }
    calBody.append(content);
    displayDateEvents(m, y);

    $(".month").click(
            function() {
                var login = getCookie("connection");
                if (login == null) {
                    setNotification("Veuillez vous connecter afin de pouvoir utiliser Duckalendar");
                } else {
                    $("#newEventSubmit").css({display: "none"});
                    var panel = $("#rightPanel");
                    panel.css({display: "block"});
                    panel.animate({right: '0%'});
                    stretched = true;
                    selectedDay = $(this).text();

                    $("#topRightPanel").html(
                            selectedDay
                            + " "
                            + numericToStrMonth(m - 1)
                            + " "
                            + y
                            );
                    var innerPanel = $("#innerRightPanel");
                    selectedDate = new Date(y, m - 1, selectedDay);
                    var todayDate = new Date();
                    if (selectedDate.compare(todayDate) == -1) {
                        $("#plus").css({display: "none"});
                    } else {
                        $("#plus").css({display: "block"});
                    }
                    displayListEvents(innerPanel, selectedDate);
                }
            });

    $("#panelArrow").click(
            function() {
                if (stretched) {
                    var panel = $("#rightPanel");
                    panel.animate({right: '-22%'}, 400, "swing", function() {
                        panel.css({display: "none"});
                        $("#newEventSubmit").css({display: "none"});
                        stretched = false;
                    });
                }
            });

    $("#plus").click(
            function() {
                if (getCookie("connection") == null) {
                    setNotification("C'est de la triche");
                } else {
                    var innerPanel = $("#innerRightPanel");
                    displayAddEventForm(innerPanel);
                }
            });

    $(".monthUp").click(
            function() {
                monthUp();
            });

    $(".monthDown").click(
            function() {
                monthDown();
            });
}

function printHour() {
    var date = new Date();
    $("#date").html(
            twoInt(date.getDate())
            + " "
            + numericToStrMonth(date.getMonth())
            + " "
            + date.getFullYear()
            + "&nbsp;&nbsp;&nbsp;"
            + twoInt(date.getHours())
            + ":"
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
var selectedDate;
var m = date.getMonth() + 1;
var y = date.getFullYear();
var stretched = false;
var addEventFormDisplayed = false;

drawMonth(m, y);
$("#left").click(function() {
    monthDown();
});
$("#right").click(function() {
    monthUp();
});
window.onload = printHour();
