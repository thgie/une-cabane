$(function(){

    moment.locale('fr', {
        months : 'janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre'.split('_'),
        monthsShort : 'janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.'.split('_'),
        monthsParseExact : true,
        weekdays : 'dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi'.split('_'),
        weekdaysShort : 'dim._lun._mar._mer._jeu._ven._sam.'.split('_'),
        weekdaysMin : 'Di_Lu_Ma_Me_Je_Ve_Sa'.split('_'),
        weekdaysParseExact : true,
        longDateFormat : {
            LT : 'HH:mm',
            LTS : 'HH:mm:ss',
            L : 'DD/MM/YYYY',
            LL : 'D MMMM YYYY',
            LLL : 'D MMMM YYYY HH:mm',
            LLLL : 'dddd D MMMM YYYY HH:mm'
        },
        calendar : {
            sameDay : '[Aujourd’hui à] LT',
            nextDay : '[Demain à] LT',
            nextWeek : 'dddd [à] LT',
            lastDay : '[Hier à] LT',
            lastWeek : 'dddd [dernier à] LT',
            sameElse : 'L'
        },
        relativeTime : {
            future : 'dans %s',
            past : 'il y a %s',
            s : 'quelques secondes',
            m : 'une minute',
            mm : '%d minutes',
            h : 'une heure',
            hh : '%d heures',
            d : 'un jour',
            dd : '%d jours',
            M : 'un mois',
            MM : '%d mois',
            y : 'un an',
            yy : '%d ans'
        },
        dayOfMonthOrdinalParse : /\d{1,2}(er|e)/,
        ordinal : function (number) {
            return number + (number === 1 ? 'er' : 'e');
        },
        meridiemParse : /PD|MD/,
        isPM : function (input) {
            return input.charAt(0) === 'M';
        },
        // In case the meridiem units are not separated around 12, then implement
        // this function (look at locale/id.js for an example).
        // meridiemHour : function (hour, meridiem) {
        //     return /* 0-23 hour, given meridiem token and hour 1-12 */ ;
        // },
        meridiem : function (hours, minutes, isLower) {
            return hours < 12 ? 'PD' : 'MD';
        },
        week : {
            dow : 1, // Monday is the first day of the week.
            doy : 4  // Used to determine first week of the year.
        }
    });

    $('.calendar').clndr({
        template: $('.calendar-template').html(),
        events: reservations,
        moment: moment,
        multiDayEvents: {
            singleDay: 'date',
            endDate: 'endDate',
            startDate: 'startDate'
        }
    });

    $('input[name="dates"]').daterangepicker({
        autoUpdateInput: false,
        "locale": {
            "format": "DD.MM.YYYY",
            "separator": " - ",
            "applyLabel": "Sélectionnez",
            "cancelLabel": "Avorter",
            "fromLabel": "De",
            "toLabel": "Jusqu'à",
            "customRangeLabel": "Custom",
            "weekLabel": "W",
            "daysOfWeek": [
                "Di",
                "Lu",
                "Ma",
                "Me",
                "Je",
                "Ve",
                "Sa"
            ],
            "monthNames": [
                "Janvier", "Février", "Mars", "Avril",
                "Mai", "Juin", "Juillet", "Août",
                "Septembre", "Octobre", "Novembre", "Décembre"
            ],
            "firstDay": 1
        }
    });

    $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
        $('input[name="from"]').val(picker.startDate.format('YYYY-MM-DD'));
        $('input[name="to"]').val(picker.endDate.format('YYYY-MM-DD'));
    });

    var editor = new Quill('.notes-editor', {
        placeholder: 'Notes / Notizen',
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['link', 'image'],
                [{ list: 'ordered' }, { list: 'bullet' }]
            ]
        }
    });

    if($('input[name="notes"]').val()){
        editor.setContents(JSON.parse(decodeURI($('input[name="notes"]').val())))
        if($('.notes-display').length){
            $('.notes-display').html(editor.root.innerHTML);
            $('.notes-editor, .ql-toolbar').remove();
        }
    }

    editor.on('text-change', function(delta, oldDelta, source) {
        $('input[name="notes"]').val(encodeURI(JSON.stringify(editor.getContents())))
    });

    $('.delete').on('click', function(evt){
        evt.preventDefault();
        if(confirm('Sicher?')){
            window.location = $(this).attr('href');
        }
    })

})