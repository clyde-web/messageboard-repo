$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $(".btn-scroll").fadeIn();
        } else {
            $(".btn-scroll").fadeOut();
        }
    })

    $(document).on("click", ".btn-scroll", function () {
        $("html, body").animate({ scrollTop: 0 }, "smooth");
    })
})	