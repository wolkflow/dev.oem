<div class="form-group">
    <label class="control-label">Файл:</label>
    
    <? if (!empty($pval)) { ?>
        <? $file = CFile::getByID($pval); ?>
        <? $path = CFile::getPath($pval); ?>
        <br/>
        <? if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['png', 'jpeg', 'jpg', 'gif'])) { ?>
            <a href="<?= $path ?>" target="_blank"><img width="56" height="56" src="<?= $path ?>" /></a>
        <? } else { ?>
            <a href="<?= $path ?>" target="_blank"><img width="56" height="56" src="/local/templates/.default/build/images/download.png" /></a>
        <? } ?>
    <? } ?>
    <input type="hidden" class="form-control" name="PRODUCTS[<?= $pbid ?>][<?= $pid ?>][PROPS][FILE]" value="<?= $pval ?>" />
    <input type="file" class="form-control" name="PRODUCTS[<?= $pbid ?>][<?= $pid ?>][PROPS][ATTACHE]" />
 </div>