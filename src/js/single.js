jQuery(document).ready(function($) {
    $('.wp-post-image').addClass('sticky top').css('max-width', $('.wp-post-image').width());

    $(document).on('scroll', function() {
        var el = $('.single-full'),
            postImage = el.find('.wp-post-image'),
            postContainer = el.find('.index-single-post');

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
    });

    resizeImages();

    $(window).smartresize(function() {
        resizeImages();
        $('.wp-post-image').css('max-width', $('.index-single-post').width() / 2);
    });

    function resizeImages() {
        $('.lazy').each(function() {
            var imgWidth = $(this).attr('width') || $(this).width(),
                parent = $(this).parents('.index-single-content-container');

            if (!$(this).hasClass('wp-post-image')) {
                if (imgWidth > parent.width() || imgWidth == 0) {
                    imgWidth = parent.width();
                }
            }

            $(this).attr('src', $(this).data('src') + '?width=' + (imgWidth * window.devicePixelRatio));
        });
    }
});
