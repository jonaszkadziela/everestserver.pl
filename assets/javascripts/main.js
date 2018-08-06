/* --------------------------------
Global Variables
-------------------------------- */

var NAVBAR_HEIGHT = 80;

/* --------------------------------
Main Function
-------------------------------- */

$(document).ready(function()
{
  setupNiceScroll();

  $("a.js-scroll-trigger[href*='#']:not([href='#'])").on("click", scrollTrigger);

  new ResizeSensor($("html"), function()
  {
    $("html").getNiceScroll().resize();
  });
});

/* --------------------------------
Function Scroll Trigger
-------------------------------- */

function scrollTrigger()
{
  if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname)
  {
    var target = $(this.hash);
    target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
    if (target.length)
    {
      $("html").animate(
      {
        scrollTop: (target.offset().top - (NAVBAR_HEIGHT - 1))
      }, "slow");
      return false;
    }
  }
}

/* --------------------------------
Nicescroll.js Setup
-------------------------------- */

function setupNiceScroll()
{
  var mobileBrowsers = new RegExp("Android|webOS|iPhone|iPad|BlackBerry|Windows Phone|Opera Mini|IEMobile|Mobile", "i");
  if (!mobileBrowsers.test(navigator.userAgent))
  {
    $("html").niceScroll
    ({
      railpadding: { top: 0, right: 1, left: 10, bottom: 0 },
      background: "rgba(0, 0, 0, 0.3)",
      cursordragspeed: 0.4,
      cursorwidth: 8,
      cursoropacitymax: 0.7,
      cursorborder: "none",
      cursorborderradius: "10px",
      cursorcolor: "#111"
    });
  }
}
