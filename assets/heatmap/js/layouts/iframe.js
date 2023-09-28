"use strict";
$(document).ready(function() {
    $('.visual_editor').summernote({
        height: 180,
        minHeight: 180,
        toolbar: [
            ['font', ['bold', 'underline','italic','clear']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
});