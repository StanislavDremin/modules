<?php

namespace DigitalWand\AdminHelper\Widget;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * Виджет для выбора элемента инфоблока.
 *
 * Доступные опции:
 * <ul>
 * <li> <b>IBLOCK_ID</b> - (int) ID инфоблока
 * <li> <b>INPUT_SIZE</b> - (int) значение атрибута size для input </li>
 * <li> <b>WINDOW_WIDTH</b> - (int) значение width для всплывающего окна выбора элемента </li>
 * <li> <b>WINDOW_HEIGHT</b> - (int) значение height для всплывающего окна выбора элемента </li>
 * </ul>
 *
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class IblockElementWidget extends NumberWidget
{
	static protected $defaults = array(
		'FILTER' => '=',
		'INPUT_SIZE' => 5,
		'WINDOW_WIDTH' => 600,
		'WINDOW_HEIGHT' => 500,
	);

	public function __construct(array $settings = array())
	{
		Loc::loadMessages(__FILE__);
		Loader::includeModule('iblock');

		parent::__construct($settings);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEditHtml()
	{
		$iblockId = (int)$this->getSettings('IBLOCK_ID');
		$inputSize = (int)$this->getSettings('INPUT_SIZE');
		$windowWidth = (int)$this->getSettings('WINDOW_WIDTH');
		$windowHeight = (int)$this->getSettings('WINDOW_HEIGHT');

		$name = 'FIELDS';
		$key = $this->getCode();

		$elementId = $this->getValue();

		if (!empty($elementId)){
			$rsElement = ElementTable::getById($elementId);

			if (!$element = $rsElement->fetchAll()){
				$element['NAME'] = Loc::getMessage('IBLOCK_ELEMENT_NOT_FOUND');
			}
		} else {
			$elementId = '';
		}

		return '<input name="'.$this->getEditInputName().'"
                     id="'.$name.'['.$key.']"
                     value="'.$elementId.'"
                     size="'.$inputSize.'"
                     type="text">'.
		'<input type="button"
                    value="..."
                    onClick="jsUtils.OpenWindow(\'/bitrix/admin/iblock_element_search.php?lang='.LANGUAGE_ID
		.'&amp;IBLOCK_ID='.$iblockId.'&amp;n='.$name.'&amp;k='.$key.'\', '.$windowWidth.', '
		.$windowHeight.');">'.'&nbsp;<span id="sp_'.md5($name).'_'.$key.'" >'
		.static::prepareToOutput($element['NAME'])
		.'</span>';
	}

	protected function getMultipleEditHtml()
	{
		$iblockId = (int)$this->getSettings('IBLOCK_ID');
		$inputSize = (int)$this->getSettings('INPUT_SIZE');
		$windowWidth = (int)$this->getSettings('WINDOW_WIDTH');
		$windowHeight = (int)$this->getSettings('WINDOW_HEIGHT');


		$style = $this->getSettings('STYLE');
		$size = $this->getSettings('SIZE');
		$uniqueId = $this->getEditInputHtmlId();

		$rsEntityData = null;

		if (!empty($this->data['ID'])){
			$entityName = $this->entityName;
			$rsEntityData = $entityName::getList(array(
				'select' => array('REFERENCE_' => $this->getCode().'.*'),
				'filter' => array('=ID' => $this->data['ID']),
			));
		}

		ob_start();
		?>

		<div id="<?=$uniqueId?>-field-container" class="<?=$uniqueId?>">
		</div>

		<script>

			var htmlInput = '';
			htmlInput += '{{field_original_id}}';
			htmlInput += '<input type="text" name="<?= $this->getCode()?>[{{field_id}}][<?=$this->getMultipleField('VALUE')?>]"';
			htmlInput += 'style="<?=$style?>" size="<?=$size?>" value="{{value}}"';
			htmlInput += ' />';
			htmlInput += '<input type="button"  value="..." ';
			htmlInput += 'onClick="jsUtils.OpenWindow(\'/bitrix/admin/iblock_element_search.php?lang=<?=LANGUAGE_ID?>&amp;IBLOCK_ID=<?=$iblockId?>&amp;n=\'<?= $this->getCode()?>[{{field_id}}][<?=$this->getMultipleField('VALUE')?>]\'&amp;k=\'<?=$this->getCode()?>\', <?=$windowWidth?>,<?=$windowHeight?>);" />';
			htmlInput += '&nbsp;<span id="sp_<?=md5('FIELDS')?>_<?=$this->getCode()?>"><?=static::prepareToOutput($element['NAME'])?></span>';

			htmlInput +=' />';


			var multiple = new MultipleWidgetHelper(
				'#<?= $uniqueId ?>-field-container',
				'{{field_original_id}}<input type="text" ' +
				'name="<?= $this->getCode()?>[{{field_id}}][<?=$this->getMultipleField('VALUE')?>]" style="<?=$style?>" size="<?=$size?>" value="{{value}}">'
			);


			<?
			if ($rsEntityData)
			{
				while($referenceData = $rsEntityData->fetch()){

					if (empty($referenceData['REFERENCE_'.$this->getMultipleField('ID')])){
						continue;
					}


					/*
					 * '<input name="'.$this->getEditInputName().'"
                     id="'.$name.'['.$key.']"
                     value="'.$elementId.'"
                     size="'.$inputSize.'"
                     type="text">'.




		'<input type="button"
                    value="..."
                    onClick="jsUtils.OpenWindow(\'/bitrix/admin/iblock_element_search.php?lang='.LANGUAGE_ID
		.'&amp;IBLOCK_ID='.$iblockId.'&amp;n='.$name.'&amp;k='.$key.'\', '.$windowWidth.', '
		.$windowHeight.');">'.'&nbsp;<span id="sp_'.md5($name).'_'.$key.'" >'
		.static::prepareToOutput($element['NAME'])
		.'</span>';
					 *
					 * */
				?>
					multiple.addField({
						value: '<?= static::prepareToJs($referenceData['REFERENCE_'.$this->getMultipleField('VALUE')]) ?>',
						field_original_id: '<input type="hidden" name="<?= $this->getCode()?>[{{field_id}}][<?= $this->getMultipleField('ID') ?>]"' +
						' value="<?= $referenceData['REFERENCE_'.$this->getMultipleField('ID')] ?>">',
						field_id: <?= $referenceData['REFERENCE_'.$this->getMultipleField('ID')] ?>,
						name: ''
					});
				<?
				}
			}
			?>

			// TODO Добавление созданных полей
			multiple.addField();
		</script>
		<?
		return ob_get_clean();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValueReadonly()
	{
		$elementId = $this->getValue();

		if (!empty($elementId)){
			$rsElement = ElementTable::getList([
				'filter' => [
					'ID' => $elementId,
				],
				'select' => [
					'ID',
					'NAME',
					'IBLOCK_ID',
					'IBLOCK.IBLOCK_TYPE_ID',
				],
			]);

			$element = $rsElement->fetch();

			return '<a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$element['IBLOCK_ID']
			.'&type='.$element['IBLOCK_ELEMENT_IBLOCK_IBLOCK_TYPE_ID'].'&ID='
			.$elementId.'&lang=ru">['.$elementId.'] '.static::prepareToOutput($element['NAME']).'</a>';
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function generateRow(&$row, $data)
	{
		if ($this->getSettings('MULTIPLE')) {

		} else {
			$elementId = $this->getValue();

			if (!empty($elementId)){
				$rsElement = ElementTable::getList([
					'filter' => [
						'ID' => $elementId,
					],
					'select' => [
						'ID',
						'NAME',
						'IBLOCK_ID',
						'IBLOCK.IBLOCK_TYPE_ID',
					],
				]);

				$element = $rsElement->fetch();

				$html = '<a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$element['IBLOCK_ID']
					.'&type='.$element['IBLOCK_ELEMENT_IBLOCK_IBLOCK_TYPE_ID'].'&ID='
					.$elementId.'&lang=ru">['.$elementId.'] '.static::prepareToOutput($element['NAME']).'</a>';
			} else {
				$html = '';
			}

			$row->AddViewField($this->getCode(), $html);
		}
	}
}
