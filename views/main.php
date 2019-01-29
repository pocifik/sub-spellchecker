<?php

use core\Core;

/**
 * @var array $data
 */

$error = Core::$session->get('errors', true);

?>

<?php if (isset($error)) : ?>
    <div class="alert alert-danger" role="alert">
        <strong>Error!</strong> <?= $error ?>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-header"><strong>SUB Spellchecker</strong><br><small>Upload your sub file</small></div>
    <div class="card-body">
        <form action="/result" method="post" enctype="multipart/form-data" id="js-upload-form">
            <div class="form-inline">
                <div class="form-group">
                    <input type="file" name="file" id="js-upload-file">
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-sm btn-primary" id="js-upload-submit">Upload file</button>
        </form>
        <br>
        <div class="upload-drop-zone" id="drop-zone">
            Drag and drop file here
        </div>
    </div>
</div>

<?php

$scripts = '<script src="/src/js/main.js"></script>';

?>