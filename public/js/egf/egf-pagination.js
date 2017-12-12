"use strict";

/**
 * Pagination.
 * @todo setToPageEvent
 */
function Pagination() {

    /** @type {Pagination} */
    var that = this;

    /** @type {object} Id and class referrers to Html elements. */
    this.oElementReferrer = {
        // The pagination container's css class. There can be more than one.
        containers: '?',
        // Elem id of button to first.
        toFirst:    '_pagination-to-first',
        // Elem id of button to previous.
        toPrevious: '_pagination-to-previous',
        // Elem id of button to next.
        toNext:     '_pagination-to-next',
        // Elem id of button to last.
        toLast:     '_pagination-to-last'
    };

    /** @type {object} Translations. */
    this.oTranslations = {
        first:    'First',
        previous: 'Previous',
        next:     'Next',
        last:     'Last'
    };

    /** @type {object} */
    this.oContainers = [];

    /** @type {number} The current page. */
    this.iCurrentPage = 1;

    /** @type {number} The max page. */
    this.iMaxPage = 1;

    /** @type {object} The event functions on clicking pagination buttons. */
    this.oEvents = {
        toFirst:    function () {},
        toPrevious: function () {},
        toNext:     function () {},
        toLast:     function () {}
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Init                                                       **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Set element referrers.
     * @param oElementReferrer {object}
     * @return {Pagination}
     */
    this.setElementReferrer = function (oElementReferrer) {
        Egf.Cl.log(arguments);

        this.oElementReferrer = Egf.Util.objectAssign(this.oElementReferrer, oElementReferrer);

        return that;
    };

    /**
     * Set translations.
     * @param oTranslations {object}
     * @return {Pagination}
     */
    this.setTranslations = function (oTranslations) {
        Egf.Cl.log(arguments);

        this.oTranslations = Egf.Util.objectAssign(this.oTranslations, oTranslations);

        return that;
    };

    /**
     * Initialize.
     * @return {Pagination}
     */
    this.init = function () {
        Egf.Cl.log(arguments);

        that.oContainers = document.getElementsByClassName(that.oElementReferrer.containers);

        return that;
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Refresh                                                    **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Set the current page.
     * @param iCurrentPage {number}
     * @return {Pagination}
     */
    this.setCurrentPage = function (iCurrentPage) {
        Egf.Cl.log(arguments);

        that.iCurrentPage = iCurrentPage;

        return that;
    };

    /**
     * Set the max page.
     * @param iMaxPage {number}
     * @return {Pagination}
     */
    this.setMaxPage = function (iMaxPage) {
        Egf.Cl.log(arguments);

        that.iMaxPage = iMaxPage;

        return that;
    };

    /**
     * Refresh the content of pagination containers.
     * @return {Pagination}
     */
    this.refresh = function () {
        Egf.Cl.log(arguments);

        for (var iKey in that.oContainers) {
            if (that.oContainers.hasOwnProperty(iKey) && iKey !== 'length') {
                Egf.Template.toElementByTemplate(that.oContainers[iKey], 'js-template-egf-pagination', {
                    text:         {
                        first:    that.oTranslations.first,
                        previous: that.oTranslations.previous,
                        next:     that.oTranslations.next,
                        last:     that.oTranslations.last
                    },
                    elemReferrer: {
                        toFirst:    that.oElementReferrer.toFirst,
                        toPrevious: that.oElementReferrer.toPrevious,
                        toNext:     that.oElementReferrer.toNext,
                        toLast:     that.oElementReferrer.toLast
                    },
                    currentPage:  that.iCurrentPage,
                    maxPage:      that.iMaxPage
                });
            }
        }

        that.addEvents();

        return that;
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Events                                                     **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Add events to buttons.
     * @return {Pagination}
     */
    this.addEvents = function () {
        Egf.Cl.log(arguments);

        // To first.
        Egf.Util.elementsEvent(that.oElementReferrer.toFirst, 'click', function (e) {
            e.preventDefault();
            that.oEvents.toFirst();
        });

        // To previous.
        Egf.Util.elementsEvent(that.oElementReferrer.toPrevious, 'click', function (e) {
            e.preventDefault();
            that.oEvents.toPrevious();
        });

        // To next.
        Egf.Util.elementsEvent(that.oElementReferrer.toNext, 'click', function (e) {
            e.preventDefault();
            that.oEvents.toNext();
        });

        // To last.
        Egf.Util.elementsEvent(that.oElementReferrer.toLast, 'click', function (e) {
            e.preventDefault();
            that.oEvents.toLast();
        });

        return that;
    };

    /**
     * Set the event of clicking first button.
     * @param fnToFirst {function}
     * @return {Pagination}
     */
    this.setToFirstEvent = function (fnToFirst) {
        Egf.Cl.log(arguments);

        that.oEvents.toFirst = fnToFirst;

        return that;
    }

    /**
     * Set the event of clicking previous button.
     * @param fnToPrevious {function}
     * @return {Pagination}
     */
    this.setToPreviousEvent = function (fnToPrevious) {
        Egf.Cl.log(arguments);

        that.oEvents.toPrevious = fnToPrevious;

        return that;
    }

    /**
     * Set the event of clicking next button.
     * @param fnToNext {function}
     * @return {Pagination}
     */
    this.setToNextEvent = function (fnToNext) {
        Egf.Cl.log(arguments);

        that.oEvents.toNext = fnToNext;

        return that;
    }

    /**
     * Set the event of clicking last button.
     * @param fnToLast {function}
     * @return {Pagination}
     */
    this.setToLastEvent = function (fnToLast) {
        Egf.Cl.log(arguments);

        that.oEvents.toLast = fnToLast;

        return that;
    }

}
