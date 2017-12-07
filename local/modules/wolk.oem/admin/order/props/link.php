<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Oem\Basket; ?>

<div class="form-group">
    <label class="control-label"><?= Loc::getMessage('HEADER_PROPERTY_LINK') ?>:</label>
    <input type="text" class="form-control" name="PRODUCTS[<?= $pbid ?>][PROPS][<?= Basket::PARAM_LINK ?>]" value="<?= $pval ?>" />
 </div>