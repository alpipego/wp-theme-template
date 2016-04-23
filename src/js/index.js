jQuery(document).ready(function($) {
    var container = $('#index-posts'),
        postOffset = 2,
        ajaxPending = false,
        twitterUrl = $('#twitter-share').attr('href'),
        facebookUrl = $('#facebook-share').attr('href'),
        grid = container.isotope({
            itemSelector : '.index-single-post-container',
            columnWidth: $('.index-single-post-link').width(),
            resize: false,
            isResizeBound: false
        });

    $('#pagination').css('visibility', 'hidden');

    grid.imagesLoaded().progress(function() {
        resizeMason();
    });

    $(window).smartresize(function() {
        resizeMason();
    });

    if (isElementInViewport($('#pagination'))) {
        getAjaxPosts();
    }

    $(document)
        .on('mouseenter', '.index-single-post-container:not(.single-full)', function() {
            $(this).addClass('hover');
        })
        .on('mouseleave', '.index-single-post-container.hover', function() {
            $(this).removeClass('hover');
        });

    $(document).on('scroll', function() {
        if ($('.index-single-post-container').hasClass('single-full')) {
            var el = $('.single-full'),
                distance = $(window).scrollTop() + $(window).height() * .33,
                direction = (distance < el.offset().top - $(window).height() * .33 || $(window).scrollTop() === 0) ? 'up' : 'down';

            if (
                !container.data('scrolling') && (distance >= el.offset().top + el.height()
                || distance < el.offset().top - $(window).height() * .33
                || $(window).scrollTop() === 0)
                || $(window).scrollTop() == $(document).height() - $(window).height()
            ) {
                $.when(el.removeClass('single-full').delay(250)).done(function() {
                    container.isotope('layout');
                    $('#twitter-share').attr('href', twitterUrl);
                    $('#facebook-share').attr('href', facebookUrl);
                    if (direction === 'down') {
                        scrollToOpen(el);
                    }
                });
            }
        }
        if(!($(window).scrollTop() < $(document).height() - $(window).height() - 200)) {
            getAjaxPosts();
        }
    });

    $(document).on('click', '.index-single-post-link', function(e) {
        e.preventDefault();
        var postContainer = $(this).parents('.index-single-post-container'),
            contentContainer = $(this).siblings('.index-single-content-container'),
            emptyContainer = $.trim(contentContainer.html()).length === 0 ? true : false,
            progress = .2;

        if (emptyContainer && !ajaxPending) {
            contentContainer.loadie(progress);
        }

        postContainer
            .addClass('single-full')
            .siblings('.index-single-post-container').removeClass('single-full')

        container.isotope('layout');
        // scrollToOpen();

        // several times the '.single-full' class got removed
        if (!postContainer.hasClass('single-full')) {
            postContainer.addClass('single-full');
        }

        if (emptyContainer && !ajaxPending) {
            ajaxPending = true;
            var intervalID = window.setInterval(function() {
                if (progress < 0.6) {
                    progress += 0.1;
                } else {
                    progress += 0.05;
                }

                if (progress >= 1) {
                    window.clearInterval(intervalID);
                    progress = .99;
                }

                contentContainer.loadie(progress);
            }, 400)
            $.get(ajaxurl, {
                action: 'get_single_post',
                post_id: $(this).parents('.index-single-post-container').data('id')
            }, function(response) {
                contentContainer
                    .loadie(1)
                    .html(response).imagesLoaded(function() {
                        container.isotope('layout');
                        scrollToOpen();
                        ajaxPending = false;
                        window.clearInterval(intervalID);
                    });
            });
            $.getJSON(ajaxurl, {
                action: 'get_share_urls',
                post_id: $(this).parents('.index-single-post-container').data('id')
            }, function(response) {
                $('#twitter-share').attr('href', response.twitterUrl);
                $('#facebook-share').attr('href', response.facebookUrl);
            });
        } else {
            scrollToOpen();
        }
    });

    function getAjaxPosts() {
        if (!ajaxPending) {
            ajaxPending = true;
            var data = {
                action: 'get_index_posts',
                offset: postOffset
            }
            if (typeof query_request !== 'undefined' && query_request !== '') {
                data.tax = query_request.tax_query;
            }
            $.get(ajaxurl, data, function(response) {
                var response = $(response);
                container.append(response).imagesLoaded(function() {
                    container.isotope('appended', response);
                    postOffset++;
                    ajaxPending = false;
                });
            });
        }
    }

    function scrollToOpen(el) {
        el = el || $('.single-full');
        grid.isotope('once', 'layoutComplete', function() {
            container.data('scrolling', true);
            var navOffset;
            if ($(window).outerWidth() > 700) {
                navOffset = $('#nav').outerHeight() + ($('#nav').css('position') == 'static' ? 120 : 0);
            } else {
                navOffset = 0;
            }

            $('html, body').animate({
                scrollTop: el.offset().top - navOffset
            }, 200, function() {
                container.removeData('scrolling');
            });
        });
    }

    function resizeMason() {
        var cols = 100/$('.index-single-post-container').first().clone().appendTo('body').wrap('<div id="widthCalc" style="display: none"></div>').width(),
            targetColWidth = $('.index-single-post-container').first().outerWidth();

        $('#widthCalc').remove();

        if (!(targetColWidth * cols < container.innerWidth())) {
            var diff = targetColWidth * cols - container.innerWidth(),
                colWidth = targetColWidth - Math.ceil(diff/cols);
        } else {
            colWidth = targetColWidth;
        }

        container.isotope({
            masonry: {
                columnWidth: colWidth
            }
        });
    }
});
