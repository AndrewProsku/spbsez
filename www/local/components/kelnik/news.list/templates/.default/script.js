$(document).ready(function(){
    "use strict";
    $(document).on('click', '.news-btn', function(e) {
        e.preventDefault();
        var $block  = $('#block_' + $(this).attr('data-id'));
        var $btn    = $(this);
        var pageNum = parseInt($btn.attr('data-page'));

        if (!$block.length) {
            $btn.hide();
            return false;
        }

        $.ajax({
            data: $btn.attr('data-ajax-param') +
                    '&page=' + pageNum,
            dataType: 'html',
            success: function (response) {
                $block.append(response);
                $btn.attr('data-page', ++pageNum);
            },
            error: function () {
                $btn.hide();
            }
        });
    });
});