//Des variables utiles
var date = new Date();
var selectedDate = new Date();
var m = date.getMonth() + 1;
var y = date.getFullYear();
var stretched = false;
var addEventFormDisplayed = false;
//Le booléen indiquant si tel jour à un évènement ou non
var dayHasEvent = new Array();
//Le tableau du nom des évènements affichés dans le panel
var displayedEvents = new Array();
var displayedEventsNb = 0;
//Pour le clic sur les listes d'événements
var imgClicked = false;
//La taille d'un input de titre d'événement
var eventNameInputLength = 25;

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
    return newDate(parseInt(splitedStr[2]), parseInt(splitedStr[1]), parseInt(splitedStr[0]));
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
    var date2Minutes = date2.getMinutes();
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

function DMY_to_YMD(str) {
    var splitedStr = str.split("-");
    return splitedStr[2] + "-" + splitedStr[1] + "-" + splitedStr[0];
}

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
 * Fonction asynchrone de modification d'un évènement en fonction des
 * formulaires de modification possiblement affichés.
 */
function modifEvent() {
    //On empêche de mettre une date de fin antérieure à la date de début
    var endDateValue = $("#panelEndDate").val();
    var endTimeValue = $("#panelEndTime").val();
    var beginTimeValue = $("#panelBeginTime").val();
    var ok = true;
    if (endDateValue == "") {
        endDateValue = DMY_to_YMD($("#panelEndDate").html());
    }
    var endDate = newDateFromStr(endDateValue);
    if (selectedDate.compare(endDate) == 1) {
        setNotification("Veuilez entrer une date de fin posterieure ou identique à celle de début");
        ok = false;
    }
    if (beginTimeValue == "") {
        beginTimeValue = $("#panelBeginTime").html();
    }
    if (endTimeValue == "") {
        endTimeValue = $("#panelEndTime").html();
    }
    var endTimeDate = newDateTimeFromStr(endTimeValue + ":00");
    var beginTimeDate = newDateTimeFromStr(beginTimeValue + ":00");
    if ((selectedDate.compare(endDate) == 0)
            && (beginTimeDate.compareHoursAndMinutes(endTimeDate) != -1)) {
        //Si la date de début et de fin et la même, l'heure de fin doit être
        //strictement supérieure à celle de début
        setNotification("Veuillez entrer une horaire de fin posterieure à celle de début");
        ok = false;
    }

    if (ok) {
        var newName = $("#panelEventName").val();
        var originalName = $("#panelOriginalName").val();
        $.ajax({
            url: 'eventManager.ajax.php',
            type: 'POST',
            data: {
                req: "modif",
                eventName: newName,
                oriEventName: originalName,
                oriBeginDate: selectedDate.YMDFormat(),
                beginTime: $("#panelBeginTime").val(),
                endTime: $("#panelEndTime").val(),
                endDate: endDateValue,
                desc: $("#panelDesc").val()
            },
            error: function(j, textStatus, errorThrown) {
                setNotification("Erreur lors de la modification asynchrone d'un événement");
            },
            success: function(data) {
                var result = JSON.parse(data);
                if (result.status == "inputsError") {
                    setNotification("Erreur dans les données envoyées au serveur");
                } else if (result.status == "unidentified") {
                    setNotification("Veuillez vous identifier afin de pouvoir utiliser Duckalendar");
                } else if (result.status == "bddConnectionFailed") {
                    setNotification("Erreur de connexion à la base de données");
                } else if (result.status == "modifFailed") {
                    setNotification("La modification de l'événement en base de données à échoué");
                } else if (result.status == "noSuchEvent") {
                    setNotification("Evénement introuvable en base de données");
                } else if (result.status == "ok") {
                    //OK
                    if (newName == "") {
                        newName = originalName;
                    }
                    displayOneEvent($("#innerRightPanel"), newName, selectedDate);
                } else {
                    setNotification("Réponse du serveur inconnue");
                }
            }
        });
    }
}

/**
 * Affiche dans le container tout un événement, permettant de le modifier
 * et de le supprimer.
 */
