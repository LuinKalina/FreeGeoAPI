$(document).ready(function() {

    $('.example').not('#php-example').hide();

    $('body').on('click', '.snippet-btn', function() {

        var target = $(this).attr('data-target');

        $('.example').hide();

        $('#'+target).show();

    });

    hljs.initHighlightingOnLoad();

});