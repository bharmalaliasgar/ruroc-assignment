require(['jquery', 'mage/url', 'mage/loader', 'mage/translate'], function ($, urlBuilder, loader, $t) {
    $(document).ready(function () {
        $('#delete-account-submit').on('click', function () {
            if ($('input[name="delete_account"]').is(':checked')) {
                $('#delete-account-submit').prop('disabled', true);
                $.ajax({
                    url: urlBuilder.build('accountsection/account/delete'),
                    type: 'POST',
                    data: {
                        delete_account: 1
                    },
                    success: function (response) {
                        $('#notification').addClass("success").text($t('Your request has been submitted successfully !!'));
                    },
                    error: function (xhr, status, error) {
                       $('#notification').addClass("error").text($t('An error occurred while submitting your request. Please try again later.'));
                    }
                });
            }
        });
    });
});
