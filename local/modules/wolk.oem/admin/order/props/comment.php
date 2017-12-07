<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Oem\Basket; ?>

<div class="form-group">
    <label class="control-label"><?= Loc::getMessage('HEADER_PROPERTY_COMMENT') ?>:</label>
    <textarea name="PRODUCTS[<?= $pbid ?>][PROPS][<?= Basket::PARAM_COMMENT ?>]" class="form-control"><?= $pval ?></textarea>
 </div>