function displayOneEvent(container, eventName, date) {
    $("#newEventSubmit").css({display: 'none'});
    //Requête ajax afin de récupérer les informations 
    //de l'événement
    $.ajax({
        url: 'eventManager.ajax.php',
        type: 'POST',
        data: {
            req: 'infos',
            eventName: eventName,
            beginDate: selectedDate.YMDFormat()
        },
        error: function(j, textStatus, errorThrown) {
            setNotification("Erreur lors de la requête asynchrone");
        },
        success: function(data) {
            var result = JSON.parse(data);
            if (result.status == "inputsError") {
                setNotification("Erreur de données envoyées au serveur");
            } else if (result.status == "unknownReq") {
                setNotification("Type de requête inconnu par le serveur");
            } else if (result.status == "unidentified") {
                setNotification("Vous devez être identifier afin de pouvoir utiliser Duckalendar");
            } else if (result.status == "bddConnectionFailed") {
                setNotification("Erreur de connexion à la base de données");
            } else if (result.status == "NoSuchEvent") {
                setNotification("Evénement introuvable en base de données");
            } else if (result.status == "ok") {
                //OK
                $("#undo").css({display: "block"});
                //Action de l'annulation
                $("#undo").click(function() {
                    displayListEvents(container, date);
                });

                //Le panel
                var panel = $("#innerRightPanel");
                panel.html("");
                panel.append('<form action="" method="post">')
                panel.append('<h3 id="panelEventName" class="modifText">' + eventName + '</h3>');
                panel.append('<input type="hidden" id="panelOriginalName" value="' + eventName + '" />');
                panel.append('<h4>Début : </h4>');
                panel.append('<p id="panelBeginTime" class="modifTime">' + result.beginTime + '</p>');
                panel.append('<h4>Fin : </h4>');
                panel.append('<p><span id="panelEndTime" class="modifTime">' + result.endTime + '</span> le <span id="panelEndDate" class="modifDate">' + result.endDateDMY + '</span></p>');
                panel.append('<h4>Description :</h4>');
                if (result.desc == "") {
                    panel.append('<div id="panelDesc" class="modifArea"><p>Aucune description.</p></div>');
                } else {
                    panel.append('<div id="panelDesc" class="modifArea"><p>' + result.desc + '</p></div>');
                }
                panel.append('</form>');

                //formulaires de modification seulement si l'évènement
                //n'est pas passé
                var endTimeDate = newDateTimeFromStr(result.endTime);
                var nowDate = new Date();
                var endDate = newDateFromStr(result.endDateYMD);
                if ((selectedDate.compare(nowDate) != -1) || ((endDate.compare(nowDate) == 0) && (endTimeDate.compareHoursAndMinutes(nowDate) == 1)) || (endDate.compare(nowDate) == 1)) {
                    $(".modifText").click(function() {
                        var value = $(this).html();
                        var id = $(this).attr("id");
                        //On enlève le handler de clic
                        $("#" + id).unbind('click');
                        $(this).removeAttr("id");
                        $(this).html('<input type="text" id="' + id + '" value="' + value + '" maxlength="' + eventNameInputLength + '" size="' + (eventNameInputLength + 1) + '" />');
                        $("#newEventSubmit").attr("value", "Modifier");
                        $("#newEventSubmit").css({display: "block"});
                        $("#addEventForm").unbind('submit');
                        $("#addEventForm").submit(function() {
                            modifEvent();
                            return false;
                        });
                    });

                    $(".modifTime").click(function() {
                        var value = $(this).html();
                        var id = $(this).attr("id");
                        //On enlève le handler de clic
                        $("#" + id).unbind('click');
                        $(this).removeAttr("id");
                        $(this).html('<input type="time" id="' + id + '" value="' + value + '" />');
                        $("#newEventSubmit").attr("value", "Modifier");
                        $("#newEventSubmit").css({display: "block"});
                        $("#addEventForm").unbind('submit');
                        $("#addEventForm").submit(function() {
                            modifEvent();
                            return false;
                        });
                    });

                    $(".modifDate").click(function() {
                        var value = $(this).html();
                        var id = $(this).attr("id");
                        //On enlève le handler de clic
                        $("#" + id).unbind('click');
                        $(this).removeAttr("id");
                        $(this).html('<input type="date" id="' + id + '" value="' + DMY_to_YMD(value) + '" />');
                        $("#newEventSubmit").attr("value", "Modifier");
                        $("#newEventSubmit").css({display: "block"});
                        $("#addEventForm").unbind('submit');
                        $("#addEventForm").submit(function() {
                            modifEvent();
                            return false;
                        });
                    });

                    $(".modifArea").click(function() {
                        var value = $(this).children().html();
                        if (value == "Aucune description.") {
                            value = "";
                        }
                        var id = $(this).attr("id");
                        //On enlève le handler de clic
                        $("#" + id).unbind('click');
                        $(this).removeAttr("id");
                        $(this).html('<textarea rows="15" cols="30" id="' + id + '">' + value + '</textarea>');
                        $("#newEventSubmit").attr("value", "Modifier");
                        $("#newEventSubmit").css({display: "block"});
                        $("#addEventForm").unbind('submit');
                        $("#addEventForm").submit(function() {
                            modifEvent();
                            return false;
                        });
                    });
                }


            } else {
                setNotification("Réponse du serveur inconnue");
            }
        }
    });
}

