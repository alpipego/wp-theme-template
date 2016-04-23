//custom stuff goes here

jQuery(document).ready(function($) {
    $('html').removeClass('no-js').addClass('js');

    $('#nav').scrollspy({
        min: $('#nav').offset().top,
        max: Number.MAX_SAFE_INTEGER,
        onEnter: function(element, position) {
            $("#nav").addClass('fixed');
        },
        onLeave: function(element, position) {
            $("#nav").removeClass('fixed full');
            $("#filter").removeClass('showing');
        }
    });

    $('#search-toggle').on('click', function(e) {
        e.preventDefault();
        var calcHeight = $(window).outerHeight() - $('#nav').outerHeight();
        $(this).toggleClass('showing');
        $('#filter').toggleClass('showing').css('height', calcHeight);
        $('body').toggleClass('disable-scrolling');
    });

    $('.filter-decade').on('click', function() {
        $(this)
            .addClass('expanded')
            .siblings('.filter-decade').removeClass('expanded');
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
