+ function($) {
    'use strict';

    let dropZone = document.getElementById('drop-zone')
    let uploadForm = document.getElementById('js-upload-form')

    let startUpload = function(file) {
        $("input[type='file']")
            .prop("files", file)
            .closest("form")
            .submit()
    }

    uploadForm.addEventListener('submit', function(e) {
        let uploadFile = document.getElementById('js-upload-file').files;
        e.preventDefault()

        startUpload(uploadFile)
    })

    dropZone.ondrop = function(e) {
        e.preventDefault();
        this.className = 'upload-drop-zone';

        startUpload(e.dataTransfer.files)
    }

    dropZone.ondragover = function() {
        this.className = 'upload-drop-zone drop';
        return false;
    }

    dropZone.ondragleave = function() {
        this.className = 'upload-drop-zone';
        return false;
    }

}(jQuery);