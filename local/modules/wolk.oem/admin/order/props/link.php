<? use Bitrix\Main\Localization\Loc; ?>

<div class="form-group">
    <label class="control-label"><?= Loc::getMessage('HEADER_PROPERTY_LINK') ?>:</label>
    <input type="text" class="form-control" name="PRODUCTS[<?= $pbid ?>][PROPS][LINK]" value="<?= $pval ?>" />
 </div>