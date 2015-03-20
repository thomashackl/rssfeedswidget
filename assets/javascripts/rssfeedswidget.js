STUDIP.RSSFeedsWidget = {

    addFeed: function() {
        var newline = $('<tr>').
            append($('<td>').
                append($('<input>').
                    attr('type', 'text').
                    attr('size', '30').
                    attr('maxlength', '255').
                    attr('name', 'feeds[_new][name]'))).
            append($('<td>').
                append($('<input>').
                    attr('type', 'text').
                    attr('size', '50').
                    attr('maxlength', '1024').
                    attr('name', 'feeds[_new][url]'))).
            append($('<td>').
                append($('<input>').
                    attr('type', 'checkbox').
                    attr('checked', true).
                    attr('name', 'feeds[_new][visible]'))).
            append($('<td>'));
        $('table#myfeeds tr.feed').parent().append(newline);
        $('#add-feed').remove();
        return false;
    }

}

jQuery(function() {
    $('#add-feed').click(function() {
        return STUDIP.RSSFeedsWidget.addFeed();
    });
    $('.delete-feed').each(function (index) {
        $(this).click(function() {
            if (confirm($(this).data('confirm'))) {
                return true;
            }
            return false;
        });
    });
});