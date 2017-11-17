<? use Bitrix\Main\Localization\Loc; ?>

<div class="form-group">
    <label class="control-label"><?= Loc::getMessage('HEADER_PROPERTY_TEXT') ?>:</label>
    <input type="text" class="form-control" name="PRODUCTS[<?= $pbid ?>][PROPS][TEXT]" value="<?= $pval ?>" />
 </div>