function deleteEventIntoDatabase(eventName, date) {
    $.ajax({
        url: 'eventManager.ajax.php',
        type: 'POST',
        data: {
            req: 'del',
            eventName: eventName,
            beginDate: date.YMDFormat()
        },
        error: function(j, textStatus, errorThrown) {
            setNotification("Erreur lors de la requête asynchrone")
        },
        success: function(data) {
            var result = JSON.parse(data);
            if (result.status == "inputsError") {
                setNotification("Erreur de données entrées au serveur");
            } else if (result.status == "noSuchEvent") {
                setNotification("Evénement à supprimer introuvable en base de données");
            } else if (result.status == "delFailed") {
                setNotification("La suppression de l'évènement a rencontré un problème");
            } else if (result.status == "ok") {
//                setNotification("L'évènement à bien été supprimé");
            } else {
                setNotification("Etat du serveur inconnu");
            }
            displayListEvents($("#innerRightPanel"), date);
        }
    });
}

/**
 * Affiche dans le container le formulaire d'ajout.
 * @param {} container L'élément html dans lequel sera affiché le formulaire.
 */
function displayAddEventForm(container) {
    var dateStr = selectedDate.YMDFormat();
    container.html(
            '<form action="" method="post">'
            + '<p>Nom de l\'événement :</p>'
            + '<input type="text" id="eventNameInput" maxlength="' + eventNameInputLength + '" size="' + (eventNameInputLength + 1) + '" />'
            + '<p>Date de fin :</p>'
            + '<input type="date" min="' + dateStr + '" value="' + dateStr + '" id="endDateInput" />'
            + '<p>Heure de début :</p>'
            + '<input type="time" value="08:00" id="beginTimeInput" />'
            + '<p>Heure de fin :</p>'
            + '<input type="time" value="09:00" id="endTimeInput" />'
            + '<p>Description de l\'événement :</p>'
            + '<textarea id="descInput" rows="10" cols="30">Facultatif...</textarea>'
            + '</form>'
            );
    //Affichage du bouton d'enregistrement
    $("#newEventSubmit").css({display: "block"});
    $("#newEventSubmit").attr("value", "Enregistrer");
    //Affichage du bouton d'annulation
    $("#undo").css({display: "block"});
    //Action d'annulation
    $("#undo").click(function() {
        displayListEvents(container, selectedDate);
    });

    //Effacement du contenu de la textarea en cas de 1er clic
    $("#descInput").click(function() {
        if ($(this).val() == "Facultatif...") {
            $(this).val("");
        }
    });

    //Traitement asynchrone du formulaire
    $("#addEventForm").unbind('submit');
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
                        } else if (result.status == "unidentified") {
                            setNotification("Veuillez vous connecter afin d'utiliser Duckalendar");
                        } else if (result.status == "inputsError") {
                            setNotification("Erreur sur les formulaires");
                        } else if (result.status == "existsAlready") {
                            setNotification("L'événement \"" + eventNameValue + "\" existe déjà à cette date");
                        } else if (result.status == "insertionFailed") {
                            setNotification("L'insertion de l'événement en base de données à échoué");
                        } else if (result.status == "unknownReq") {
                            setNotification("Requête inconnue par le serveur");
                        } else if (result.status == "unexpectedEOF") {
                            setNotification("Fin inattendue du script serveur");
                        } else if (result.status == "ok") {
                            setNotification("Evènement ajouté avec succès");
                            displayListEvents($("#innerRightPanel"), selectedDate);
                            $("#newEventSubmit").css({display: "none"});
                        } else {
                            setNotification("Réponse du serveur inconnue");
                        }
                        //Faire un drawMonth fait planter le serveur
                        //(trop de requêtes AJAX)
                        //drawMonth(m, y);
                        //---------------
                        //Comment mettre la classe .hasEvent sans
                        //réécrire tout le calendrier ?
                    }
                });
            }
        }

        newEventStatus.css({display: "none"});
        return false;
    });
}

