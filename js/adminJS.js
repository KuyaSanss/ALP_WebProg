$(document).ready(function () {
  $("#adminNavBarPosition").load("../php/adminNavBar.php", function () {
    var currentPage = window.location.pathname.split("/").pop();

    $(".navLink").each(function () {
      var linkPage = $(this).attr("href");
      if (linkPage === currentPage) {
        $(this).addClass("bg-[#355e3b] text-white hover:bg-[#355e3b]");
      }
    });
  });

  $(document).on("click", "#profileButton", function(e){
    e.stopPropagation();
    $("#profileMenu").toggleClass("hidden");
  });

  $(document).on("click", function(){
    $("#profileMenu").addClass("hidden");
  });

  $(document).on("click", "#hamburgerButton", function(){
    $("#mobileMenu").toggleClass("hidden");
  });
});
