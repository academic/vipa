function setupSubmissionForm(abstractTemplates) {
    $(function () {
        function reviseCitationOrders() {
            $.each($('#citation-forms-container').find('.citation-item-order'), function (index, value) {
                $(this).html(index + 1);
            });
        }

        reviseCitationOrders();
        setInterval(function () {
            reviseCitationOrders();
        }, 1000);
        function reviseInstitutionInputs() {
            $.each($('.institutionNotListed'), function (index, value) {
                var $institutionSelect = $(this).parent().parent().parent().parent().find('.institution');
                var $institutionName = $(this).parent().parent().parent().parent().find('.institutionName');
                if (this.checked) {
                    $institutionName.parent().removeClass('hidden');
                    $institutionSelect.parent().addClass('hidden');
                } else {
                    $institutionName.parent().addClass('hidden');
                    $institutionSelect.parent().removeClass('hidden');
                }
            });
        }

        reviseInstitutionInputs();
        setInterval(function () {
            reviseInstitutionInputs();
        }, 1000);
        $(document).on('change', '.institutionNotListed', function () {
            var $institutionSelect = $(this).parent().parent().parent().parent().find('.institution');
            var $institutionName = $(this).parent().parent().parent().parent().find('.institutionName');
            if (this.checked) {
                $institutionName.parent().removeClass('hidden');
                $institutionSelect.parent().addClass('hidden');
            } else {
                $institutionName.parent().addClass('hidden');
                $institutionSelect.parent().removeClass('hidden');
            }
        });
        $(document).on('click', '.toggle-author-detail', function () {
            var $authorDetails = $(this).parent().find('.author-details');
            if ($authorDetails.is(':visible')) {
                $(this).find('i').removeClass('fa-arrow-up');
                $(this).find('i').addClass('fa-arrow-down');
                $authorDetails.hide();
            } else {
                $(this).find('i').removeClass('fa-arrow-down');
                $(this).find('i').addClass('fa-arrow-up');
                $authorDetails.show();
            }
        });

        $.each(abstractTemplates, function (locale, abstractTemplate) {
            var findRequiredLocaleAbstractInput = $('.a2lix_translationsFields-' + locale).find('.note-editable');
            var abstractValue = findRequiredLocaleAbstractInput.html();
            if (abstractValue == '<p><br></p>') {
                findRequiredLocaleAbstractInput.html(abstractTemplate);
            }
        });
    });
}
