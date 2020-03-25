



var we = (function (){
    var we = {};

    /**
     * State of the framework
     * @type {{ready: boolean}}
     */
    var weState = {
        ready         : false,
        documentReady : false
    };

    /**
     * Variable to hold singleton instances
     * @type {{}}
     */
    var singletons = {};

    var cookieFactory = function () {
        function extend () {
            var i = 0;
            var result = {};
            for (; i < arguments.length; i++) {
                var attributes = arguments[ i ];
                for (var key in attributes) {
                    result[key] = attributes[key];
                }
            }
            return result;
        }

        function decode (s) {
            return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
        }

        function init (converter) {
            function api() {}

            function set (key, value, attributes) {
                if (typeof document === 'undefined') {
                    return;
                }

                attributes = extend({
                    path: '/'
                }, api.defaults, attributes);

                if (typeof attributes.expires === 'number') {
                    attributes.expires = new Date(new Date() * 1 + attributes.expires * 864e+5);
                }

                // We're using "expires" because "max-age" is not supported by IE
                attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

                try {
                    var result = JSON.stringify(value);
                    if (/^[\{\[]/.test(result)) {
                        value = result;
                    }
                } catch (e) {}

                value = converter.write ?
                    converter.write(value, key) :
                    encodeURIComponent(String(value))
                        .replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);

                key = encodeURIComponent(String(key))
                    .replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
                    .replace(/[\(\)]/g, escape);

                var stringifiedAttributes = '';
                for (var attributeName in attributes) {
                    if (!attributes[attributeName]) {
                        continue;
                    }
                    stringifiedAttributes += '; ' + attributeName;
                    if (attributes[attributeName] === true) {
                        continue;
                    }

                    // Considers RFC 6265 section 5.2:
                    // ...
                    // 3.  If the remaining unparsed-attributes contains a %x3B (";")
                    //     character:
                    // Consume the characters of the unparsed-attributes up to,
                    // not including, the first %x3B (";") character.
                    // ...
                    stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
                }

                return (document.cookie = key + '=' + value + stringifiedAttributes);
            }

            function get (key, json) {
                if (typeof document === 'undefined') {
                    return;
                }

                var jar = {};
                // To prevent the for loop in the first place assign an empty array
                // in case there are no cookies at all.
                var cookies = document.cookie ? document.cookie.split('; ') : [];
                var i = 0;

                for (; i < cookies.length; i++) {
                    var parts = cookies[i].split('=');
                    var cookie = parts.slice(1).join('=');

                    if (!json && cookie.charAt(0) === '"') {
                        cookie = cookie.slice(1, -1);
                    }

                    try {
                        var name = decode(parts[0]);
                        cookie = (converter.read || converter)(cookie, name) ||
                            decode(cookie);

                        if (json) {
                            try {
                                cookie = JSON.parse(cookie);
                            } catch (e) {}
                        }

                        jar[name] = cookie;

                        if (key === name) {
                            break;
                        }
                    } catch (e) {}
                }

                return key ? jar[key] : jar;
            }

            api.set = set;
            api.get = function (key) {
                return get(key, false /* read as raw */);
            };
            api.getJSON = function (key) {
                return get(key, true /* read as json */);
            };
            api.remove = function (key, attributes) {
                set(key, '', extend(attributes, {
                    expires: -1
                }));
            };

            api.defaults = {};

            api.withConverter = init;

            return api;
        }

        return init(function () {});
    };

    /**
     * Configuration of the framework. Can be overriden in runtime
     * @type {{general: {baseUrl: string}, ajax: {}, debug: {active: boolean}, security: {token: string}, language: {languageTag: string}}}
     */
    we.config = {
        language : {
            languageTag: 'he',
            translations : {}
        },
        dom :{
            spinnerMarkup       : '<div class="we-loading active"><div class="load-animation"><div class="load-animation-inner"></div></div></div>',
            spinnerMarkupLocal  : '<div class="we-loading we-loading--local"><div class="load-animation"><div class="load-animation-inner"></div></div></div>',
            select2             : { language: 'he', width: 'default'}, // language dynamically set by initConfig()
            iframeModalMarkup   : "<div class='we-message-window hidden-by-default we-modal--iframe' data-role='we-iframe-modal' data-is-modal data-name='iframe-modal'><div class='we-message__inner' data-role='modal-content-wrapper'><div class='we-message__close' data-role='we-modal-close'></div><div class='we-message_content' data-role='iframe-modal-content'></div></div></div>",
            resizeTimeout       : 75,
            scrollToTopSpeed    : 200
        },
        general : {
            baseUrl : '',
            classes : {
                'active' : 'active',
                'loaded' : 'loaded',
                'hidden' : 'hidden-by-default'
            }
        },
        hash    : {
            'enableHashHandling'            : false, // if this is of, hash handler does not run at all
            "runHashTasksOnReady"           : true,
            'allowHashChangeByIframe'       : true,
            'iframeModalCloseResetsHash'    : true
        },
        ajax : {  },
        debug : {
            active: true
        },
        user : {
            isLoggedIn : false,
            isOwner    : false,
            personalId : null
        },
        security: {
            token: ''
        },
        datepicker :   {
            showSpeed: 'fast',
            minDate  : 0
        },
        slider : {
            "nextButtonMarkup" : "<div class=\"we-slider__next-button\" data-role='slider-next'></div>",
            "prevButtonMarkup" : "<div class=\"we-slider__prev-button\" data-role='slider-prev'></div>"
        }
    };

    var polyfills = {
        'arrayIncludes' :  function () {
            // https://tc39.github.io/ecma262/#sec-array.prototype.includes - polyfil from developer.mozilla
            if (!Array.prototype.includes) {
                Object.defineProperty(Array.prototype, 'includes', {
                    value: function(valueToFind, fromIndex) {

                        if (this == null) {
                            throw new TypeError('"this" is null or not defined');
                        }

                        var o = Object(this);
                        var len = o.length >>> 0;
                        if (len === 0) {
                            return false;
                        }

                        var n = fromIndex | 0;
                        var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

                        function sameValueZero(x, y) {
                            return x === y || (typeof x === 'number' && typeof y === 'number' && isNaN(x) && isNaN(y));
                        }

                        while (k < len) {
                            if (sameValueZero(o[k], valueToFind)) {
                                return true;
                            }
                            k++;
                        }

                        return false;
                    }
                });
            }
        }
    };

    /**
     * Extend string to support ucFirst.
     * @returns {string}
     */
    String.prototype.ucFirst = function () {
        var first = this.charAt(0).toUpperCase();
        return first + this.substr(1);
    };

    /**
     * Extend string to support lcFirst.
     * @returns {string}
     */
    String.prototype.lcFirst = function () {
        var first = this.charAt(0).toLowerCase();
        return first + this.substr(1);
    };

    /**
     * Convert hyphenated names to small camel case
     * @returns {string}
     */
    String.prototype.hyphenToSmallCamel = function () {
        return this.replace(/-([a-z])/g, function (match) { return match[1].toUpperCase(); });
    };

    // jquery extensions
    jQuery.fn.extend({
        'flashClass' :  function (className, duration) {
            var that = this;
            if (typeof duration !== 'number') {
                duration = 300;
            }

            this.addClass(className);
            setTimeout(function () {that.removeClass(className)},duration);
        }
    });

    /**
     * We library helpers. Each is instantiated immediately
     * @type {{debug, testHelper, event, dom, object, validation, language}}
     */
    var helpers = {
        /**
         * Helper for debugging stuff
         */
        debug       : (function () {
            var product = {};

            /**
             * Simple function to default the value of the lavel variable
             * for reportError method
             * @param input
             */
            var prepareLevelInputForReportError  = function(input) {
                var levelNumeric, levelText;

                // default / validate the input
                switch (input) {
                    case '1':
                    case 1:
                    case 'notice' :
                    case 'Notice' :
                    case 'NOTICE' :
                        levelText = '1 - Notice';
                        levelNumeric = 1;
                        break;
                    case '2':
                    case 2:
                    case 'warning' :
                    case 'Warning' :
                    case 'WARNING' :
                        levelText = '2 - Warning';
                        levelNumeric = 2;
                        break;
                    case '3':
                    case 3:
                    case 'fatal' :
                    case 'Fatal' :
                    case 'FATAL' :
                        levelText = '3 - Fatal';
                        levelNumeric = 3;
                        break;
                    default:
                        levelText = '0 - not provided by caller (or not supported)';
                        levelNumeric = 0;
                        break;
                }

                return {
                    levelText : levelText,
                    levelNumeric: levelNumeric
                }
            };

            /**
             * Log stuff to console
             * In case console.log is an issue at some point
             * @param string
             */
            product.log = function (string) {
                console.log(string);
                return product;
            };

            /**
             * Method to log stuff only if debug is on
             * @param string
             * @returns {{}}
             */
            product.debugLog = function (string) {
                if (we.config.debug.active) {
                    product.log(string);
                }

                return product;
            };

            /**
             * Raise a notice if the framework is not ready
             * @param msg   - message from the caller
             * @param data  - related data from the caller
             */
            product.suggestInit = function (msg, data) {
                if ( weState.ready == false   &&
                    we.config.debug.active     ) {
                    product.reportError('Framework not ready yes, watch out. Message: ' + msg, 1, data);
                }
            };

            /**
             * Method to cleanly report errors and throw exceptions.
             * Please use this EVERYWHERE for easy debugging!
             * @param text
             * @param level
             * @param data
             */
            product.reportError = function (text, level, data){
                var levelResult,levelText,levelNumeric;

                // process the level input
                levelResult  = prepareLevelInputForReportError(level);
                levelText    = levelResult.levelText;
                levelNumeric = levelResult.levelNumeric;

                if (typeof text == 'undefined') {
                    text = 'Caller did not provide text';
                }

                if (typeof data == 'undefined') {
                    data = 'Caller did not provide related data';
                }

                if (we.config.debug.active || levelNumeric > 2) {
                    // log the error
                    product.log('=========================');
                    product.log('We debugger reporting, logging level, message, data');
                    product.log(levelText);
                    product.log(text);
                    product.log(data);
                    product.log('=========================');
                }


                // throw exception out if fatal error is requested
                if (levelNumeric == 3) {
                    throw ({
                        'helperDebug' : {
                            'message'           : 'The logged error is fatal',
                            'level'             : levelText,
                            'levelNumeric'      : levelNumeric,
                            'originalMessage'   : text,
                            'relatedData'       : data
                        }
                    })
                }
            };
            return product;
        })(),
        testHelper  : (function () {
            var product = {};

            /**
             * Simple test method
             */
            product.test = function () {
                console.log('test helper - test method');
            };

            return product;
        })(),
        event       : (function () {
            var product = {};

            /**
             * Adds and event listener by name, and binds a handler in addition to passed closure data to it.
             * The handler then gets e, data (event) and closureData as arguments
             * uses the we: event namespace
             *
             * @param eventName
             * @param handler
             * @param closureData
             * @param onlyOnce - should the event only be listened to once? default to false
             */
            product.listen = function (eventName, handler, closureData, onlyOnce) {
                var bindMethod = 'on';
                if (typeof closureData == 'undefined') {
                    closureData = {};
                }

                if (onlyOnce === true) {
                    bindMethod = 'one';
                }

                if ( ! we.validateSimple('function', handler)) {
                    helpers.debug.reportError('can not helpers.event.listen(), handler argument must be a function', 2, {'eventName':name, 'handler':handler});
                    return product;
                }
                jQuery(window)[bindMethod]('we:' + eventName, function (e, data) {
                    handler(e, data, closureData);
                });

                return product;
            };

            /**
             * Fires an event on window level by name, and attaches the data to it.
             * Uses the we: event namespace
             *
             * @param eventName
             * @param data
             */
            product.fire = function (eventName, data) {
                if (typeof data == 'undefined') {
                    data = null;
                }

                jQuery(window).trigger('we:' + eventName, [data]);
            };

            /**
             * Proxy for the fire method
             * @param eventName
             * @param data
             */
            product.trigger = function (eventName, data) {
                product.fire(eventName, data);
            };

            /**
             * Listen to and fire events on resize
             */
            product.listenToLoaclIframes = function () {
                window.addEventListener("message", function(event){
                    var data = event.data;
                    if( ! helpers.validation.validate('propertyExists', data, 'eventName')) {
                        helpers.debug.reportError('Can not trigger iframe event, invalid event data provided by calling window', 2, data);
                        return;
                    }

                    helpers.event.fire('iframe'+data.eventName.ucFirst(), data);
                }, false);
            };

            /**
             * Send a message to the rop frame
             * @param event
             * @param message
             * @returns {boolean}
             */
            product.messageTopFrame = function (event, message) {
                var finalMessage;

                if ( ! helpers.validation.validateSimple('string', event) ) {
                    helpers.debug.reportError('Cant message top, event argument must be string', 1, event);
                    return false;
                }

                if ( ! helpers.validation.validateSimple('object', message) ) {
                    helpers.debug.reportError('Cant message top, message argument must be object', 1, event);
                    return false;
                }

                finalMessage = helpers.object.extend(message, {'eventName':event});

                window.parent.postMessage(finalMessage, '*');
                return true;
            };

            return product;
        })(),
        dom         : (function () {
            var product = {},
                classes = {
                    active : 'active'
                },
                messageWindowselectors = {
                    messageWindow       : "[data-role='we-message-window']",
                    messageClose        : "[data-role='we-message-close']",
                    messageTitle        : "[data-role='we-message-title']",
                    messageWarning      : "[data-role='we-message-warning']",
                    messageText         : "[data-role='we-message-text']",
                    messageButton       : "[data-role='we-message-button']",
                    messageButtonAbort  : "[data-role='we-message-button-abort']"
                },
                $spinner = jQuery(),
                $messageWindow                  = jQuery(),
                windowMessageElements           = {

                },
                messageModalReady               = false,
                modals                          = {},
                sliders                         = {},
                events                          = {},
                scrollActivators                = [],
                togglers                        = [],
                activators                      = [],
                modalIframesTriggers            = [],
                deactivators                    = [],
                stikies                         = [],
                listeningToResize               = false,
                resizeThrottle                  = null,
                iframeModalCreated              = false,
                $iframeModal                    = jQuery(),
                $iframeModalContent             = jQuery(),
                $iframeModalWrapper             = jQuery(),
                $iframeModalSpinner             = jQuery(),
                eventIndex                      = 0,
                lastId                          = 0,
                defaultScrollToSpeed            = 300,
                defaultScrollToOffset           = -50,
                scrollCallbackQueue             = {},
                scrollCallbackIndex             = 0,
                scrollPollRate                  = 40,
                scrollThrottle                  = null,
                currentIframeModalConfig        = {
                    'maxWidth' : '935px',
                    'height'   : '540px',
                    'href'     : ''
                },
                defaultIframeModalConfig        = {
                    'maxWidth' : '935px',
                    'height'   : '540px',
                    'href'     : ''
                };


            /**
             * Populate references to the parts of the $messageWindow we care about
             * @returns {boolean}
             */
            var lazyCreateMessageModal = function () {
                if ( ! messageModalReady) {

                    $messageWindow = jQuery(we.config.dom.messageWindowMarkup);

                    jQuery.each(messageWindowselectors, function (index, selector) {
                        windowMessageElements[index] = $messageWindow.find(selector);
                    });

                    messageModalReady = true;
                }

                // refresh the modal window events - off for everything, then rebind basic behaviour
                windowMessageElements.messageButton
                    .add(windowMessageElements.messageClose)
                    .add(windowMessageElements.messageButtonAbort)
                    .off('click')
                    .on('click', function () {
                        helpers.dom.hideMessage();
                    });

                return true;
            };

            var globalBehaviourHandlers = {
                simplifyNumberInputs : function (options) {
                    var $inputs = jQuery('[type="number"]').not('[data-allow-arrows]');

                    $inputs.on('keydown', function(e) {
                        if (e.which === 38 || e.which === 40) {
                            e.preventDefault();
                        }
                    });
                },
                tabs                 : function (options) {
                    var classes = {
                            'active' : 'active'
                        },
                        contexts = [],
                        $allTabElements = jQuery('[data-tab-context]');

                    function populateContexts () {
                        $allTabElements.each(function (index, element){
                            var context = jQuery(element).attr('data-tab-context');
                            if ( contexts.indexOf(context) === -1) {
                                contexts.push(context);
                            }
                        });
                    }

                    function initTabsByContext(context) {
                        var $triggers = jQuery('[data-tab-context="'+context+'"][data-tab-trigger]'),
                            $bodies   = jQuery('[data-tab-context="'+context+'"][data-tab-body]'),
                            frozen    = false;

                        var freezeContext   = function () {
                            frozen = true;
                        };
                        var unfreezeContext = function () {
                            frozen = false;
                        };

                        var isFrozen        = function ($target) {
                            return frozen;
                        };

                        jQuery($triggers.on('click', function (e) {
                            var $eventTarget = jQuery(e.target),
                                targetName = $eventTarget.attr('data-tab-trigger'),
                                $activator = $eventTarget,
                                $parent;

                            if (isFrozen($activator)) {
                                return false;
                            }

                            if (typeof targetName === 'undefined') {
                                $parent = $eventTarget.parents('[data-tab-trigger]');
                                targetName = $parent.attr('data-tab-trigger');
                                $activator = $parent;
                            }

                            freezeContext();

                            $bodies.each(function (index, body) {
                                var $body = jQuery(body),
                                    name  =  $body.attr('data-tab-body');
                                if (name === targetName) {
                                    jQuery(body).removeClass(classes.active);
                                    jQuery(body).addClass(classes.active);
                                } else {
                                    jQuery(body).removeClass(classes.active);
                                }
                            });

                            $triggers.removeClass(classes.active);
                            $activator.addClass(classes.active);

                            setTimeout(function (){
                                unfreezeContext();
                            }, 50);
                        }));
                    }

                    // find all contexts, and then make tabs for each
                    populateContexts();
                    jQuery.each(contexts, function (index, context) {
                        initTabsByContext(context);
                    });
                },
                modals               : function (options) {
                    // find modals
                    var $modals = jQuery('[data-is-modal]'),
                        $modal, modalName;



                    // bind each, skip existing
                    $modals.each(function (index, el) {
                        var $triggers, $modal, modalName;

                        var openModal = function () {
                            helpers.event.fire('modalOpened', {modalName:modalName,$modal:$modal,$trigger:jQuery(this)});
                            $modal.addClass(classes.active);
                            jQuery('html, body').trigger('resize');
                        };

                        $modal    = jQuery(el);
                        modalName = $modal.data('name');


                        // if modal not exist - list it, bind close button
                        if ( ! modals.hasOwnProperty(modalName)) {
                            $modal.find('[data-role="we-modal-close"]').on('click', function (e) {
                                $modal.removeClass(classes.active);
                                modals[modalName] = $modal;
                            });
                        }

                        // regradless of modal's existence, -refresh- it's activators

                        // activators
                        $triggers =  jQuery('[data-modal-trigger="'+modalName+'"]');
                        $triggers.on('click', openModal);
                    });
                    return true;
                },
                sliders              : function (options) {
                    var $sliders;

                    var Slider = function ($target) {
                        var slider = {};

                        slider.state =  {
                            $el             : null,
                            slides          : {},
                            currentSlide    : 0,
                            currentBatch    : 0,
                            batchSize       : 1,
                            lastSlideId     : -1,
                            $nextButton     : jQuery(),
                            $prevButton     : jQuery(),
                            $buttons        : jQuery(),
                            destroyed       : false
                        };

                        slider.classes = {
                            active : 'active',
                            slide  :  'we-slider__slide',
                            hidden : 'hidden'
                        };

                        // slider.
                        slider.id = null;

                        /**
                         * Get the maximal batch index
                         * @returns {number}
                         */
                        var getMaxBatchIndex = function () {
                            var totalBatches = Math.ceil(getSlideCount()/slider.state.batchSize); // rounded up - slides/size.
                            return totalBatches - 1; //first index is 0
                        };

                        /**
                         * Get count of sliders we have
                         * @returns {number}
                         */
                        var getSlideCount = function () {
                            var count = 0;
                            jQuery.each(slider.state.slides, function (index, value) {
                                if (slider.state.slides.hasOwnProperty(index)) {
                                    count = count + 1;
                                }
                            });
                            return count;
                        };

                        /**
                         * Get the slider id
                         * @returns {null|number|*}
                         */
                        slider.getId  = function () {
                            return slider.id;
                        };

                        /**
                         * Get the main element of the slider
                         * @returns {null}
                         */
                        slider.getElement = function () {
                            return slider.state.$el;
                        };

                        /**
                         * Get the current item (first in batch)
                         */
                        slider.getCurrentItem = function () {
                            return slider.state.currentSlide;
                        };

                        /**
                         * Get the current batch index
                         * @returns {*}
                         */
                        slider.getCurrentBatch = function () {
                            return slider.state.currentBatch();
                        };

                        /**
                         * Show the next batch
                         */
                        slider.showNextBatch = function () {
                            slider.goToBatch(slider.state.currentBatch + 1);
                        };

                        /**
                         * Show the previous batch
                         */
                        slider.showPreviousBatch = function () {
                            slider.goToBatch(slider.state.currentBatch - 1);
                        };

                        /**
                         * Go to a spcific batch of slides
                         * @param index
                         */
                        slider.goToBatch = function (index) {
                            var firstIndex = slider.state.batchSize * index,
                                lastIndex  = firstIndex + slider.state.batchSize - 1;

                            // find the items that are required.
                            if (firstIndex < 0) {
                                slider.goToBatch(0);
                                return slider;
                            }

                            if (firstIndex > getSlideCount() - 1) { // first index is something we dont even have..
                                slider.goToBatch(getMaxBatchIndex());
                                return slider;
                            }

                            // hide all items
                            jQuery.each(slider.state.slides, function (index, slide) {
                                if (index >= firstIndex && index <=lastIndex) {
                                    slide.$el.addClass(slider.classes.active);
                                    return true;
                                }
                                slide.$el.removeClass(slider.classes.active);
                            });

                            // show both buttons
                            slider.state.$buttons.removeClass(slider.classes.hidden);

                            if (firstIndex == 0) {
                                slider.state.$prevButton.addClass(slider.classes.hidden);
                            }

                            if (index >= getMaxBatchIndex()) {
                                slider.state.$nextButton.addClass(slider.classes.hidden);
                            }

                            slider.state.currentBatch = index;
                            slider.state.currentSlide = firstIndex;

                            return slider;
                        };

                        /**
                         * Go to the start of the slider - show first items
                         */
                        slider.toStart = function () {
                            slider.goToBatch(0);
                        };

                        /**
                         * Show all the slides at once (may cause height overflow depending on container original css)
                         */
                        slider.showAll = function () {
                            jQuery.each(slider.state.slides, function (index, slide){
                                slide.$el.addClass(slider.classes.active);
                            });
                        };

                        /**
                         * Hide all slides
                         */
                        slider.hideAll = function () {
                            jQuery.each(slider.state.slides, function (index, slide){
                                slide.$el.removeClass(slider.classes.active);
                            });
                        };

                        /**
                         * Go to the last batch of slides
                         * @returns {{}}
                         */
                        slider.toEnd = function () {
                            slider.goToBatch(getMaxBatchIndex());
                            return slider;
                        };

                        /**
                         * Destroy the slider
                         */
                        slider.destroy = function () {
                            slider.state.$buttons.detach();
                            slider.state.destroyed = true;
                            return slider;
                        };

                        /**
                         * Inject and bind slider buttons into the dom
                         */
                        slider.injectButtons = function () {
                            var $next = jQuery(we.config.slider.nextButtonMarkup),
                                $prev = jQuery(we.config.slider.prevButtonMarkup);

                            $next.on('click', function () {
                                slider.showNextBatch();
                            });

                            $prev.on('click', function () {
                                slider.showPreviousBatch();
                            });

                            slider.$el.append($next);
                            slider.$el.append($prev);
                            slider.state.$nextButton = $next;
                            slider.state.$prevButton = $prev;
                            slider.state.$buttons = $prev.add($next);
                        };

                        /**
                         * Populate some elements form dom and save their reference for later use
                         */
                        slider.populateElements = function () {
                            slider.$el = slider.state.$el;
                            slider.$items = slider.$el.find(' > *');
                            slider.injectButtons(); // it is CRITICAL that this is done AFTER setting slider.$items
                        };

                        /**
                         * Populate slider.state.slides based on dom and state of slider.$items
                         */
                        slider.populateSlides = function () {
                            var slideCount = 0;
                            slider.$items.each(function (index, el) { // slider.$items is what we had before button injection. critical to use only this
                                var slideId = slider.state.lastSlideId + 1; // do not overwrite the last slide
                                slider.state.lastSlideId = slider.state.lastSlideId + 1; // advance last slide
                                slider.state.slides[slideId] = {
                                    id  : slideId,
                                    $el : jQuery(el)
                                };

                                jQuery(el).addClass(slider.classes.slide);
                                slideCount = slideCount + 1;
                            });

                            if (slideCount < 1) {
                                helpers.debug.reportError('Warning - you are making a slider with no items in it. Expect problems.', 2, {slider : slider, $el: slider.$el});

                            }
                        };

                        /**
                         * Reset all counts, and recalculate batch size
                         */
                        slider.reset = function () {
                            var itemOuterWidth, // dont calculate before show all
                                containerWidth,
                                newBatchSize;

                            // show all items so that it is possible to calculate
                            slider.showAll();

                            // determine and set batch size based on visible items that fit in container
                            try { // will blow up if no slides, although warning is displayed beforehand.
                                itemOuterWidth = slider.state.slides[0].$el.outerWidth(true);
                            } catch (e) {
                                itemOuterWidth = 1;
                            }

                            containerWidth = slider.$el.outerWidth();
                            if (containerWidth == 0 ) { // just, dont allow for strange things
                                containerWidth = 1920;
                            }

                            newBatchSize = Math.floor(containerWidth/itemOuterWidth);

                            slider.state.batchSize = newBatchSize;

                            // done - go to first batch
                            slider.toStart();
                        };

                        /**
                         * init the slider
                         */
                        slider.init = function () {
                            var resizeThrottle;

                            slider.id = lastId + 1;
                            lastId    = lastId + 1;

                            slider.state.$el = $target;
                            slider.state.$el.addClass('we-slider');//so BEM makes sense in css to whoever comes after us

                            slider.populateElements();
                            slider.populateSlides(); // this MUST run after populate elements as it is adding elements

                            slider.reset();

                            jQuery(window).on('resize', function () {
                                clearTimeout(resizeThrottle);
                                resizeThrottle = setTimeout(slider.reset, 50);
                            });

                            // allow access to slider via element data
                            slider.state.$el.data('slider', slider);
                            return slider;
                        };

                        return slider;
                    };

                    /**
                     * Create a slider for an element
                     * @param $el
                     */
                    function createSlider($el) {
                        var slider;

                        if ( ! helpers.validation.validateSimple('jQuery', $el) && $el.length > 0) {
                            helpers.debug.reportError('cant init slider, argument not jQuery or is empty jQuery', 2, $el);
                            return;
                        }

                        slider = new product.slider(jQuery($el[0])); // pass only first element as jQuery
                        sliders[slider.getId()] = slider;
                    }

                    /**
                     * Does a slider exist for this element
                     * @param $el
                     * @returns {boolean}
                     */
                    function doesSliderExist($el) {
                        var sliderFound = false;

                        jQuery.each (sliders, function (index, slider) {
                            // if is JQuery that is not empty, and its first element is like the slider element - slider exists
                            if ( ( helpers.validation.validateSimple('jQuery', $el) && $el.length > 0) &&
                                slider.getElement()[0] == $el[0])                                        {
                                sliderFound = true;
                                return false; // no need to search any more
                            }
                        });

                        return sliderFound;
                    }

                    // find and init all the slider elements
                    $sliders = jQuery('[data-is-slider]');

                    // for each new one - create slider - no double sliders on same element, sliders variable on helper root context
                    $sliders.each(function (index, el) {
                        var $slider = jQuery(el);
                        if  ( ! doesSliderExist($slider)) {
                            createSlider($slider);
                        }
                    });
                },
                scrollsTo            : function (options) {
                    // find scroll activators
                    var $targets = jQuery('[data-behaviour="scrollsTo"]');

                    var bindScrollTo = function (element) {
                        var $el, speed, $target, targetSelector;

                        // if already bound, return
                        if (scrollActivators.indexOf(element) !== -1) {
                            return true;
                        }

                        // prepare to bind behaviour
                        $el             = jQuery(element);
                        speed           = options.speed;
                        targetSelector  = $el.data('behaviour-data');

                        // validate
                        if ( ! we.validateSimple('selector', targetSelector)) {
                            we.reportError('Can not bind scrollTo behaviour, target argument is not a valid selector', $el);
                            return;
                        }

                        $target = jQuery(targetSelector);
                        if ( ! we.validateSimple('jQuery', $target) || $target.length === 0) {
                            we.getHelper('debug').reportError('Can not bind scrollTo behaviour, target argument is selector, but using it did not yield a jQuery object, or it is empty', $el);
                            return;
                        }

                        // bind
                        $el.on('click touchend', function () {
                            we.getHelper('dom').scrollBodyTo($target, true, speed, options.offset);
                        });

                        // add to element so we dont bind it again
                        scrollActivators.push(element);
                    };

                    if ( ! we.validateSimple('object', options, null)) {
                        options = {};
                    }

                    if ( ! we.validateSimple('propertyExists', options, 'speed')) {
                        options.speed = defaultScrollToSpeed;
                    }
                    if ( ! we.validateSimple('propertyExists', options, 'offset')) {
                        options.offset = defaultScrollToOffset;
                    }

                    $targets.each (function (index, element) {
                        return bindScrollTo(element);

                    });

                },
                activator            : function (options) {
                    // find scroll activators
                    var $targets = jQuery('[data-behaviour="activator"]');

                    var bindActivation = function (element) {
                        var $el, $target, targetSelector, test = 1;


                        // if already bound, return
                        if (activators.indexOf(element) !== -1 && typeof activators.indexOf(element)  !== 'undefined') {
                            return true;
                        }

                        // prepare to bind behaviour
                        $el             = jQuery(element);
                        targetSelector  = $el.data('behaviour-data');

                        // validate
                        if ( ! we.validateSimple('selector', targetSelector)) {
                            we.reportError('Can not bind activator behaviour, target argument is not a valid selector', $el);
                            return;
                        }

                        $target = jQuery(targetSelector);
                        if ( ! we.validateSimple('jQuery', $target) || $target.length === 0) {
                            we.getHelper('debug').reportError('Can not bind activator behaviour, target argument is selector, but using it did not yield a jQuery object, or it is empty',1, {$el:$el,argument:targetSelector});
                            return;
                        }



                        // bind
                        $el.on('click', function () {
                            $el.toggleClass(we.config.general.classes.active);
                            $target.toggleClass(we.config.general.classes.active);
                        });

                        // add to element so we dont bind it again
                        activators.push(element);
                    };

                    if ( ! we.validateSimple('object', options, null)) {
                        options = {};
                    }


                    $targets.each (function (index, element) {
                        return bindActivation(element);

                    });

                },
                toggler              : function (options) {
                    // find scroll activators
                    var $targets = jQuery('[data-behaviour="toggler"]');

                    var bindTogglerAction = function (element) {
                        var $el, $target, targetSelector;

                        // if already bound, return
                        if (togglers.indexOf(element) !== -1) {
                            return true;
                        }

                        // prepare to bind behaviour
                        $el             = jQuery(element);
                        targetSelector  = $el.data('behaviour-data');

                        // validate
                        if ( ! we.validateSimple('selector', targetSelector)) {
                            we.reportError('Can not bind toggler behaviour, target argument is not a valid selector', $el);
                            return;
                        }

                        $target = jQuery(targetSelector);
                        if ( ! we.validateSimple('jQuery', $target) || $target.length === 0) {
                            we.getHelper('debug').reportError('Can not bind toggler behaviour, target argument is selector, but using it did not yield a jQuery object, or it is empty',1, {$el:$el,argument:targetSelector});
                            return;
                        }

                        // bind
                        $el.on('click', function () {
                            $el.toggleClass(we.config.general.classes.active);
                            if ( ! $el.is($target)) {
                                $target.toggleClass(we.config.general.classes.active);
                            }
                        });

                        // add to element so we dont bind it again
                        togglers.push(element);
                    };

                    if ( ! we.validateSimple('object', options, null)) {
                        options = {};
                    }

                    $targets.each (function (index, element) {
                        return bindTogglerAction(element);

                    });
                },
                deactivator          : function (options) {
                    // find scroll activators
                    var $targets = jQuery('[data-behaviour="deactivator"]');

                    var bindDeactivation = function (element) {
                        var $el, $target, targetSelector;

                        // if already bound, return
                        if (deactivators.indexOf(element) !== -1) {
                            return true;
                        }

                        // prepare to bind behaviour
                        $el             = jQuery(element);
                        targetSelector  = $el.data('behaviour-data');

                        // validate
                        if ( ! we.validateSimple('selector', targetSelector)) {
                            we.reportError('Can not bind deactivator behaviour, target argument is not a valid selector', $el);
                            return;
                        }

                        $target = jQuery(targetSelector);
                        if ( ! we.validateSimple('jQuery', $target) || $target.length === 0) {
                            we.getHelper('debug').reportError('Can not bind deactivator behaviour, target argument is selector, but using it did not yield a jQuery object, or it is empty', $el);
                            return;
                        }

                        // bind
                        $el.on('click', function () {
                            $target.removeClass(we.config.general.classes.active);
                        });

                        // add to element so we dont bind it again
                        deactivators.push(element);
                    };

                    if ( ! we.validateSimple('object', options, null)) {
                        options = {};
                    }

                    $targets.each (function (index, element) {
                        return bindDeactivation(element);
                    });

                    return true;

                },
                modaleIframes        : function (options) {
                    var $activators = jQuery('[data-behaviour="modal-iframe-trigger"]');

                    // method to extend a given config object, based on $activator attributes
                    function extendIframeModalConfigByActivator($activator, config) {
                        // attempt to extract from element (json string), then make sure we ended up with valid config
                        var configRaw = $activator.data('behaviour-data');

                        if (typeof configRaw === 'string') {
                            try {configRaw = JSON.parse(configRaw)} catch (e) {}
                        }

                        if (typeof configRaw === 'object') {
                            config = helpers.object.extend(config, configRaw)
                        }

                        return config;
                    }

                    // make sure we are ready to show iframe modals, then bind the elements we find
                    product.activateSupportForIframeModals();

                    // for each activator found, figure out it's config and bind it. bindIframeActivator() will not bind same element twice
                    $activators.each(function (index, activator) {
                        var $activator  = jQuery(activator),
                            finalConfig = extendIframeModalConfigByActivator($activator, helpers.object.extend({}, defaultIframeModalConfig));

                        bindIframeActivator($activator, finalConfig)
                    });

                },
                sticky               : function (options) {
                    // find scroll activators
                    var $targets  = jQuery('[data-behaviour="sticky"]'),
                        scrollTop = jQuery(window).scrollTop();

                    var adjustSticky = function ($el, threshold, scrollTop) {
                        if (scrollTop > threshold) {
                            $el.addClass('sticky');
                        } else {
                            $el.removeClass('sticky');
                        }
                    };

                    var makeSticky = function (element) {
                        var $el =  jQuery(element), threshold = 0;

                        // if already bound, return
                        if (stikies.indexOf(element) !== -1) {
                            return true;
                        }
                        scrollCallbackQueue[scrollCallbackIndex] = function (scrollTop) {
                            adjustSticky($el, threshold, scrollTop);
                        };

                        // adjust the stikcy state right now
                        adjustSticky($el, threshold, scrollTop);
                        scrollCallbackIndex++;
                    };

                    if ( ! we.validateSimple('object', options, null)) {
                        options = {};
                    }

                    $targets.each (function (index, element) {
                        return makeSticky(element);
                    });

                },
            };

            // bind an $activator as an iframe activator based on config (NOT $activator attributes)
            function bindIframeActivator ($activator, config) {
                // validate config
                if ( ! we.validateSimple('propertyExists', config, 'src' )) {
                    helpers.debug.reportError('can not bind iframe - config argument is invalid (needs to be object with src property, or missing.', 2, {'$activator': $activator, 'config': config});
                    return;
                }

                // validate the $activator
                if ($activator.length < 1) {
                    helpers.debug.reportError('can not bind iframe - $activator must have at least one element.', 2, {'$activator': $activator, 'config': config});
                    return;
                }

                // do not re-bind items
                if (modalIframesTriggers.indexOf($activator[0]) !== -1 && typeof modalIframesTriggers.indexOf($activator[0]) != 'undefined') {
                    return true;
                }

                // good to go - bind it
                $activator.on('click', function (e) {
                    e.preventDefault();
                    product.showIframeInModal(config)
                });

                // note this element is bound as an iframe activator
                modalIframesTriggers.push($activator[0]);
            }

            /**
             * Make a link open as modal with iframe
             * Can take override config, object.
             * If override not object or has no href OR src property, will use link href attribute for url
             * @param $link
             * @param config
             */
            product.modalifyLink = function ($link, config) {
                var finalConfig = helpers.object.extend({}, defaultIframeModalConfig);

                if (typeof config === 'object' && config !== null) {
                    finalConfig = helpers.object.extend(finalConfig, config)
                }

                // validate
                if ( ! helpers.validation.validateSimple('jQuery', $link)) {
                    helpers.debug.reportError('Can not modlify link, invalid first argument - needs jQuery', 2, $link);
                    return false;
                }

                if ( ! finalConfig.hasOwnProperty('href') || finalConfig.href == '') {
                    finalConfig.href = $link.attr('href');
                }

                // allows us to provide either src or href attribute
                finalConfig.src = finalConfig.href;


                bindIframeActivator($link, finalConfig);
                return true;

            };

            /**
             * Resize the iframe modal based on input and/or defaults/config that is set in helper state
             * @param event
             * @param eventData
             */
            product.resizeIframeModal = function (event, eventData) {
                var targetMaxWidth =  currentIframeModalConfig.maxWidth,
                    targetHeight   =  currentIframeModalConfig.height;

                // event data may override the default set when binding the original link on parent window
                if (typeof eventData === 'object') {
                    if (eventData.hasOwnProperty('maxWidth')) {
                        targetMaxWidth          =  eventData.maxWidth;
                        currentIframeModalConfig.maxWidth  = eventData.maxWidth;
                    }

                    if (eventData.hasOwnProperty('height')) {
                        targetHeight         =  eventData.height;
                        currentIframeModalConfig.height = eventData.height;
                    }
                }

                // on mobile, we use responsive styles and we do not care what the iframe wants
                if (product.isMobileDisplay()) {
                    $iframeModalWrapper.css('max-width', '90vw');
                    $iframeModalWrapper.css('height', '90vh');
                    return;
                }

                // this is desktop display mode, lets use our final sizes and apply them
                $iframeModalWrapper.css('max-width', targetMaxWidth);
                $iframeModalWrapper.css('height', targetHeight);
            };

            /**
             * Show an iframe in the iframe modal based on config
             * @param config
             * @returns {boolean}
             */
            product.showIframeInModal = function (config) {
                var $iframe, originalTransition, iframeModelType = 'default';

                if ( ! we.validateSimple('propertyExists', config, 'src' )) {
                    helpers.debug.reportError('can not show iframe in modal - config argument is invalid (needs to be object with src property, or missing.', 2, {'$activator': $activator, 'config': config});
                    return false;
                }

                product.activateSupportForIframeModals();

                // iframe modal type
                if (config.hasOwnProperty('type')) {
                    iframeModelType = config.type;
                }


                $iframe = jQuery('<iframe class="" ></iframe>');

                // indicate on closure scope that we are now working on this config as default
                currentIframeModalConfig = config;

                helpers.dom.showSpinner();
                $iframeModal.removeClass(we.config.general.classes.loaded);
                $iframeModal.attr('iframe-type', iframeModelType);

                // whenever the iframe is loaded, resize it
                $iframe.on('load', function () {
                    product.resizeIframeModal();
                    helpers.dom.hideSpinner();
                    $iframeModal.addClass(we.config.general.classes.loaded);
                });

                // now we can order the iframe to load. We had to do the listening first
                $iframe.attr('src', config.src);

                // clear modal from existing content, append the iframe to it's place, resize it for the first time.
                $iframeModalContent.html('');

                // we need to disable css transition for initial display - we no longer show transition at all
                originalTransition = $iframeModalWrapper.css('transition');
                $iframeModalWrapper.css('transition', 'none');

                $iframe.appendTo($iframeModalContent);
                product.resizeIframeModal();

                // show spinner
                $iframeModalSpinner.addClass(we.config.general.classes.active);

                // show the modal
                $iframeModal.addClass(we.config.general.classes.active);

                // restore transition
                setTimeout(function () {
                    //  $iframeModalWrapper.css('transition', originalTransition);
                }, 50);

                return true;
            };

            /**
             * Hide the iframe modal
             */
            product.hideIframeModel = function () {
                product.activateSupportForIframeModals();
                $iframeModal.removeClass(we.config.general.classes.active);

                return product;
            };

            /**
             * Activates support for iframe modal. injects it and listens to events
             * @returns {boolean}
             */
            product.activateSupportForIframeModals = function () {

                /**
                 * Inject an iframe modal to the dom. Note some of its elements for later usage in closure scope
                 */
                function injectIframeModal () {
                    // create the modal, save references
                    $iframeModal        = jQuery(we.config.dom.iframeModalMarkup);
                    $iframeModalWrapper = $iframeModal.find("[data-role='modal-content-wrapper']");
                    $iframeModalContent = $iframeModal.find("[data-role='iframe-modal-content']");

                    // create and append a local loading spinner
                    //  $iframeModalSpinner = jQuery(we.config.dom.spinnerMarkupLocal);
                    //   $iframeModalSpinner.appendTo($iframeModalWrapper);

                    // close action
                    $iframeModal.find('[data-role=we-modal-close]').on('click', function () {
                        $iframeModal.removeClass(we.config.general.classes.active);

                        if (we.config.hash.iframeModalCloseResetsHash) {
                            we.getObject('hashHandler').setHash('');
                        }
                    });

                    // add to the dom, note this action so we can avoid repetition
                    $iframeModal.appendTo('body');
                    iframeModalCreated = true;
                }

                if ( iframeModalCreated === true ) {
                    return true;
                }

                // listen to resize and requests of iframe to resize, so we can adjust
                helpers.event.listen('iframeRequestsClose', product.hideIframeModel);
                helpers.event.listen('iframeRequestsResize',  product.resizeIframeModal );
                helpers.event.listen('iframeRequestsRedirect',  product.redirectByIframeRequest );
                helpers.event.listen('domResize',  product.resizeIframeModal );
                injectIframeModal();

                return true;
            };

            /**
             * Redirect per iframes request. event data may provide the url, otherwise - main page
             * @param event
             * @param eventData
             */
            product.redirectByIframeRequest = function (event, eventData) {
                var redirect      = we.config.general.baseUrl,
                    onlyForMobile = false;

                if (typeof eventData === 'object' && typeof eventData.url === 'string') {
                    redirect = eventData.url;
                }

                if (typeof eventData === 'object' && typeof eventData.onlyForMobile === 'boolean') {
                    onlyForMobile = eventData.onlyForMobile;
                }

                if (onlyForMobile &&  ! product.isMobileDisplay () ) {
                    return;
                }

                product.hideIframeModel();


                we.getHelper('route').redirectWithSpinner(redirect);
            };

            /**
             * Figure out if this is a mobile display by width
             * @returns {boolean}
             */
            product.isMobileDisplay = function () {
                return jQuery(window).width() < 1000;
            };

            /**
             * Listen to and fire events on resize
             */
            product.listenToResize = function () {
                jQuery(window).on('resize', function () {
                    clearTimeout(resizeThrottle);
                    resizeThrottle = setTimeout(function () {
                        helpers.event.fire('domResize', jQuery(window).width());
                    }, we.config.dom.resizeTimeout);
                });
            };

            /**
             * Create a slider from a dom element
             * @param $target
             * @param autoInit - default true. should we also just init the slider right now
             * @returns {{}}
             */
            product.slider = function ($target, autoInit) {
                var slider = {};

                autoInit = (autoInit !== false); // default autoInit to true

                slider.state =  {
                    $el             : null,
                    slides          : {},
                    currentSlide    : 0,
                    currentBatch    : 0,
                    batchSize       : 1,
                    lastSlideId     : -1,
                    $nextButton     : jQuery(),
                    $prevButton     : jQuery(),
                    $buttons        : jQuery(),
                    destroyed       : false
                };

                slider.classes = {
                    active : 'active',
                    slide  :  'we-slider__slide',
                    hidden : 'hidden'
                };

                // slider.
                slider.id = null;

                /**
                 * Get the maximal batch index
                 * @returns {number}
                 */
                var getMaxBatchIndex = function () {
                    var totalBatches = Math.ceil(getSlideCount()/slider.state.batchSize); // rounded up - slides/size.
                    return totalBatches - 1; //first index is 0
                };

                /**
                 * Get count of sliders we have
                 * @returns {number}
                 */
                var getSlideCount = function () {
                    var count = 0;
                    jQuery.each(slider.state.slides, function (index, value) {
                        if (slider.state.slides.hasOwnProperty(index)) {
                            count = count + 1;
                        }
                    });
                    return count;
                };

                /**
                 * Get the slider id
                 * @returns {null|number|*}
                 */
                slider.getId  = function () {
                    return slider.id;
                };

                /**
                 * Get the main element of the slider
                 * @returns {null}
                 */
                slider.getElement = function () {
                    return slider.state.$el;
                };

                /**
                 * Get the current item (first in batch)
                 */
                slider.getCurrentItem = function () {
                    return slider.state.currentSlide;
                };

                /**
                 * Get the current batch index
                 * @returns {*}
                 */
                slider.getCurrentBatch = function () {
                    return slider.state.currentBatch();
                };

                /**
                 * Show the next batch
                 */
                slider.showNextBatch = function () {
                    slider.goToBatch(slider.state.currentBatch + 1);
                };

                /**
                 * Show the previous batch
                 */
                slider.showPreviousBatch = function () {
                    slider.goToBatch(slider.state.currentBatch - 1);
                };

                /**
                 * Go to a spcific batch of slides
                 * @param index
                 */
                slider.goToBatch = function (index) {
                    var firstIndex = slider.state.batchSize * index,
                        lastIndex  = firstIndex + slider.state.batchSize - 1;

                    // find the items that are required.
                    if (firstIndex < 0) {
                        slider.goToBatch(0);
                        return slider;
                    }

                    if (firstIndex > getSlideCount() - 1) { // first index is something we dont even have..
                        slider.goToBatch(getMaxBatchIndex());
                        return slider;
                    }

                    // hide all items
                    jQuery.each(slider.state.slides, function (index, slide) {
                        if (index >= firstIndex && index <=lastIndex) {
                            slide.$el.addClass(slider.classes.active);
                            return true;
                        }
                        slide.$el.removeClass(slider.classes.active);
                    });

                    // show both buttons
                    slider.state.$buttons.removeClass(slider.classes.hidden);

                    if (firstIndex == 0) {
                        slider.state.$prevButton.addClass(slider.classes.hidden);
                    }

                    if (index >= getMaxBatchIndex()) {
                        slider.state.$nextButton.addClass(slider.classes.hidden);
                    }

                    slider.state.currentBatch = index;
                    slider.state.currentSlide = firstIndex;

                    return slider;
                };

                /**
                 * Go to the start of the slider - show first items
                 */
                slider.toStart = function () {
                    slider.goToBatch(0);
                };

                /**
                 * Show all the slides at once (may cause height overflow depending on container original css)
                 */
                slider.showAll = function () {
                    jQuery.each(slider.state.slides, function (index, slide){
                        slide.$el.addClass(slider.classes.active);
                    });
                };

                /**
                 * Hide all slides
                 */
                slider.hideAll = function () {
                    jQuery.each(slider.state.slides, function (index, slide){
                        slide.$el.removeClass(slider.classes.active);
                    });
                };

                /**
                 * Go to the last batch of slides
                 * @returns {{}}
                 */
                slider.toEnd = function () {
                    slider.goToBatch(getMaxBatchIndex());
                    return slider;
                };

                /**
                 * Destroy the slider
                 */
                slider.destroy = function () {
                    slider.state.$buttons.detach();
                    slider.state.destroyed = true;
                    return slider;
                };

                /**
                 * Inject and bind slider buttons into the dom
                 */
                slider.injectButtons = function () {
                    var $next = jQuery(we.config.slider.nextButtonMarkup),
                        $prev = jQuery(we.config.slider.prevButtonMarkup);

                    $next.on('click', function () {
                        slider.showNextBatch();
                    });

                    $prev.on('click', function () {
                        slider.showPreviousBatch();
                    });

                    slider.$el.append($next);
                    slider.$el.append($prev);
                    slider.state.$nextButton = $next;
                    slider.state.$prevButton = $prev;
                    slider.state.$buttons = $prev.add($next);
                };

                /**
                 * Populate some elements form dom and save their reference for later use
                 */
                slider.populateElements = function () {
                    slider.$el = slider.state.$el;
                    slider.$items = slider.$el.find(' > *');
                    slider.injectButtons(); // it is CRITICAL that this is done AFTER setting slider.$items
                };

                /**
                 * Populate slider.state.slides based on dom and state of slider.$items
                 */
                slider.populateSlides = function () {
                    var slideCount = 0;
                    slider.$items.each(function (index, el) { // slider.$items is what we had before button injection. critical to use only this
                        var slideId = slider.state.lastSlideId + 1; // do not overwrite the last slide
                        slider.state.lastSlideId = slider.state.lastSlideId + 1; // advance last slide
                        slider.state.slides[slideId] = {
                            id  : slideId,
                            $el : jQuery(el)
                        };

                        jQuery(el).addClass(slider.classes.slide);
                        slideCount = slideCount + 1;
                    });

                    if (slideCount < 1) {
                        helpers.debug.reportError('Warning - you are making a slider with no items in it. Expect problems.', 2, {slider : slider, $el: slider.$el});

                    }
                };

                /**
                 * Reset all counts, and recalculate batch size
                 */
                slider.reset = function () {
                    var itemOuterWidth, // dont calculate before show all
                        containerWidth,
                        newBatchSize;

                    // show all items so that it is possible to calculate
                    slider.showAll();

                    // determine and set batch size based on visible items that fit in container
                    try { // will blow up if no slides, although warning is displayed beforehand.
                        itemOuterWidth = slider.state.slides[0].$el.outerWidth(true);
                    } catch (e) {
                        itemOuterWidth = 1;
                    }

                    containerWidth = slider.$el.outerWidth();
                    if (containerWidth == 0 ) { // just, dont allow for strange things
                        containerWidth = 1920;
                    }

                    newBatchSize = (containerWidth/itemOuterWidth);
                    newBatchSize = Math.floor(containerWidth/itemOuterWidth);
                    newBatchSize = Math.floor(containerWidth/itemOuterWidth);

                    if (newBatchSize == 0) {
                        newBatchSize = 1;
                    }

                    slider.state.batchSize = newBatchSize;

                    // done - go to first batch
                    slider.toStart();
                };

                /**
                 * init the slider
                 */
                slider.init = function () {
                    var resizeThrottle;

                    slider.id = lastId + 1;
                    lastId    = lastId + 1;

                    slider.state.$el = $target;
                    slider.state.$el.addClass('we-slider');//so BEM makes sense in css to whoever comes after us

                    slider.populateElements();
                    slider.populateSlides(); // this MUST run after populate elements as it is adding elements

                    slider.reset();

                    jQuery(window).on('resize', function () {
                        clearTimeout(resizeThrottle);
                        resizeThrottle = setTimeout(slider.reset, 50);
                    });

                    // allow access to slider via element data
                    slider.state.$el.data('slider', slider);
                    return slider;
                };

                if (autoInit) {
                    slider.init();
                }
                return slider;
            };

            /**
             * Transform a dom element into jQuery select2
             * @param $target
             * @param overrideOptions
             * @returns {*}
             */
            product.select2 = function ($target, overrideOptions) {
                var options = we.config.dom.select2, tmp;

                if ( ! helpers.validation.validateSimple('jQuery', $target) || ! $target.is('select') || $target.count > 1) {
                    we.reportError('Invalid argument provided to helpers.dom.select2, $target must be jQuery with 1 element that is a select.', 1, {$target: $target});
                    return jQuery();
                }

                // attempt to extract object from json string, by decoding
                if (helpers.validation.validateSimple('string',overrideOptions)){
                    try {
                        overrideOptions = JSON.parse(overrideOptions);
                    } catch (e) {

                    }
                }

                // attempt to extract object from json string, by replacing ' with " and decoding
                if (helpers.validation.validateSimple('string',overrideOptions)){
                    try {
                        tmp = overrideOptions;
                        tmp = tmp.replace(new RegExp("'", 'g'),'"');
                        overrideOptions = JSON.parse(tmp);
                    } catch (e) {

                    }
                }

                // if we got object override options, lets use them otherwise use default
                if (helpers.validation.validateSimple('object', overrideOptions)) {
                    options = overrideOptions;
                }

                try {
                    $target.select2(options);
                } catch (e) {
                    helpers.debug.reportError('Can not init select2, exception thrown.', 1, {exception: e, $field: $target});
                    return jQuery();
                }

                return $target;
            };

            /**
             * Apply a global behaviour on the website
             * @param behaviour
             * @param options
             */
            product.applyGlobalBehaviour = function (behaviour, options) {
                var baseObject = we.getObject('base');

                // validate
                if ( ! baseObject.validateAndReport('propertyExists', globalBehaviourHandlers, 'behaviour not supported', 1, behaviour, behaviour)){
                    return product;
                }

                // default options
                if ( ! helpers.validation.validateSimple('object', options)) {
                    options = {};
                }

                // check ready, if not - run when ready
                if (weState.documentReady) {
                    globalBehaviourHandlers[behaviour](options);
                } else {
                    jQuery(document).ready(function () {
                        globalBehaviourHandlers[behaviour](options);
                    });
                }

                return product;
            };

            /**
             * Hide the message window
             */
            product.hideMessage = function () {

                $messageWindow.removeClass('active');
                setTimeout(function () {
                    $messageWindow.detach();
                }, 205);

            };

            /**
             * Message to show a message to the user
             * @param text
             * @param titleText
             * @param warningText
             * @param buttonText
             * @param abortButtonText
             * @param showAbortButton
             * @param abortButtonText
             * @param showAbortButton
             */
            product.messageUser = function (text, titleText, warningText, buttonText, abortButtonText, showAbortButton) {
                showAbortButton = typeof showAbortButton === 'boolean' ? showAbortButton : false;

                lazyCreateMessageModal();

                if (typeof text != 'string') {
                    text = '';
                    windowMessageElements.messageText.css('display', 'none');
                } else {
                    windowMessageElements.messageText.css('display', 'block');
                }

                if (typeof titleText != 'string') {
                    titleText = helpers.language.translate('MESSAGE_TITLE_GENERIC');
                }

                if (typeof warningText != 'string') {
                    warningText = '';
                    windowMessageElements.messageWarning.css('display', 'none');
                } else {
                    windowMessageElements.messageWarning.css('display', 'block');
                }

                if (typeof buttonText != 'string') {
                    buttonText = helpers.language.translate('MESSAGE_BUTTON_GENERIC_OK');
                }

                if (typeof abortButtonText != 'string') {
                    abortButtonText = helpers.language.translate('MESSAGE_BUTTON_GENERIC_ABORT');
                }

                if (showAbortButton) {
                    windowMessageElements.messageButtonAbort.removeClass(we.config.general.classes.hidden);
                } else {
                    windowMessageElements.messageButtonAbort.addClass(we.config.general.classes.hidden);
                }

                // populate texts
                windowMessageElements.messageTitle.html(titleText);
                windowMessageElements.messageText.html(text);
                windowMessageElements.messageWarning.html(warningText);
                windowMessageElements.messageButton.html(buttonText);
                windowMessageElements.messageButtonAbort.html(abortButtonText);
                $messageWindow.appendTo('body');

                $messageWindow.addClass('active');
                return product;
            };

            /**
             * Message the user. But return a promise which is fulfiled on confirm button and rejected on close
             * @param text
             * @param titleText
             * @param warningText
             * @param buttonText
             * @param abortButtonText
             * @param showAbortButton
             */
            product.messageUserPromise = function (text, titleText, warningText, buttonText,abortButtonText, showAbortButton) {
                product.messageUser(text, titleText, warningText, buttonText,abortButtonText, showAbortButton);

                return new Promise(function (fulfil, reject) {
                    windowMessageElements.messageButton.on('click', fulfil);
                    windowMessageElements.messageButtonAbort.on('click', reject);
                    windowMessageElements.messageClose.on('click', reject);
                });

            };


            /**
             * Bind call back for when a click is NOT on the element
             * @param $target
             * @param callback
             * @returns {boolean}
             */
            product.onClickOutOf = function ($target, callback) {

                if (helpers.validation.validateSimple('selector', $target)) {
                    $target = jQuery($target);
                }

                if ( ! helpers.validation.validateSimple('jQuery', $target)) {
                    helpers.dom.reportError('can not bind onClickOutOf, target must be selector or jQuery');
                    return false;
                }

                jQuery(document).click(function(event) {
                    let $eventTarget = jQuery(event.target);
                    if( ! $eventTarget.closest($target).length) {
                        callback();
                    }
                });

                return true;
            };

            /**
             * Method to show a spinner on the page
             * @param text - text to show
             */
            product.showSpinner = function (text) {
                // remove existing spinner if any

                $spinner.remove();

                if (typeof text !== 'string') {
                    text = '';
                }

                $spinner = jQuery(we.config.dom.spinnerMarkup)
                    .appendTo('body')
                    .addClass(classes.active);
                $spinner.find('[data-role="loading-text"]').html(text);

                return product;
            };

            // proxy
            product.showLoading = product.showSpinner;

            /**
             * Method to hide the spinner form the page
             */
            product.hideSpinner = function () {
                $spinner.remove();
                return product;
            };

            // proxy
            product.hideLoading = product.hideSpinner;

            /**
             * Method to scroll to an element
             * @param selectorOrJquery
             * @param considerHeader
             * @param speed
             * @param offsetInput - additional space
             */
            product.scrollBodyTo = function (selectorOrJquery, considerHeader, speed, offsetInput) {
                var $body       = jQuery('html, body'),
                    $el         = false,
                    offset      = offsetInput,
                    $header, headerHeight;

                // validate and default variables
                if (helpers.validation.validate('jQuery', selectorOrJquery, null, true)) {
                    $el = selectorOrJquery;
                }

                if (helpers.validation.validate('selector', selectorOrJquery, null, true)) {
                    $el = jQuery(selectorOrJquery);
                }

                if ( ! helpers.validation.validate('integer', offsetInput, null, true) ) {
                    if (typeof offsetInput != 'undefined') {
                        helpers.debug.reportError('helper dom scrollBodyTo - invalid offset argument. integer and undefined supported. defaulting to 0', 1, offsetInput);
                    }
                    offset = 0;
                }
                if ($el == false ) {
                    helpers.debug.reportError('can not scroll body to element, invalid input - must be selector or jQuery instance', 1, selectorOrJquery);
                }

                if (typeof considerHeader == 'undefined' || considerHeader !== true) {
                    considerHeader = false;
                }

                if ( ! helpers.validation.validate('integer', speed, null, true)) {
                    helpers.debug.reportError('scroll body to element speed must be integer. attempting to typecast', 1, speed);
                    speed = parseInt(speed);
                }


                // it is not possible to scroll to stuff with display none
                if ($el.css('display') === 'none') {
                    $el = $el.parent();
                }
                if (considerHeader) {
                    // calculate header size
                    $header         = jQuery('header').first();
                    headerHeight    = $header.outerHeight();
                    if (typeof headerHeight !== "number" || isNaN(headerHeight)) {
                        headerHeight = 0;
                    }
                    // animate
                    $body.stop().animate({ scrollTop: (+$el.offset().top + offset) - headerHeight + 1 }, (speed));

                    // while on the go - check for header size change and correct for it
                    setTimeout(function () { // correct for header height change 'on the go'
                        var headerHeightNow = $header.outerHeight();
                        if (typeof headerHeightNow !== "number" || isNaN(headerHeightNow)) {
                            headerHeightNow = 0;
                        }

                        if (headerHeightNow != headerHeight) {
                            $body.stop().animate({ scrollTop: (+$el.offset().top + offset) - headerHeightNow + 1 }, (speed /8));
                        }
                    }, ((speed/8)*7));
                    return product;
                }

                $body.stop().animate({ scrollTop: (+$el.offset().top + offset) }, speed);
                return product;
                //$el.offset().top - jQuery('.header').outerHeight()
                // midway - check if header thingy, and if so - recalculate with half the speed for animation
            };

            /**
             * Scroll the window to top
             */
            product.scrollToTop = function () {
                jQuery("html, body").animate({ scrollTop: 0 }, we.config.dom.scrollToTopSpeed);
            };

            /**
             * Bind events on a given element
             * Using data attributes, case elements to trigger events
             *
             * Relevant attributes on element
             * data-event           : name of event to trigger
             * data-event-type      : which dom event triggers the fireing of the we: event - default click
             * data-prevent-default : should we also prevent default. will present if attribute present regardless of value - default false
             * data-event-data      : data to go alongside the event                        - default null
             */
            product.bindEventByDomElement = function (selectorOrJquery) {
                var $el             = false,
                    type            = 'click',
                    preventDefault  = false,
                    eventData       = null,
                    eventName       = null,
                    eventExists     = false;

                if (helpers.validation.validate('jQuery', selectorOrJquery, null, true)) {
                    $el = selectorOrJquery;
                }

                if (helpers.validation.validate('selector', selectorOrJquery, null, true)) {
                    $el = jQuery(selectorOrJquery);
                }

                if ($el == false) {
                    helpers.debug.reportError('bindEventsInNamespace, invalid input - must be selector or jQuery instance', 1, selectorOrJquery);
                }

                // get variable overrides from dom and prepare.
                type            = (typeof $el.attr('data-event-type')      === 'undefined' ? type       : $el.attr('data-event-type'));
                preventDefault  =  typeof $el.attr('data-prevent-default') !== 'undefined';
                eventData       = (typeof $el.attr('data-event-data')      === 'undefined' ? eventData  : $el.attr('data-event-data'));
                eventName       =  (typeof $el.attr('data-event')          === 'undefined' ? eventName  : $el.attr('data-event'));

                if (eventName == null) {
                    helpers.debug.reportError('Can not bind event to dom element, element found but has no data-event attribute', 2, selectorOrJquery);
                    return product;
                }
                // attempt to json decode if event data is json
                try {
                    if (typeof eventData == 'string' && eventData.charAt(0) == '{'){
                        eventData = JSON.parse(eventData);
                    }
                } catch (e){

                }

                // no double binding!
                jQuery.each(events, function (irrelevantIndex, eventInfo) {
                    if (eventInfo.name == eventName && eventInfo.$el.is($el)) {
                        eventExists = true;
                        return false;
                    }
                });

                if (eventExists) {
                    return product;
                }
                $el.on(type, function (e) {
                    if (preventDefault == true) {
                        e.preventDefault();
                    }

                    helpers.event.fire(eventName, {originalEvent:e, eventData:eventData});
                });

                events[eventIndex] = {name:eventName,$el:$el};
                eventIndex = eventIndex + 1;

                return product;
            };

            /**
             * Bind events in namespace
             * Using data attributes, case elements to trigger events
             *
             * Relevant attributes on element
             * data-event           : name of event to trigger
             * data-event-type      : which dom event triggers the fireing of the we: event                                 - default click
             * data-prevent-default : should we also prevent default. will present if attribute present regardless of value - default false
             * data-event-data      : data to go alongside the event                                                        - default null
             */
            product.bindEventsInNamespace = function (selectorOrJquery) {
                var $el             = false;

                // validate and default selectorOrJquery
                if (helpers.validation.validate('jQuery', selectorOrJquery, null, true)) {
                    $el = selectorOrJquery;
                }

                if (helpers.validation.validate('selector', selectorOrJquery, null, true)) {
                    $el = jQuery(selectorOrJquery);
                }

                if ($el == false) {
                    helpers.debug.reportError('bindEventsInNamespace, invalid input - must be selector or jQuery instance', 1, selectorOrJquery);
                }

                $el.find('[data-event]').each(function (index, element) {
                    product.bindEventByDomElement(jQuery(element));
                });

            };

            // run scroll listener
            jQuery(window).on('scroll', function () {
                clearTimeout(scrollThrottle);
                scrollThrottle = setTimeout(function (){
                    var scrollTop = jQuery(window).scrollTop();
                    jQuery.each(scrollCallbackQueue, function (index, callable) {
                        try {
                            callable(scrollTop);
                        } catch(e) {

                        }
                    });
                }, scrollPollRate);
            });

            return product;
        })(),
        object      : (function () {
            var product = {};

            /**
             * Method to 'extend' objects
             * @param extendable
             * @param extending
             */
            product.extend = function (extendable, extending) {
                return jQuery.extend(true, extendable, extending);
            };

            /**
             * Method to duplicate object (not with reference)
             * @param source
             */
            product.duplicate = function (source) {
                return jQuery.extend(true, {}, source);
            };

            return product;
        })(),
        validation  : (function () {
            var product = {};

            /**
             * Object containing validation rule methods
             * @type {{numeric: numeric, string: string, selector: selector, jQuery: jQuery, object: Object, array: array, notNull: notNull, min: min, max: max}}
             */
            var validators = {
                numeric             : function (value) {
                    return typeof value == 'number';
                },
                integer             : function (value) {
                    return value === parseInt(value, 10);
                },
                string              : function (value) {
                    return typeof value == 'string';
                },
                selector            : function (value) {
                    try {
                        jQuery(value);
                        return true;
                    } catch (e) {
                        return false;
                    }
                },
                jQuery              : function (value) {
                    return value !== null && typeof value === 'object' && value instanceof jQuery;
                },
                object              : function (value) {
                    return typeof value == 'object' && value !== null;
                },
                array               : function (value) {
                    return value !== null && typeof value === 'object' && value.constructor === Array;
                },
                notNull             : function (value) {
                    return value !== null;
                },
                function            : function (value) {
                    var getType = {};
                    return value && getType.toString.call(value) === '[object Function]';

                },
                boolean             : function (value) {
                    return value === true || value === false;
                },
                propertyExists      : function (value, propName) {

                    if  ( ! helpers.validation.validate('string',propName, null, true ) ) {
                        helpers.debug.reportError('propertyExists validation expects argument propName to be string', 2, propName);
                        return false;
                    }

                    if  ( ! helpers.validation.validate('object',value, null, true ) ) {
                        return false;
                    }

                    return value.hasOwnProperty(propName);
                },
                stringLongerThan    : function (value, args) {
                    return typeof value == 'string' && value.length >= args;
                },
                min                 : function (value, args) {
                    if (we.config.debug.active) {
                        console.log('todo: min validation'); // todo remember to operate differently for numbers and strings, and to check for other strange types
                    }
                    return true;
                },
                max                 : function (value, args) {
                    if (we.config.debug.active) {
                        console.log('todo: max validation');  // todo remember to operate differently for numbers and strings, and to check for other strange types
                    }
                    return true;
                }
            };

            /**
             * Method to validate a value
             * @param rule    - how to validate this value (type)
             * @param value   - value to validate
             * @param args    - additional relevant params
             * @param simple  - will make this just return true/false instead of cool object
             */
            /**
             * Method to validate a value
             * @param rule    - how to validate this value (type)
             * @param value   - value to validate
             * @param additionalArgument    - additional relevant params
             * @param simple  - will make this just return true/false instead of cool object
             */
            product.validate = function(rule, value, additionalArgument, simple) {
                var itemResult = {
                    'valid'         : true,
                    'reason'        : '',
                    'relatedData'   : null
                };

                rule = rule.lcFirst();

                // check that validation method exists
                if ( ! validators.hasOwnProperty (rule)) {
                    itemResult.reason = 'Validation method does not exist';
                    itemResult.valid  = false;

                    if (simple) {
                        helpers.debug.reportError('helpers.validator.validate - Validation rule not found, returning false', 2, {rule:rule, value:value, args:additionalArgument, simple:simple});
                        return false;
                    }
                    return itemResult;
                }


                itemResult.valid = validators[rule](value, additionalArgument);

                // if we did not pass, make the whole thing not pass
                if (itemResult.valid !== true) {
                    itemResult.reson = 'failed validation rule - ' + rule;
                }

                if (typeof simple !== 'undefined' && simple === true) {
                    return itemResult.valid;
                }

                return itemResult;
            };

            /**
             * Simple validation method to just get true/false
             * @param rule
             * @param value
             * @param args
             */
            product.validateSimple = function (rule, value, args) {
                if (typeof args == 'undefined') {
                    args = null;
                }
                return product.validate(rule, value, args, true);
            };

            /**
             * Run several validations at once
             * @param object  - object with rules (indexs are rule types, and values can contain additional agruments
             * @param val
             */
            product.validateBatch = function (object, val) {
                var response = {
                    valid : true,
                    rules : {}
                };

                // validate object
                if (  typeof object !== 'object') {
                    helpers.debug.reportError('cant validateBatch, method must take an object as argument 1', 2, object);
                    return response;
                }

                jQuery.each(object, function (rule, arguments) {
                    var itemResult;


                    // type cast arguments
                    if (typeof arguments == 'undefined') {
                        arguments = null;
                    }


                    if ( ! validators['array'](arguments)) {
                        arguments = [arguments];
                    }

                    rule                    = rule.lcFirst();
                    itemResult              = product.validate(rule, val, arguments);


                    response.rules[rule]    = itemResult;
                    // if the validation did not pass, make the whole batch fail
                    if (itemResult.valid == false) {
                        response.valid = false;
                    }
                    // log this item in the result object regardless
                    response.rules[rule] = itemResult;
                });
                return response;
            };

            return product;
        })(),
        language    : (function () {
            var product = {};

            /**
             * Method to get the language tag
             */
            product.getTag = function () {
                return we.config.language['languageTag'];
            };

            /**
             * Get short version of language tag
             */
            product.getShortTag = function () {
                var longTag =  product.getTag();
                if (typeof longTag != 'string') {
                    //
                    helpers.debug.reportError("Can not get short langauge tag, getLanaugeTag did not return string\n[and this should never happen. Check your config", 2, longTag);
                    return 'he';
                }
                return  longTag.substr(0, 2);
            };

            /**
             *
             * @param key
             * @param arguments
             */
            product.translate = function (key, arguments) {
                var translation = '';
                if (weState.ready !== true) {
                    helpers.debug.reportError('NOTICE: attempting to translate before framework ready, will not work.', 1);
                }
                if ( ! helpers.validation.validate('object', we.config.language.translations)) {
                    helpers.debug.reportError('Language.translate - can not translate, dictionary is not object', 2, {'languageConfiguration': we.config.language});
                    return key;
                }

                if ( ! we.config.language.translations.hasOwnProperty(key)) {
                    helpers.debug.reportError('Language.translate - can not translate, key not found', 1, {'translations': we.config.language.translations});
                    return key;
                }

                // key found
                translation = we.config.language.translations[key];

                if ( typeof arguments !== 'object' || arguments === null) {
                    arguments = {};
                }

                jQuery.each(arguments, function (key, value) {
                    translation = translation.replace(':'+key, value);
                });

                return translation;
            };

            /**
             * Proxy for translate
             * @param key
             * @param arguments
             */
            product.t = function (key, arguments) {
                return product.translate(key, arguments);
            };

            return product;
        })(),
        user        : (function () {
            var product = {};

            product.get = function (key, defaultValue) {
                if (typeof defaultValue === 'undefined') {
                    defaultValue = null;
                }

                if ( ! helpers.validation.validateSimple('string', key)) {
                    helpers.debug.reportError('we.helpers.user Can not get user data - key is not string', 2, key);
                    return defaultValue;
                }

                if (we.config.user.hasOwnProperty(key)) {
                    return we.config.user[key];
                }

                return defaultValue;
            };

            return product;
        })(),
        route       : (function () {
            var product = {};

            // function to redirect with spinner
            function redirectWithSpinner (redirect) {
                helpers.dom.showSpinner();
                if (typeof redirect !== 'string'  || window.location.href === redirect) {
                    window.location.reload();
                    return;
                }

                window.location.href = redirect;
            }

            product.redirectWithSpinner = function (redirect, speed) {
                if (typeof speed === 'undefined') {
                    speed = 0;
                }

                setTimeout(function () {
                    redirectWithSpinner(redirect)
                }, speed);
            };


            /**
             * Nicely redirect a user to another page.
             * Texts are just given to dom.messageUser
             * @param redirect
             * @param text1
             * @param text2
             * @param text3
             * @param text4
             * @param delay - int, how long to show the window
             */
            product.niceRedirect = function (redirect, text1, text2, text3, text4, delay) {

                if (typeof text1 != 'string') {
                    text1 = we.translate('NICE_REDIRECT_DEFAULT_TEXT');
                }

                if (typeof text2 != 'string') {
                    text2 = we.translate('NICE_REDIRECT_DEFAULT_TITLE');
                }

                if (typeof text3 != 'string') {
                    text3 = null;
                }

                if (typeof text4 != 'string') {
                    text4 = null;
                }

                if (typeof delay != 'number') {
                    delay = 1500;
                }

                helpers.dom.messageUser(text1, text2, text3, text4);

                setTimeout(function () {
                    redirectWithSpinner(redirect)
                }, delay);
            };

            return product;
        })(),
        // cookie helper based on vendor js-cookie by  FagnerMartinsBrack
        cookie      : (function () {
            function extend () {
                var i = 0;
                var result = {};
                for (; i < arguments.length; i++) {
                    var attributes = arguments[ i ];
                    for (var key in attributes) {
                        result[key] = attributes[key];
                    }
                }
                return result;
            }

            function decode (s) {
                return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
            }

            function init (converter) {
                function api() {}

                function set (key, value, attributes) {
                    if (typeof document === 'undefined') {
                        return;
                    }

                    attributes = extend({
                        path: '/'
                    }, api.defaults, attributes);

                    if (typeof attributes.expires === 'number') {
                        attributes.expires = new Date(new Date() * 1 + attributes.expires * 864e+5);
                    }

                    // We're using "expires" because "max-age" is not supported by IE
                    attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

                    try {
                        var result = JSON.stringify(value);
                        if (/^[\{\[]/.test(result)) {
                            value = result;
                        }
                    } catch (e) {}

                    value = converter.write ?
                        converter.write(value, key) :
                        encodeURIComponent(String(value))
                            .replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);

                    key = encodeURIComponent(String(key))
                        .replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
                        .replace(/[\(\)]/g, escape);

                    var stringifiedAttributes = '';
                    for (var attributeName in attributes) {
                        if (!attributes[attributeName]) {
                            continue;
                        }
                        stringifiedAttributes += '; ' + attributeName;
                        if (attributes[attributeName] === true) {
                            continue;
                        }

                        // Considers RFC 6265 section 5.2:
                        // ...
                        // 3.  If the remaining unparsed-attributes contains a %x3B (";")
                        //     character:
                        // Consume the characters of the unparsed-attributes up to,
                        // not including, the first %x3B (";") character.
                        // ...
                        stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
                    }

                    return (document.cookie = key + '=' + value + stringifiedAttributes);
                }

                function get (key, json) {
                    if (typeof document === 'undefined') {
                        return;
                    }

                    var jar = {};
                    // To prevent the for loop in the first place assign an empty array
                    // in case there are no cookies at all.
                    var cookies = document.cookie ? document.cookie.split('; ') : [];
                    var i = 0;

                    for (; i < cookies.length; i++) {
                        var parts = cookies[i].split('=');
                        var cookie = parts.slice(1).join('=');

                        if (!json && cookie.charAt(0) === '"') {
                            cookie = cookie.slice(1, -1);
                        }

                        try {
                            var name = decode(parts[0]);
                            cookie = (converter.read || converter)(cookie, name) ||
                                decode(cookie);

                            if (json) {
                                try {
                                    cookie = JSON.parse(cookie);
                                } catch (e) {}
                            }

                            jar[name] = cookie;

                            if (key === name) {
                                break;
                            }
                        } catch (e) {}
                    }

                    return key ? jar[key] : jar;
                }

                api.set = set;
                api.get = function (key) {
                    return get(key, false /* read as raw */);
                };
                api.getJSON = function (key) {
                    return get(key, true /* read as json */);
                };
                api.remove = function (key, attributes) {
                    set(key, '', extend(attributes, {
                        expires: -1
                    }));
                };

                api.defaults = {};

                api.withConverter = init;

                return api;
            }

            return init(function () {});
        })(),
        mobile      : (function () {
            var product = {};

            /**
             * Using UA detection, determines if this is mobile
             * @returns {Array|{index: number, input: string}}
             */
            product.isMobile = function () {
                return (    navigator.userAgent.match(/Android/i)       ||
                    navigator.userAgent.match(/webOS/i)         ||
                    navigator.userAgent.match(/iPhone/i)        ||
                    navigator.userAgent.match(/iPad/i)          ||
                    navigator.userAgent.match(/iPod/i)          ||
                    navigator.userAgent.match(/BlackBerry/i)    ||
                    navigator.userAgent.match(/Windows Phone/i)     );
            };

            return product;
        })()
    };

    /**
     * We library objects. Eeach is instanciated on demand
     * @type {{Base: Base, Ajax: Ajax, Form: Form, Controller: Controller}}
     */
    var library = {
        /**
         * Base object with useful methods
         * @param config
         * @param extendable
         * @returns object
         * @constructor
         */
        Base : function (config, extendable) {
            var product = {
                config : {
                    selectors : {},
                    classes   : {}
                },
                $elements: {},
                state  : {

                },
                configValidationRules : {

                }
            };

            // extend the config
            product.config = helpers.object.extend(product.config, config);

            // list of events this object is expected to respond to.
            product.reactsTo = {};

            /**
             * Getter for object config
             * @returns {config|{}}
             */
            product.getConfig = function () {
                return product.config;
            };

            /**
             * Proxy for configure
             * @param object
             */
            product.setConfig = function (object) {
                product.configure(object);
            };

            /**
             * Translate a string using the language helper
             * @param key
             * @param arguments
             * @returns {*}
             */
            product.translate = function (key, arguments) {
                return helpers.language.translate(key, arguments);
            };

            /**
             * Proxy for translate
             * @param key
             * @param arguments
             */
            product.t = function(key, arguments) {
                return product.translate(key, arguments);
            };

            /**
             * Proxy for getting a helper via we.getObject
             * @param name
             * @returns {*}
             */
            product.getHelper = function (name) {
                return we.getObject(name, {}, 'helper');
            };

            /**
             * Proxy for product.getHelper
             * @param name
             */
            product.helper = function (name) {
                return product.getHelper(name)
            };

            /**
             * Proxy for getting an object via we.getObject
             * @param name
             * @param config
             * @returns {{}}
             */
            product.getObject = function (name, config) {
                return we.getObject(name, config, 'object');
            };

            /**
             * Proxy for product.getObject
             * @param name
             * @param config
             */
            product.object = function (name, config) {
                return product.getObject(name, config)
            };

            /**
             * Validate and safely set new configuration for the object
             * @param newConfig
             * @param chainable
             */
            product.configure = function (newConfig, chainable) {
                var response = {
                    'allAccepted' : true,
                    'items'       : {}
                };

                // validate object
                if (  typeof newConfig !== 'object') {
                    helpers.debug.reportError('cant set config, configure method must take an object as argument 1', 2, newConfig);
                    return response;
                }

                jQuery.each(newConfig, function (key, val) {
                    if (product.configValidationRules.hasOwnProperty(key)) { // check for validation rules - apply them

                        // check the rulse and validate
                        var rules = product.configValidationRules[key];
                        response.items[key] = helpers.validation.validateBatch(rules, val);

                        // validation did not pass
                        if (response.items[key].valid == false) {
                            response.allAccepted = false;
                            return true;
                        }

                        // validation passed
                        product.config[key] = val;
                    }

                    // no validation rules, just accept it
                    response.items[key]= {
                        rules : {},
                        valid : true
                    };


                    if (product.validateSimple('object', product.config[key])   &&
                        product.validateSimple('object', val)                   &&
                        product.config[key] != null                                 ) {
                        product.config[key] = product.helper('object').extend(product.config[key], val);
                    } else {

                        product.config[key] = val;
                    }


                });

                if (product.hasOwnProperty('afterConfigureCallback')) {
                    product.afterConfigureCallback();
                }

                if (typeof chainable == 'boolean' && chainable === true) {
                    return product;
                }
                return response

            };

            /**
             * Method to populate object variables with relevant elements,
             * so that we can interact with them easily.
             * All selectors become indexes in $elements, containing respective $elements
             */
            product.populateElements = function () {

                // optional automatic populating using the data-role="x" convension
                if (typeof product.config === 'object' && product.config.hasOwnProperty('namespace') && product.validateSimple('selector', product.config.namespace)){
                    jQuery(product.config.namespace).find('[data-role]').each(function (index, element) {
                        var $el  = jQuery(element), name;

                        try {
                            name = String($el.data('role')).hyphenToSmallCamel();
                        } catch(e) {
                            name = '-1';
                        }


                        product['$elements'][name] = $el;
                    });
                }

                jQuery.each(product.config.selectors, function (index, selector) {
                    product['$elements'][index] = jQuery(selector);
                });

                return product;
            };

            /**
             * Bind events from product.reactsTo, to respective handlers
             */
            product.autoBindToEvents = function() {
                //validate
                if ( ! product.hasOwnProperty('reactsTo') || typeof product.reactsTo != 'object') {
                    helpers.debug.reportError('can not bind automatically to the reactsTo events - the reactsTo property is missing or not object', 1, product);
                    return product;
                }

                jQuery.each (product.reactsTo, function (index, closure) {
                    var methodName = index + 'Handler';
                    if ( ! product.hasOwnProperty(methodName)){
                        helpers.debug.reportError('can not auto-bind event from reactsTo - handler missing (should be eventName + Handler) (capital H)', 1, index);
                        return true;
                    }

                    helpers.event.listen(index, product[methodName], closure);
                });
                return product;

            };

            /**
             * Method to validate a value, debug any failure, and return bool (is valid)
             * @param rule    - rule to validate by
             * @param value   - value to validate
             * @param message - message for the debugger on fail
             * @param level   - message error level for debugger on fail
             * @param data    - associated data for debugger on fail
             * @param validationArguments - arguments for the validation rule
             */
            product.validateAndReport = function (rule, value, message, level, data, validationArguments) {
                var valid, debuggerData;

                if (typeof data == 'undefined') {
                    data = {};
                }

                if (typeof validationArguments == 'undefined') {
                    validationArguments = null;
                }

                if (typeof message !== 'string') {
                    message = '';
                }

                if (typeof rule !== 'string') {
                    helpers.debug.reportError('can not validate and report this - rule input must be a string', 2, {rule:rule,value:value,message:message, level:level, data:data, validationArguments:validationArguments});
                    return false;
                }

                debuggerData = {
                    'rule' : rule,
                    'value' : value,
                    'callerData' : data
                };

                valid = helpers.validation.validate(rule, value, validationArguments, true);

                message = 'Validation rule: "' + rule + '" has failed for value - ' + value + "\ncaller message: "  + message;
                if ( ! valid) {
                    helpers.debug.reportError(message, level, debuggerData);
                }

                return valid;
            };

            /**
             * Delegates validation task to validation helper
             * @param rule
             * @param value
             * @param arguments
             * @param simple
             */
            product.validate = function (rule, value, arguments, simple) {
                return helpers.validation.validate(rule, value, arguments, simple);
            };

            /**
             * Proxy for validate where the simple argument = true
             * @param rule
             * @param value
             * @param arguments
             */
            product.validateSimple = function (rule, value, arguments) {
                return product.validate(rule, value, arguments, true);
            };

            /**
             * Quickly validate one rule - get bool answe
             */
            product.validateSimple = function (rule, value, data) {
                return helpers.validation.validate(rule, value, data, true);
            };

            /**             * Proxy for helpers.debug.reportError()
             * @param msg
             * @param level
             * @param data
             */
            product.reportError = function (msg, level, data) {
                return helpers.debug.reportError(msg, level, data);
            };

            return product;
        },

        /**
         * Ajax wrapper - it is the AJAX to AJAX. Get it? :D
         * @param config
         * @param extendable
         * @returns {{}}
         * @constructor
         */
        Ajax : function (config, extendable) {
            var product = we.getObject('Base', config);

            /**
             * Object that configures the ajax request
             * @type {{url: string, type: string, method: string, local: boolean, date: {}, expectWeResponse: boolean, success: success, error: null, closure: {}}}
             */
            product.ajaxRequest = {
                url                 : '',
                type                : 'post',
                method              : 'post',
                local               : true,
                data                : {},
                expectWeResponse    : true,
                success             : function (){},
                error               : null,
                closure             : {},
                hideSpinnerWhenDone : true,
            };

            /**
             * A factory method to generate standardized and flexible success callback for ajax requests
             * These wrap around requested success handlers provided with some validation or closure depending on settings
             */
            product.getSuccessHandler = function () {
                var enforceResponseStructure = function (response) {
                    var standardResponse = {
                            status: {
                                'code'      : -1,
                                'text'      : 'Invalid response - can not parse',
                                'message'   : 'Ajax object unable to standardize the response',
                                'isError'   : true
                            },
                            data : null
                        },
                        decoded;
                    try {
                        decoded = JSON.parse(response)
                    } catch (e) {
                        helpers.debug.reportError('WARNING: can not decode ajax respnse, but the ajax object was set to expect standard we response. Returning empty standard response to avoid exception',2,{ajaxObject:product, response:response});
                        return standardResponse;
                    }

                    if ( typeof decoded != 'object' || ! decoded.hasOwnProperty('status') || ! decoded.hasOwnProperty('data')){
                        helpers.debug.reportError('WARNING: can not parse ajax standard response - decoded, but not object or missing status/data properties.Returning empty standard response to avoid exception',2,{ajaxObject:product, response:response, decoded:decoded});
                        return standardResponse;
                    }

                    // all good
                    standardResponse.data   = decoded.data;
                    standardResponse.status = helpers.object.extend(standardResponse.status, decoded.status);
                    return standardResponse;
                };

                if (product.ajaxRequest.expectWeResponse) {
                    return function (data, textStatus, jXHR) {
                        product.ajaxRequest.success(enforceResponseStructure(data), textStatus, jXHR, product.ajaxRequest.closure);
                    };
                } else {
                    return function (data, textStatus, jXHR) {
                        product.ajaxRequest.success(data, textStatus, jXHR, product.ajaxRequest.closure);
                    };
                }

            };

            /**
             * Factory method to generate a standard error handler method, that wraps any requested error handler
             * @returns {Function}
             */
            product.getErrorHandler = function () {
                if (product.ajaxRequest.hasOwnProperty('error') && typeof product.ajaxRequest.error === 'function') {
                    return function (data, textStatus, jqXHR) {
                        helpers.debug.reportError('ajax error handler triggered', 1, {originalRequestObject: product.ajaxRequest, data:data,textStatus:textStatus, jqXHR:jqXHR});
                        product.ajaxRequest.error(data, textStatus, jqXHR, product.ajaxRequest.closure);
                    }
                } else {
                    return function (data, textStatus, jqXHR) {
                        helpers.debug.reportError('ajax error handler triggered', 1, {originalRequestObject: product.ajaxRequest, data:data,textStatus:textStatus, jqXHR:jqXHR});
                        helpers.dom.hideSpinner().messageUser(null, null, helpers.language.translate('ERROR_500_GENERIC'));
                    };
                }

            };

            /**
             * Method to set a key/property pair in the upcoming ajax call
             * @param key
             * @param value
             */
            product.set = function(key, value) {
                product.ajaxRequest[key] = value;
                return product;
            };

            /**
             * Set several properties or the ajax request at once, using an object
             * @param object
             * @returns {{}}
             */
            product.massSet = function (object) {
                // validate
                if ( ! helpers.validation.validate('object', object).valid) {
                    helpers.debug.reportError('Can not mass set ajax properties, input must be object', 2, object);
                    return product;
                }

                jQuery.each(object, function (key, value) {
                    product.set(key, value);
                });

                return product
            };

            /**
             * Method to execute an ajax request based on state
             *
             */
            product.execute = function () {
                var finalOptions = helpers.object.extend({}, product.ajaxRequest);

                // append root to url for local requests
                if (product.ajaxRequest.local) {
                    finalOptions.url = we.config.general.baseUrl + product.ajaxRequest.url;
                }

                finalOptions.success = product.getSuccessHandler();
                finalOptions.error   = product.getErrorHandler();

                if (typeof finalOptions.data != 'object') {
                    helpers.debug.reportError('Ajax execute - please set an OBJECT type data so we can include security in it. Skipping security expect errors.',2, {'optionsBeforeExecute':product.ajaxRequest});
                    return;
                }

                // handle formData, add security token
                if (finalOptions.data instanceof FormData) {
                    finalOptions.processData = false;
                    finalOptions.contentType = false;
                    finalOptions.data.append('weSecurityToken', we.config.security.token);
                } else {
                    finalOptions.data.weSecurityToken = we.config.security.token;
                }

                jQuery.ajax(finalOptions);

                return product;
            };

            /**
             * API similar to native fetch. instead of wrapping given callback, just returns a promise
             * @param url
             * @param data
             * @param options
             */
            product.fetchPromise = function (url, data, options) {
                var showSpinner = true, showAjaxErrorMessage = true;

                var executeAsPromise  = function () {
                    return new Promise(function (fulfil, reject) {
                        var success = function (data, textStatus, jXHR) {
                                if (showSpinner) {
                                    product.getHelper('dom').hideSpinner();
                                }

                                fulfil(data, textStatus, jXHR)
                            },
                            error   = function (response) {
                                if (showSpinner) {
                                    product.getHelper('dom').hideSpinner();
                                }

                                if (showAjaxErrorMessage) {
                                    product.getObject('ajax').getErrorHandler()();
                                }


                                reject(response);
                            };

                        product.set('success', success);
                        product.set('error', error);

                        if (showSpinner) {
                            product.getHelper('dom').showSpinner();
                        }

                        product.execute();
                    });
                };

                // setup the object with request details
                product.set('url', url);

                if (typeof data == 'undefined' || data == null) { // 'silent' type casting
                    data = {};
                }

                if (typeof data != 'object') { // if still no object then called did something wrong
                    product.reportError('FetchPromise - data needs to be object, defaulting it to empty object', 2, data);
                    data = {};
                }

                product.set('data', data);

                // todo: maybe support more options
                if (typeof options == 'object') {
                    // support the method option
                    if (options.hasOwnProperty('method')){
                        product.set('method', options['method']);
                    }

                    // support spinner
                    if (options.hasOwnProperty('showSpinner') && options.showSpinner === false){
                        showSpinner = false;
                    }

                    // support spinner
                    if (options.hasOwnProperty('showAjaxErrorMessage') && options.showAjaxErrorMessage === false){
                        showAjaxErrorMessage = false;
                    }
                }


                return executeAsPromise().then(function (response) {
                    // request passed, we must return promise, but we can just resolve it
                    if (response.status.code != 402) {
                        return Promise.resolve(response);
                    }

                    // perform with hard auth. explanation in end of method
                    return new Promise(function (fulfil, reject) {
                        we.getObject('hardAuth')
                            .initAsPromise(response.data)
                            .then(function () {
                                    executeAsPromise()
                                        .then(function (finalResult) {
                                            fulfil(finalResult)
                                        });
                                }
                            );
                    });

                    /*  -- Explanation for hard auth handling.
                         1. we create and return a promise, because our API requires it. this is the "top" promise. it is returned to whomever called fetchPromise()
                         2. inside, that, we do hard auth. Hard auth returns us a promise.
                         3. When that is fulfiled, we run the request again.
                             *note: Server makes sure we do not get 402 (the hard auth promised is confirmed after the server approves hard auth is ok)
                         4. Once the original request has passed, THEN we fulfil the top promise with it's response

                         This way, the whole hard auth thing is "transparent" to the caller. The caller get their promise. It is fulfilled when we have the response they expect.
                    */
                }); // end execute as promise

            }; // end fetchPromise method

            // end ajax object
            return product;
        },

        /**
         * Object to abstract representation and interaction with form elements
         * @param config
         * @param extendable
         * @returns {{}}
         * @constructor
         */
        Form : function (config, extendable) {
            var product = we.getObject('Base', config),
                fields, classes;

            product.fieldObjectTemplate = {
                'type'              : null,
                '$el'               : null,
                'value'             : null,
                'name'              : null,
                'subType'           : null,
                'previousValue'     : null,
                'tab'               : null
            };
            product.state['$form']       = null;
            product.state.formAttributes = {};
            product.state.fields         = {};
            product.state.buttons        = {};
            product.state.ready          = false;
            product.state.tabs           = {};

            //noinspection JSValidateTypes - php storm is just mad, ignore him, it's fine.
            product.config.animations = {
                'scrollToErrorSpeed' : 300
            };
            product.config.classes = {
                errorClass          : 'form-error',
                selected            : 'selected',
                opened              : 'open',
                active              : 'active',
                locationSpecified   : 'js-location-specified',
                jsActive            : 'js-active',
                highlight           : 'js-highlight',
                disabled            : 'disabled'
            };

            product.config.imageResizeMimeTypes = {0:'image/jpeg', 1:'image/png'};

            product.configValidationRules = {
                classes: {
                    object : null
                },
                errorClassOn :{
                    string : null
                },
                wrapperSelector : {
                    selector : null
                },
                errorElementClass : {
                    string : null
                },
                scrollToErrors : {
                    boolean : null
                }
            };

            // extend config
            product.config.scrollToErrors       = false;
            product.config.hideErrorsOnChange   = true;
            product.config.errorClassOn         = 'input';
            product.config.errorElementClass    = 'error-message';
            product.config.subSelectors = {
                dropDown : {
                    container             :   '[data-behaviour="dropdown-container"]',
                    input                 :   '[data-behaviour="dropdown-input"]',
                    button                :   '[data-behaviour="dropdown-button"]',
                    placeHolder           :   '[data-behaviour="dropdown-placeholder"]',
                    list                  :   '[data-behaviour="dropdown-list"]',
                    listItems             :   '[data-behaviour="dropdown-list-item"]',
                    listItemActivator     :   '[data-behaviour="dropdown-list-item-activator"]'
                }
            };

            fields  = product.state.fields;
            classes = product.config.classes;


            /**
             * Is this form ready for use
             * @returns {boolean|*|jQuery.ready|ready}
             */
            var isReady = function () {
                return product.state.ready;
            };

            /**
             * Log a debug message if the form is not ready for ust
             * @param msg
             */
            var logIfNotReady = function (msg) {
                if ( ! isReady()) {
                    helpers.debug.reportError('To perform this action, form object must have been initialized', 2, msg);
                }
            };

//TODO: refactor this this works nice but it is 200 lines almost - break into several sub methods
            // TODO and remember that this can be overriden!
            /**
             * Containes a method that can set a visual error message to a field
             * @param field
             * @param text
             */
            var showErrorHandler = function (field, text) {
                var errorTemplate = '<span class="'+product.config.errorElementClass+'" data-role="error-message">||TEXT||</span>',
                    fieldObject, $error, $errorTarget, $requestedErrorTarget, errorTargetSelector, errorPosition;

                if ( ! product.state.fields.hasOwnProperty(field)){
                    helpers.debug.reportError('can not set error because the field argument given is not in the fields of this form', 1, {field:field,form:product});
                    return;
                }

                $error = jQuery(errorTemplate.replace('||TEXT||', text));
                fieldObject = product.state.fields[field];

                switch (product.config.errorClassOn) {
                    case 'wrapper':
                        fieldObject['$wrapper'].addClass(product.config.classes.errorClass);
                        break;
                    case 'input':
                        fieldObject['$el'].addClass(product.config.classes.errorClass);
                        break;
                    default:
                        helpers.debug.reportError('showErrorHandler: errorClassOn config key of the form is not supported, defaulting to "input"',1,{form:form, errorClassOn : product.config.errorClassOn})
                        fieldObject['$el'].addClass(product.config.classes.errorClass);
                        break;
                }

                // radio has multiple elements and we don't want to show the error on all of them
                if (fieldObject.type == 'radio') {
                    $errorTarget = jQuery(fieldObject['$el'][0]);
                } else {
                    $errorTarget = fieldObject['$el'];
                }

                // tab error indication
                if (fieldObject.tab != null) {
                    product.state.tabs[fieldObject.tab].$control.addClass(product.config.classes.errorClass);
                }

                // now for the simple input check if it has directions for error message position
                errorTargetSelector = $errorTarget.attr('data-displays-error-on');
                if (typeof errorTargetSelector == 'undefined') {
                    $error.insertAfter($errorTarget);
                    return;
                }

                // carefully handle the "element command" for error message location
                $error.addClass(classes.locationSpecified);

                // validate the error position selector
                if ( ! helpers.validation.validate('selector', errorTargetSelector, null, true)) {
                    product.reportError('Can not place error message where element has requested, data-displays-error-on must be selector',1, {form:product,field:fieldObject,element:fieldObject['$el']});
                    $error.insertAfter($errorTarget);
                    return;
                }

                $requestedErrorTarget = jQuery($errorTarget.parents($errorTarget.attr('data-displays-error-on')).first());

                if ( ! helpers.validation.validate('jQuery', $requestedErrorTarget, null, true) || $requestedErrorTarget.length < 1) {
                    product.reportError('Can not place error message where element has requested, data-displays-error-on selected no parents',1, {form:product,field:fieldObject,element:fieldObject['$el']});
                    $error.insertAfter($errorTarget);
                    return;
                }

                // log error element on the field
                product.state.fields[field]['$errorElement'] = $error;

                // check the error element position before we switch the target
                errorPosition = $errorTarget.attr('data-element-error-position');

                // the error element will go on whatever the field determined with it's data attribute
                $errorTarget  = $requestedErrorTarget;

                // check the position:
                if (typeof errorPosition == 'string' && errorPosition == 'before') {
                    $error.insertBefore($errorTarget);
                } else {
                    $error.insertAfter($errorTarget);
                }



            };

            /**
             * Contains a method that can remove an already set message dom element from a field
             * @param field
             */
            var hideErrorHandler = function (field) {
                var errorMessageSelector = '[data-role="error-message"]',
                    $area, $errorElements;

                if ( ! product.state.fields.hasOwnProperty(field)){
                    helpers.debug.reportError('can not remove error because the field argument given is not in the fields of this form', 2, {field:field,form:product});
                    return;
                }

                // remove error class
                switch (product.config.errorClassOn) {

                    case 'wrapper':
                        product.state.fields[field]['$wrapper'].removeClass(product.config.classes.errorClass);
                        break;

                    case 'input':
                        product.state.fields[field]['$el'].removeClass(product.config.classes.errorClass);
                        break;

                    default:
                        helpers.debug.reportError('hideErrorHandler: errorClassOn config key of the form is not supported, defaulting to "input"',1,{form : product, errorClassOn : product.config.errorClassOn});
                        product.state.fields[field]['$el'].removeClass(product.config.classes.errorClass);
                        break;

                }

                // find and remove error elements
                $area = product.state.fields[field]['$el'];
                if (product.config.errorClassOn == 'wrapper') {
                    $area.add(product.state.fields[field]['$wrapper']);
                }

                $errorElements = $area.find(errorMessageSelector).add($area.siblings(errorMessageSelector));
                $errorElements.remove();

                // in case $errorElement is not there, and was logged on the field object
                try {
                    product.state.fields[field]['$errorElement'].remove();
                    product.state.fields[field]['$errorElement'] = jQuery();
                } catch (e) {

                }

            };

            /**
             * Allows to set how this form shows error messages by replacing the showErrorHandler method
             * @param method
             */
            product.setShowErrorHandler = function (method) {
                var isFunction =  typeof method === "function";

                if (isFunction) {
                    showErrorHandler = method;
                } else {
                    helpers.debug.reportError('can not set showErrorHandler on form because argument provided is not a function', 2, method);
                }

                return product;
            };

            /**
             * Allows to set how this form hides error messages by replacing the hideErrorHandler method
             * @param method
             */
            product.setHideErrorHandler = function (method) {
                var isFunction =  typeof method === "function";
                if (isFunction) {
                    hideErrorHandler = method;
                } else {
                    helpers.debug.reportError('can not set showErrorHandler on form because argument provided is not a function', 2, method);
                }

                return product;
            };

            /**
             * This encapsulates form field object structure so that we can extend it easily later
             */
            product.getEmptyFieldObject = function () {
                return product.helper('object').extend({}, product.fieldObjectTemplate);
            };

            /**
             * Method to bind advanced features to change event, beyon listening to value
             * @param fieldObject
             */
            product.bindAdditionalChangeBehaviours = function (fieldObject) {
                var $target, targetText;
//todo: this is a little ugly and not to the level a framework should be. refactor when we get the chance
// todo: this includes the addFieldByDomElement - break the switch into functions
                // these conditions depend on existance of $el
                if (fieldObject['$el'] == null) {
                    return;
                }

                if (typeof fieldObject['$el'].attr('data-is-condition') != 'undefined') {
                    targetText  = fieldObject['$el'].attr('data-condition-target');
                    $target     = product.state['$form'].find('[data-condition-target="'+targetText+'"]');

                    fieldObject['$el'].on('change mouseup focusout keyup', function () {

                        // for checkbox and radio
                        setTimeout(function () {
                            var condition = fields[fieldObject.name].value == true; //temp condition - check if "truthy" (ie checked, not empty not 0
                            if (condition) {
                                $target.addClass(classes.active);
                            } else {
                                $target.removeClass(classes.active);
                            }
                        }, 10);
                    });
                }
            };

            /**
             * Add a radio type field
             * @param $field
             * @param wrapperSelector
             * @return {} || boolean if new or false if existing
             */
            var addRadioFieldByDomElement = function ($field, wrapperSelector) {
                var fieldObject = product.getEmptyFieldObject(),
                    name = $field.attr('name'),
                    val  = $field.val();

                // if exists, append the additional radio input, and check if it is checked (change value)
                if (product.state.fields.hasOwnProperty(name)){

                    product.state.fields[name]['$el'] = product.state.fields[name]['$el'].add($field);

                    if ($field.prop('checked') == true) {
                        product.state.fields[name]['value'] = val;
                    }
                    return false;
                }

                fieldObject.type            = 'radio';
                fieldObject.subType         = 'radio';
                fieldObject.name            = name;
                fieldObject.value           = ($field.prop('checked') ? val : null);
                fieldObject['$el']          = $field;
                fieldObject['$wrapper']     = (wrapperSelector === null ? fieldObject['$el'] : fieldObject['$el'].parent(wrapperSelector));

                bindFieldBehaviourByDomElement($field, fieldObject);

                return fieldObject;
            };

            /**
             * Add a checkbox type field
             * @param $field
             * @param wrapperSelector
             * @return Object
             */
            var addCheckboxFieldByDomElement = function ($field, wrapperSelector) {
                var fieldObject = product.getEmptyFieldObject();
                fieldObject.type            = 'checkbox';
                fieldObject.subType         = 'checkbox';
                fieldObject.value           = $field.prop('checked');
                fieldObject.name            = $field.attr('name');
                fieldObject['$el']          = $field;
                fieldObject['$wrapper']     = (wrapperSelector === null ? fieldObject['$el'] : fieldObject['$el'].parent(wrapperSelector));

                bindFieldBehaviourByDomElement($field, fieldObject);

                return fieldObject;
            };

            /**
             * Add a select type field
             * @param $field
             * @param wrapperSelector
             * @return Object
             */
            var addSelectFieldByDomElement = function ($field, wrapperSelector) {
                var fieldObject = product.getEmptyFieldObject();

                fieldObject.type            = 'select';
                fieldObject.subType         = 'select';
                fieldObject.value           = $field.val();
                fieldObject.name            = $field.attr('name');
                fieldObject['$el']          = $field;
                fieldObject['$wrapper']     = (wrapperSelector === null ? fieldObject['$el'] : fieldObject['$el'].parent(wrapperSelector));

                bindFieldBehaviourByDomElement($field, fieldObject);

                return fieldObject;
            };

            /**
             * Add a input (text, email, tel etc) type field
             * @param $field
             * @param wrapperSelector
             * @return Object
             */
            var addInputFieldByDomElement = function ($field, wrapperSelector) {
                var fieldObject = product.getEmptyFieldObject();

                fieldObject.type            = 'input';
                fieldObject.subType         = $field.attr('type');
                fieldObject.value           = $field.val();
                fieldObject.name            = $field.attr('name');
                fieldObject['$el']          = $field;
                fieldObject['$wrapper']     = (wrapperSelector === null ? fieldObject['$el'] : fieldObject['$el'].parent(wrapperSelector));

                bindFieldBehaviourByDomElement($field, fieldObject);

                return fieldObject;
            };

            /**
             * Add a textarea type field
             * @param $field
             * @param wrapperSelector
             * @return Object
             */
            var addTextareaFieldByDomElement = function ($field, wrapperSelector) {
                var fieldObject = product.getEmptyFieldObject();

                fieldObject.type            = 'textarea';
                fieldObject.subType         = '';
                fieldObject.value           = $field.val();
                fieldObject.name            = $field.attr('name');
                fieldObject['$el']          = $field;
                fieldObject['$wrapper']     = (wrapperSelector === null ? fieldObject['$el'] : fieldObject['$el'].parent(wrapperSelector));

                bindFieldBehaviourByDomElement($field, fieldObject);

                return fieldObject;
            };

            /**
             * Methods to support the form behaviours
             *
             * @type {{ajaxAutoComplete: ajaxAutoComplete}}
             */
            product.behaviours = {

                /**
                 * Binder to apply ajax auto complete behaviour
                 *
                 * Supported configuration values:
                 * - field - copy of the value passed into us in the field variable
                 * - route - where we get the ajax results, passed into the AJAX object
                 * - sendFields - array or object with name of fields, if present - these are all sent with the request, else, we send the current field
                 * - dataNames  - object where key is name of field being sent and value is override name which will be the key in the request query
                 *  @param field
                 * @param configuration
                 * @returns {boolean}
                 */
                ajaxAutoComplete : function (field, configuration){
                    var lengthThreshold         = 2,
                        resultCount             = 5,
                        intervalDelay           = 400,
                        $options                = jQuery(),
                        cachedOptions           = {},
                        currentOptionIndex      = 0,
                        totalOptions            = 0,
                        $displayElement         = jQuery('<div class="we-auto-complete-container hidden-by-default" data-role="auto-complete-container"></div>'),
                        displayOptionTemplate   = '<span class="we-auto-complete-option" data-value="||VALUE||">||LABEL||</span>',
                        throttle, route;

                    /**
                     * Handler for keypress
                     * @param e
                     */
                    var keyHandlerForArrowNavigation = function (e) {
                        if (e.keyCode != 40 && e.keyCode != 38) {
                            return;
                        }

                        switch (e.keyCode) {
                            case 40 :
                                if (currentOptionIndex < (totalOptions - 1)) {
                                    currentOptionIndex = currentOptionIndex + 1 ;
                                }
                                break;

                            case 38 :
                                if (currentOptionIndex > 0) {
                                    currentOptionIndex = currentOptionIndex - 1;
                                }
                                break;
                            default:
                                return;
                        }

                        // un-highlight options, then highlight the option we need (but not the option we deserve)
                        $options.removeClass(classes.highlight);
                        jQuery(cachedOptions[currentOptionIndex]['$el']).addClass(classes.highlight);
                    };

                    /**
                     * Attempt to select the current option
                     */
                    var tryToSelectCurrentOption= function () {
                        if ( ! cachedOptions.hasOwnProperty(currentOptionIndex)) {
                            return;
                        }

                        product.setValue(field.name, cachedOptions[currentOptionIndex].data.label);
                        $displayElement.removeClass(product.config.classes.jsActive);
                        fields[field.name]['$el'].trigger('change');
                    };

                    /**
                     * Update display options by an array
                     * @param options
                     */
                    var updateDisplayOptions = function (options) {
                        // reset the options on larger scope
                        $options            = jQuery();
                        cachedOptions       = {};
                        currentOptionIndex  = -1;
                        totalOptions        = 0;

                        $displayElement.empty();

                        jQuery.each(options, function (index, option) {
                            var $option = jQuery(displayOptionTemplate.replace('||VALUE||', option.value).replace('||LABEL||', option.label));
                            $option.appendTo($displayElement);

                            $option.on('click', function (e) {
                                product.setValue(field.name, option.label);
                                $displayElement.removeClass(product.config.classes.jsActive);
                                fields[field.name]['$el'].trigger('change');
                            });

                            totalOptions         = totalOptions +1;
                            $options             = $options.add($option);
                            cachedOptions[index] = {'$el': $option, 'data' : option};
                        });

                        // toggle autocomplete container visibility
                        if (totalOptions > 0) {
                            $displayElement.addClass(product.config.classes.jsActive);
                        } else {
                            $displayElement.removeClass(product.config.classes.jsActive);
                        }

                        // unbind previous handler see next line
                        jQuery(field.$el).add($options).off('keyup', keyHandlerForArrowNavigation);

                        // bind arrow navigation again
                        jQuery(field.$el).add($options).on('keyup', keyHandlerForArrowNavigation);
                    };


                    /**
                     * Method to show the results once the ajax has returned
                     */
                    var ajaxSuccessCallback = function (result, textStatus, jqHXR) {
                        if (result.status.code != 200 ){
                            product.reportError('ajax auto complete returned, and status is not 200.', 2, {result:result, formObject:product, methodField:field, methodConfiguration:configuration});
                            return;
                        }

                        if (typeof result.data != 'object' && result.data != null) {
                            product.reportError('ajax auto complete returned, status is 200 but data is not an object/array', 2, {result:result, formObject:product, methodField:field, methodConfiguration:configuration});
                            return;
                        }

                        updateDisplayOptions(result.data);
                    };

                    /**
                     * Prepare the data for the ajax request to get auto complete values
                     * @returns {*}
                     */
                    var getDataForAjaxRequest = function () {
                        var data = {resultCount : resultCount}, value;
                        if (configuration.hasOwnProperty('sendFields')) {
                            jQuery.each (configuration.sendFields, function (index, fieldName) {
                                value = product.getValue(fieldName);
                                data[fieldName] = value;
                            });

                        } else {
                            data[field.name] = field['$el'].val();
                        }


                        if ( ! configuration.hasOwnProperty('dataNames')) {
                            return data
                        }


                        // validate
                        if ( ! product.validateAndReport('object', configuration.dataNames, 'Can not rename autocomplete request variables, dataNames present in configuration, but is not object', 2, configuration)) {
                            return false;
                        }

                        // loop
                        jQuery.each(configuration.dataNames, function (originalName, newName) {
                            // skip if something is invalid
                            if ( ! product.validateAndReport('string', originalName, 'Can not rename autocomplete request variable,the dataNames object must have string as key', 2, {configuration:configuration, badKey:originalName}) ||
                                ! product.validateAndReport('string', newName, 'Can not rename autocomplete request variable,the dataNames object must have string as value', 2, {configuration:configuration, badKey:newName})             ){
                                return true;
                            }

                            data[newName] = data[originalName];
                        });

                        return data
                    };

                    /**
                     * Method to obtains ajax auto complete for the field
                     */
                    var getAjaxAutoCompleteOptions = function () {
                        var data = getDataForAjaxRequest(),
                            value;


                        product.object('ajax')
                            .set('url', configuration.route)
                            .set('data', data)
                            .set('success', ajaxSuccessCallback)
                            .execute();
                    };

                    /**
                     * Method to handle when the autocomplete field changes
                     */
                    var ajaxAutoCompleteEventHandler = function (e) {
                        var value = field['$el'].val();

                        // exclude arrow keys and enter
                        if (e.keyCode == 38 || e.keyCode == 40 || e.keyCode == 13) {
                            return;
                        }
                        if (value.length < lengthThreshold) {
                            return;
                        }

                        clearTimeout(throttle);
                        throttle = setTimeout(function () {
                            getAjaxAutoCompleteOptions();
                        }, intervalDelay);
                    };

                    if ( ! product.validateAndReport('object', field, 'Form.behaviours.ajaxAutoComplete - field argument must be a field. Make sure you passed a valid field name in configuration to Form.behaviour', 2, {field:field, configuration:configuration})) {
                        return false;
                    }

                    if ( ! configuration.hasOwnProperty('route')) {
                        product.reportError('form.behaviours.ajaxAutoComplete can not run, configuration is object but it does not have the "route" property that is required', 2, configuration);
                        return false;
                    }

                    if (field.type == 'checkbox' || field.type == 'select' || field.type == 'radio') {
                        product.reportError('form.behaviours.ajaxAutoComplete can not run, field was found but this type ('+field.type+') is not supported for ajax auto-complete (need to be "text-like")', 2, configuration);
                        return false;
                    }

                    // disable browser autocomplete
                    field['$el'].attr('autocomplete', 'off');

                    // append the container to the parent
                    $displayElement.appendTo(field['$el'].parent());

                    // bind auto complete fetch
                    field['$el'].on('change, keyup', ajaxAutoCompleteEventHandler);

                    // change enter key behavior to select option instead of submit form
                    field['$el'].on('keydown', function (e) {
                        if (e.keyCode == 13) {
                            e.preventDefault();
                            tryToSelectCurrentOption();
                        }
                    });
                    return true;
                },

                /**
                 * For this form, any change will project as an event. Will use config for event name. If not defined, will attempt to use data-role
                 * @param field
                 * @param configuration
                 * @returns {boolean}
                 */
                projectFieldChanges : function (field, configuration) {
                    var baseEventName   = 'genericForm',
                        eventNameSuffix = 'ValueChanged',
                        finalEventName   = '',
                        projectThrottle;

                    /**
                     * Set the event name for usage in the callback
                     * @returns {boolean}
                     */
                    var setEventName = function () {
                        var  rollAttribute;

                        if (    typeof configuration == 'object'            &&
                            configuration.hasOwnProperty('eventName')   &&
                            product.validateAndReport('string', configuration.eventName, 'Can not project form event to your event name, configuration has event name but it is not a string', 2, configuration)) {
                            baseEventName = configuration.eventName;
                        }

                        if (baseEventName == 'genericForm') {
                            rollAttribute = product.state.$form.data('role');

                            if (typeof rollAttribute == 'string') {
                                baseEventName = rollAttribute.hyphenToSmallCamel();
                            }
                        }

                        if (product.validateSimple('propertyExists', configuration, 'noEventNameSuffix') && configuration.noEventNameSuffix === true) {
                            eventNameSuffix = '';
                        }


                        finalEventName = baseEventName + eventNameSuffix;
                        return true;
                    };

                    var projectFieldChange = function (fieldName) {
                        helpers.event.fire(finalEventName, {'fieldName' : fieldName, formValues: product.getValues(), formObject : product});
                    };

                    /**
                     * Handler for when we need to project value changes
                     * @param e
                     * @param data
                     */
                    var projectFieldChangesHandler = function (e, data) {
                        var name = null;

                        if (e.hasOwnProperty('data') && typeof e.data == 'object' && e.data.hasOwnProperty('name')) {
                            name = e.data.name
                        }

                        if (name == null) {
                            try {
                                name = jQuery(e.target).attr('name');
                            } catch (e) {

                            }
                        }

                        if (name == null) {
                            product.reportError('Can not project form change event, because was not able to discern the field name', 2, {event: e});
                            return;
                        }

                        clearTimeout(projectThrottle);
                        projectThrottle = setTimeout(function () {
                            projectFieldChange(name);
                        }, 50);
                    };

                    setEventName();

                    jQuery.each(product.state.fields, function (index, field) {
                        field['$el'].on('change keyup', {'name':field.name}, projectFieldChangesHandler);
                    });

                    return true;
                },

                /**
                 * Allows the form to manipulate it's file inputs, and make smaller images.
                 * Those are appended to new form fields, to hold the data
                 **/
                resizeImages : function (fieldObject, configuration) {
                    var maxWidth    = 2000,
                        maxHeight   = 3000,
                        field       = fieldObject.$el[0],
                        parallelFieldName, $parallelField, tmp;

                    if (typeof(window.EXIF) !== 'function') {
                        product.reportError('Exif helper not found. Can not use form behaviour resizeImages. Check your configuration');
                        return false;
                    }

                    /**
                     * Create an input to hold file data for this field
                     */
                    function createParallelField () {
                        parallelFieldName = 'data_' + fieldObject.name;
                        $parallelField    = jQuery('<input type="hidden" name="'+parallelFieldName+'" />');

                        $parallelField.appendTo(product.state.$form);
                        product.addFieldByDomElement($parallelField);
                    }

                    /**
                     * Takes an image and returns as data a smaller version
                     * @param img
                     * @param type
                     * @returns {string}
                     */
                    function resizeImage(img, type, orientation) {
                        var canvas  = document.createElement('canvas'),
                            width   = img.width,
                            height  = img.height,
                            context = canvas.getContext("2d");

                        // set new sizes if image is too large.
                        if (width > height && width > maxWidth) {
                            height = Math.round(height *= maxWidth / width);
                            width = maxWidth;
                        }

                        if (height >= width && height > maxHeight) {
                            width = Math.round(width *= maxHeight / height);
                            height = maxHeight;
                        }

                        // default canvas size
                        canvas.width  = width;
                        canvas.height = height;

                        // if orientation is sideways, invert dimentions of canvas
                        switch (orientation) {
                            case 5:
                            case 6:
                            case 7:
                            case 8:
                                canvas.width  = height;
                                canvas.height = width;
                                break;
                            default:
                                break;
                        }

                        // rotate  canvas based on orientation (1 - no rotation)
                        switch (orientation) {
                            case 2:
                                context.transform(-1, 0, 0, 1, width, 0);
                                break;
                            case 3:
                                context.transform(-1, 0, 0, -1, width, height);
                                break;
                            case 4:
                                context.transform(1, 0, 0, -1, 0, height);
                                break;
                            case 5:
                                context.transform(0, 1, 1, 0, 0, 0);
                                //
                                break;
                            case 6:
                                context.transform(0, 1, -1, 0, height, 0);
                                //
                                break;
                            case 7:
                                context.transform(0, -1, -1, 0, height, width);
                                //
                                break;
                            case 8:
                                context.transform(0, -1, 1, 0, 0, width);
                                //
                                break;
                            default: break;
                        }

                        context.drawImage(img, 0, 0, width, height);

                        // return the new image as data
                        return canvas.toDataURL(type,0.7);
                    }

                    /**
                     * Method to process a file from a fiel field
                     * @param file
                     * @param targetInput
                     * @returns {Promise<any>}
                     */
                    function processFile(file, targetInput) {
                        return new Promise(function (fulfil, reject) {
                            var reader           = new FileReader(),
                                resizeableMime   = false,
                                mimeType         = file.type;

                            // check if this file is resizeable
                            jQuery.each(product.config.imageResizeMimeTypes, function (index, validType) {
                                if (validType === mimeType) {
                                    resizeableMime = true;
                                }
                            });

                            // if not, save it as base64 in its parallel input
                            if ( ! resizeableMime) {
                                reader.onload = function(fileLoadedEvent) {
                                    targetInput.value = fileLoadedEvent.target.result;
                                    fulfil();
                                };

                                reader.readAsDataURL(file);
                                return;
                            }

                            // this is a resizeable image, so we have to resize it before we save as base64
                            reader.onload = function (event) {
                                var blob, blobURL, image;
                                blob            = new Blob([event.target.result]);
                                window.URL      = window.URL || window.webkitURL;
                                blobURL         = window.URL.createObjectURL(blob);
                                image           = new Image();
                                image.src       = blobURL;

                                image.onload = function () {
                                    // get orientation
                                    EXIF.getData(file, function () {
                                        var orientation = orientation = EXIF.getTag(this, "Orientation");

                                        if (typeof orientation == 'undefined') {
                                            orientation = 1;
                                        }
                                        // now we can resize the image
                                        targetInput.value = resizeImage(image, file.type, orientation);
                                        fulfil();
                                    });

                                }
                            };

                            reader.readAsArrayBuffer(file);
                        });
                    }

                    /**
                     * Callback to be attached to the original field to resize it's images
                     * @param e
                     */
                    function originalFieldCallback (e) {
                        var files = fieldObject.$el[0].files,
                            resizeableMime = false,
                            file;

                        // if file not selected, clear the parallel field
                        if (files.length === 0) {
                            product.setValue(parallelFieldName, null);
                            return;
                        }

                        file = fieldObject.$el[0].files[0];

                        jQuery.each(product.config.imageResizeMimeTypes, function (index, validType) {
                            if (validType === file.type) {
                                resizeableMime = true;
                            }
                        });

                        if ( ! resizeableMime) {
                            return true;
                        }

                        // get shrinked image, populate the parallel field with shrinked image data, and then clear the original
                        processFile(file, $parallelField[0]).then(function (){
                            // allow chance for other scripts to react
                            setTimeout(function () {
                                fieldObject.$el.val(null);
                                product.getFieldValueFromDom(field.name);
                            }, 10);
                        });
                    }

                    // crate field to hold file data
                    createParallelField ();

                    // bind field
                    fieldObject.$el.on('change', originalFieldCallback);

                    return true;
                }
            };

            /**
             * Behaviours to be bound via the DOM
             * @type {{mask: mask}}
             */
            product.domBehaviours = {
                mask             : function ($field, configuration) {
                    // convert string input to object
                    if (typeof configuration !== 'object' || ! configuration.hasOwnProperty('pattern')) {
                        configuration = {'pattern':configuration.pattern};
                    }

                    // we need mask plugin
                    if ( ! jQuery.fn.mask) {
                        product.reportError('Can not implement mask behaviour, jQuery.mask plugin is not loaded.');
                        return false;
                    }

                    // run with placeholder if we have
                    if (configuration.hasOwnProperty('placeholder')) {
                        $field.mask(configuration.pattern, {'placeholder' : configuration.placeholder});
                        return true;
                    }

                    // run with no placeholder
                    $field.mask(configuration.pattern);
                    return true;
                },
                phoneMask   : function ($field, configuration) {
                    if ( ! jQuery.fn.mask) {
                        product.reportError('Can not implement phoneMask behaviour, jQuery.mask plugin is not loaded.');
                        return false;
                    }

                    //   $field.mask('050-0000000', {'placeholder' : '05x-xxxxxxx'}); // with placeholder
                    $field.mask('050-0000000', {'placeholder' : ''});
                },
                maxlength :  function ($field, configuration) {
                    //validate
                    if ( ! helpers.validation.validateSimple('string', configuration) ||
                        ! Number(configuration) == configuration                        ){
                        product.reportError('can not appy max length, your arguments attribute is invalid - should be a number-like string', 2, configuration);
                        return;
                    }

                    // handler for enforcing length
                    function enforceLength (e) {
                        var val = $field.val();

                        if ( val.length > configuration) {
                            $field.val(val.substr(0, configuration));
                        }
                    }

                    // bind to field
                    $field.on('keyup', enforceLength);
                },
                datepicker : function ($field, configuration) {
                    var onSelect = function () {
                        setTimeout(function () {
                            $field.trigger('change');
                        },1);
                    };
                    var finalConfig = product.helper('object').extend({
                        onSelect: onSelect
                    },we.config.datepicker);

                    if (helpers.validation.validateSimple('object', configuration)) {
                        finalConfig = helpers.object.extend(finalConfig, configuration);
                    }

                    $field.datepick(finalConfig);
                },
                select2     : function ($field, configuration) {
                    var $result = we.getHelper('dom').select2($field, configuration);

                    return helpers.validation.validateSimple('jQuery', $result) &&  $result.count == 1; // if non-empty jQuery is returned, it is successful
                },
                resizesImages : function ($field, configuration, fieldObject) {

                    if ( ! product.validateSimple('object', fieldObject)) {
                        product.reportError('can not bing resizesImages behaviour, fieldObject argument not provided or not object',2,fieldObject);
                        return false;
                    }

                    product.behaviours.resizeImages(fieldObject, null);
                }
            };

            /**
             * Get field object that matches the field element
             * @param el
             * @returns {*}
             */
            product.getFieldObjectByElement = function (el) {
                var resultField = null;

                jQuery.each(product.state.fields, function (index, field) {
                    if (field.$el.attr('name') === field.name) {
                        resultField = field;
                        return false;
                    }
                });

                return resultField;
            };

            /**
             * Method to activate cool form behaviours
             * @returns {{}}
             */
            product.behaviour = function (behaviourMethod, configuration) {
                var field = null, result;

                if ( ! product.behaviours.hasOwnProperty(behaviourMethod)) {
                    product.reportError('Form.behaviour - invalid behaviourMethod argument, not supported.', 2, behaviourMethod);
                    return product;
                }

                if ( ! product.validateAndReport('object', configuration, 'Form.behaviour - configuration argument must be and object', 2, configuration)){
                    return product;
                }

                if (configuration.hasOwnProperty('field')) {
                    field = configuration.field;
                }

                if (field != null && fields.hasOwnProperty(field)) {
                    field = fields[field];
                }

                result = product.behaviours[behaviourMethod](field, configuration);

                if ( result != true) {
                    product.reportError('Form.behaviour - behaviour failed to start, check your arguments', 2, {behaviourMethod:behaviourMethod, configuration:configuration});
                }
                return product;
            };

            /**
             * Bind field behavior on the field element based on data attributes
             * @param $el
             * @param fieldObject - may be ommited
             */
            var bindFieldBehaviourByDomElement = function ($el, fieldObject) {
                var behaviourProperty = $el.attr('data-field-behaviour'),
                    behaviours;

                if (typeof behaviourProperty == 'undefined' || behaviourProperty == null || behaviourProperty == '') {
                    return;
                }

                behaviours = behaviourProperty.split(',');

                jQuery.each(behaviours, function (index, behaviour) {
                    var behaviourData     = $el.attr('data-'+behaviour+'-arguments'),
                        finalBehaviorData = false;

                    try {
                        finalBehaviorData = JSON.parse(behaviourData);
                    } catch (e) {

                    }

                    // default to the original attribute if could not parse it as json
                    if (typeof finalBehaviorData != 'object') {
                        finalBehaviorData = behaviourData;
                    }

                    if ( ! product.domBehaviours.hasOwnProperty(behaviour)) {
                        product.reportError('We.From : Can not bind behaviour based on data attribute, no method handler found', 2, {$el:$el, behaviour:behaviour});
                        return true;
                    }

                    product.domBehaviours[behaviour]($el, finalBehaviorData, fieldObject )
                });
            };

            /**
             * Load a field by name
             * @param $field
             */
            product.addFieldByDomElement = function ($field) {
                var valid       = helpers.validation.validate('jQuery', $field).valid,
                    fieldObject, type, wrapperSelector, $el, errorOn;

                // validate and default some things
                if  ( ! valid) {
                    helpers.debug.reportError('Can not add field by dom element - not jQuery argument', 2, $field);
                }

                // select is not input so we better clear this up first
                if ($field.is('select')) {
                    type = 'select';
                } else {
                    type = $field.attr('type');
                }

                if ( ! helpers.validation.validate('selector', product.state.wrapperSelector).valid) {
                    wrapperSelector = null;
                } else {
                    wrapperSelector = product.state.wrapperSelector;
                }

                // switch type
                switch (type) {
                    case 'select':
                        fieldObject =  addSelectFieldByDomElement($field, wrapperSelector); // this is in the switch because of special function of radio
                        product.state.fields[fieldObject.name] = fieldObject;
                        break;

                    case 'checkbox':
                        fieldObject =  addCheckboxFieldByDomElement($field, wrapperSelector); // this is in the switch because of special function of radio
                        product.state.fields[fieldObject.name] = fieldObject;
                        break;
                    case 'textarea':
                        fieldObject =  addTextareaFieldByDomElement($field, wrapperSelector); // this is in the switch because of special function of radio
                        product.state.fields[fieldObject.name] = fieldObject;
                        break;
                    case 'radio':
                        fieldObject =  addRadioFieldByDomElement($field, wrapperSelector); // this is in the switch because of special function of radio
                        if (fieldObject === false) {
                            break;
                        }
                        product.state.fields[fieldObject.name] = fieldObject;
                        break;

                    default:
                        fieldObject =  addInputFieldByDomElement($field, wrapperSelector); // this is in the switch because of special function of radio
                        product.state.fields[fieldObject.name] = fieldObject;
                        break;
                }

                // we don't need the next actions in case this is a radio button from a set we already have (in which case fieldObject == false)
                if (fieldObject == false) {
                    return;
                }

                fieldObject.defaultValue = fieldObject.value;

                fieldObject.previousValue = fieldObject.value;

                product.bindAdditionalChangeBehaviours(fieldObject);

                product.assignTabToField(fieldObject);
            };

            /**
             * Assign tab value to a field
             * @param fieldObject
             */
            product.assignTabToField = function (fieldObject) {
                var $input = fieldObject.$el,
                    $potentialTab = $input.closest('[data-tab-body]'),
                    tabName;

                if ($potentialTab.length < 1) {
                    return product;
                }

                tabName = $potentialTab.data('tab-body');
                product.state.fields[fieldObject.name].tab = tabName;
            };

            /**
             * Load tabs into the state
             */
            product.populateTabsByState = function () {
                var $tabBodies = product.state['$form'].find('[data-tab-body]');

                jQuery.each($tabBodies, function (index, el) {
                    var $body       = jQuery(el),
                        context     = $body.data('tab-context'),
                        name        = $body.data('tab-body'),
                        $control    =  product.state['$form'].find('[data-tab-trigger="'+name+'"]');

                    product.state.tabs[name] = {
                        'context' : context,
                        'name'    : name,
                        '$control': $control,
                        '$body'   : $body
                    };
                });

            };

            /**
             * Load fields by the currently populated form dom element
             */
            product.populateFieldsByState = function () {
                var $fields = jQuery(product.state['$form']).find('input, select, textarea');
                jQuery.each($fields, function (index, element) {
                    product.addFieldByDomElement(jQuery(element));
                });

                return product;
            };

            /**
             * Find the buttons on the dom and add them to the form
             * @returns {{}}
             */
            product.populateButtons = function () {
                var $buttons = product.state['$form'].find('button');

                $buttons.each (function (index, element) {
                    var $button      = jQuery(element),
                        buttonObject = {'$button':$button, roll: null};

                    if ($button.is(['data-role'])) {
                        buttonObject.role = $button.data('role');
                    }

                    product.state.buttons[index] = buttonObject;

                });

                return product;
            };

            /**
             * Get the form buttons
             */
            product.getButtons = function () {
                return product.state.buttons;
            };

            /**
             * Pull the value of a field from the dom, by field name
             * @param name
             * @returns {*}
             */
            product.getFieldValueFromDom = function (name) {
                var $radios, $checked;
                // if radio - find the checked one, return
                switch (product.state.fields[name].type) {
                    case 'radio':
                        $radios = product.state.fields[name]['$el'];
                        $checked = $radios.filter(':checked');
                        return $checked.attr('value');
                        break;

                    case 'checkbox':
                        return product.state.fields[name]['$el'].prop('checked');
                        break;

                    default :// inputs, select, textarea
                        return product.state.fields[name]['$el'].val();
                        break;
                }
            };

            /**
             * Update a field from dom
             * @param name
             */
            var updateFieldFromDom = function (name) {
                product.state.fields[name].value         = product.getFieldValueFromDom(name);

                if (product.config.hasOwnProperty('changeEventName')) {
                    product.helper('event').fire(product.config.changeEventName, {form:product, data: product.getAllValues(), 'changedField' : name});
                }

                return product;
            };

            /**
             * Update all field values from dom
             */
            product.updateFieldsFromDom = function () {
                jQuery.each(product.state.fields, function (name, fieldObject) {
                    updateFieldFromDom(name);
                });

                return product;
            };

            /**
             * Generic callback for when a field is changed
             * @param e
             */
            product.fieldChangeCallback = function (e){
                var name = e.data.name;

                if ( ! product.state.fields.hasOwnProperty(name) ) {
                    helpers.debug.reportError('form.fieldChangeCallback - attempting to handle event, but event.data.name points to non-existing field - skipping',1, e);
                    return;
                }

                // some stuff we need to do before the timeout of radio and checkboxk
                product.state.fields[name].previousValue = product.state.fields[name].value;
                product.state.lastChangedField           = product.state.fields[name];

                // the dom may not actually reflect the checked property right away
                if (product.state.fields[name].type == 'radio' || product.state.fields[name].type == 'checkbox') {
                    setTimeout(function () {
                        updateFieldFromDom(name)
                    }, 1);
                } else {
                    updateFieldFromDom(name);
                }

                if (product.config.hideErrorsOnChange) {
                    product.hideError(name);
                }

            };

            /**
             * Method to bind to events that may change field values in this form
             */
            product.bindChangeEvents = function () {
                logIfNotReady('cant bind to form fields, form not ready');
                jQuery.each(product.state.fields, function (index, field) {

                    field['$el'].on('change mouseup focusout keyup', {'name':field.name}, product.fieldChangeCallback);
                });

                return product;
            };

            /**
             * Set what to do if this form submits
             *
             * Currently supported:
             * **event, triggetEvent
             *     Makes form trigger a WE event, instead of normal submission. form object and arges used as closure data passed
             *
             * ** customEvent
             *     Makes form trigger a CUSTOM WE event, instead of normal submission args[name] is mandatory
             *
             * ** none
             *     Prevents default, and replaces it with nothing else
             *
             * ** default
             *     Removes all bound submit actions, using .off
             *
             * @param action
             * @param args     - optional argument whos function changes based on action
             */
            product.setSubmitAction = function (action, args) {
                var closure = null, clearPrevious = false;

                if (product.validateSimple('propertyExists', args, 'closure')) {
                    closure = args.closure;
                }

                if (product.validateSimple('propertyExists', args, 'clearPrevious')) {
                    clearPrevious = args.clearPrevious;
                }

                if (clearPrevious === true) {
                    product.state['$form'].off();
                }

                logIfNotReady('Warning: setSubmission action may not work , form not ready');
                // if this gets too complecated, break into functions
                switch (action) {
                    case 'triggerEvent':
                    case 'event' :
                        product.state['$form'].on ('submit', function (e){
                            helpers.event.fire('formSubmission', {form:product,closure:args});
                            e.preventDefault();
                            return false;
                        });
                        break;
                    case 'customEvent' :
                        // validate: args must be object with an 'eventName' property of type string
                        if (  args == null || typeof args != 'object' || ! args.hasOwnProperty('eventName') || ! helpers.validation.validate('string', args['eventName']).valid ) {
                            helpers.debug.reportError('can not make form submit as custom event because the args must be an object with a string eventName property', 2 ,args);
                            break;
                        }
                        product.state['$form'].on ('submit', function (e){
                            helpers.event.fire(args['eventName'], {form:product,closure:args});
                            e.preventDefault();
                            return false;
                        });
                        break;
                    case 'callback' :
                        if (  args == null || typeof args != 'object' || ! args.hasOwnProperty('callback') ) {
                            helpers.debug.reportError('can not make form submit as callback because the args must be an object with a function type callback property', 2 ,args);
                            break;
                        }

                        product.state['$form'].on ('submit', function (e){
                            e.preventDefault();
                            args['callback'](product, product.getAllValues(), closure);
                        });
                        break;
                    case 'none' :
                        product.state['$form'].on ('submit', function (e){
                            e.preventDefault();
                        });
                        break;

                    case 'default' :
                        product.state['$form'].off();
                        break;
                    default:
                        helpers.debug.reportError('This action is not supported as a form submission action (form.setSubmitAction',2, action);
                        break;
                }

                return product;
            };

            /**
             * Method to hide all visible error messages
             */
            product.hideErrors = function () {
                jQuery.each(product.state.fields, function (index, field) {
                    hideErrorHandler(index);
                });

                // remove error classes from tabs
                jQuery.each(product.state.tabs, function (index, tab) {
                    tab.$control.removeClass(product.config.classes.errorClass);
                });

                return product;
            };

            product.hideError = function (fieldName) {
                jQuery.each(product.state.fields, function (index, field) {
                    if (field.name == fieldName) {
                        hideErrorHandler(index);
                        return false;
                    }

                });

                /* TODO: check if this was the last error in the tab, if so , remove error indicator from tab
                // remove error classes from tabs
                jQuery.each(product.state.tabs, function (index, tab) {
                    tab.$control.removeClass(product.config.classes.errorClass);
                    if (field.name == fieldName) {
                        hideErrorHandler(index);
                        return false;
                    }
                });*/

                return product;
            };

            /**
             * Method to show some error messages
             * @param errorObject  - if provided, will use this. else, use state
             * @param scrollTo     - should we scroll to the first error, default true, 'TOP' makes it scroll to top of form
             */
            product.showErrors = function (errorObject, scrollTo) {
                var firstErrorField = null;
                // TODO: allow to show errors by state if no errorObject is provided
                if (typeof errorObject != 'object') {
                    helpers.debug.reportError('can not show errors, argument must be object', 2, errorObject);
                    return product;
                }

                jQuery.each(errorObject, function (fieldName, text) {
                    showErrorHandler(fieldName, text);
                    if (firstErrorField === null) {
                        firstErrorField = fieldName;
                    }
                });

                // deal with scrolling
                switch (scrollTo) {
                    case false :
                        break;
                    case "top":
                        helpers.dom.scrollBodyTo(product.state['$form'], true, product.config.animations.scrollToErrorSpeed);
                        break;

                    case true:
                    default:
                        if (firstErrorField != null) {
                            try {
                                helpers.dom.scrollBodyTo(product.state.fields[firstErrorField]['$el'], true, product.config.animations.scrollToErrorSpeed, -50);
                            } catch (e) {
                                helpers.debug.reportError('Can not scroll to first error. Your first error probably relates to a field that does not exist', 1, {exception:e, input:errorObject, goTo:firstErrorField});
                            }
                        }
                        break;
                }

                return product;
            };

            /**
             * Method to get one of the form's attributes
             * @param name
             */
            product.getAttribute = function (name) {
                if ( !product.state.formAttributes.hasOwnProperty(name)) {
                    return null;
                }

                return product.state.formAttributes[name];
            };

            /**
             * Method to get the value of one field
             * @param name
             * @param withDataAttributes
             */
            product.getValue = function (name, withDataAttributes) {
                var returnObject =  {}, fieldObject, $selectedRadio;
                if ( ! product.state.fields.hasOwnProperty(name)) {
                    helpers.debug.reportError('Can not get form value - fields does not exist', 1, {form:product, requestedField:name});
                    return null;
                }

                if (typeof withDataAttributes !== 'undefined' && withDataAttributes === true) {
                    fieldObject        = product.state.fields[name];
                    returnObject.value = fieldObject.value;

                    if (fieldObject.type == 'radio') {
                        // multiple elements in $el
                        $selectedRadio = fieldObject['$el'].filter(':checked');
                        returnObject.data = $selectedRadio.data();
                    } else {
                        // single element in $el
                        returnObject.data = fieldObject['$el'].data();
                    }
                    return returnObject;
                }
                return product.state.fields[name].value;
            };

            /**
             * Proxy for get value
             * @param name
             */
            product.get = function (name) {
                return product.getValue(name);
            };

            /**
             * Does this form has a field with the given name
             *
             * @param name
             * @return boolean
             */
            product.hasField = function (name) {
                // validate string or number
                if ( ! helpers.validation.validate('string', name, null, true)  &&
                    ! helpers.validation.validate('integer', name, null, true)      ){
                    return false;
                }

                return fields.hasOwnProperty(name);
            };

            /**
             * Make a field show its value in it's respective drop down
             * @param fieldObject
             */
            product.indicateDropDownSelectionVisual = function (fieldObject) {
                if ( ! fieldObject.hasDropDown) {
                    return product;
                }

                jQuery.each(fieldObject.dropDown.$subElements.listItems, function (index, listItem) {
                    var $listItem = jQuery(listItem);

                    $listItem.removeClass(classes.selected);
                    if ($listItem.attr('data-value') == fieldObject.value) {
                        $listItem.addClass(classes.selected);
                        fieldObject.dropDown.$subElements.placeHolder.html( $listItem.attr('data-value-visual'));
                    }
                });

                return product;
            };

            /**
             * Method to set a value to a form field
             */
            product.setValue = function (name, value, ignoreMissing) {
                var fieldObject, radioFound;

                if (typeof ignoreMissing === 'undefined') {
                    ignoreMissing = true;
                }

                ignoreMissing = (ignoreMissing === true);

                // validate
                if ( ! product.hasField(name)) {
                    if ( ! ignoreMissing) {
                        helpers.debug.reportError('Form can not set value to this, field not found on form or invalid input', 1, {name:name, value:value});
                    }
                    return product;
                }

                fieldObject = fields[name];

                // do visual
                switch (fieldObject.type) {
                    case 'checkbox':
                        if ( ! helpers.validation.validate('boolean', value, null, true) &&
                            value !== 0                                               &&
                            value !== 1                                                  ) {
                            helpers.debug.reportError('Form set value - setting value to a checkbox expects boolean input. Type casting...', 1, {name:name, value:value});
                        }

                        value = Boolean(value);
                        fieldObject.$el.prop('checked', value);
                        fieldObject.value = value;

                        break;

                    case 'radio':
                        // for each, remove checkd. if corret value set on that radio, set checked true and note that
                        fieldObject.$el.each( function (index, radioElement) {
                            var $radioElement = jQuery(radioElement);
                            $radioElement.prop('checked', false);

                            if ($radioElement.val() == value) {
                                $radioElement.prop('checked', true);
                                radioFound = true;
                            }
                        });

                        if ( ! radioFound) {
                            helpers.debug.reportError("Form set value - you are setting a value for radio field, but there is no radio for this value\nSetting form state with the value, regardless.", 1, {name:name,value:value,form:product});
                        }

                        // change the value in the form object, regardless
                        fieldObject.value = value;
                        break;

                    case 'select':
                    case 'input':
                    case 'textarea':
                    default:
                        fieldObject.$el.val(value);
                        fieldObject.value = value;
                        break;
                }

                // adjust dropdown if needed
                if (fieldObject.hasDropDown) {
                    product.indicateDropDownSelectionVisual(fieldObject);
                }

                //adjust select2 if needed
                if (fieldObject.$el.hasClass("select2-hidden-accessible")) { // this is the way to do this check, from select2 documentation
                    fieldObject.$el.trigger('change.select2');
                }

                return product;
            };

            /**
             * Set several form values at once
             * @param obj
             * @param ignoreMissing
             */
            product.setManyValues = function (obj, ignoreMissing) {
                if ( ! product.validateAndReport('object', obj, 'setManyValues first argument should be object')){
                    return product;
                }

                // typecast ignoreMissing
                if (typeof ignoreMissing === 'undefined') {
                    ignoreMissing = true;
                }

                ignoreMissing = (ignoreMissing === true);
                jQuery.each(obj, function (index, value) {
                    product.setValue(index, value, ignoreMissing);
                });
                return product;
            };

            /**
             * Revert the last change in value
             * @param field - optional, the specific field to revert. Defaults to the last changed field
             * @returns {{}}
             */
            product.revertLastChange = function (field) {

                if (typeof field == 'undefined' || field === null) {
                    field = product.state.lastChangedField.name;
                }

                // find which field we need to work with
                if ( ! product.validateSimple('string', field)) {
                    product.reportError('Revert change: the field does not exist, defaulting to the last field that was changed', 2, field);
                    return product;
                }

                if ( ! product.validateSimple('propertyExists', product.state.fields, field)) {
                    product.reportError('unable to revert change, the field does not exist', 2, field);
                    return product;
                }

                product.setValue(field, product.state.fields[field].previousValue);

                if (product.state.fields[field].hasDropDown) {
                    product.state.fields[field].dropDown.updateVisual();
                }


                return product;

            };

            /**
             * Get the field that was last changed.
             * By copy, not by reference
             */
            product.getLastChangedField = function () {
                return helpers.object.extend({}, product.state.lastChangedField);
            };

            /**
             * Disable a field
             * @param name
             * @param ignoreMissing
             */
            product.disableField = function (name, ignoreMissing) {
                return product.enableField(name, false, ignoreMissing);
            };

            /**
             * Enable a field or disable it
             * @param name
             * @param val
             * @param ignoreMissing
             */
            product.enableField = function (name, val, ignoreMissing) {
                // typecast ignoreMissing
                if (typeof ignoreMissing === 'undefined') {
                    ignoreMissing = true;
                }

                ignoreMissing = (ignoreMissing === true);

                // typecast val
                val = (val === true);

                if ( ! fields.hasOwnProperty(name)) {
                    if ( ! ignoreMissing) {
                        product.reportError('Can not enable/disable field - not exists', 1, name);
                    }
                    return product;
                }


                fields[name].$el.prop('disabled', ! val);


                return product;
            };

            /**
             * Submit the form
             * @returns {{}}
             */
            product.submit = function () {
                product.state['$form'].trigger('submit');
                return product;
            };

            /**
             * Clear the form so that every field has unchecked or no value selected
             */
            product.clear = function () {
                jQuery.each(fields, function (index, field) {
                    // fields that are drop downs, we set to the first element, not to empty
                    if (product.isDropDownField(field.name)) {
                        product.setDropDownValue(field.name);
                        return product;
                    }

                    switch (field.type) {
                        case 'checkbox':
                            field.$el.prop('checked', false);
                            field.value = false;
                            break;

                        case 'radio':
                            // un-check all the radios
                            field.$el.each( function (index, radioElement) {
                                var $radioElement = jQuery(radioElement);
                                $radioElement.prop('checked', false);
                            });


                            field.value = '';
                            break;
                        case 'select': // selects need to be reset to their default value, if they allow something like "select one" / null - that will be the default anyways
                            field.$el.val(field.defaultValue);
                            field.value = field.defaultValue;
                            break;

                        case 'input':
                        default:
                            field.$el.val('');
                            field.value = '';
                            break;
                    }
                });
                return product;
            };

            /**
             * Reset the form to its initial state
             * @returns {{}}
             */
            product.reset = function () {
                if (we.config.debug.active) {
                    console.log('todo: Form.reset was never tested');
                }

                jQuery.each(fields, function (index, field) {
                    // fields that are drop downs, we set to the first element, not to empty
                    if (product.isDropDownField(field.name)) {
                        product.setDropDownValue(field.name);
                        return product;
                    }

                    product.setValue(field['name'], field['defaultValue']);

                    //make conditional elements update
                    product.state['$form'].find('[data-is-condition]"').trigger('change');
                });

                return product;
            };

            /**
             * Enable the form
             */
            product.enable     = function () {
                jQuery.each(product.state.buttons, function (index, buttonObject) {
                    buttonObject['$button'].attr('disabled', false);
                });
                product.state.$form.removeClass(classes.disabled);
                return product
            };

            /**
             * Disable the form
             */
            product.disable    = function () {
                jQuery.each(product.state.buttons, function (index, buttonObject) {
                    buttonObject['$button'].attr('disabled', true);
                });

                product.state.$form.addClass(classes.disabled);

                return product
            };

            /**
             * Is the form disabled
             */
            product.isDisabled = function () {
                var isEnabled = false;
                jQuery.each(product.state.buttons, function (index, buttonObject) {
                    if (buttonObject['$button'].is('[disabled')){
                        return true;
                    }

                    isEnabled = true;
                });

                return ! isEnabled;
            };

            /**
             * Is the form enabled
             * @returns {boolean}
             */
            product.isEnabled = function () {
                return ! product.isDisabled();
            };

            /**
             * Is a field a drop down?
             * @param name
             * @returns {boolean} false if not dropdown, or does not exist
             */
            product.isDropDownField = function (name) {
                if ( ! product.hasField(name)) {
                    return false;
                }

                return fields[name].hasOwnProperty('dropDown');
            };

            /**
             * Method to get the value of all fields
             */
            product.getAllValues = function () {
                var response = {};
                jQuery.each(product.state.fields, function (key, field) {
                    response[key] = field.value;
                });

                return response;
            };

            /**
             * Get all fields
             * @returns {*|string[]}
             */
            product.getFields = function () {
                return product.state.fields;
            };

            /**
             * Get reference to the dom element related to the field
             * @param name
             * @returns {*}
             */
            product.getFieldDomElement = function (name) {
                if ( ! product.hasField(name)) {
                    return false;
                }

                return product.state.fields[name].$el;
            };

            /**
             * Get possible value list for a field
             * @param name
             */
            product.getFieldOptions = function (name) {
                var field, result;

                if ( ! product.hasField(name)) {
                    return false;
                }

                field = product.state.fields[name];

                switch(field.type) {
                    case 'radio' :
                        result = {};

                        field.$el.each(function (index, el) {
                            var $el = jQuery(el);
                            result[index] = {'value' : $el.prop('value'), 'text':$el.text().trim()}

                        });
                        break;
                    case 'select':
                        result = {};

                        field.$el.find('option').each(function (index, el) {
                            var $el = jQuery(el);
                            result[index] = {'value' : $el.prop('value'), 'text':$el.text().trim()}
                        });

                        return result;
                    case 'checkbox':
                        return {'true':'true','false':'false'};
                    default:
                        result = '*';
                        return result;
                }
            };

            /**
             * Get jquery element associated with given field
             */
            product.getFieldElement = function (field) {
                if ( ! product.validateSimple('string', field, null) || ! product.hasField(field)) {
                    helpers.debug.reportError('getFieldElement - field argument invalid or field not found', 2, field);
                    return false;
                }

                return product.state.fields[field].$el;

            };

            product.getValues = product.getAllValues;
            product.getAll = product.getAllValues;

            /**
             * Make form trigger a WE event on the change of a field
             * @param fieldName  - name of the field that triggers the event
             * @param eventName  - custom name for the event
             * @param extraEventData - extra data for this event
             * @returns {{}}
             */
            product.projectFieldChanges = function (fieldName, eventName, extraEventData ) {
                var validName  = product.validateAndReport('string', fieldName, 'Can not project field changes invalid field name', 2, {fieldName:fieldName, eventName:eventName}),
                    validEvent = product.validateAndReport('string', eventName, 'Can not project field changes invalid event name', 2, {fieldName:fieldName, eventName:eventName});

                if (typeof extraEventData == 'undefined') {
                    extraEventData = {};
                }
                if ( ! validName || ! validEvent) {
                    return product;
                }

                if ( ! product.hasField(fieldName)) {
                    helpers.debug.reportError('can not project field event - field not found in form',2, {form:product, fields:fields, fieldName:fieldName});
                    return product;
                }
                logIfNotReady('cant project field changes, form not ready');

                fields[fieldName]['$el'].on('change mouseup focusout keyup', function () {
                    // checkbox and radio are annoying
                    setTimeout(function () {

                        helpers.event.fire(eventName, {
                            origin    : 'form.projectFieldChanges for ' + fieldName,
                            form      : product,
                            field     : fields[fieldName],
                            value     : product.getValue(fieldName),
                            extraData : extraEventData
                        });
                    }, 5);

                });


                return product;
            };

            /**
             * Init the form by a selector
             * @param selector
             * @return object
             */
            product.initBySelector = function (selector) {
                var isValidSelector = helpers.validation.validate('selector', selector).valid;
                if ( ! isValidSelector) {
                    helpers.debug.reportError('form.initBySelector - argument given must be valid selector',2, selector);
                    return product;
                }

                return product.initByDomElement(jQuery(selector));
            };

            /**
             * Set value for a dropDown field
             * @param name
             * @param value empty => set to default
             */
            product.setDropDownValue = function (name, value) {
                var valueFound = false;

                /**
                 * Function to do the "heavy lifting" of setting the field value
                 * @param field
                 * @param targetValueIndex
                 */
                var setDropDownValueFinal = function (field, targetValueIndex) {
                    var selectedItem = fields[name].dropDown.items[targetValueIndex],
                        $dropDownElements = field.dropDown['$subElements'],
                        value = selectedItem.value,
                        valueVisual = selectedItem.valueVisual,
                        $item = selectedItem['$el'];


                    $dropDownElements.placeHolder.html(valueVisual);

                    // manipulate list item classes
                    $dropDownElements.listItems.removeClass(product.config.classes.selected);
                    $item.addClass(product.config.classes.selected);

                    // close drop down
                    $dropDownElements.container.removeClass(product.config.classes.opened);

                    // update value
                    $dropDownElements.input.val(value);
                    field.value = value;
                    $dropDownElements.input.trigger('keyup');
                };

                // validate, set default for undefined value
                if ( ! product.isDropDownField(name)) {
                    helpers.debug.reportError('Can not set drop down value, this field does not exist ot is not a drop down field.', 2, {name:name, value:value});
                    return product;
                }

                if (typeof value == 'undefined') {
                    value = fields[name].dropDown['defaultValue'];
                }

                // check that this value actually exists
                jQuery.each(fields[name].dropDown.items, function (index, item) {
                    if (item.value == value) {
                        valueFound = index;
                        return false;
                    }
                });

                // set the value but default it if not found
                if (valueFound === false) {
                    helpers.reportError('Attempting to set drop down value to avalue that does not exist! setting default, instead', 2, {name:name, form:product, value:value, defaultValue:fields[name].dropDown['defaultValue']});
                    value = fields[name].dropDown['defaultValue'];
                }

                setDropDownValueFinal(fields[name], valueFound);
                return product;
            };

            /**
             * Method to init a dropdown for a form
             * @param $dropDownContainer
             * @returns {{}}
             */
            product.initDropDownByElement = function ($dropDownContainer) {
                var dropDownSelectors = product.config.subSelectors.dropDown,
                    items             = [],
                    $dropDownElements , name;

                /**
                 * Update the dropdown to reflect current Value
                 */
                var updateVisual            = function () {
                    var val       = $dropDownElements.input.val(),
                        $listItem = $dropDownElements.listItems.find('[data-value="'+val+'"]'),
                        $activator = $listItem.find(['[data-value-visual]']),
                        valueVisual = $activator.attr('data-value-visual');


                    // the previous value was forcefully set and does not have a matching LI
                    if (typeof valueVisual == 'undefined') {
                        valueVisual = '-';
                        $dropDownElements.placeHolder.html(valueVisual);
                        $dropDownElements.listItems.removeClass(product.config.classes.selected);
                        return;
                    }

                    // the previous value is "normal" and has a matching li
                    $dropDownElements.placeHolder.html(valueVisual);
                    $dropDownElements.listItems.removeClass(product.config.classes.selected);
                    $listItem.addClass(product.config.classes.selected);
                };

                /**
                 * Iterate through configured selectors, and use them to populate sub elements for the dropdown
                 */
                var populateDropDownSubElements = function () {
                    var $dropDownElements = {};
                    jQuery.each(dropDownSelectors, function (index, selector) {
                        var $subElement = $dropDownContainer.find(selector);
                        // if element found - add it
                        if ( $subElement.length > 0) {
                            $dropDownElements[index] = $subElement;
                            return true;
                        }

                        /* element not found. in some cases this is ok */
                        // container may be the outer most element
                        if (index == 'container') {
                            $dropDownElements[index] = $subElement;
                            return true;
                        }

                        // not all dropdown lists have 'activator'
                        if (index == 'listItemActivator') {
                            return true;
                        }

                        // element is missing - that is a problem.
                        helpers.debug.reportError('initDropDownByElement - sub element not found! Expect trouble. Check your HTML.',2,{$container:$dropDownContainer,subElementSelector:{index:index,selector:selector}});
                        $dropDownElements[index] = jQuery();
                    });
                    return $dropDownElements;
                };

                /**
                 * Method to bind dropDown open event
                 */
                var bindDropDownOpenEvent = function () {
                    // open drop down action - on all sub elements
                    $dropDownElements.button.add($dropDownElements.background).on('click', function ()  {
                        $dropDownContainer.addClass(product.config.classes.opened);
                    });
                };

                /**
                 * Bind a single drop down item for relevant events
                 *
                 * @param $listItem  (the list item itself)
                 * @param $activator (element to be bound to click)
                 */
                var bindDropDownItem = function ($listItem, $activator) {
                    $activator.on('click', function (e) {
                        var valueVisual = $listItem.attr('data-value-visual'), // todo find values
                            value       = $listItem.attr('data-value');        // todo find values

                        // support for elements that are links
                        e.preventDefault();

                        // populate place holder
                        $dropDownElements.placeHolder.html(valueVisual);

                        // manipulate list item classes
                        $dropDownElements.listItems.removeClass(product.config.classes.selected);
                        $listItem.addClass(product.config.classes.selected);

                        // close drop down
                        $dropDownContainer.removeClass(product.config.classes.opened);

                        // update value
                        $dropDownElements.input.val(value);

                        $dropDownElements.input.trigger('keyup');
                    });
                };

                var closeDropDown = function () {
                    $dropDownContainer.removeClass(product.config.classes.opened);
                };

                /**
                 *
                 */
                var bindBodyClickClose = function () {
                    jQuery('html, body').on('click', function (e) {
                        var contained  = e.originalEvent.target,
                            container  = $dropDownContainer[0];

                        if ( jQuery.contains(container, contained) !== true) {
                            closeDropDown();
                        }
                    });
                };

                // find drop down elements
                $dropDownElements = populateDropDownSubElements();

                // bind drop down elements
                bindDropDownOpenEvent();
                bindBodyClickClose();
                // iterate the list items
                $dropDownElements.listItems.each (function (index, listItem) {
                    var $listItem = jQuery(listItem),
                        $activator = $listItem.find(dropDownSelectors.listItemActivator);

                    // if we cant find an activator child, set activator to the list item iteself. activator is bound to the click events
                    if ($activator.length < 1) {
                        $activator = $listItem;
                    }

                    // bind them
                    bindDropDownItem($listItem, $activator);

                    // log them for later reference
                    items.push({
                        '$el'           : $listItem,
                        '$activator'    : $activator,
                        'value'         : $listItem.attr('data-value'),
                        'valueVisual'   : $listItem.attr('data-value-visual')
                    });
                });

                // log all this stuff in the field object
                name = $dropDownElements.input.attr('name');

                fields[name]['hasDropDown'] = true;
                fields[name]['dropDown'] = {
                    $subElements        : $dropDownElements,
                    items               : items,
                    defaultValue        : (typeof items[0] != 'undefined' ? items[0].value : ''),
                    defaultValueVisual  : (typeof items[0] != 'undefined' ? items[0].valueVisual : ''),
                    updateVisual        : updateVisual
                };

                return product;
            };

            /**
             * Find and init drop downs in this jQuery element
             * @param $el
             */
            product.enableDropDownsInNameSpace = function ($el){
                if ( ! helpers.validation.validate('jQuery',$el,null,true)) {
                    helpers.debug.reportError('can not enable drop down in namespace, argument not instance of jQuery', 2, $el);
                    return product;
                }

                $el.find(product.config.subSelectors.dropDown.container).each(function (index, element) {
                    product.initDropDownByElement(jQuery(element));
                });

                return product;
            };

            /**
             * Transforms the form into a standard ajax form. will run success on success and error on error using ajax object
             * Requires that action and method be set
             * @param success
             * @param error
             * @param showSpinner bool
             * @returns {{}}
             */
            product.ajaxify = function (success, error, showSpinner, asFormData) {
                // validate porperties
                if ( ! product.validateAndReport('propertyExists', product.state.formAttributes, 'Can not ajaxify, need action attribute', 2, product.state, 'action')) {
                    return product;
                }

                if ( ! product.validateAndReport('propertyExists', product.state.formAttributes, 'Can not ajaxify, need method attribute', 2, product.state, 'method')) {
                    return product;
                }

                asFormData  = asFormData  === true; // always false, unless set exactly to tr
                showSpinner = showSpinner === true;

                product.state['$form'].on ('submit', function (e){
                    var ajax = we.getObject('ajax'),
                        data;

                    if (showSpinner) {
                        product.helper('dom').showSpinner();
                    }

                    if (asFormData) {
                        data = new FormData(product.state.$form.get(0));
                    } else {
                        data = product.getAllValues()
                    }

                    ajax.fetchPromise(
                        product.state.formAttributes.action,
                        data,
                        {'method':product.state.formAttributes.method}
                    ).then(success);

                    e.preventDefault();
                    return false;
                });


                // bind
                // disable default
                // run ajax with the form data
                return product;
            };

            product.hasFileField = function () {
                var result = false;

                jQuery.each(product.state.fields, function (index, field) {
                    if (field.subType == 'file') {
                        result = true;
                        return false;
                    }
                });

                return result;
            };

            /**
             * Transforms the form into a standard ajax form. will run success on success and error on error using ajax object
             * Requires that action and method be set
             * Also automatically applies behaviour to response code other than 200-299
             * @param success
             * @param error
             * @param showSpinner bool
             * @param asFormData
             * @returns {{}}
             */
            product.ajaxifySmart = function (success, error, showSpinner, asFormData) {
                var successHandler = function (response) {
                    product.helper('dom').hideSpinner();
                    product.hideErrors();

                    if (response.status.code <300 && response.status.code > 199) {
                        product.hideErrors();
                        success(response);
                        return;
                    }

                    if (response.status.code == 422) {
                        product.showErrors(response.data, product.config.scrollToErrors);
                        return;
                    }

                    we.getObject('ajax').getErrorHandler()();

                };
                // validate porperties
                if ( ! product.validateAndReport('propertyExists', product.state.formAttributes, 'Can not ajaxify, need action attribute', 2, product.state, 'action')) {
                    return product;
                }

                if ( ! product.validateAndReport('propertyExists', product.state.formAttributes, 'Can not ajaxify, need method attribute', 2, product.state, 'method')) {
                    return product;
                }

                asFormData  = (asFormData  === true); // always false, unless set exactly to true
                showSpinner = ! (showSpinner === false); // always true, unless set exactly to false

                product.state['$form'].on ('submit', function (e){
                    var ajax = we.getObject('ajax'),
                        data;

                    product.updateFieldsFromDom();

                    if (asFormData) {
                        data = new FormData(product.state.$form.get(0));
                    } else {
                        data = product.getAllValues()
                    }

                    if (showSpinner) {
                        product.helper('dom').showSpinner();
                    }

                    ajax.fetchPromise(
                        product.state.formAttributes.action,
                        data,
                        {'method':product.state.formAttributes.method}
                    ).then(successHandler);
                    e.preventDefault();
                    return false;
                });

                // bind
                // disable default
                // run ajax with the form data
                return product;
            };

            /**
             * Init the form by an instance of jQuery
             * @param $element
             * @return object
             */
            product.initByDomElement = function ($element) {
                // validate
                var isValidElement = helpers.validation.validate('jQuery', $element).valid;
                if ( ! isValidElement) {
                    helpers.debug.reportError('form.initByDomElement - argument given must be and instance of jQuery',2, $element);
                    return product;
                }

                if ( ! $element.is('form')) {
                    helpers.debug.reportError('form.initByDomElement - argument given is jQuery but not a form',2, $element);
                    return product;
                }

                // make sure we are only working with one form
                if ($element.length > 1) {
                    helpers.debug.reportError('form.initByDomElement - element given selects several forms. Form object will use the first one, only.',2, $element);
                    $element = jQuery($element[0]);
                }

                // form element
                product.state['$form'] = $element;

                // form params
                jQuery.each($element[0].attributes, function () {
                    product.state.formAttributes[this.name] = this.value;
                });

                // setup buttons
                product.populateButtons();

                // setup all the fields
                product.populateFieldsByState();

                // setup pretty drop-downs
                product.enableDropDownsInNameSpace($element);

                // pouplate tabs
                product.populateTabsByState();

                // form object ready for all operations
                product.state.ready = true;

                // listen to change events
                product.bindChangeEvents();

                $element[0].weForm = product;
                return product;
            };

            // todo we should do product.configure(config) but now is a bad time - may cause unexpected errors
            return product;
        },

        /**
         * A "template" object for controllers
         * @param config
         * @param extendable
         * @returns {{}}
         * @constructor
         */
        Controller :  function (config, extendable) {
            var product = we.getObject('Base', config);
            var initOnReadyBound = false;



            /**
             * Method to init the controller.
             */
            product.init = function () {
                return product;
            };

            /**
             * Bind init method to document ready, if not already bound
             */
            var bindInitOnReady = function () {
                if (initOnReadyBound) {
                    return;
                }
                jQuery(document).ready(function () {
                    product.init();
                });
                initOnReadyBound = true;
            };

            // What to do after the product is configured
            product.afterConfigureCallback = function () {
                if (product.config.hasOwnProperty ('initOnReady') && product.config.initOnReady === true) {
                    bindInitOnReady();
                }
            };

            product.configure(config);

            return product;
        },

        /**
         * Object that manages hard auth operations
         * @param config
         * @param extendable
         * @returns {{}}
         * @constructor
         */
        HardAuth : function (config, extendable) {
            var product = we.getObject('Base', config),
                $elements, state, form;

            product.state = {
                form    : null
            };

            product.$elements = {

            };

            product.formConfig = {
                // if we want in future
            };

            $elements = product.$elements;
            state     = product.state;
            form      = product.state.form;
            /**
             * Find and save references to hard auth elements
             * Requires that product.$elements.container be defined
             */
            product.populateHardAuthElements = function () {
                // find form
                $elements.form = $elements.container.find('[data-role="hard-auth-form"]').first();

                // find close
                $elements.closeButton = $elements.container.find('[data-role="we-modal-close"]').first();

                return product;
            };

            /**
             * Creates the hard auth form object
             * Requires elements to be pouplated (to find th eform element)
             */
            product.createForm = function () {
                product.state.form = we.getObject('form', {});
                product.state.form.configure(product.formConfig);
                product.state.form.initByDomElement($elements.form);
            };

            /**
             * binds hard auth events
             * Requires that hard auth elements are defined
             */
            product.bindHardAuthEvents = function (fulfil, reject) {
                // form submit action
                product.state.form.ajaxifySmart(function (){
                    $elements.container.remove();
                    fulfil();
                });

                // close
                $elements.closeButton.on('click', function () {
                    $elements.container.remove();
                    product.getHelper('dom').hideSpinner();
                    product.getHelper('dom').messageUser(product.translate('HARD_AUTH_CANCEL_EXPLAIN'), product.translate('HARD_AUTH_CANCEL_TITLE'));
                    // notice: we do not reject. because then callers must provide handlers
                });
            };

            /**
             * Setup hard auth and return a promise representing it
             * @param serverHardAuthData
             * @returns {Promise}
             */
            product.initAsPromise = function (serverHardAuthData) {
                // validation and error handling. must be object with layoutContent property that is non-empty string
                if ( ! product.validateSimple('propertyExists', serverHardAuthData, 'layoutContent') ||
                    ! product.validateSimple('string', serverHardAuthData.layoutContent) ||
                    serverHardAuthData.layoutContent == '') {
                    product.helper('dom').hideSpinner();
                    product.helper('dom').messageUser(null, product.translate('HARD_AUTH_DISPLAY_ERROR_TITLE'), product.translate('HARD_AUTH_DISPLAY_ERROR_EXPLAIN'));
                    return new Promise(function (fulfil, reject) {});
                }

                product.$elements.container = jQuery(serverHardAuthData.layoutContent);
                product.$elements.container.appendTo('body');

                product.populateHardAuthElements();
                product.createForm();

                state.promise =  new Promise(function (fulfil, reject) {
                    product.bindHardAuthEvents(fulfil, reject);
                });

                return state.promise;
            };

            return product;
        },

        /**
         * Model base "class"
         * @param config
         * @param extendable
         * @returns {{}}
         * @constructor
         */
        Model : function (config, extendable) {
            var product = we.getObject('Base', config);


            /**
             * Fetch data method scafold
             * @param state
             * @param args
             * @returns {Promise}
             */
            product.fetch = function (state, args) {
                return new Promise(function (fulfil, reject) {
                    product.reportError('Model does nothing, please extend it and then assign as a model', 1, product);
                    fulfil({});
                });
            };
            return product;
        },

        /**
         * Object to manipulate specific areas of them dom as components
         * @param constructorConfig
         * @param extendable
         * @returns {{}}
         * @constructor
         */
        DomReflector : function (constructorConfig, extendable) {
            var product = we.getObject('Base', constructorConfig),
                nextCallbackName = 0,
                state, config, selectors, extendedConfig;

            // default config and validation rules
            product.defaultConfig         = {
                'namespace'   : '',
                twoWayBinding : true,
                selectors     : {
                    'node'                  : '[data-dr-item]',
                    'domReflectorContainer' : '[data-dr-container]'
                },
                dataAttrNames : {
                    'containerName' : 'dr-container',
                    'name' : 'dr-item',
                    'type' : 'dr-type'
                },
                classes       : {
                    'loading' : 'js-loading'
                }
            };
            product.configValidationRules = {
                namespace       : {
                    selector : null,
                    string   : null
                },
                twoWayBinding   : {
                    boolean : null
                }
            };

            // extend base object state
            product.defaultState = {
                $el             : jQuery(),
                $spinner        : null,
                nodes           : {},
                init            : false,
                domChangesBound : false,
                changeCallbacks : {
                    'default' : function () {
                        product.helper('event').fire('domReflectorChanged', {reflector:product, values:product.getAll(), name:state.name})
                    }
                }
            };

            product.defaultNodeObject =  {
                '$el'           : jQuery(),
                'type'          : null,
                'name'          : '',
                'manipulator'   : null,
                'state'         : null
            };
            product.state = helpers.object.extend(product.state, product.defaultState);

            // shortcuts
            state       = product.state;
            config      = product.config;

            // object that can read/write nodes
            product.manipulators = {
                text : {
                    read: function ($el) {
                        return $el.text();
                    },
                    write: function ($el, arg) {
                        $el.text(arg);
                        return true;
                    }
                },
                html : {
                    read: function ($el) {
                        return $el.html();
                    },
                    write: function ($el, arg) {
                        $el.html(arg);
                        return true;
                    }
                },
                select: {
                    read: function ($el) {
                        var $options = $el.find('option'),
                            result = {
                                'options' : [],
                                'value'   : null
                            };
                        $options.each(function (index, el){
                            var option = {};
                            option.text     = el.text;
                            option.value    = el.value;
                            option.selected = el.selected;

                            if (option.selected ) {
                                result.value = option.value;
                            }
                            result.options.push(option);
                        });

                        return result;
                    },
                    write: function ($el, arg) {
                        $el.find('option').remove();
                        jQuery.each(arg.options, function (index, optionData){
                            var $option = jQuery('<option value=""></option>');
                            $option.attr('value', optionData.value);
                            $option.text(optionData.text);

                            $el.append($option);
                        });

                        $el.val(arg.value);
                    }
                },
                activeBoolean : {
                    read: function ($el) {
                        return $el.hasClass('active');
                    },
                    write: function ($el, arg) {
                        if (arg) {
                            $el.addClass('active');
                        } else {
                            $el.removeClass('active');
                        }
                        return true;
                    }
                }
            };

            // models - ways we can update ourselves
            product.models = {
                ajax : (function () {
                    var model = we.getObject('model');

                    // overload fetch
                    model.fetch = function (reflector, state, args) {
                        return new Promise(function (fulfil, reject) {
                            var success = function (result, jqHXR, closure) {
                                    product.indicateNotLoading();
                                    if (result.status.code < 300 && result.status.code > 199) {
                                        fulfil(result, jqHXR, closure);
                                    } else {
                                        reject(result, jqHXR, closure);
                                    }

                                },
                                error   = function (response) {
                                    product.indicateNotLoading();
                                    reject(response);
                                };

                            if ( ! product.validateSimple('propertyExists', state, 'attributes') ||
                                ! product.validateSimple('propertyExists', state.attributes, 'drUrl')){
                                product.reportError('Unable to obtain url. state argument must have attributes property which is an object with a drUrl property',2, {model:model,state:state});
                                reject('Unable to obtains ajax url');
                                return;
                            }

                            product.indicateLoading();
                            we.getObject('ajax')
                                .set('url', state.attributes.drUrl)
                                .set('data', product.getHelper('object').extend(reflector.getAll(), args))
                                .set('success', success)
                                .set('error', error)
                                .execute();
                        });
                    };
                    return model;
                })()
            };

            /**
             * Indicate product is not loading
             */
            product.hideLoading = function () {
                product.alertIfNotInit('caller: hide/show loading');
                state.$el.removeClass(config.classes.loading);
                return product;
            };

            /**
             * Indicate that the product is loading
             * @param arg (false -> not loading
             */
            product.showLoading = function (arg) {
                if (typeof arg !== 'undefined' && arg === false) {
                    return product.hideLoading();
                }

                product.alertIfNotInit('caller: hide/show loading');
                state.$el.addClass(config.classes.loading);
                return product;
            };

            // proxies
            product.indicateLoading    = product.showLoading;
            product.indicateNotLoading = product.hideLoading;

            /**
             * Method to produce a debug warning if the product is not ready
             * @param message
             */
            product.alertIfNotInit  = function (message) {
                if ( ! helpers.validation.validate('string', message, [], true)) {
                    message = '-';
                }
                if ( ! product.state.init) {
                    product.reportError('Notice: dom reflector not initiated but this operation requires init.\n caller message: ' + message, 2, product);
                }

                return product;
            };

            /**
             * Get an empty object representing a node
             * @returns {{$el: *, type: null, name: string}}
             */
            product.getEmptyNodeObject = function () {
                return helpers.object.extend({}, product.defaultNodeObject);
            };

            /**
             * Get a manipulator
             * @param name
             * @returns {null}
             */
            product.getManipulator = function (name) {
                if (product.manipulators.hasOwnProperty(name)) {
                    return product.manipulators[name];
                }

                return null;
            };

            /**
             * Add your own cool manipulator (manipulators are used to read and write onto dom elements
             * @param name
             * @param manipulator
             * @returns {{}}
             */
            product.addManipulator = function (name, manipulator) {
                // name must be string
                if ( ! product.validateAndReport('string', name, 'can not add manipulator, name argument must be string', 2)){
                    return product;
                }

                // manipulator must be object
                if ( ! helpers.validation.validate('object', manipulator, [], true) || ! manipulator.hasOwnProperty('read') || ! manipulator.hasOwnProperty('write')){
                    helpers.debug.reportError('can not add manipulator, argument must be object with read and write properties', 2, manipulator);
                    return product;
                }

                // read and write must be functions
                if ( ! helpers.validation.validate('function', manipulator.read, [], true) || ! helpers.validation.validate('function', manipulator.write, [], true)){
                    helpers.debug.reportError('can not add manipulator, argument must be object with read and write properties WHICH ARE FUNCTIONS', 2, manipulator);
                    return product;
                }
                // arg must have read and write properties which are functions

                product.manipulators[name] = manipulator;
                return product;
            };

            /**
             * Add several manipulators at once
             * @param obj
             */
            product.addManipulators = function (obj) {
                if ( ! product.validateSimple('object', obj) && ! product.validateSimple ('array', obj)) {
                    helpers.debug.reportError('Can not add manipulators, first argument must be array or object', 1, {argument:obj});
                    return product;
                }

                jQuery.each (obj, function (name, manipulator) {
                    product.addManipulator(name, manipulator);
                });

                return product;
            };

            /**
             * Populate a single node
             * @param $node
             * @returns {{}}
             */
            product.populateNode = function ($node) {
                var result = product.getEmptyNodeObject();
                if ( ! product.validateAndReport('jQuery', $node, 'Can not puplate node - not jQuery input', 2, $node)){
                    return product;
                }

                result.name = $node.data(config.dataAttrNames.name);
                result.type = $node.data(config.dataAttrNames.type);
                result.manipulator = product.getManipulator(result.type);
                result.$el  = $node;

                // validate that node has a name
                if ( ! helpers.validation.validate('string', result.name, [], true ) || result.name == ''){
                    product.reportError('Can not populate node, name attribute has no value (data-dr-item)', 2, $node);
                    return product;
                }

                // make sure we have manipulator for this node type
                if ( ! product.validateAndReport('object', result.manipulator, 'Can not populate node- manipulator not supported', 2, $node)){
                    return product;
                }

                state.nodes[result.name] = result;
                return product;
            };

            /**
             * Update state from dom
             */
            product.updateStateFromDom = function () {
                product.alertIfNotInit('caller: update state from dom');
                jQuery.each (product.state.nodes, function (index, node) {
                    node.state = node.manipulator.read(node.$el);
                });
            };

            /**
             * Update state to the dom
             */
            product.updateStateToDom = function () {
                product.alertIfNotInit('caller: update state from dom');
                jQuery.each (product.state.nodes, function (index, node) {
                    node.state = node.manipulator.write(node.$el, node.state);
                });
            };

            /**
             *
             * @param input
             * @param name
             * @returns {{}}
             */
            product.registerCallback = function (input, name) {
                if ( ! product.validateAndReport('function', input, null, 'caller: registerCallback')) {
                    return product;
                }

                if (typeof name !== 'string') {
                    name = nextCallbackName;
                    nextCallbackName = nextCallbackName + 1;
                }

                state.changeCallbacks[name] = input;

                return product;
            };

            /**
             * Execute state change callbacks
             */
            product.executeChangeCallbacks = function () {
                jQuery.each(state.changeCallbacks, function (name, callback) {
                    callback({reflector:product, values:product.getAll(), name:state.name});
                });
            };

            /**
             * Set state for a node
             * @param nodeName
             * @param newState
             * @param settings
             */
            product.set = function (nodeName, newState, settings) {
                // validate node name
                var node;
                if ( ! helpers.validation.validate('string', nodeName, [], true) || ! state.nodes.hasOwnProperty(nodeName)){
                    helpers.debug.reportError('Can not set node state, invalid node argument (not string or does not exists', 2, {nodeName:nodeName, state:state});
                    return product;
                }
                node =  state.nodes[nodeName];

                // update state
                node.state = newState;

                // update dom
                node.manipulator.write(node.$el, node.state);

                if (product.validate('propertyExists', settings, 'executeCallbacks', true) && settings.executeCallbacks == true) {
                    product.executeChangeCallbacks();
                }

                return product;
            };

            /**
             * Set several nodes at once
             * @param object
             * @param settings
             * @returns {{}}
             */
            product.massSet = function (object, settings) {
                if ( ! product.validateAndReport('object', object, 'Can not mass set -invalid argument', 2)){
                    return product;
                }

                jQuery.each(object, function (key, value) {
                    product.set(key, value, settings);
                });
                return product;
            };

            /**
             * get state for a node
             * @param nodeName
             */
            product.get = function (nodeName) {
                // validate node name
                if ( ! helpers.validation.validate('string', nodeName, [], true) || ! state.nodes.hasOwnProperty(nodeName)){
                    helpers.debug.reportError('Can not get node state, invalid node argument (not string or does not exists', 2, {nodeName:nodeName, state:state});
                    return product;
                }

                return state.nodes[nodeName].state;
            };

            /**
             * get several nodes at once
             * @param array
             * @returns {{}}
             */
            product.massGet = function (array) {
                var result = {};
                if ( ! product.validateAndReport('array', array, 'Can not mass set -invalid argument', 2)){
                    return product;
                }

                jQuery.each(array, function (index, nodeName) {
                    result[nodeName] = product.get(nodeName);
                });
                return result;
            };

            /**
             * Get value of all the nodes
             * @returns {{}}
             */
            product.getAll = function() {
                var result = {};

                jQuery.each(state.nodes, function (index, node) {
                    result[node.name] = node.state;
                });
                return result;
            };

            /**
             * Automatically fetch and update the state
             * @param modelName
             * @param args
             */
            product.update = function (modelName, args) {
                var fulfil = function (result, b) {
                        try {
                            product.massSet(result.data);
                        } catch (e) {
                            product.reportError('fatal error while updateing data after successful model update', 2, null);
                        }
                    },
                    reject = function (result) {
                        if (product.validateSimple('propertyExists', args,  'reportError') && args.reportError == true) {
                            product.getHelper('dom').messageUser(null, helpers.language.translate('MODEL_UPDATE_AJAX_ERROR_GENERIC_TITLE'),helpers.language.translate('MODEL_UPDATE_AJAX_ERROR_GENERIC_TEXT'));
                        }
                        if (product.validateSimple('propertyExists', args,  'failEvent') && product.validateSimple('string', args.failEvent)) {
                            product.helper('event').fire(args.failEvent, {reflector:product,result:result,modelName:modelName});
                        }



                        product.reportError('model promise - reject logging response', 1, result);
                    };
                // validate method
                if ( ! (product.validateSimple('string', modelName) && product.validateSimple('propertyExists', product.models, modelName))) {
                    product.reportError('can not update, invalid model argument given', 2, {reflector:product,modelName:modelName});
                    return false;
                }

                // arguments -> object
                if ( typeof args !== 'object' || args == null) {
                    product.reportError('dom reflector update type casting arguments to object', 1, args);
                    args = {};
                }


                return product.models[modelName].fetch(product, product.state, args).then(fulfil, reject);
            };

            /**
             * Find and create state nodes in namespace
             */
            product.populateNodes = function () {
                var $nodes;
                product.alertIfNotInit('caller: populateNodes');

                $nodes = state.$el.find(config.selectors.node);

                // exclude all the nodes that are inside a dom element container - they are out of scope
                $nodes = $nodes.filter(function (index, element) {
                    var $el               = jQuery(element),
                        // look for parents that are containers, up to but not including our namespce
                        $containerParents = $el.parentsUntil(state.$el[0], config.selectors.domReflectorContainer);

                    // if parents are found, this node lives inside a nested dom reflector container
                    return $containerParents.length == 0;
                });

                $nodes.each(function (index, node) {
                    product.populateNode(jQuery(node));
                });

                product.updateStateFromDom();

                return product;
            };

            /**
             * Update state of one node from the dom
             * @param name
             */
            product.updateNodeStateFromDom = function (name) {
                var node;
                // validate node name
                if ( ! helpers.validation.validate('string', name, [], true) || ! state.nodes.hasOwnProperty(name)){
                    helpers.debug.reportError('Can not update single node state, invalid node argument (not string or does not exists', 2, {nodeName:nodeName, state:state});
                    return product;
                }

                node = state.nodes[name];
                node.state = node.manipulator.read(node.$el);
                product.executeChangeCallbacks();
                return product;
            };

            /**
             * Bind all dom changes to update state
             * @returns {{}}
             */
            product.bindDomChanges = function () {

                product.alertIfNotInit('Caller: bindDomChanges');
                product.state.domChangesBound = true;
                jQuery.each(state.nodes, function (nodeName, node) {
                    var observer = new MutationObserver(function () {product.updateNodeStateFromDom(node.name);});
                    observer.observe(node.$el[0], {attributes : true, childList : true, characterData:true, subtree:true});

                    // also notice input value change, that is also interesting
                    if(node.$el.is('select, textarea, input')) {
                        node.$el.on('change', function () {product.updateNodeStateFromDom(node.name);});
                    }

                });
                return product;
            };

            /**
             * Create, append and bind a standard spinner element to be used to indicate element is loading
             */
            product.createAndBindSpinnerElement = function () {

                var $spinner = jQuery(we.config.dom.spinnerMarkupLocal);
                product.alertIfNotInit('caller - createAndBindSpinnerElement');
                state.$spinner = $spinner;
                $spinner.appendTo(state.$el);
                return product;
            };

            /**
             * Init the product
             * @returns {{}}
             */
            product.init = function () {
                if ( state.init == true) {
                    return product;
                }

                state.init = true;

                // set namespace
                state.$el        = jQuery(config.namespace);
                state.name       = state.$el.data(config.dataAttrNames.containerName);
                state.attributes = state.$el.data();

                // nodes
                product.populateNodes();

                // dom->state binding (state->dom is controlled by the methods of the object)
                if (config.twoWayBinding) {
                    product.bindDomChanges();
                }

                // spinner - lazy create and store reference
                state.$spinner = state.$el.find(config.selectors.spinner);
                if (state.$spinner.length == 0) {
                    product.createAndBindSpinnerElement();
                }

                // TODO: children nesting? children are currently just excluded



                return product;
            };

            // configure the product based on config and default config
            extendedConfig = helpers.object.extend(product.defaultConfig,config);
            if ( ! product.configure(extendedConfig).allAccepted ) {
                product.reportError('Dom reflector: invalid config provided', 2, {input:config, rules: product.configValidationRules,validationResult:product.configure(extendedConfig)});
            }

            return product;
        },

        /**
         * Hash Handler base "class"
         * To handle tasks passed through via the document location hash
         * @param config
         * @param extendable
         * @returns {{}}
         * @constructor
         */
        HashHandler : function (config, extendable) {
            var product, hashConfig, hash, hashRoutes, defaultHash;

            if (singletons.hasOwnProperty('hashHandler')) {
                return singletons.hashHandler;
            }

            // create the object
            product = we.getObject('Base', config);

            // parsed hash data
            defaultHash = {
                'clean' : null,
                'tasks' : []
            };

            // stores hash state
            hash = {
                'clean' : null,
                'tasks' : []
            };

            // shortcut to relevant config
            hashConfig = we.config.hash;

            hashRoutes = we.config.hash.hashRoutes;

            // our state
            product.state = {
                initialized : false
            };

            // all the tasks we can run
            product.taskHandlers = {
                showWindow : function (arguments) {
                    var routeName, route;

                    // skip empty or invalid arguments
                    if ( ! product.validateSimple('array', arguments) || arguments.length < 1 ) {
                        return;
                    }

                    routeName = arguments.shift();

                    if ( ! hashRoutes.showWindow.hasOwnProperty(routeName)) {
                        helpers.debug.reportError('can not execute hash task show window, first argument must be a route in routes.showWindow', 2, {'shiftedFirstElement': routeName});
                        return;
                    }

                    route = hashRoutes.showWindow[routeName];

                    jQuery.each(arguments, function (index, argument) {
                        route = route.replace('{{'+index+'}}', argument);
                    });

                    // purge route of remaining ommited variables
                    route = route.replace(/\{[{}]*(\{[^{}]*\}[^{}]*)*\}/g, "");

                    // pop iframe with the final route.
                    helpers.dom.showIframeInModal({'src':route, 'height': '300px', 'maxWidth': '300px', 'type': routeName});
                }
            };

            /**
             * Set hash
             * @param hash
             */
            product.setHash = function (hash) {
                if ( ! helpers.validation.validateSimple ('string', hash) ){
                    helpers.debug.reportError('can not set hash, hash must be string', 2, hash);
                    return false;
                }

                if (hash !== '') {
                    window.location.hash = '#'+hash;
                } else {
                    window.location.hash = '';
                }

                product.populateHash(hash);

                return product;
            };

            /**
             * Get hash
             * @returns {string}
             */
            product.getHash = function () {
                return window.location.hash;
            };


            /**
             * Handler for when the iframe wants a to change hash
             * @param event
             * @param data
             */
            product.iframeRequestHashChangeHandler = function (event, data) {
                if ( ! hashConfig.allowHashChangeByIframe) {
                    return;
                }

                if ( ! product.validateSimple('propertyExists', data, 'requestedHash')) {
                    helpers.debug.reportError('Can not change has per iframe request - requestedHash is missing from data', 2, {data:data});
                    return;
                }

                if ( ! product.validateSimple('string', data.requestedHash)) {
                    helpers.debug.reportError('Can not change has per iframe request - requestedHash is not string', 2, {data:data});
                    return;
                }

                product.setHash(data.requestedHash);
                return true;
            };

            /**
             * Method to allow hash running through iframes
             * @param event
             * @param data
             * @returns {boolean}
             */
            product.iframeRunHashHandler = function (event, data) {
                product.populateHash(product.getHashFromWindow());
                product.executeHashTasks();
                return true;
            };

            /**
             * Method to grab the lcoation hash
             * @returns {string}
             */
            product.getHashFromWindow = function () {
                var raw = window.location.hash;
                return raw.substr(1);
            };

            /**
             * Method to populate the has variable based on window
             */
            product.populateHash = function (hashInput) {
                if ( ! product.validateSimple('string', hashInput)) {
                    helpers.debug.reportError('can not populate hash, hash variable must be string', 2, hash);
                    return product;
                }

                /**
                 * Parse the hash tasks and popuplate them in state
                 * @param hashString
                 */
                function populateHashTasks (hashString) {
                    var explodedHashString = hashString.split('['); // our tasks are strings inside square brackets

                    jQuery.each(explodedHashString, function (index, taskString) {
                        var explodedTask;

                        if (taskString === '') {
                            return true;
                        }
                        // clean trailing ]
                        taskString = taskString.replace(']', '');

                        explodedTask = taskString.split('/');

                        hash.tasks.push({
                            'taskName' : explodedTask.shift(),
                            'arguments': explodedTask
                        });
                    });
                }

                // reset hash
                hash = helpers.object.duplicate(defaultHash);

                // note the whole string
                hash.clean = hashInput;

                // populate and parse tasks
                populateHashTasks(hash.clean);

                return product;
            };

            /**
             * Method to execute hash tasks based on state
             */
            product.executeHashTasks = function () {
                jQuery.each(hash.tasks, function(index, taskData) {
                    // a bad hash is very easy to come by. so we will have to fail silently here
                    if ( ! product.taskHandlers.hasOwnProperty(taskData.taskName)) {
                        //  helpers.debug.reportError('unknownHashTask, skipping', 1, {taskData:taskData,windowHash:window.location.hash});
                        return true;
                    }

                    product.taskHandlers[taskData.taskName](taskData.arguments);
                });

                return product;
            };

            /**
             * Init the object
             * @returns {{}}
             */
            product.init = function () {
                var hash = product.getHashFromWindow();

                product.state.initialized = true;

                if (hashConfig.allowHashChangeByIframe) {
                    helpers.event.listen('iframeRequestHashChange', product.iframeRequestHashChangeHandler);
                    helpers.event.listen('iframeRunHash', product.iframeRunHashHandler);
                }

                if ( !hashConfig.runHashTasksOnReady) {
                    return product;
                }

                we.getHelper('event').listen('documentReady', function () {
                    if (typeof hash === 'string' && hash !== '') {
                        we.getHelper('dom').showSpinner();
                    }
                });

                jQuery(window).on('load', function (e) {
                    setTimeout(function () {
                        if (typeof hash === 'string' && hash !== '') {
                            we.getHelper('dom').hideSpinner();
                            product.populateHash(hash).executeHashTasks();
                        }
                    }, 50);
                });

                return product;
            };

            singletons['hashHandler'] = product;
            return product;
        },
    };

    /**
     * Object to generate or give access to framework objects and helpers
     */
    var factory = (function () {
        var product = {};

        /**
         * Method to get an object from the library
         * @param type
         * @param config
         * @returns {*}
         */
        product.getObject = function (type, config) {
            if (typeof type != 'string') {
                helpers.debug.reportError('we.factory.getObject - argument must be string', 2, type);
                type = '';
            }

            // check for object availability or throw
            if (typeof config != 'object') {
                if (typeof config != 'undefined') {
                    helpers.debug.reportError('While creating objects, please provide Object type config argument, or ommit it.', 1, config);
                }

                config = {};
            }
            if ( ! library.hasOwnProperty(type)) {
                throw 'Object of type "' + type + '" is not available in the library.';
            }
            return library[type](config);

        };

        /**
         * Get a helper from the framework
         * @param type
         * @returns {*}
         */
        product.getHelper = function (type) {
            if (typeof type != 'string') {
                helpers.debug.reportError('we.factory.getHelper - argument must be string', 2, type);
                type = '';
            }

            type = type.lcFirst();
            if ( ! helpers.hasOwnProperty(type)) {
                throw 'Object of type "' + type + '" is not available in the helpers list.';
            }
            return helpers[type];
        };

        return product;
    })();

    /**
     * Extend the framework by adding an object
     * @param name        - name of the object
     * @param constructor - method that yields the object
     * @param type        - object/helper, default object
     *
     * @return boolean
     */
    we.extend = function (name, constructor, type) {
        if (typeof type != 'string') {
            helpers.debug.reportError('we.extend first argument must be string. returning', 2, name);
            return false;
        }

        type = type.toLowerCase();

        if (typeof constructor != 'function') {
            helpers.debug.reportError('we.extend second argument must be a constructor function. returning', 2, constructor);
            return false;
        }

        if (type == 'object') {
            name = name.ucFirst();

            if (library.hasOwnProperty(name)) {
                helpers.debug.reportError('we.extend : this object already exists in library. returning', 2, name);
                return false;
            }
            library[name] = constructor;
            return true;
        }


        if (type == 'helper') {
            name = name.lcFirst();
            if (helpers.hasOwnProperty(name)) {
                helpers.debug.reportError('we.extend : this object already exists in library. returning', 2, name);
                return false;
            }
            helpers[name] = constructor();

            return true;
        }

        // report error invalid type


        helpers.debug.reportError('we.extend third argument type was invalid.  "object" and "helper" are supported', 2, type);
        return false;
    };

    /**
     * Get an object from the framework
     * @param name
     * @param config
     * @param type
     * @returns {{}}
     */
    we.getObject = function (name, config, type) {

        // validate
        if (typeof name != 'string') {
            helpers.debug.reportError('Name of object passed to getObject must be string. Defaulting to ""', 2, name);
            name = '';
        }
        if (type == 'helper' || type == 'Helper') {
            type = 'Helper';
        } else {
            type = 'Object';
        }


        try {
            return factory['get'+type](name.ucFirst(), config);
        } catch (e) {
            helpers.debug.reportError('Factory encountered exception while creating object: ' + name + ' (type=' + type + ')', 2, e);
            return {};
        }
    };

    /**
     * Short method to get a helper
     * @param name
     * @returns {{}}
     */
    we.getHelper = function (name) {
        return we.getObject(name, {}, 'helper');
    };

    /**
     * Proxy for validation helper's validateSimple method;
     * @param rule
     * @param value
     * @param args
     * @returns {*|void}
     */
    we.validateSimple = function (rule, value, args) {
        return we.getHelper('validation').validateSimple(rule, value, args);
    };

    /**
     * Method to allow quick access to helpers.debug.debugLog
     * @param string
     */
    we.debugLog = function (string) {
        helpers.debug.debugLog(string);
    };

    /**
     * Translate a string using language helper
     * @param string
     * @param arguments
     * @returns {*}
     */
    we.translate = function (string, arguments) {
        return helpers.language.translate(string, arguments);
    };

    /**
     * proxy for translate
     * @type {(function(*=, *=): (*|*|*))|*}
     */
    we.t = we.translate;

    /**
     * Init config, set up dynamic config values (i.e. language based etc)
     * @param config
     */
    we.initConfig = function (config) {
        we.config = helpers.object.extend(we.config, config);
        we.config.dom.select2.language = helpers.language.getShortTag();
    };

    /**
     * Make the framework ready by getting a configuration object
     * @param config
     * @returns {boolean}
     */
    we.init = function (config) {
        we.initConfig(config);

        helpers.dom.listenToResize();

        helpers.event.listenToLoaclIframes();

        if ( we.config.hash.enableHashHandling) {
            we.getObject('hashHandler').init();
        }

        weState.ready = true;

        return true;
    };

    /**
     * Method to run some setup immediately, on object definition.
     * @param config
     */
    we.preInit = function (config) {
        // load polyfills
        jQuery.each(polyfills, function (index, polyfill) {
            if (polyfills.hasOwnProperty(index)) {
                polyfills[index]();
            }
        });

        // listen to doc ready
        jQuery(document).ready(function (e) {
            weState.documentReady = true;
            we.getHelper('event').fire('documentReady', e , {'calledBy': 'we.init->document.ready'});
        });

        return we;
    };

    we.reportError = function (text, level, data) {
        we.getHelper('debug').reportError(text, level, data);
    };

    we.preInit();
    return we;
})();
