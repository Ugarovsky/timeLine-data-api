FrontEndEditController = function (inputConfiguration, extendable) {
    var product = we.getObject('controller', inputConfiguration),
        config, $elements, forms, callbacks, classes;

    product.reactsTo = {

    };

    product.config = {
        namespace : 'body',
        selectors : {

        },
        formConfiguration : {

        },
        routes : {
            'getFormHtml' : 'index.php?option=com_localstory&task=edit.getFormHtml&format=raw',
            'oneClickEdit' : 'index.php?option=com_localstory&task=edit.edit&format=raw'
        },
        cancelRedirect: we.config.general.baseUrl,

        classes : {
            hidden : 'hidden-by-default',
            active : 'active'
        }
    };

    product.forms = {

    };

    product.lastEventSearch = '';

    product.editables = {};

    product.state = {
        $currentModal : jQuery(),
    };

    var editableHtmlInject = '<div class="editable-indicator"><i class="icon icon--edit icon--lead icon--max icon-editable-marker"></i></div>';

    var editableBaseObject = {
        '$el'       : null,
        'element'   : null,
        'config'    : {},
        'index'     : null
    };

    var editableDefaultConfig = {
        'type'       : null,
        'fields'     : [],
        'injectIcon' : true,
        'data'       : {},
        'editMethod' : 'withForm'
    };

    var editableIndex = 0;

    // shortcut
    config       = product.config;
    classes      = product.config.classes;
    $elements    = product.$elements;
    forms        = product.forms;

    callbacks = {
        editFormCallback : function (response) {

            doFinalRedirect = function () {
                let redirect = window.location.href;

                if (response.data.hasOwnProperty('redirect') && response.data.redirect) {
                    redirect = response.data.redirect;
                }
                we.getHelper('route').redirectWithSpinner(redirect);
            };


            we.getHelper('dom').messageUserPromise (
                product.translate('LIFELINE_FRONT_END_EDIT_SUCCESS_TEXT'),
                product.translate('LIFELINE_FRONT_END_EDIT_SUCCESS_TITLE'),
                null,
                product.translate('LIFELINE_FRONT_END_EDIT_SUCCESS_BUTTON'))
                .then(doFinalRedirect, doFinalRedirect);

            return;
            // todo in future - more behaviours
            if ( ! product.validateSimple('propertyExists',response.data, 'handling')) {
                we.getObject('ajax').getErrorHandler()();
                return;
            }
        }
    };

    /**
     * Check if element is already editable (controlled by us)
     * TODO: support jQuery (what if multiple elements passed?
     * @param element
     * @returns {boolean}
     */
    product.isElementEditable = function (element) {
        var exists = false;

        // check if element exists already, if so skip it
        jQuery.each(product.editables, function (index, editable)  {
            if (editable.element === element) {
                exists = true;
                return false;
            }
        });

        return exists;
    };

    /**
     * Show edit form by HTML
     * @param html
     */
    product.showFormHtml = function (html) {
        let form   = we.getObject('form'),
            $modal = jQuery(html),
            $form  = $modal.find('form'),
            $close = $modal
                .find('[data-behaviour="deactivator"][data-behaviour-data="[data-role=front-end-edit]"]')
                .add($modal.find('[data-cancel-edit]'));


        /**
         * Callback for confirmation modal
         */
        let submitWithConfirmation = function () {
            product.promiseEditConfirmation().then(
                function () {
                    setFormBehaviourToSubmission();
                    form.submit();

                },
                function (){
                   // $modal.remove();
                });
        };

        /**
         * Set the form, to require confirmation, and not do submission
         */
        let setFormBehaviourToConfirmation = function () {
            form.setSubmitAction('none', {'clearPrevious' : true});
            form.setSubmitAction('callback', {'callback':submitWithConfirmation});
        };

        /**
         * Set the form, to allow submission
         */
        let setFormBehaviourToSubmission = function () {
            form.setSubmitAction('none', {'clearPrevious' : true});
            form.ajaxify(formAjaxCallback);
        };

        /**
         * Form full submission callback. does this:
         *
         * 1. Make form require confirmation again,
         * 2. handle the response: success (200-299), errors (422) and exceptions (anything else)
         *
         * @param response
         */
        let formAjaxCallback = function (response) {
            product.helper('dom').hideSpinner();
            form.hideErrors();

            // make any further submission require confirmation
            setFormBehaviourToConfirmation();

            // success
            if (response.status.code <300 && response.status.code > 199) {
                // clean up the ui, and the ui state
                form.hideErrors();
                $modal.remove();
                product.state.$currentModal = false;

                // handle the edit response with the main object callback
                callbacks.editFormCallback(response);
                return;
            }

            // validation error
            if (response.status.code == 422) {
                form.showErrors(response.data, false);
                return;
            }

            // oh no!
            we.getObject('ajax').getErrorHandler()();
        };

        // setup the modal
        $close.on('click', function () {
            $modal.remove();
        });

        jQuery('body').append($modal);

        we.getHelper('dom').applyGlobalBehaviour('deactivator');

        //setup the form
        form.initByDomElement($form);

        // put the form in "confirmation mode"
        setFormBehaviourToConfirmation ();

        // log the current modal in state
        product.state.$currentModal = $modal;
    };

    product.promiseEditConfirmation = function () {
        return we.getHelper('dom').messageUserPromise(
            product.t('LIFELINE_FRONT_END_EDIT_CONFIRM_TEXT'),
            product.t('LIFELINE_FRONT_END_EDIT_CONFIRM_TITLE'),
            null,
            product.t('LIFELINE_FRONT_END_EDIT_CONFIRM_OK_BUTTON'),
            product.t('LIFELINE_FRONT_END_EDIT_CONFIRM_ABORT_BUTTON'),
            true);
    };

    product.startEditWithForm = function (editable) {
        return we.getObject('ajax').fetchPromise(
            product.config.routes.getFormHtml,
            editable.config,
            {}
        ).then(
            function (response) {
                if ( ! product.validateSimple('propertyExists',response.data, 'handling')) {
                    we.getObject('ajax').getErrorHandler()();
                    return;
                }

                switch (response.data.handling) {
                    case 'message':
                        we.getHelper('dom').messageUser(response.data.message, product.translate('FRONT_END_EDIT_GENERIC_ERROR_TITLE'));
                        break;
                    case 'default' :
                        product.showFormHtml(response.data.html);
                        break;
                    default:
                        we.getObject('ajax').getErrorHandler()();
                        break;
                }
            },
            function (response) {
                console.log('fetch html form fail');
            },
        );

        return product;
    };

    product.startEditOneClick = function (editable) {
        let data, edits;

        oneClicEditCallback = function (response) {
            // validate
            if(typeof response == 'undefined') {
                return;
            }

            if (response.status.code <199 || response.status.code > 299) {
                we.getHelper('dom').messageUser(
                    product.t('COM_LOCALSTORY_FRONT_END_EDIT_GENERIC_FAIL_TEXT'),
                    product.t('COM_LOCALSTORY_FRONT_END_EDIT_GENERIC_FAIL_TITLE'),
                );
                return;
            }
            // use the form callback for success
            callbacks.editFormCallback(response);
        };

        ajaxOneClickEdit = function () {
            return product.getObject('ajax').fetchPromise(
                product.config.routes.oneClickEdit,
                data
            );
        };
        if ( ! editable.config.hasOwnProperty('edits')) {
            product.reportError('Can no use oneClickEdit becuase editable config has no edites property', 1, {'editable':editable});
            return product;
        }

        edits = editable.config.edits;

        if ( ! typeof edits === 'object' && edits != null) {
            product.reportError('Can no use oneClickEdit becuase editable config edits property is not an object', 1, {'editable':editable});
            return product;
        }

        data = product.getHelper('object').extend(editable.config, editable.config.edits);

        // the actual edit method uses "editType" so that a field called "type" can be supported. However, we use type to maintain naming conventions with the form edit
        data.editType = data.type;

        product.promiseEditConfirmation()
            .then(ajaxOneClickEdit, function () {})
            .then(oneClicEditCallback, oneClicEditCallback);
    };

    product.startEdit = function (index) {
        var editable, editMethod = editableDefaultConfig.editMethod, controllerMethodName;

        if ( ! product.editables.hasOwnProperty(index)) {
            product.reportError('can not start edit, index not found', 2, {'index':index, 'editables':product.editables});
            return product;
        }

        editable = product.editables[index];

        // check if we have an edit method override
        if (editable.config.hasOwnProperty('editMethod')) {
            editMethod = editable.config.editMethod;
        }

        controllerMethodName = 'startEdit'+editMethod.ucFirst();

        // use the override if supported
        if (product.hasOwnProperty(controllerMethodName)) {
            return product[controllerMethodName](editable);
        }


        // use form
        return product.startEditWithForm(editable);


    };

    /**
     * Add an element as editable
     * TODO: support jQuery input
     * @param element
     */
    product.addEditableElement = function (element) {
        var $el = jQuery(element),
            defaultConfigCopy = we.getHelper('object').duplicate(editableDefaultConfig),
            editable;

        // add element
        editable                                = we.getHelper('object').duplicate(editableBaseObject);
        editable.element                        = element;
        editable.$el                            = $el;
        editable.config                         = $el.data('editable');
        editable.index                          = editableIndex;


        // extend and validate the config
        if (typeof editable.config !== 'object') {
            product.reportError('bad config for editable, not object', 2, {editable:editable});
        }

        editable.config = we.getHelper('object').extend(defaultConfigCopy, editable.config);


        // modify html
        if (editable.config.injectIcon) {
            $el.prepend(editableHtmlInject);
        }

        // bind
        editable.$el.on('click', function (){
            product.startEdit(editable.index);
        });


        // add to list
        product.editables[editable.index] = editable;

        editableIndex = editableIndex + 1;

    };

    /**
     * Find and bind all editables in DOM
     */
    product.bindEditablesInDom = function () {
        jQuery(product.config.namespace).find('[data-editable]').each(function (index, element) {
            if ( ! product.isElementEditable(element)) {
                product.addEditableElement(element);
            }
        });
        console.log(product.editables);
    };


    /**
     * Init the object
     * @returns {{}}
     */
    product.init  = function () {
        product.populateElements();
        we.getHelper('dom').bindEventsInNamespace(config.namespace);
        product.bindEditablesInDom();

        return product;
    };

    product.configure(inputConfiguration);

    product.autoBindToEvents();

    return product;

};

// extend the we framework with objects related to activation
we.extend('FrontEndEditController', FrontEndEditController, 'object');
