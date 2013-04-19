jQuery(function($){

    $(document).ready( function() {

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

});