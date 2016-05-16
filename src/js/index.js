jQuery(document).ready(function($) {
    var container = $('#index-posts'),
        postOffset = 2,
        ajaxPending = false,
        twitterUrl = $('#twitter-share').attr('href'),
        facebookUrl = $('#facebook-share').attr('href'),
        permalinkUrl = $('#link-share').attr('href'),
        slowConnection = false,
        grid = container.isotope({
            itemSelector : '.index-single-post-container',
            columnWidth: $('.index-single-post-link').width(),
            resize: false,
            isResizeBound: false
        });

    $('#speedTest').on('load', function() {
        slowConnection = ((new Date()).getTime() - speedTestStart < 2500) ? false : true;
    });

    $('#pagination').css('visibility', 'hidden');

    loadResizedImages('all');

    grid.imagesLoaded().progress(function() {
        resizeMason();
    });

    $(window).smartresize(function() {
        resizeMason();
        loadResizedImages('all', true);
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
                postImage = el.find('.wp-post-image'),
                postContainer = el.find('.index-single-post'),
                distance = $(window).scrollTop() + $(window).height() * 0.33,
                direction = (distance < el.offset().top - $(window).height() * 0.33 || $(window).scrollTop() === 0) ? 'up' : 'down';

            if (!container.data('scrolling')) {
                // keep the post image inside postContainer
                if (postImage[0].getBoundingClientRect().top < postContainer[0].getBoundingClientRect().top) {
                    postImage.addClass('top');
                }
                if (postImage[0].getBoundingClientRect().top < 115) {
                    postImage.removeClass('top');
                }

                if (postImage[0].getBoundingClientRect().bottom > postContainer[0].getBoundingClientRect().bottom) {
                    postImage.addClass('bottom');
                }
                if (postImage[0].getBoundingClientRect().bottom > (postImage.height() + 115)) {
                    postImage.removeClass('bottom');
                }

                if (
                    (distance >= el.offset().top + el.height()
                    || distance < el.offset().top - $(window).height() * 0.33
                    || $(window).scrollTop() === 0)
                    || $(window).scrollTop() + 20 >= $(document).height() - $(window).height()
                ) {
                    $.when(el.removeClass('single-full hover').delay(250)).done(function () {
                        container.isotope('layout');
                        $('#twitter-share').attr('href', twitterUrl);
                        $('#facebook-share').attr('href', facebookUrl);
                        $('#link-share').attr('href', permalinkUrl);
                        postImage.removeClass('sticky top bottom');
                        if (direction === 'down') {
                            scrollToOpen(el);
                        }
                    });
                }
            }
        }
        // load posts earlier if slow connection
        if($(window).scrollTop() > ($(document).height() - (slowConnection ? $(window).height() * 2 : $(window).height() * 1.5))) {
            getAjaxPosts();
        }
    });

    // open an article
    $(document).on('click', '.index-single-post-link', function(e) {
        e.preventDefault();
        var postContainer = $(this).parents('.index-single-post-container'),
            contentContainer = $(this).siblings('.index-single-content-container'),
            emptyContainer = $.trim(contentContainer.html()).length === 0 ? true : false,
            progress = .2;

        if (emptyContainer && !ajaxPending) {
            contentContainer.loadie(progress);
        }

        container.data('scrolling', true);

        postContainer
            .addClass('single-full')
            .siblings('.index-single-post-container').removeClass('single-full hover');

        container.isotope('layout');

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
                        // make sure the post container still has the class after isotope repainted
                        if (!postContainer.hasClass('single-full')) {
                            postContainer.addClass('single-full');
                        }

                        scrollToOpen(postContainer, function() {
                            var postImg = postContainer.find('.wp-post-image');
                            postImg
                                .addClass('sticky top')
                                .css('max-width', postContainer.width() / 2);

                            loadResizedImages(postImg);
                        });

                        loadContentImages(postContainer.find('.index-single-content-container'));

                        ajaxPending = false;
                        container.removeData('scrolling');
                        window.clearInterval(intervalID);
                    });
            });
            $.getJSON(ajaxurl, {
                action: 'get_share_urls',
                post_id: $(this).parents('.index-single-post-container').data('id')
            }, function(response) {
                $('#twitter-share').attr('href', response.twitterUrl);
                $('#facebook-share').attr('href', response.facebookUrl);
                $('#link-share').attr('href', response.permalinkUrl);
            });
        } else {
            scrollToOpen(null, function() {
                $('.single-full .wp-post-image').addClass('sticky');
            });
        }
    });

    function getAjaxPosts() {
        if (!ajaxPending) {
            ajaxPending = true;
            var data = {
                action: 'get_index_posts',
                offset: postOffset
            };

            if (typeof query_request !== 'undefined') {
                data.tax = query_request.tax_query || '';
                data.post_type = query_request.post_type || '';
            }

            $.get(ajaxurl, data, function(response) {
                response = $(response);

                container.append(response);

                loadResizedImages('all');
                container.imagesLoaded(function() {
                    container.isotope('appended', response);
                    container.find('.isotope-hidden').removeClass('isotope-hidden');
                    postOffset++;
                    ajaxPending = false;
                });
            });
        }
    }

    function scrollToOpen(el, callback) {
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
            }, 200)
                // animation finished
                .promise().then(function() {
                    container.removeData('scrolling');
                    // if there is a callback, execute
                    if (typeof callback !== 'undefined') {
                        callback();
                    }
                });
        });

        container.imagesLoaded(function() {

            container.isotope('layout');
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

    function loadResizedImages(el, force) {
        if (el == 'all' || typeof el == 'undefined') {
            if (typeof force !== 'undefined' && force) {
                $('.lazy').each(function() {
                    $(this).attr('src', $(this).data('src') + '?width=' + $(this).width());
                });
                return;
            }
            $('.lazy[src=""]').each(function() {
                $(this).attr('src', $(this).data('src') + '?width=' + $(this).width());
            });
        } else {
            el.attr('src', el.data('src') + '?width=' + el.width());
        }
    }

    function loadContentImages(content) {
        var images = content.find($('img'));

        images.each(function(i, img) {
            var imgWidth = $(img).attr('width') || $(img).width();
            if (imgWidth > content.width() || imgWidth == 0) {
                imgWidth = content.width();
            }
            $(img).attr('src', $(img).data('src') + '?width=' + imgWidth);
        });
    }
});
