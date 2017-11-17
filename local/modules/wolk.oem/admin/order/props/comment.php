<? use Bitrix\Main\Localization\Loc; ?>

<div class="form-group">
    <label class="control-label"><?= Loc::getMessage('HEADER_PROPERTY_COMMENT') ?>:</label>
    <textarea name="PRODUCTS[<?= $pbid ?>][PROPS][COMMENT]" class="form-control"><?= $pval ?></textarea>
 </div>