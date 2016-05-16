//custom stuff goes here

jQuery(document).ready(function($) {
    $('html').removeClass('no-js').addClass('js');

    // header image
    var logoSrc = $('#header-logo').data('src') + '?width=' + $('#header-logo').width();

    var image = new Image();
    image.src = logoSrc;

    image.onload = function() {
        $('#header-logo').attr('src', logoSrc);

        $('#nav').scrollspy({
            min: $('#nav').offset().top - 8,
            max: Number.MAX_SAFE_INTEGER,
            onEnter: function(element, position) {
                $("#nav").addClass('fixed');
                $('header').addClass('nav-fixed');
            },
            onLeave: function(element, position) {
                $("#nav").removeClass('fixed full');
                $("#filter").removeClass('showing');
                $('header').removeClass('nav-fixed');
            }
        });
    };

    $('#menu-toggle').on('click', function(e) {
        e.preventDefault();
        $('#mobile-menu').toggleClass('showing');
    });

    $('#search-toggle').on('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('showing');
        $('#filter').toggleClass('showing').css('height', $(window).outerHeight(true) - $('#filter').offset().top);
        $('body').toggleClass('disable-scrolling');
    });

    $('.filter-decade').on('click', function() {
        $(this)
            .addClass('expanded')
            .siblings('.filter-decade').removeClass('expanded');
    });

    $(window).smartresize(function() {
        $('#header-logo').attr('src', $('#header-logo').data('src') + '?width=' + $('#header-logo').width());
        if ($('#filter').hasClass('showing')) {
            $('#filter').css('height', $(window).outerHeight(true) - $('#filter').offset().top);
        }
    });
});

function isElementInViewport (el) {

    //special bonus for those using jQuery
    if (typeof jQuery === "function" && el instanceof jQuery) {
        el = el[0];
    }

    var rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
}

(function($,sr){

  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;

      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null;
          };

          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);

          timeout = setTimeout(delayed, threshold || 100);
      };
  }
  // smartresize
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');
