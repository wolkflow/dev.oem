<? use Wolk\Oem\Basket; ?>

<div class="form-group">
    <label class="control-label">Форма подвесной конструкции:</label>
	
    <table class="form-handing-table">
		<tbody>
			<tr>
				<td colspan="6">
					<label class="control-label">Компания</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][COMPANY]" value="<?= $pval['COMPANY'] ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label class="control-label">Пафильон №</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][PAVILION]" value="<?= $pval['PAVILION'] ?>" />
				</td>
				<td colspan="2">
					<label class="control-label">Зал №</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][HALL]" value="<?= $pval['HALL'] ?>" />
				</td>
				<td colspan="2">
					<label class="control-label">Стенд №</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][STAND]" value="<?= $pval['STAND'] ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label class="control-label">Размер (мм)</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][SIZE]" value="<?= $pval['SIZE'] ?>" />
				</td>
				<td colspan="2">
					<label class="control-label">Материал</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][MATERIAL]" value="<?= $pval['MATERIAL'] ?>" />
				</td>
				<td colspan="2">
					<label class="control-label">Вес (кг)</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][WEIGHT]" value="<?= $pval['WEIGHT'] ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<label class="control-label">Список размещения оборудования на структуру</label>
					<textarea class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][LIST]"><?= $pval['LIST'] ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<label class="control-label">Материал</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][ONMATERIAL]" value="<?= $pval['ONMATERIAL'] ?>" />
				</td>
				<td colspan="3">
					<label class="control-label">Вес на одну точку (кг)</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][POINTWEIGHT]" value="<?= $pval['POINTWEIGHT'] ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<label class="control-label">Высота	(мм)</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][HEIGHT]" value="<?= $pval['HEIGHT'] ?>" />
				</td>
				<td colspan="3">
					<label class="control-label">Итоговый вес (кг)</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][FULLWEIGHT]" value="<?= $pval['FULLWEIGHT'] ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<label class="control-label">Ответственный за проект структуры</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][PERSON_PROJECT]" value="<?= $pval['PERSON_PROJECT'] ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<label class="control-label">Ответственный за монтажные работы</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][PERSON_MOUNT]" value="<?= $pval['PERSON_MOUNT'] ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<label class="control-label">Номер телефона</label>
					<input type="text" class="form-control" name="PRODUCTS[<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>][PHONE]" value="<?= $pval['PHONE'] ?>" />
				</td>
			</tr>
		</tbody>
	</table>
	
	<style>
		.form-handing-table {
			width: 100%;
		}
		.form-handing-table td {
			padding: 6px 2px 2px 2px;
		}
	</style>
</div>


