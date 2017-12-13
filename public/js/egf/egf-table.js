"use strict";

// Check requirements. TODO pagination
if (typeof Egf.Util === 'undefined' || typeof Egf.Template === 'undefined') {
    console.error('The files egf.js, egf-template.js and egf-pagination.js are required!');
}

/**
 * Egf table creator.
 *
 * Order of processing rows...
 * - GlobalSearch
 * - ColumnSearch todo
 * - Order
 * - Limit
 */
Egf.Table = function () {

    /** @type {Egf.Table} The this. */
    var ctrl = this;

    /** @type {string} ElemId of the container. */
    this.containerElemId = '';

    /** @type {Object[]} Column and header of table. */
    this.columns = [];

    /** @type {Object[]} Raw content of table. It contains everything. All the rows unfiltered and unsorted. */
    this.rawContents = [];

    /** @type {Object[]} All the filtered by globalSearch contents of table. ColumnSearch, order and limit comes later. */
    this.filteredByGlobalSearchContents = [];

    /** @type {Object[]} All the filtered by globalSearch and columnSearch contents of table. Order and limit comes later. */
    this.filteredByColumnSearchContents = [];

    /** @type {Object[]} All the filtered (global and column) and ordered content of table. Limit comes later.*/
    this.sortedContents = [];

    /** @type {Object[]} All the filtered (global and column), ordered and limited content of table. It'll be rendered into table body. */
    this.limitedContents = [];

    /** @type {string} The globally searched string. */
    this.globalSearch = '';

    /** @type {number} Currently visible page. */
    this.currentPageNumber = 0;

    /** @type {number} Max pages. */
    this.maxPageNumber = 0;

    /** @type {Object} Config of table. */
    this.config = {
        /** @var {number} Minimum length of search. */
        searchMinLength:        2,
        /** @type {number} Delay (in milliseconds) before doing the search.  */
        delaySearch:            500,
        /** @type {string} The property of order by. */
        orderByProperty:        '',
        /** @type {boolean} The direction of order by. */
        orderDirectionReversed: false,
        /** @type {number} Number of visible rows. */
        rowsOnPage:             10,
        /** @type {number} Number of visible pagination pages near to the current one. */
        visibleNeighbourPages:  3
    };

    /** @type {Object} Translations. */
    this.translations = {
        contentInfo:             'Showing %from% to %to% of %all% rows.',
        contentInfoFiltered:     'Showing %from% to %to% of %all% rows. Filtered from %total% rows.',
        globalSearchPlaceholder: 'Global search'
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Setters                                                    **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Set the elemId of the container.
     * @param elemId {string}
     * @return {Egf.Table}
     */
    this.setContainerElemId = function (elemId) {
        console.log('Table setContainerElemId(' + elemId + ')');

        this.containerElemId = (Egf.Util.startsWith(elemId, '#') ? elemId : '#' + elemId);

        return this;
    };

    /**
     * Set config.
     * @param config {Object}
     * @return {Egf.Table}
     */
    this.setConfig = function (config) {
        console.log('Table setConfig({Object})', config);

        this.config = Egf.Util.objectAssign(this.config, config);

        return this;
    };

    /**
     * Set translations.
     * @param translations {Object}
     * @return {Egf.Table}
     */
    this.setTranslations = function (translations) {
        console.log('Table setTranslations({Object})', translations);

        this.translations = Egf.Util.objectAssign(this.translations, translations);

        return this;
    };

    /**
     * Set columns with header data.
     * @param columns {Object[]}
     * @return {Egf.Table}
     */
    this.setColumns = function (columns) {
        console.log('Table setColumns({Object[]})', columns);

        this.columns = columns;

        return this;
    };

    /**
     * Set the raw content. Array of objects or a Json string.
     * @param xContent {string|Object[]}
     * @return {Egf.Table}
     */
    this.setContents = function (xContent) {
        console.log('Table setContent({string|object}})', xContent);

        if (typeof xContent === 'string') {
            this.rawContents = JSON.parse(xContent);
        } else {
            this.rawContents = xContent;
        }

        return this;
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Init                                                       **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Initialize table from zero.
     */
    this.init = function () {
        console.log('init');

        // Init table frame.
        this.loadTableFrame();

        // Add events.
        this.addGlobalSearchEvent()
            .addTableOrderEvent();

        // Content filter/order/limit.
        this.calculateFilteredByGlobalSearchContents()
            .calculateFilteredByColumnSearchContents()
            .calculateSortedContents()
            .calculateLimitedContents();

        // Show content.
        this.showCurrentContents();
    };

    /**
     * Load table frame from template and add it to Html.
     */
    this.loadTableFrame = function () {
        console.log('loadTableFrame');

        Egf.Template.toElementByTemplate(this.containerElemId, 'js-template-egf-table', {
            columns:      ctrl.columns,
            translations: this.translations
        });
    };

    /**
     * Add event to the globalSearch input.
     * @return {Egf.Table}
     */
    this.addGlobalSearchEvent = function () {
        console.log('addTableGlobalSearchEvent');

        var timeout = null;

        Egf.Elem.addEvent(this.containerElemId + ' .egf-table-global-search', 'keyup', function (event) {
            clearTimeout(timeout);

            timeout = setTimeout(function () {
                ctrl.globalSearch = event.target.value;

                ctrl.calculateFilteredByGlobalSearchContents()
                    .calculateFilteredByColumnSearchContents()
                    .calculateSortedContents()
                    .calculateLimitedContents();

                ctrl.showCurrentContents();
            }, ctrl.config.delaySearch);
        });

        return this;
    };

    /** todo */
    this.addColumnSearchEvent = function () {
        /*this
            .calculateFilteredByColumnSearchContents()
            .calculateSortedContents()
            .calculateLimitedContents();*/
    };

    /**
     * Add sorting events to headers.
     * @return {Egf.Table}
     */
    this.addTableOrderEvent = function () {
        console.log('addTableOrderEvent');

        Egf.Elem.addEvent(this.containerElemId + ' .egf-table-head', 'click', function (event) {
            var orderProperty = event.target.getAttribute('data-property');

            // Secondary click on the column header set the direction to desc.
            if (orderProperty === ctrl.config.orderByProperty) {
                ctrl.config.orderDirectionReversed = !ctrl.config.orderDirectionReversed;
            }
            // If the clicked orderBy property is not the current, then make it current and set the direction to asc.
            else {
                ctrl.config.orderByProperty = orderProperty;
                ctrl.config.orderDirectionReversed = false;
            }

            ctrl.calculateSortedContents()
                .calculateLimitedContents();

            ctrl.showCurrentContents();
        });

        return this;
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Show                                                       **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Update the rows in the table by content rows this should be currently visible.
     */
    this.showCurrentContents = function () {
        console.log('loadCurrentContent');

        console.log(this.limitedContents);

        var tableBodyElements = Egf.Elem.find(this.containerElemId + " > table > tbody");
        if (tableBodyElements instanceof NodeList && typeof tableBodyElements[0] !== 'undefined') {
            tableBodyElements[0].innerHTML = this.getTableContentHtml();
        }
        else {
            throw new Error("Table body not found! Searched for: " + this.containerElemId + " > table > tbody");
        }
    };

    /**
     * Get the HTML content of table body.
     * @return {string}
     */
    this.getTableContentHtml = function () {
        var htmlResult = '';
        Egf.Util.forEach(this.limitedContents, function (row) {
            htmlResult += ctrl.getRowHtml(row);
        });

        return htmlResult;
    };

    /**
     * Get the HTML of one table row.
     * @return string
     */
    this.getRowHtml = function (row) {
        var rowContent = {};
        var key        = 0;

        Egf.Util.forEach(this.columns, function (column) {
            // Show property as simple data.
            if (column.hasOwnProperty('property')) {
                rowContent[key++] = row[column.property];
            }
            // Call function with row data and show the result.
            else if (column.hasOwnProperty('func')) {
                if (typeof column['func'] === 'function') {
                    rowContent[key++] = column['func'](row);
                } else {
                    throw new Error('Table content expects function, got ' + (typeof column['func']) + ' instead!');
                }
            } else {
                throw new Error('Table header has to have "property" or "func" to know what to do with cell content.');
            }
        });

        return Egf.Template.getTemplateContent('js-template-egf-table-row', {
            rowContent: rowContent
        });
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Content Filter/Sorter                                      **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Remove rows from filteredByGlobalSearchContents which shouldn't be visible by global search.
     * Works from rawContents.
     * @return {Egf.Table}
     */
    this.calculateFilteredByGlobalSearchContents = function () {
        console.log('calculateFilteredByGlobalSearchContents');

        // If there is something to search.
        if (typeof this.globalSearch === 'string' && this.globalSearch.length >= this.config.searchMinLength) {
            // Reset filteredByGlobalSearchContents and load it from rawContents data.
            this.filteredByGlobalSearchContents = [];

            /** @type {string[]} Searched words, space separated. */
            var searchFragments  = this.globalSearch.split(' ');
            /** @type {string[]} Properties to search in. */
            var searchProperties = [];

            // Get properties to search in.
            Egf.Util.forEach(this.columns, function (column) {
                if (typeof column.search !== 'undefined') {
                    // Search by property.
                    if (typeof column.property !== 'undefined') {
                        searchProperties.push(column.property);
                    }
                    // Search by function result.
                    else if (typeof column.func === 'function') {
                        searchProperties.push(column.func);
                    }
                }
            });

            // Iterate temporally stored filteredByGlobalSearchContents and update the real one by filter results.
            Egf.Util.forEach(this.rawContents, function (row) {
                // If row has the string in it then add to real filteredByGlobalSearchContents.
                if (ctrl.isInObjectTextContent(row, searchProperties, searchFragments, ctrl.config.searchMinLength, false)) {
                    ctrl.filteredByGlobalSearchContents.push(row);
                }
            });
        }
        // If there is no globalSearch.
        else {
            this.filteredByGlobalSearchContents = this.rawContents;
        }

        return this;
    };

    /**
     * todo
     * @return {Egf.Table}
     */
    this.calculateFilteredByColumnSearchContents = function () {
        // console.log('calculateFilteredByColumnSearchContents TODO');

        this.filteredByColumnSearchContents = this.filteredByGlobalSearchContents;

        return this;
    };

    /**
     * Reorder content rows.
     * Works from filteredByGlobalSearchContents.
     * @return {Egf.Table}
     */
    this.calculateSortedContents = function () {
        console.log('calculateSortedContents');

        // Copy content (sortObjects works with passed by reference).
        this.sortedContents = this.filteredByColumnSearchContents;

        // Do the sorting.
        if (typeof this.config.orderByProperty === 'string' && this.config.orderByProperty.length) {
            Egf.Util.sortObjects(this.sortedContents, this.config.orderByProperty, this.config.orderDirectionReversed)
        }

        return this;
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Content Limit/Pagination                                   **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Update the limitedContents array with the actually visible content.
     * @return {Egf.Table}
     */
    this.calculateLimitedContents = function () {
        console.log('calculateLimitedContents TODO');

        this.limitedContents = this.sortedContents;

        return this;
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Search by string                                           **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * If the parameter is a string, then put the string in an array and give it back. Otherwise it gives back the variable.
     * @param value {Array|Object|String} The string or the array object.
     * @return {Array} An array itself or the array with the string value in it.
     */
    this.ifStringToArrayAsValue = function (value) {
        if (typeof value === "string") {
            var stringValue = value;
            value           = [];
            value.push(stringValue);
        }
        else if (typeof value !== "object") {
            console.error("Invalid variable for _func.ifStringToArrayAsValue()! It got a(n) " + (typeof value) + " but it accept only string or an array object! The variable is: ", value);
        }

        return value;
    };

    /**
     * Check if the object properties have any of the searched text.
     * @param haystack {Object} The object where the text could be.
     * @param properties {Array|String} The properties of the object, this could have the text as content.
     * @param needles {Array|String} The searched strings.
     * @param minLength {Number} The minimum number this the length of searched text should be.
     * @param caseSensitive {Boolean} If it's true, then it'll do a case sensitive search.
     * @return {boolean} Gives back true if there was at least one result, false otherwise.
     * @todo Right now it does OR search. +1 param to decide if it should do AND search instead?
     */
    this.isInObjectTextContent = function (haystack, properties, needles, minLength, caseSensitive) {
        properties    = this.ifStringToArrayAsValue(properties);
        needles       = this.ifStringToArrayAsValue(needles);
        minLength     = (typeof minLength === "undefined" ? 2 : minLength);
        caseSensitive = (typeof caseSensitive === "undefined" ? false : caseSensitive);
        var result    = false;

        // Iterate searched words.
        Egf.Util.forEach(needles, function (word) {
            if (word.length >= minLength) {
                // Iterate object properties.
                Egf.Util.forEach(properties, function (property) {
                    if (haystack.hasOwnProperty(property) || typeof property === 'function') {
                        var haystackPropertyValue = '';
                        // Method of object, call it and check result.
                        if (haystack[property] === 'function') {
                            haystackPropertyValue = haystack[property]();
                        }
                        // Function with object parameter.
                        else if (typeof property === 'function') {
                            haystackPropertyValue = property(haystack);
                        }
                        // Number.
                        else if (typeof haystack[property] === 'number') {
                            haystackPropertyValue = haystack[property].toString();
                        }
                        // String... probably.
                        else {
                            haystackPropertyValue = haystack[property];
                        }

                        // Compare strings.
                        if (ctrl.isWordFound(haystackPropertyValue, word, caseSensitive)) {
                            result = true;
                        }
                    }
                    else {
                        console.error("Object doesn't have the property: " + property + "! The object is: ", haystack);
                    }
                });
            }
        });
        return result;
    };

    /**
     * Check if the word exists in the property of object.
     * @param haystackPropertyValue {string} Property value.
     * @param word {string} Searched word.
     * @param caseSensitive {boolean} Case sensitive search or not.
     * @returns {boolean}
     */
    this.isWordFound = function (haystackPropertyValue, word, caseSensitive) {
        return (haystackPropertyValue && (
                (caseSensitive && haystackPropertyValue.search(word) >= 0) ||
                (!caseSensitive && haystackPropertyValue.toLowerCase().search(word.toLowerCase()) >= 0)
            )
        );
    };

};
