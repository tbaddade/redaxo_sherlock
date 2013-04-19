jQuery(function($){

    var $watson = $('#sherlock-watson');

    $(document).ready( function() {
        /*
        $('#sherlock-finder').macFinder({
            folder_image: '/files/addons/sherlock/triangle.png'
        });
        */
        var watson_id   = getUrlParameter('watson_id');
        var watson_text = getUrlParameter('watson_text');
        if (watson_id && watson_text) {
            $('#' + watson_id).val(watson_text).focus();
        }

        $('.sherlock-finder').click(function(){
           if ($(this).hasClass('sherlock-active')) {
               $('#sherlock-finder').slideUp();
               $(this).removeClass('sherlock-active');
           } else {
               $('#sherlock-finder').slideDown();
               $(this).addClass('sherlock-active');
           }
        });

        $('#sherlock-finder').columnview({
            tag: 'span'
        });
    });

    $(document).keydown(function(e) {
        if ((e.keyCode == 32 && e.ctrlKey)) {
            checkWatson();
        }
    });
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            hideWatson();
        }
    });

    function onAutocompleted(evt, datum) {
        console.log('autocompleted');
        console.log(evt);
        console.log(datum);
    }

    function checkWatson() {
        if ($watson.hasClass('sherlock-active')) {
            hideWatson($watson);
        } else {
            showWatson($watson);
        }
    }

    function showWatson() {

        $('.typeahead').typeahead({
            name: 'watson-result',
            remote: rex.backendUrl + '?watson=%QUERY',
            //prefetch: rex.backendUrl + '?page=sherlock&subpage=watson',
            limit: 10,
            /*
             template: [
             '{{template}}'
             ].join(''),
             */
            engine: Hogan
        }).on('typeahead:selected', function(evt, item) {
                if (item.url !== undefined) {
                    window.location.href = item.url;
                }
            });

        $watson.fadeIn('fast').addClass('sherlock-active');
        $watson.find('input').focus();
    }

    function hideWatson() {
        $watson.fadeOut('fast').removeClass('sherlock-active');
        $('.typeahead').typeahead('destroy');
    }

    function getUrlParameter(name) {
        return decodeURI(
            (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
        );
    }
});