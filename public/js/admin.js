$(document).ready(function() {
    $("#new-article-submit").submit(function() {
        $("#hidden-text").val($(".ql-editor").text());
    });
});