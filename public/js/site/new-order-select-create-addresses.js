"use strict";

/**
 * Select address or create new ones while user creating a new order.
 */
var AddressSelectorCreator = function () {

    /** @type {AddressSelectorCreator} */
    var that = this;

    /** @type {string} Delivery or billing. */
    this.type = '';

    /**
     * Set type.
     * @param type {string}
     * @return {AddressSelectorCreator}
     */
    this.setType = function (type) {
        that.type = type;

        return this;
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Init                                                       **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Initialize.
     */
    this.init = function () {
        this.initEvents();
    };

    /**
     * Initialize events.
     */
    this.initEvents = function () {
        // Change visibility status of address box.
        Egf.Elem.addEvent('#select_address_askingFor' + Egf.Util.ucfirst(that.type) + 'Checkbox', 'change', function (event) {
            var isChecked = event.target.checked;

            that.addressBoxToVisible('#' + that.type + '-address', isChecked);
            that.addressValidationToRequired(isChecked);
            that.addressDropDownToDisabled(!isChecked);
        });

        // Change visiblility status of new address box.
        Egf.Elem.addEvent('#select_address_new' + Egf.Util.ucfirst(that.type) + 'AddressCheckbox', 'change', function (event) {
            var isChecked = event.target.checked;

            that.addressBoxToVisible('#new-' + that.type + '-address', isChecked);
            that.addressValidationToRequired(isChecked);
            that.addressDropDownToDisabled(isChecked);
        });
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Status changes                                             **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Change the visibility status of an address box.
     * @param boxElemId {string} Elem id.
     * @param isVisible {boolean} Should it be visible or not.
     */
    this.addressBoxToVisible = function (boxElemId, isVisible) {
        Egf.Elem.cssClass(boxElemId, 'hidden', !isVisible);
    };

    /**
     * Change the validation for required on new delivery address inputs.
     * @param toStatus True if required, false otherwise.
     */
    this.addressValidationToRequired = function (toStatus) {
        Egf.Elem.find('#select_address_new' + Egf.Util.ucfirst(that.type) + 'Address_city').required        = toStatus;
        Egf.Elem.find('#select_address_new' + Egf.Util.ucfirst(that.type) + 'Address_zipCode').required     = toStatus;
        Egf.Elem.find('#select_address_new' + Egf.Util.ucfirst(that.type) + 'Address_street').required      = toStatus;
        Egf.Elem.find('#select_address_new' + Egf.Util.ucfirst(that.type) + 'Address_houseNumber').required = toStatus;
    };

    /**
     * Disable the address dropDown.
     * @param toStatus {boolean} True to disabled, false to enable.
     */
    this.addressDropDownToDisabled = function (toStatus) {
        Egf.Elem.find('#select_address_' + that.type + 'Address').disabled = toStatus;
    };

};