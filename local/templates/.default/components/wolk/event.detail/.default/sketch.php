<? use Bitrix\Main\Localization\Loc; ?>

<div class="pagetitle">
	<?= ucfirst(Loc::getMessage('sketch')) ?>
    <div class="active pagetitle__button customizable">
    <?= Loc::getMessage('help') ?>
</div>
</div>
<div class="pagedescription">
    <? Helper::includeFile('sketch_desc_'.\Bitrix\Main\Context::getCurrent()->getLanguage()); ?>
</div>

<div id="designer" style="margin-top:40px; width: 940px; height:680px" onmouseout="ru.octasoft.oem.designer.Main.stopDragging()"></div>

<div class="sketchNav">
    <div @click.prevent="prevStep" class="basketcontainer__backstepbutton">
        <?= Loc::getMessage('back') ?>
    </div>
    <div @click="nextStep" class="basketcontainer__nextstepbutton">
        <?= Loc::getMessage('next') ?>
    </div>
</div>

<div class="sketchAfter">
    <div class="sketchAfterLeft">
        <div class="commentsForm">
            <div class="commentsForm__title">
				<?= Loc::getMessage('comments') ?>
			</div>
            <textarea v-model="orderDesc" placeholder="<?= Loc::getMessage('additional_info') ?>"><?= strip_tags($arResult['ORDER']['ORDER_DATA']['USER_DESCRIPTION']) ?></textarea>
        </div>
    </div>
    <div class="sketchAfterRight">
        <div class="pagetitle"><?= Loc::getMessage('Review your configuration') ?></div>
        <div class="reviewconfigurationcontainer customizable_border">
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle">
					<?= Loc::getMessage('system_booth') ?>:
				</span>
                {{ selectedStand['LANG_NAME_' + curLang] || selectedStand.NAME}}
            </div>
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle">
					<?= Loc::getMessage('width') ?> &amp; <?= Loc::getMessage('depth') ?>: 
				</span>
                {{ selectedParams.WIDTH }} &times; {{ selectedParams.DEPTH }}
            </div>
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle">
					<?= Loc::getMessage('type') ?>:
                </span>
				{{ selectedParams.TYPE || '<?= Loc::getMessage('individual') ?>' | t }}
            </div>
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle">
					<?= Loc::getMessage('exhibition') ?>: 
				</span>
				{{ curEvent.NAME }}
            </div>
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle">
					<?= Loc::getMessage('location') ?>:
				</span>
				{{ curEvent.LOCATION }}
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