/**
 * Affiche dans le conteneur html la liste des événements du jour.
 * @param {} container Le conteneur html
 * @param {Date} selectedDate La date dont les événements sont à afficher
 */
function displayListEvents(container, selectedDate) {
    $("#undo").css({display: "none"});
    $("#newEventSubmit").css({display: "none"});
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
                setNotification("Erreur de connexion à la base de données. Liste des événements du jour indisponible");
            } else if (result.status == "ok") {
                //Ok
                container.html("<dl>");
                displayedEventsNb = result.eventsNb;
                for (var i = 0; i < displayedEventsNb; i++) {
                    var event = result.eventsArray[i];
                    displayedEvents[i] = event.name;
                    container.append('<dt id="e' + i + '">' + '<img id="img' + i + '" alt="delete" src="/Duckalendar/images/moins.png" class="deleteEventImg" />' + '<span class="displayedEventTitle">[' + event.beginTime + "] " + event.name + '</span></dt>');
                    if (event.desc != "") {
                        container.append('<dd class="eventDesc">' + event.desc + '</dd>');
                    }
                    container.append('<hr />');

                    //handler du clic sur chaque événement
                    $("#e" + i).click(function() {
                        if (imgClicked) {
                            deleteEventIntoDatabase(displayedEventNameFromId($(this).attr("id")), selectedDate);
                        } else {
                            displayOneEvent(container, displayedEventNameFromId($(this).attr("id")), selectedDate);
                        }
                        imgClicked = false;
                    });
                    //handler du clic sur chaque image de supression
                    $("#img" + i).click(function() {
                        imgClicked = true;
                    });
                }

                container.append("</dl>");
            } else {
                setNotification("Réponse serveur inconnue");
            }
        }
    });
}

