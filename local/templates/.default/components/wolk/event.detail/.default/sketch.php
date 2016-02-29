<? use Bitrix\Main\Localization\Loc; ?>
<div class="pagetitle"><?=ucfirst(Loc::getMessage('sketch'))?>
    <div class="active pagetitle__button customizable"><?=Loc::getMessage('help')?></div>
</div>
<div class="pagedescription">
    <?Helper::includeFile('sketch_desc_'.\Bitrix\Main\Context::getCurrent()->getLanguage());?>
</div>
<div id="designer" style="margin-top:40px; width: 940px; height:680px" onmouseout="ru.octasoft.oem.designer.Main.stopDragging()" ></div>
<div class="sketchNav">
    <div @click.prevent="prevStep" class="basketcontainer__backstepbutton">
        <?= Loc::getMessage('back') ?>
    </div>
    <div @click="nextStep" class="basketcontainer__nextstepbutton">
        <?= Loc::getMessage('next') ?>
    </div>
</div>

<div class="commentsForm">
    <div class="commentsForm__title">Comments</div>
    <textarea placeholder="Additioan information about your order"></textarea>
</div>