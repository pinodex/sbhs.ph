$(document).foundation({
    equalizer: {
        equalize_on_stack: true
    }
});

WebFont.load({
    google: {
        families: ['Oswald', 'Open+Sans:400,300:latin']
    }
});

$.fn.newsTicker = function(interval) {
    var _this = this;
    
    _this.find('li:first-child').fadeIn();

    if(_this.find('li').length == 1) {
        return this;
    }

    setInterval(function() {
        _this.find('li:visible').fadeOut(function() {
            if ($(this).next().length) {
                $(this).next().fadeIn();
                return;
            }

            _this.find('li:first-child').fadeIn();
        });
    }, interval);

    return this;
}

if ($('.post-cards.authors').length) {
    $('.post-cards.authors .image').each(function() {
        $(this).height($(this).width());
    });
}

$('.gallery-photos li a').click(function(event) {
    event.preventDefault();
    
    var lightbox = $('.photo-lightbox');

    lightbox.find('.photo img').attr('src', $(this).data('image'));
    lightbox.show();
});

$('.photo-lightbox').click(function() {
    if (!$(event.target).parents('.photo-lightbox .photo').length) {
        $('.photo-lightbox').fadeOut(100);
    }
});

$(document).ready(function() {
    if ($('.event-calendar').length) {
        $('.event-calendar').clndr({
            template: $('#event-calendar-template').html(),
            events: eventsList
        });
    }

    $('.announcements ul').newsTicker(5000);
});