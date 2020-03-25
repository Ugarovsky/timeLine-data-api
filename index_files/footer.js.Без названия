// user state update listener
we.getHelper('event').listen('requestUpdateUserStatus', function () {
    var userUpdateStateRoute = 'index.php?option=com_ajax&plugin=Localstory&format=json',
        userStateUpdateCallback =function (response) {
            var $targets = jQuery('[data-role="hp-user-display"]');
            jQuery(response.data[0]).insertAfter($targets);
            $targets.remove();
        };

    we.getObject('ajax')
        .massSet({'url' : userUpdateStateRoute, 'expectWeResponse' : false, 'success' :userStateUpdateCallback})
        .execute();

});

// contact - order is important, because we need to block the modal iframe on it
jQuery(function () {
    var $contactForm    = jQuery('[data-role="contact-form"]'),
        contactForm     = we.getObject('form').initByDomElement($contactForm),
        $contactModal   =  jQuery('[data-name="main-contact"][data-is-modal]');

        contactForm.ajaxifySmart(function (response) {
        // show message
        we.getHelper('dom').messageUser(response.data.message);

        // close form popup
        $contactModal.removeClass('active');

        // clear form
        contactForm.reset();
    });


    jQuery('.nav .contact[data-behaviour="modal-iframe-trigger"]').on('click', function (e){
        e.preventDefault();
        e.stopImmediatePropagation();
        e.stopPropagation();
        $contactModal.addClass('active');
    });
});


