$(function(){

    $('.calendar').clndr({
        template: $('.calendar-template').html(),
        weekOffset: 1,
        events: reservations,
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
            "applyLabel": "Auswählen",
            "cancelLabel": "Abbrechen",
            "fromLabel": "Von",
            "toLabel": "Bis",
            "customRangeLabel": "Custom",
            "weekLabel": "W",
            "daysOfWeek": [
                "So",
                "Mo",
                "Di",
                "Mi",
                "Do",
                "Fr",
                "Sa"
            ],
            "monthNames": [
                "Januar", "Februar", "März", "April",
                "Mai", "Juni", "Juli", "August",
                "September", "Oktober", "November", "Dezember"
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
    }

    editor.on('text-change', function(delta, oldDelta, source) {
        $('input[name="notes"]').val(encodeURI(JSON.stringify(editor.getContents())))
    });

})