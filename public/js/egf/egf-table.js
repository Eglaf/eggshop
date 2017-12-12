"use strict";

// Check requirements.
if (typeof Egf.Util === 'undefined' || typeof Egf.Template === 'undefined') {
    console.error('The file egf.js is required!');
}

/**
 * Some object to poke tables.
 * todo Make it poke things.
 */
Egf.Table = function () {

    /** @type {Egf.Table} */
    var ctrl = this;

    /** @type {string} ElemId of the container. */
    this.sContainerElemId = '';

    /** @type {Object[]} Column and header of table. */
    this.aoColumns = [];

    /** @type {Object[]} Content of table. It contains everything. */
    this.aoContent = [];

    /** @type {Object[]} All of the filtered and sorted content of table. Limit comes later. */
    this.aoSelectedContent = [];

    /** @type {Object[]} Limited content of table. It'll be rendered into table body. */
    this.aoLimitedContent = [];

    /** @type {string} The globally searched string. */
    this.sGlobalSearch = '';

    /** @type {number} Currently visited page. */
    this.iCurrentPage = 0;

    /** @type {Object} Config of table. */
    this.oConfig = {
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
    this.oTrans = {
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
     * @param {string} sElemId Element id.
     * @return {Object} This.
     */
    this.setContainerElemId = function (sElemId) {
        console.log('Table setContainerElemId(' + sElemId + ')');

        this.sContainerElemId = (Egf.Util.startsWith(sElemId, '#') ? sElemId : '#' + sElemId);

        return this;
    };

    /**
     * Set config.
     * @param {Object} oConfig Settings.
     * @return {Object} This.
     */
    this.setConfig = function (oConfig) {
        console.log('Table setConfig({Object})', oConfig);

        this.oConfig = Egf.Util.objectAssign(this.oConfig, oConfig);

        return this;
    };

    /**
     * Set translations.
     * @param oTrans {Object}
     * @return This.
     */
    this.setTranslations = function (oTrans) {
        console.log('Table setTranslations({Object})', oTrans);

        this.oTrans = Egf.Util.objectAssign(this.oTrans, oTrans);

        return this;
    };

    /**
     * Set columns.
     * @param {Object[]} aoColumns Columns.
     * @return {Object} This.
     */
    this.setColumns = function (aoColumns) {
        console.log('Table setColumns({Object[]})', aoColumns);

        this.aoColumns = aoColumns;

        return this;
    };

    /**
     * Set content.
     * @param xContent
     * @return {Object} This.
     */
    this.setContent = function (xContent) {
        console.log('Table setContent({string|object}})', xContent);

        if (typeof xContent === 'string') {
            this.aoContent = JSON.parse(xContent);
        } else {
            this.aoContent = xContent;
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

        this.loadTableFrame();
        this.addTableEvents();

        this.recalculateSelectedContent();
        this.recalculateLimitedContent();

        this.loadCurrentContent();
    };

    /**
     * Load table frame from template and add it to Html.
     */
    this.loadTableFrame = function () {
        console.log('loadTableFrame');

        Egf.Template.toElementByTemplate(this.sContainerElemId, 'js-template-egf-table', {
            aoColumns: ctrl.aoColumns,
            oTrans:    this.oTrans
        });
    };

    /**
     * Add events to elements.
     */
    this.addTableEvents = function () {
        console.log('addTableEvents TODO');
    };

    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Filter content                                             **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Update the selectedContent array with the filtered content.
     */
    this.recalculateSelectedContent = function () {
        console.log('recalculateSelectedContent TODO');

        this.aoSelectedContent = this.aoContent;
    };

    /**
     * Update the limitedContent array with the actually visible content.
     */
    this.recalculateLimitedContent = function () {
        console.log('recalculateLimitedContent TODO');

        this.aoLimitedContent = this.aoSelectedContent;
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Show content                                               **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Update the rows in the table by content rows that should be currently visible.
     */
    this.loadCurrentContent = function () {
        console.log('loadCurrentContent TODO');

        var aeTableBodies = Egf.Elem.find(this.sContainerElemId + " > table > tbody");
        if (aeTableBodies instanceof NodeList && typeof aeTableBodies[0] !== 'undefined') {
            aeTableBodies[0].innerHTML = this.getTableContentHtml();
        }
        else {
            throw new Error("Table body not found! Searched for: " + this.sContainerElemId + " > table > tbody");
        }
    };

    /**
     * Get the HTML content of table body.
     * @return {string}
     */
    this.getTableContentHtml = function () {
        var sHtml = '';
        Egf.Util.forEach(this.aoLimitedContent, function (oRow) {
            sHtml += ctrl.getRowHtml(oRow);
        });

        return sHtml;
    };

    /**
     * Get the HTML of one table row.
     * @return string
     */
    this.getRowHtml = function (oRow) {
        var oRowContent = {};
        var iKey = 0;

        Egf.Util.forEach(this.aoColumns, function (oColumn) {
            // Show property as simple data.
            if (oColumn.hasOwnProperty('prop')) {
                oRowContent[iKey++] = oRow[oColumn.prop];
            }
            // Do some function mage on row and show the result.
            else if (oColumn.hasOwnProperty('func')) {
                if (typeof oColumn['func'] === 'function') {
                    oRowContent[iKey++] = oColumn['func'](oRow);
                } else {
                    throw new Error('Table content expects function, got ' + (typeof fn) + ' instead!');
                }
            } else {
                throw new Error('Table header has to have "prop" or "func" to know what to do with cell content.');
            }
        });

        return Egf.Template.getTemplateContent('js-template-egf-table-row', {
            oRowContent: oRowContent
        });
    };




    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * todo                                                       **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * If the parameter is a string, then put the string in an array and give it back. Otherwise it gives back the variable.
     * @param aVar {Array|Object|String} The string or the array object.
     * @return {Array} An array itself or the array with the string value in it.
     */
    this.ifStringToArrayAsValue = function (aVar) {
        if (typeof aVar === "string") {
            var sVar = aVar;
            aVar = [];
            aVar.push(sVar);
        }
        else if (typeof aVar !== "object") {
            console.error("Invalid variable for _func.ifStringToArrayAsValue()! It got a(n) " + (typeof aVar) + " but it accept only string or an array object! The variable is: ", aVar);
        }

        return aVar;
    };

    /**
     * Check if the object properties have any of the searched text.
     * @param oHaystack {Object} The object where the text could be.
     * @param aProperties {Array|String} The properties of the object, that could have the text as content.
     * @param aNeedles {Array|String} The searched strings.
     * @param iMinLength {Number} The minimum number that the length of searched text should be.
     * @param bCaseSensitive {Boolean} If it's true, then it'll do a case sensitive search.
     * @return {boolean} Gives back true if there was at least one result, false otherwise.
     * @todo Right now it does OR search. +1 param to decide if it should do AND search instead?
     */
    this.isInObjectTextContent = function (oHaystack, aProperties, aNeedles, iMinLength, bCaseSensitive) {
        aProperties = this.ifStringToArrayAsValue(aProperties);
        aNeedles = this.ifStringToArrayAsValue(aNeedles);
        iMinLength = (typeof iMinLength === "undefined" ? 2 : iMinLength);
        bCaseSensitive = (typeof bCaseSensitive === "undefined" ? false : bCaseSensitive);

        var bResult = false;
        Egf.Util.forEach(aNeedles, function (sWord) {
            if (sWord.length >= iMinLength) {
                Egf.Util.forEach(aProperties, function (sProperty) {
                    if (oHaystack.hasOwnProperty(sProperty) || typeof sProperty === 'function') {
                        var sHayStackPropertyValue = '';
                        // Method of object.
                        if (oHaystack[sProperty] === 'function') {
                            sHayStackPropertyValue = oHaystack[sProperty]();
                        }
                        // Function with object parameter.
                        else if (typeof sProperty === 'function') {
                            sHayStackPropertyValue = sProperty(oHaystack);
                        }
                        // Number.
                        else if (typeof oHaystack[sProperty] === 'number') {
                            sHayStackPropertyValue = oHaystack[sProperty].toString();
                        }
                        // String... probably.
                        else {
                            sHayStackPropertyValue = oHaystack[sProperty];
                        }
                        if (sHayStackPropertyValue && ((bCaseSensitive && sHayStackPropertyValue.search(sWord) >= 0) || (!bCaseSensitive && sHayStackPropertyValue.toLowerCase().search(sWord.toLowerCase()) >= 0))) {
                            bResult = true;
                        }
                    }
                    else {
                        console.error("Object doesn't have the property: " + sProperty + "! The object is: ", oHaystack);
                    }
                });
            }
        });
        return bResult;
    };

};