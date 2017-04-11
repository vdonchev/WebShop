$(function () {
    $(".delete-item").click(function (e) {
        e.preventDefault();
        var form = $(this).parent("form");
        bootbox.confirm("Are you sure that you want to delete this?", function (conf) {
            if (conf) {
                form.submit();
            }
        });
    });

    // Auto fix thumbnail heights
    // $(".thumbnail").height(Math.max.apply(null, $(".thumbnail").map(function () {
    //     return $(this).height();
    // })));
});


