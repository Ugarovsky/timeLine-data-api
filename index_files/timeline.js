TimeLineController = function (inputConfiguration, extendable) {
    var product = we.getObject('controller', inputConfiguration),
        config, $elements, forms, callbacks, classes, state, searchResultTemplate;

    product.reactsTo = {
        requestShowWidget           : {},
        requestScroll               : {},
        requestSearchYear           : {},
        requestSearchCategory       : {},
        requestMainFacebookShare    : {},
        requestCloseSearch          : {}
     //   requestResetTopFrame        : {}
    };

    product.config = {
        eventRoute : 'index.php?option=com_localstory&view=event&tmpl=component&forceReadMore=1&id=',
        timeline: {
            thumbWidth: 100,
            animationSpeed : 0,
        },
        selectors : {
            test                        : '[data-role="test"]',
            timelineOuter               : '[data-role="timeline-outer"]',
            timelineInner               : '[data-role="timeline-inner"]',
            timelineViewPort            : '[data-role="timeline-inner"] .viewport',
            timelineYearContainer       : '[data-role="timeline-inner"] .overview',
            years                       : '[data-role="timeline-year"]',
            widgets                     : '[data-is-mobile-widget]',
            mobileControls              : '[data-role="mobile-controls"]',
            timelineFirstVisitTooltip   : '[data-role="timeline-first-tooltip"]',
            timelineScrollBar           : '[data-role="timeline-outer"] .scroll-bar.horizontal',
            timelineScrollThumb         : '[data-role="timeline-outer"] .scroll-bar.horizontal .thumb',
            searchResultWrapper             : '[data-role="search-results-wrapper"]',
            searchResultTitle               : '[data-role="search-results-title"]',
            searchResultContainer           : '[data-role="search-results-container"]',
            searchResultTemplateContainer   : '[data-role="search-result-template-container"]',
            searchResultItemWrapper         : '[data-role="search-result-item-wrapper"]',
            searchResultItemYear            : '[data-role="result-year"]',
            searchResultItemText            : '[data-role="result-text"]',
            searchNoResults                 : '[data-role="no-results"]',
            closeSearch                     : '[data-role="close-search"]',
            categoryItems                   : '[data-role="category-item"]',
            categoryItemText                : '[data-role="category-text"]',
            textSearchForm                  : '[data-role="text-search-form"]',
            eventLinkShort                  : '[data-role="event-link-short"]',
            eventLinkFull                   : '[data-role="event-link-full"]',
            mainFacebookShareLink           : '[data-role="main-facebook-share-link"]',
            yearEventList                   : '[data-role="year-event-list"]',
            lazyBackgroundImages            : '[data-lazy-background-image]',
            timelineScrollButtons           : '[data-event="requestScroll"]'
        },

        formConfiguration : {

        },
        routes : {
            //textSearch : 'index.php?option=com_localstory&task=events.textSearch&format=raw'
        },
        classes : {
            hidden : 'hidden-by-default',
            active : 'active',
            highlight : 'highlight'
        }
    };

    product.forms = {

    };

    product.state = {
        lastSearchCategoryId : -1
    };
    // shortcut
    config       = product.config;
    classes      = product.config.classes;
    $elements    = product.$elements;
    forms        = product.forms;
    state        = product.state;

    callbacks    = {
        textSearch : function (result) {
            product.showSearchResults(result.data);
        }
    };

    /**
     * Respond to request to share the main timeline page via facebook
     * @param eventData
     */
    product.requestMainFacebookShareHandler = function (eventData) {
        var height              = 545,
            width               = 433,
            leftPosition        = (window.screen.width  / 2) - ((width  / 2) + 10),
            topPosition         = (window.screen.height / 2) - ((height / 2) + 50),
            link                = $elements.mainFacebookShareLink.attr('href');

        window.open(link, "Window2", "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
    };

    /**
     * Highlight event links by text
     * @param text - if this string exists in element text, it will be highlighted
     */
    product.highlightEventLinksByText = function (text) {
        var $targets = $elements.eventLinkShort;

        $targets.each(function (index, el){
            var $el = jQuery(el);

            $el.removeClass(classes.highlight);

            if ($el.text().indexOf(text) !== -1) {
                $el.addClass(classes.highlight);
            }
        });
    };

    /**
     * Remove Text Highlight from event links
     */
    product.unHighlightEventLinks = function () {
        $elements.eventLinkShort.removeClass(classes.highlight);
    };

    /**
     * Handler for when search is closed
     */
    product.requestCloseSearchHandler = function () {
        product.unHighlightEventLinks();
    };

    /**
     * Highlight event links by event object list (must have eventId)
     * @param list
     */
    product.highlightEventLinksByEventList = function (list) {
        var $targets = $elements.eventLinkShort, arrEventIds = [];

        jQuery.each(list, function (index, eventObject) {
            arrEventIds.push(eventObject.eventId);
        });

        $targets.each(function (index, el){
            var $el     = jQuery(el),
                eventId = $el.attr('data-event-id');

            $el.removeClass(classes.highlight);

            if (arrEventIds.includes(eventId)) {
                $el.addClass(classes.highlight);
            }
        });
    };


    /**
     * method to show search results
     * @param data
     * @returns {{}}
     */
    product.showSearchResults = function (data) {
        /**
         * method to add a link to search results, based on an object
         * @param item
         */
        addResultLink = function (item) {
            var $el = jQuery(searchResultTemplate),
                link = we.config.general.baseUrl +  product.config.eventRoute;

            // build link
            link = link + item.eventId;

            // optional asset
            if (typeof item.assetType  != 'undefined' && item.assetType !== null) {
                link = link + '&showAsset=' + item.assetType + '&showId=' + item.assetId;
            }

            // search term highlight
            if ( item.hasOwnProperty('term')) {
                link = link + '&term='+item.term;
            }

            // year
            $el.find(product.config.selectors.searchResultItemYear).text(item.year);

            // text
            $el.find(product.config.selectors.searchResultItemText).text(item.text);

            // populate link
            $el.attr('href', link);

            // open this link in modal iframe
            we.getHelper('dom').modalifyLink($el, {'type':'event'});

            // append to container
            $el.appendTo($elements.searchResultContainer);
        };

        /**
         * Validate incoming data in the "data" argumant
         * @returns {boolean}
         */
        isValidData = function () {
            if ( ! product.validateSimple('object', data)) {
                product.reportError('showSearchResults data argument must be object', 2, data);
                return false;
            }

            if ( ! product.validateSimple('propertyExists', data, 'title')) {
                product.reportError('showSearchResults - data must have title property', 2, data);
                return false;
            }

            if ( ! product.validateSimple('propertyExists', data, 'items')) {
                product.reportError('showSearchResults data must have items property', 2, data);
                return false;
            }

            if ( ! product.validateSimple('object', data.items) || data.items === null) {
                product.reportError('showSearchResults: data must have items, object or array', 2, data);
                return false;
            }

            return true;
        };

        if ( ! isValidData () ) {
            return product;
        }

        // populate title
        $elements.searchResultTitle.text(data.title);

        // based on if count result > 0 - show them or the no result message
        if (Object.keys(data.items).length < 1) {
            $elements.searchNoResults.addClass(classes.active);
            $elements.searchResultContainer.removeClass(classes.active);
        } else {
            $elements.searchNoResults.removeClass(classes.active);
            $elements.searchResultContainer.addClass(classes.active);
        }

        // result items - empty container, and add the new links. this needs to be done even when there are no results
        $elements.searchResultContainer.empty();
        jQuery.each(data.items, function (index, item) {
            addResultLink(item);
        });

        // show the window
        $elements.searchResultWrapper.addClass(classes.active);

        // uncheck categories
        $elements.categoryItems.removeClass(classes.active);
        return product;
    };

    /**
     * Handle a request for search by category
     * @param e
     * @param additionalData
     * @param closureData
     */
    product.requestSearchCategoryHandler = function (e, additionalData, closureData) {
        var categoryId = additionalData.eventData,
            items = {},
            category;

        if (product.state.lastSearchCategoryId == categoryId) {
            $elements.searchResultWrapper.removeClass(classes.active);
            $elements.categoryItems.removeClass(classes.active);
            product.state.lastSearchCategoryId = -1;
            return;
        }

        jQuery.each(product.config.categories, function (index, potentialCategory) {
            if (potentialCategory.id == categoryId) {
                category  = potentialCategory;
                return false;
            }
        });

        jQuery.each(category.events, function (index, event) {
            items[index] = {
                'year'      : event.year_value,
                'text'      : event.title,
                'eventId'   : event.id
            };

            // add asset type, asset ID if exists and not empty
            if (typeof event.assetType  != 'undefined' && event.assetType !== null) {
                items[index].assetType = event.assetType;
                items[index].assetId   = event.assetId;
            }
        });

        product.showSearchResults({'title':category.title,'items':items});

        product.state.lastSearchCategoryId = category.id;
        $elements.categoryItems.filter('[data-category-id="'+category.id+'"]').addClass(classes.active);

        // now, highlight category text in events
        product.highlightEventLinksByEventList(items);

    };

    /**
     * Handler for when we need to show search results based on year
     * @param e
     * @param additionalData
     * @param closureData
     */
    product.requestSearchYearHandler = function (e, additionalData, closureData) {
            var year = additionalData.eventData,
                yearName = '',
                items = {};

            jQuery.each(product.config.events, function (index, event) {
                if (event.year_id == year) {
                    items[index] = {
                        'year' : event.year_value,
                        'text' : event.title,
                        'eventId' : event.id
                    };
                    // while we are at it, grab the year value to avoid jsoning and searching more data
                    yearName = event.year_value;
                }
            });

            product.showSearchResults({'title':yearName,'items':items});
        };

    /**
     * Handler request to scroll the timeline
     * @param e
     * @param extraData
     */
    product.requestScrollHandler = function(e, extraData) {
        var to = extraData.eventData;

        if (to === 'end') {
            $elements.timelineInner.customScrollbar('scrollToX', 10000000);
            return;
        }

        if (to === 'start') {
            $elements.timelineInner.customScrollbar('scrollToX', 0);
            return;
        }


    };

    /**
     * Handler for mobile widget show request
     * @param e
     * @param additionalData
     * @param closureData
     */
    product.requestShowWidgetHandler = function (e, additionalData, closureData) {
        var $target  = $elements.widgets.filter('[data-role="'+additionalData.eventData+'"]'),
            isActive = $target.hasClass('active');

        $elements.widgets.removeClass('active');

        if (isActive) {
            $target.removeClass('active');
        } else {
            $target.addClass('active');
        }

        setTimeout(function () {
            $elements.timelineOuter.addClass(classes.active);
        }, 100);

    };

    /**
     * Show the first time tooltip
     */
    product.showFirstTimeTooltip = function () {
        var thumbVisible     = $elements.timelineScrollThumb.css('display') !== 'none',
            targetOffset;

        if (thumbVisible) {
            targetOffset  =  $elements.timelineScrollThumb.offset();
        } else {
            targetOffset      =  $elements.timelineScrollBar.offset();
            targetOffset.top  =  targetOffset.top - 10;
            targetOffset.left =  "calc(50% - 70px)";
        }

        $elements.timelineFirstVisitTooltip.addClass(classes.active);
        $elements.timelineFirstVisitTooltip.css({
            'opacity'   : '1',
            'top'       : targetOffset.top - $elements.timelineFirstVisitTooltip.outerHeight() - 30,
            'left'      : targetOffset.left
        });


        jQuery(product.config.namespace).add($elements.timelineScrollThumb).click(function () {
            $elements.timelineFirstVisitTooltip.css('display','none');
        });
    };

    /**
     * Enable support for keyboard timeline navigation
     */
    product.enableKeyboardTimelineNavigation = function () {
        var $fields         = $elements.textSearchForm.find('input'),
            timelineWidth   = $elements.timelineYearContainer.width();

        jQuery(document).keydown(function(e) {
            var currentTimeLineScroll, currentTimeLineScrollPositive, yearWidth;

            if($fields.is(":focus")) {
               return;
            }

            // attempt to figure out the current state. we can exploded like this, we dont need all the values
            try {
                currentTimeLineScroll = $elements.timelineYearContainer.css('transform').split(', ')[4];
                currentTimeLineScrollPositive = currentTimeLineScroll * -1;
            } catch (e) {
                return;
            }

            // we need to know the year width
            yearWidth = $elements.years.eq(0).width();

            // find out current scroll
            switch(e.which) {
                case 37: // left
                    if (currentTimeLineScrollPositive < 0) {
                        return;
                    }

                    $elements.timelineInner.customScrollbar('scrollToX', currentTimeLineScrollPositive - yearWidth*1.5);
                    break;

                case 39: // right
                    if (currentTimeLineScrollPositive >= timelineWidth) {
                        return;
                    }

                    $elements.timelineInner.customScrollbar('scrollToX', currentTimeLineScrollPositive + yearWidth*1.5);
                    break;

                default:
                  break;
            }

            setTimeout(function () {
                product.lazyLoadTimelineImages();
            }, 100);
        });
    };

    /**
     * Init the timeline
     */
    product.initTimeline = function () {
        var startingPosition = config.startingScrollPosition,
            cookie           = we.getHelper('cookie').get('hasSeenNewUserTooltip', '0'),
            timelineWidth = '';

        $elements.timelineInner.customScrollbar({
                hScroll                 : true,
                vScroll                 : false,
                swipeSpeed              : 3,
                fixedThumbWidth         : product.config.timeline.thumbWidth,
                updateOnWindowResize    : true,
                animationSpeed          : 0,
                animate                 : false
            }
        );

        // new dom elements were created - update the controller
        product.populateElements();

        product.enableKeyboardTimelineNavigation ();

        // show timeline. Optionally show first time tooltip based on cookie (and set said cookie)
        setTimeout(function () {
            $elements.timelineInner.addClass(classes.active);

            // scroll it if required
            if (startingPosition === 'end') {
                timelineWidth = $elements.timelineYearContainer.width();

                $elements.timelineInner.customScrollbar('scrollToX', timelineWidth - 455);
            } else {
                $elements.timelineInner.customScrollbar('scrollToX', 75);
            }

            if (typeof cookie == 'undefined' || cookie != '1') {
                product.showFirstTimeTooltip();
                we.getHelper('cookie').set('hasSeenNewUserTooltip', '1', 900);
            }
        }, 700);
    };

    /**
     * Support a search behaviour
     */
    product.initSearch = function () {
        searchResultTemplate  = $elements.searchResultTemplateContainer.html();
        $elements.closeSearch.on('click', function () {
            $elements.searchResultWrapper.removeClass(classes.active);
            $elements.categoryItems.removeClass(classes.active);
        });
    };

    /**
     * Init forms on timeline
     */
    product.initForms = function () {
       forms.searchForm = we.getObject('form').initByDomElement($elements.textSearchForm).ajaxifySmart(callbacks.textSearch);
    };


    function loadBackgroundImage($el) {
        var src = $el.data('lazy-background-image');
        $el.css('background-image', 'url('+src+')');
        $el.removeClass('lazy-loading');
    }


    product.lazyLoadTimelineImages = function () {

        function isInViewPort($el) { // basic solution, makes everything to the right of the timeline visibility start to be tru
            return $el.offset().left > -100; // this means if we scroll to a point deep into the left, everything on the right will show
        }

        $elements.lazyBackgroundImages.each(function (index, el) {
            var $el = jQuery(el);

            if (isInViewPort($el)) {
                loadBackgroundImage($el);
            } else {


            }
        });
    };

    product.bindLazyLoadTimelineThumbs = function () {
        var delay = 100,
            throttle, $lastMouseDownEl;


        function throttleLazyLoadImages () {
            clearTimeout(throttle);
            throttle = setTimeout(product.lazyLoadTimelineImages, delay);
        }

        $elements.timelineYearContainer.on('DOMAttrModified click touchend', throttleLazyLoadImages) ;
        $elements.timelineScrollBar.on('click touchend', throttleLazyLoadImages);
        $elements.timelineScrollButtons.on('click touchend',throttleLazyLoadImages);


        jQuery('body').on('mousedown', function (e) {
            $lastMouseDownEl = e.originalEvent.srcElement;
        });

        jQuery('body').on('mouseup', function (e) {
            if ($elements.timelineScrollThumb.is($lastMouseDownEl)){
                throttleLazyLoadImages();
            }
        });

        // load the first and last images right now
        var totalImages     = $elements.lazyBackgroundImages.length,
            yearsVisible    = 8,
            imagesPerYear   = 4,
            showPerSide     = yearsVisible * imagesPerYear,
            i;

        for (i=0; i<=showPerSide; i++) {
            loadBackgroundImage($elements.lazyBackgroundImages.eq(i));
            loadBackgroundImage($elements.lazyBackgroundImages.eq((totalImages - 1)));
        }
    };

    product.bindLazyLoadBehaviour = function () {
        product.bindLazyLoadTimelineThumbs();
    };

    /**
     * Init the object
     * @returns {{}}
     */
    product.init  = function () {
        product.populateElements();
        product.autoBindToEvents();
        we.getHelper('dom').bindEventsInNamespace(config.namespace);
        product.initTimeline();
        product.initSearch();
        product.initForms();
        jQuery(product.config.namespace).animate({'opacity':'1'}, 150);

        setTimeout(function () {
            product.bindLazyLoadBehaviour();
            product.lazyLoadTimelineImages();
        }, 700);
        /*
        setTimeout(function () { // fix mobile flickering

        }, 10);*/



        return product;


    };

    product.configure(inputConfiguration);

    return product;

};

// extend the we EventController with objects related to activation
we.extend('TimeLineController', TimeLineController, 'object');
