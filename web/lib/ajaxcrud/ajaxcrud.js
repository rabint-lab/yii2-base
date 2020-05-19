/*!
 * Ajax Crud 
 * =================================
 * Use for johnitvn/yii2-ajaxcrud extension
 * @author John Martin john.itvn@gmail.com
 */
$(document).ready(function () {

    // Create instance of Modal Remote
    // This instance will be the controller of all business logic of modal
    // Backwards compatible lookup of old ajaxCrubModal ID
    if ($('#ajaxCrubModal').length > 0 && $('#ajaxCrudModal').length == 0) {
        modal = new ModalRemote('#ajaxCrubModal');
    } else {
        modal = new ModalRemote('#ajaxCrudModal');
    }

    // Catch click event on all buttons that want to open a modal
    $(document).on('click', '[role="modal-remote"]', function (event) {
        event.preventDefault();

        // Open modal
        modal.open(this, null);
    });

    // Catch click event on all buttons that want to open a modal
    // with bulk action
    $(document).on('click', '[role="modal-remote-bulk"]', function (event) {
        event.preventDefault();

        // Collect all selected ID's
        var selectedIds = [];
        $('input:checkbox[name="selection[]"]').each(function () {
            if (this.checked)
                selectedIds.push($(this).val());
        });

        if (selectedIds.length == 0) {
            // If no selected ID's show warning
            modal.show();
            modal.setTitle('خطای انتخاب');
            modal.setContent('لطفا ابتدا آیتم های مورد خود را انتخاب نمایید');
            modal.addFooterButton("بستن", 'button','btn btn-primary', function (button, event) {
                this.hide();
            });
        } else {
            // Open modal
            modal.open(this, selectedIds);
        }
    });


});

/**
 * any helper modal
 */

function ajaxModalOpen($url, $method, $data) {

    if ($('#ajaxCrubModal').length > 0 && $('#ajaxCrudModal').length == 0) {
        modal = new ModalRemote('#ajaxCrubModal');
    } else {
        modal = new ModalRemote('#ajaxCrudModal');
    }

    $method = (typeof $method !== 'undefined') ? $method : "GET";
    $data = (typeof $data !== 'undefined') ? $data : null;


    modal.doRemote($url, $method, $data);

    // /**
    //  * Show either a local confirm modal or get modal content through ajax
    //  */
    // if ($(elm).hasAttr('data-confirm-title') || $(elm).hasAttr('data-confirm-message')) {
    //     this.confirmModal (
    //         $(elm).attr('data-confirm-title'),
    //         $(elm).attr('data-confirm-message'),
    //         $(elm).attr('data-confirm-ok'),
    //         $(elm).attr('data-confirm-cancel'),
    //         $(elm).hasAttr('data-modal-size') ? $(elm).attr('data-modal-size') : 'normal',
    //         $(elm).hasAttr('href') ? $(elm).attr('href') : $(elm).attr('data-url'),
    //         $(elm).hasAttr('data-request-method') ? $(elm).attr('data-request-method') : 'GET',
    //         bulkData
    //     )
    // } else {
    //     this.doRemote(
    //         $(elm).hasAttr('href') ? $(elm).attr('href') : $(elm).attr('data-url'),
    //         $(elm).hasAttr('data-request-method') ? $(elm).attr('data-request-method') : 'GET',
    //         bulkData
    //     );
    // }


}