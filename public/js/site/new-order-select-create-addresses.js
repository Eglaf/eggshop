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
     * @return {AddressSelectorCreator}
     */
    this.init = function () {
        this.initEvents();

        return this;
    };

    /**
     * Initialize events.
     */
    this.initEvents = function () {
        // Change visibility status of address box.
        Egf.Elem.addEvent('#select_address_askingFor' + Egf.Util.ucfirst(that.type) + 'Checkbox', 'change', function (event) {
            var isChecked = event.target.checked;

            that.addressBoxToVisible('#' + that.type + '-address', isChecked);

            // Selected address.
            if (isChecked) {
                // New address.
                if (Egf.Elem.find('#select_address_new' + Egf.Util.ucfirst(that.type) + 'AddressCheckbox').checked) {
                    that.addressDropDownToDisabled(true);
                    that.addressBoxToVisible('#new-' + that.type + '-address', true);
                    that.addressValidationToRequired(true);
                }
                // Existing address.
                else {
                    that.addressDropDownToDisabled(false);
                    that.addressBoxToVisible('#new-' + that.type + '-address', false);
                    that.addressValidationToRequired(false);
                }
            }
            // No address.
            else {
                that.addressDropDownToDisabled(true);
                that.addressValidationToRequired(false);
            }
        });

        // Change visibility status of new address box.
        Egf.Elem.addEvent('#select_address_new' + Egf.Util.ucfirst(that.type) + 'AddressCheckbox', 'change', function (event) {
            var isChecked = event.target.checked;

            that.addressBoxToVisible('#new-' + that.type + '-address', isChecked);
            that.addressValidationToRequired(isChecked);
            that.addressDropDownToDisabled(isChecked);
        });
    };


    /**************************************************************************************************************************************************************
     *                                                          **         **         **         **         **         **         **         **         **         **
     * Change by session                                          **         **         **         **         **         **         **         **         **         **
     *                                                          **         **         **         **         **         **         **         **         **         **
     *************************************************************************************************************************************************************/

    /**
     * Make an existing address selected by its id.
     * @param addressId {number}
     */
    this.selectAddress = function (addressId) {
        Egf.Elem.find('#select_address_askingFor' + Egf.Util.ucfirst(this.type) + 'Checkbox').checked = true;
        Egf.Elem.find('#select_address_' + that.type + 'Address').value                               = addressId;

        this.addressBoxToVisible('#new-' + that.type + '-address', false);
        this.addressValidationToRequired(false);
    };

    /**
     * Make the new address checkbox selected and fill the new address form inputs.
     * @param data {Array}
     */
    this.newAddress = function (data) {
        Egf.Elem.find('#select_address_askingFor' + Egf.Util.ucfirst(this.type) + 'Checkbox').checked  = true;
        Egf.Elem.find('#select_address_new' + Egf.Util.ucfirst(that.type) + 'AddressCheckbox').checked = true;

        that.addressBoxToVisible('#new-' + that.type + '-address', true);
        that.addressValidationToRequired(true);
        that.addressDropDownToDisabled(true);

        Egf.Util.forEach(data, function (key, value) {
            Egf.Elem.find('#select_address_new' + Egf.Util.ucfirst(that.type) + 'Address_' + key).value = value;
        });
    };

    /**
     * No address is selected... neither existing nor new one.
     */
    this.noAddress = function () {
        that.addressBoxToVisible('#' + that.type + '-address', false);
        that.addressValidationToRequired(false);
        that.addressDropDownToDisabled(false);
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