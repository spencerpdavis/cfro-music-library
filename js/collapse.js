jQuery(document).ready(function(){

$(".side-collapsible > li > a").on("click", function(e) {

    //if submenu is hidden, does not have active class
    if(!$(this).hasClass("active")) {

        // hide any open menus and remove active classes
        $(".side-collapsible li ul").slideUp(350);
        $(".side-collapsible li a").removeClass("active");

        // open submenu and add the active class
        $(this).next("ul").slideDown(350);
        $(this).addClass("active");

    //if submenu is visible
    }else if($(this).hasClass("active")) {

        //hide submenu and remove active class
        $(this).removeClass("active");
        $(this).next("ul").slideUp(350);
    }
});

});
