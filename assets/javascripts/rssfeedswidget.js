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
        $('table#myfeeds tbody').append(newline);
        $('#add-feed').remove();
        return false;
    },

    askDelete: function(id) {
        if (confirm($('#delete-'+id).data('confirm'))) {
            return true;
        }
        return false;
    }

}
