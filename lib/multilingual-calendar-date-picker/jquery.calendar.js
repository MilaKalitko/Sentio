/*
* jQuery-Calendar Plugin v1.1.1
*
* 2018 (c) Sebastian Knopf
* This software is licensed under the MIT license!
* View LICENSE.md for more information
*/
(function ($) {
    var currentYear, currentMonth, currentDay, currentCalendar; // Declarar globales aquí

    $.fn.calendar = function (opts) {
        var options = $.extend({
            color: '#308B22',
            emotionsData: {}, // Opción para los registros de Sentio
            months: [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ],
            days: [
                'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'
            ],
            onSelect: function (event) { }
        }, $.fn.calendar.defaults, opts);

        return this.each(function () {
            initCalendar($(this), options);
        });
    };

    function initCalendar(wrapper, options) {
        var color = options.color;

        wrapper.addClass('calendar').empty();

        var header = $('<header>').appendTo(wrapper);
        header.addClass('calendar-header');
        header.css({
            background: color,
            color: createContrast(color)
        });

        var buttonLeft = $('<span>').appendTo(header);
        buttonLeft.addClass('button').addClass('left');
        buttonLeft.html(' &lang; ');
        buttonLeft.bind('click', function () { currentCalendar = $(this).parents('.calendar'); selectMonth(false, options); });
        buttonLeft.bind('mouseover', function () { $(this).css('background', createAccent(color, -20)); });
        buttonLeft.bind('mouseout', function () { $(this).css('background', color); });

        var headerLabel = $('<span>').appendTo(header);
        headerLabel.addClass('header-label')
        headerLabel.html(' Month Year ');
        headerLabel.bind('click', function () {
            currentCalendar = $(this).parents('.calendar');
            selectMonth(null, options, new Date().getMonth(), new Date().getFullYear());

            currentDay = new Date().getDate();
            triggerSelectEvent(options.onSelect);
        });

        var buttonRight = $('<span>').appendTo(header);
        buttonRight.addClass('button').addClass('right');
        buttonRight.html(' &rang; ');
        buttonRight.bind('click', function () { currentCalendar = $(this).parents('.calendar'); selectMonth(true, options); });
        buttonRight.bind('mouseover', function () { $(this).css('background', createAccent(color, -20)); });
        buttonRight.bind('mouseout', function () { $(this).css('background', color); });

        var dayNames = $('<table>').appendTo(wrapper);
        dayNames.append('<thead><th>' + options.days.join('</th><th>') + '</th></thead>');
        dayNames.css({
            width: '100%'
        });

        var calendarFrame = $('<div>').appendTo(wrapper);
        calendarFrame.addClass('calendar-frame');

        headerLabel.click();
    }

    function selectMonth(next, options, month, year) {
        // Ocultar el popup al cambiar de mes
        $('#emotion-popup').hide();

        var tmp = currentCalendar.find('.header-label').text().trim().split(' '), tmpYear = parseInt(tmp[1], 10);

        if (month === 0) {
            currentMonth = month;
        } else {
            currentMonth = month || ((next) ? ((tmp[0] === options.months[options.months.length - 1]) ? 0 : options.months.indexOf(tmp[0]) + 1) : ((tmp[0] === options.months[0]) ? 11 : options.months.indexOf(tmp[0]) - 1));
        }

        currentYear = year || ((next && currentMonth === 0) ? tmpYear + 1 : (!next && currentMonth === 11) ? tmpYear - 1 : tmpYear);

        var calendar = createCalendar(currentMonth, currentYear, options), frame = calendar.frame();

        currentCalendar.find('.calendar-frame').empty().append(frame);
        currentCalendar.find('.header-label').text(calendar.label);

        frame.on('click', 'td', function () {
            var day = $(this).text();

            // Solo si es un día válido (no celda vacía)
            if (day !== '') {
                $('td').removeClass('selected');
                $(this).addClass('selected');

                currentDay = day;
                triggerSelectEvent(options.onSelect);

                // 🛑 Lógica para mostrar los detalles del registro 🛑
                var dayFormatted = day < 10 ? '0' + day : '' + day;
                var monthFormatted = (currentMonth + 1);
                monthFormatted = monthFormatted < 10 ? '0' + monthFormatted : '' + monthFormatted;

                var dateString = currentYear + '-' + monthFormatted + '-' + dayFormatted;
                var logData = options.emotionsData[dateString];

                if (logData) {
                    
                    var pos = $(this).offset();
                    var cellHeight = $(this).outerHeight();
                    var cellWidth = $(this).outerWidth();
                    var popup = $('#emotion-popup');
                    
                    // 1. Emoción y Color
                    $('#popup-emocion').text(logData.emocion);
                    // Aplicar el color
                    $('#popup-emocion').css('color', logData.color); 
                    
                    // 2. Nota
                    var notaTexto = logData.nota && logData.nota.trim() !== '' ? logData.nota : "Sin comentario.";
                    $('#popup-nota').text(notaTexto); 
                    
                    // 3. Controlar la visibilidad del separador y la nota (usando las clases de CSS)
                    if (notaTexto === "Sin comentario.") {
                        $('#emotion-popup').find('.popup-separador').hide();
                        $('#emotion-popup').find('.popup-linea-nota').hide();
                    } else {
                        $('#emotion-popup').find('.popup-separador').show();
                        $('#emotion-popup').find('.popup-linea-nota').show();
                    }

                    // Mostrar y posicionar el popup
                    popup.css({
                        top: pos.top + cellHeight + 5,
                        left: pos.left + (cellWidth / 2) - (popup.outerWidth() / 2)
                    }).show();
                } else {
                    $('#emotion-popup').hide();
                }
            }
        });
    }

    function createCalendar(month, year, options) {
        var currentDay = 1, daysLeft = true,
            startDay = new Date(year, month, currentDay).getDay() - 1,
            lastDays = [31, (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            calendar = [];
        if (startDay < 0) {
            startDay = 6;
        }

        var i = 0;
        while (daysLeft) {
            calendar[i] = [];

            for (var d = 0; d < 7; d++) {
                if (i == 0) {
                    if (d == startDay) {
                        calendar[i][d] = currentDay++;
                        startDay++;
                    } else if (startDay === -1) {
                        calendar[i][6] = currentDay++;
                        startDay++;
                    }
                } else if (currentDay <= lastDays[month]) {
                    calendar[i][d] = currentDay++;
                } else {
                    calendar[i][d] = '';
                    daysLeft = false;
                }

                if (currentDay > lastDays[month]) {
                    daysLeft = false;
                }
            }

            i++;
        }

        var frame = $('<table>').addClass('current');
        var frameBody = $('<tbody>').appendTo(frame);

        for (var j = 0; j < calendar.length; j++) {
            var frameRow = $('<tr>').appendTo(frameBody);

            $.each(calendar[j], function (index, item) {
                var frameItem = $('<td>').appendTo(frameRow);
                frameItem.text(item);
                
                if (item !== '' && options.emotionsData) {
                    var dayFormatted = item < 10 ? '0' + item : '' + item;
                    var monthFormatted = (month + 1);
                    monthFormatted = monthFormatted < 10 ? '0' + monthFormatted : '' + monthFormatted;

                    var dateString = year + '-' + monthFormatted + '-' + dayFormatted;

                    if (options.emotionsData[dateString]) {
                        var registro = options.emotionsData[dateString];
                        var styleAttribute = 'background-color: ' + registro.color + ' !important; ' + 
                                             'color: #2D2D2D !important; ' +
                                             'font-weight: bold;';
                                             
                        frameItem.attr('style', styleAttribute);
                        
                        // Añadir la hora al tooltip, ya que la quitamos del popup
                        var fullDateTime = new Date(registro.datetime);
                        var hora = fullDateTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                        frameItem.attr('title', 'Emoción: ' + registro.emocion + ' - Hora: ' + hora);
                    }
                }
            });
        }

        $('td:empty', frame).addClass('disabled');
        if (currentMonth === new Date().getMonth()) {
            $('td', frame).filter(function () { return $(this).text() === new Date().getDate().toString(); }).addClass('today');
        }

        return { frame: function () { return frame.clone() }, label: options.months[month] + ' ' + year };
    }

    function triggerSelectEvent(event) {
        var date = new Date(currentYear, currentMonth, currentDay);

        var label = [];
        label[0] = (date.getDate() < 10) ? '0' + date.getDate() : date.getDate();
        label[1] = ((date.getMonth() + 1) < 10) ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
        label[2] = (date.getFullYear());

        if (event != undefined) {
            event({ date: date, label: label.join('.') });
        }
    }

    function createContrast(color) {
        if (color.length < 5) {
            color += color.slice(1);
        }

        return (color.replace('#', '0x')) > (0xffffff) ? '#222' : '#fff';
    }

    function createAccent(color, percent) {
        var num = parseInt(color.slice(1), 16), amt = Math.round(2.55 * percent), R = (num >> 16) + amt, G = (num >> 8 & 0x00FF) + amt, B = (num & 0x0000FF) + amt;
        return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 + (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 + (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

}(jQuery));