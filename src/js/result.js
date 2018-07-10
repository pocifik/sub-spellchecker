+ function($) {

    $('.btn-fix').click(function() {
        let id = $(this).data('id');
        let local_id = $(this).data('local-id');
        let error_block = $('#error-block-'+id+'-'+local_id);
        let parent = error_block.parent();
        let other_error_blocks = parent.find('.error-block');
        let fix_text = $('#select-replacement-'+id+'-'+local_id+' option:selected').text();
        let textarea = $('#text-'+id);
        let text = textarea.val().replaceAt(error_block.data('offset'), error_block.data('length'), fix_text);
        textarea.val(text);

        other_error_blocks.each(function() {
            let offset = $(this).data('offset');
            let replaced_offset = error_block.data('offset');
            if (offset > replaced_offset) {
                let diff_length = fix_text.length - error_block.data('length');
                $(this).data('offset', offset + diff_length);
            }
        });

        error_block.data('length', fix_text.length);

        $(this).text('Исправлено');
        $(this).removeClass('btn-outline-secondary').addClass('btn-success');
        $(this).attr('disabled', 'disabled');
    });

    $('.select-replacement').change(function () {
        let btn = $(this).parent().find('.btn-fix');
        btn.text('Исправить');
        btn.removeClass('btn-success').addClass('btn-outline-secondary');
        btn.removeAttr('disabled');
    });

}(jQuery);

String.prototype.replaceAt=function(index, length, replacement) {
    let end = index + length;
    return this.substr(0, index) + replacement + this.substr(end);
};

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});