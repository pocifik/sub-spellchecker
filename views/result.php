<form method="post" action="/save">
<input type="hidden" name="filename" value="">
<?php foreach ($data['matches'] as $key => $match) : ?>
<div class="card mb-3">
    <div class="card-header">
        <h4 class="m-0 d-inline-block">Диалог №<?= $key ?> <span class="badge badge-danger align-middle"></span></h4>
        <i class="fa fa-chevron-down fa float-right p-1 border rounded" style="cursor: pointer;" aria-hidden="true" data-toggle="collapse" href="#dialogue-<?= $key ?>" role="button" aria-expanded="false">
        </i>
    </div>
    <div class="card-body p-2 collapse show" id="dialogue-<?= $key ?>">
        <div class="p-2 mb-2 rounded border">
            <span><?= $match['text'] ?></span>
        </div>
        <hr class="my-2">
        <?php foreach ($match['errors'] as $local_key => $error) :

            $error_tag_text = mb_substr($match['tags'], $error['offset'], $error['length']);

            $has_tags = false;
            if (strpos($error_tag_text, '{') !== false) {
                $has_tags = true;
            }

        ?>
        <div class="error-block" id="error-block-<?= $key ?>-<?= $local_key ?>" data-offset="<?= $error['offset'] ?>" data-length="<?= $error['length'] ?>">
            <div class="p-2 mb-2 rounded border">
                <span><?= mb_substr($match['tags'], 0, $error['offset']) ?></span><span class="text-danger"><?= $error_tag_text ?></span><span><?= mb_substr($match['tags'], $error['offset'] + $error['length'], -1) ?></span>
            </div>
            <div class="d-block my-2 w-50">
                <div class="input-group">
                    <select class="custom-select select-replacement" id="select-replacement-<?= $key ?>-<?= $local_key ?>" title="Варианты замены">
                        <?php foreach ($error['replace'] as $replace) : ?>
                            <option><?= $replace['value'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="input-group-append" <?php if ($has_tags) : ?> tabindex="0" data-toggle="tooltip" data-placement="top" title="Ошибка содержит теги. Автоисправление невозможно."<?php endif; ?>>
                        <button class="btn btn-outline-secondary btn-fix" data-id="<?= $key ?>" data-local-id="<?= $local_key ?>" type="button"<?= $has_tags ? ' disabled style="pointer-events: none;"' : '' ?>>Исправить</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <hr class="my-2">
        <div class="d-block">
            <div class="input-group">
                <textarea class="form-control text-final" name="dialogue[<?= $key ?>]" title="Конечный вариант" id="text-<?= $key ?>"><?= $match['tags'] ?></textarea>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<button class="btn btn-primary mb-3" type="submit">Сохранить</button>
</form>
<?php

$scripts = '<script src="/src/js/result.js"></script>';

?>
