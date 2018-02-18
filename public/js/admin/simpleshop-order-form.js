/**
 * @var number itemsCount
 * @var string deleteLabel
 * @var string deleteConfirmText
 */

// Add item.
jQuery(document).ready(function() {
    jQuery('#add-another-item').click(function(e) {
        e.preventDefault();

        var emailList = jQuery('#items-field-list');

        var newWidget = emailList.attr('data-prototype');

        newWidget = newWidget.replace(/__name__/g, itemsCount);

        var newLi = jQuery('<li></li>').html(newWidget);
        newLi.appendTo(emailList);

        addTagFormDeleteLink(newLi);

        jQuery('#order_items_' + itemsCount + '_count').val(1);

        itemsCount++;
    });
});


// Remove item.
jQuery(document).ready(function() {
    var collectionHolder = jQuery('#items-field-list');

    collectionHolder.find('li').each(function() {
        addTagFormDeleteLink(jQuery(this));
    });
});

/**
 * Do the remove element part of removing form item.
 * @param tagFormLi
 */
function addTagFormDeleteLink(tagFormLi) {
    var removeFormA = jQuery('<a href="#" class="btn btn-default pull-right">' + deleteLabel + '</a><br /><br />');
    tagFormLi.append(removeFormA);

    removeFormA.on('click', function(e) {
        e.preventDefault();
        if (confirm(deleteConfirmText)) {
            tagFormLi.remove();
        }
    });
}