function displayedEventNameFromId(id) {
    //l'id est de la forme "eN" où N est un nombre qui
    //correspond a l'index dans le tableau des noms d'event
    //On reconstruit N
    var off;
    if (id.indexOf("e") == 0) {
        //l'id commence par e
        off = 1;
    }
    /*if (id.indexOf("img") == 0) {
     //l'id commence par img
     off = 3;
     }*/
    var index = id.substr(off, id.length - off);
    return displayedEvents[index];
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

function loadEventDaysArray(m, y, callbackFunction) {
    $.ajax({
        url: 'daysHaveEventList.ajax.php',
        type: 'POST',
        data: {
            month: m,
            year: y
        },
        error: function(j, textStatus, errorThrown) {
            setNotification("Erreur du chargement asynchrone des jours ayant un événement");
        },
        success: function(data) {
            var result = JSON.parse(data);
            if (result.status == "userUnidentified") {
                setNotification("Vous avez été détecté comme étant déconnecté");
            } else if (result.status == "bddConnectionError") {
                setNotification("Erreur de connexion à la base de données");
            } else if (result.status == "inputError") {
                setNotification("Erreur des données envoyées aux serveurs");
            } else if (result.status == "ok") {
                dayHasEvent = result.array;
            } else {
                setNotification("Réponse du serveur inconnue");
            }
            callbackFunction(m, y);
        }
    });
}

/**
 * Renvoie true si cette date possède un ou plusieurs événement(s) pour
 * l'utilisateur connecté, false sinon.
 * @param {int} day
 * @param {int} month
 * @param {int} year
 * @returns {boolean}
 */
function doesThisDateHaveAnEvent(day) {
    if (dayHasEvent.indexOf(day + "") == -1) {
        return false;
    }
    return true;
}

function drawMonth(m, y) {
    if (getCookie("connection") != null) {
        loadEventDaysArray(m, y, _internDrawMonth);
    } else {
        _internDrawMonth(m, y);
    }
}

function _internDrawMonth(m, y) {
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
            if (doesThisDateHaveAnEvent(num, m, y)) {
                content += '<td class="noWork noMonth monthDown hasEvent>' + num + '</td>';
            } else {
                content += '<td class="noWork noMonth monthDown">' + num + '</td>';
            }
        } else {
            var num = lstMonthDMax - fstJ + j + 2;
            if (doesThisDateHaveAnEvent(num, m, y)) {
                content += '<td class = "noMonth monthDown hasEvent">' + num + '</td>';
            } else {
                content += '<td class = "noMonth monthDown">' + num + '</td>';
            }
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
                if (doesThisDateHaveAnEvent(i, m, y)) {
                    bal += ' hasEvent';
                }
                if (j >= 5) {
                    bal += ' noWork"';
                } else {
                    bal += '"';
                }
                if (getCookie("connection") != null) { //Un utilisateur non connecté n'est pas concerné
                    if ((i == selectedDate.getDate())
                            && (m == selectedDate.getMonth() + 1)
                            && (y == selectedDate.getFullYear())) {
                        bal += ' id="selectedDay"';
                    }
                }
                bal += ">";
                content += bal + i + "</td>";
                j++;
            }
            var k = 0;
            while (j < 7) {
                k++;
                var bal;
                if (j > 4) {
                    if (doesThisDateHaveAnEvent(k, m, y)) {
                        bal = '<td class = "noWork noMonth monthUp hasEvent">';
                    } else {
                        bal = '<td class = "noWork noMonth monthUp">';
                    }
                } else {
                    if (doesThisDateHaveAnEvent(k, m, y)) {
                        bal = '<td class = "noMonth monthUp hasEvent">';
                    } else {
                        bal = '<td class = "noMonth monthUp">';
                    }
                }
                content += bal + k + "</td>";
                j++;
            }
            j = 0;
            content += "</tr>";
        }
    }
    calBody.append(content);

    $(".month").click(
            function() {
                $(".month").removeAttr("id");
                $(this).attr("id", "selectedDay");
                var login = getCookie("connection");
                if (login == null) {
                    setNotification("Veuillez vous connecter afin de pouvoir utiliser Duckalendar");
                } else {
                    $("#newEventSubmit").css({display: "none"});
                    var panel = $("#rightPanel");
                    panel.css({display: "block"});
                    panel.animate({right: '0%'});
                    stretched = true;
                    var selectedDay = $(this).text();

                    $("#topRightPanel").html(
                            '<h3>'
                            + selectedDay
                            + " "
                            + numericToStrMonth(m - 1)
                            + " "
                            + y
                            + '</h3>'
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

    //Récupération des couleurs de l'utilisateur
    $.ajax({
        url: 'userSettings.ajax.php',
        type: 'POST',
        data: {
            //Rien
        },
        error: function(j, textStatus, errorThrown) {
            setNotification("Erreur lors de la récupération de vos paramètres personnels. Utilisation des paramètres par défaut")
        },
        success: function(data) {
            var result = JSON.parse(data);
            if (result.status == "unidentified") {
                //On laisse les paramètres par défaut.
            } else if (result.status == "bddError") {
                setNotification("Erreur lors de la connexion à la base de données");
            } else if (result.status == "queryError") {
                setNotification("Erreur lors d'une requête SQL");
            } else if (result.status == "ok") {
                $(".noWork").css({'background-color': result.settings.noWorkColor});
                $(".hasEvent").css({'background-color': result.settings.hasEventColor});
            } else {
                setNotification("Réponse du serveur inconnue");
            }
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

function animateLeftPanelRight() {
    $("#leftPanel").animate({left: '0%'}, 400, "swing",
            function() {
                $("#leftPanelToggle").attr("src", "/Duckalendar/images/fleche gauche.png");
                $("#leftPanelToggle").unbind('click');
                $("#leftPanelToggle").click(animateLeftPanelLeft);
            }
    );
}

function animateLeftPanelLeft() {
    $("#leftPanel").animate({left: '-18.5%'}, 400, "swing",
            function() {
                $("#leftPanelToggle").attr("src", "/Duckalendar/images/fleche droite.png");
                $("#leftPanelToggle").unbind('click');
                $("#leftPanelToggle").click(animateLeftPanelRight);
            }
    );
}

$(document).ready(function() {
    drawMonth(m, y);

    $("#left").click(function() {
        monthDown();
    });

    $("#right").click(function() {
        monthUp();
    });

    $("#leftPanelToggle").click(function() {
        animateLeftPanelLeft();
    });

    printHour();
});

//window.onload = printHour();