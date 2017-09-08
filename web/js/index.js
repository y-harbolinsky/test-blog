function addComment(url, id) {
    var author = $('#author').val().trim();
    var comment = $('#comment').val().trim();

    if (validate(author, comment)) {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {
                "id": id,
                "author": author,
                "comment": comment
            },
            async: true,
            success: function (data) {
                if (data.author && data.content) {
                    $('div#comments').append('<div class="col-6 col-lg-4">' +
                        '<h3>' + data.author + '</h3><p>' + data.content + '</p></div>');
                    $('#author').val('');
                    $('#comment').val('');
                }

                $('#error').removeClass('alert alert-danger').text('');
            }
        });
    } else {
        $('#error').addClass('alert alert-danger').text('Please specify your full name e.g. "Jon Smith" and comment');
    }

}

function validate(author, comment) {
    if ((author === '') || (comment === '') || (author.indexOf(' ') === -1)) return false;

    author = author.split(" ");
    if (author.length !== 2) return false;

    for (var i = 0; i < author.length; i++) {
        var upperWord = author[i].charAt(0).toUpperCase() + author[i].slice(1);
        if (author[i] !== upperWord) {
            return false;
        }
    }

    return true;
}
