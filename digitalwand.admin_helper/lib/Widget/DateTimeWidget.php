<?php

namespace DigitalWand\AdminHelper\Widget;

use Bitrix\Main\Type;

class DateTimeWidget extends HelperWidget
{
	/**
	 * Генерирует HTML для редактирования поля
	 * @see AdminEditHelper::showField();
	 * @return mixed
	 */
	protected function getEditHtml()
	{
		$time = $this->getValue();
		$value = '';
		if($time instanceof Type\DateTime || $time instanceof Type\Date){
			$value = $time->format('d.m.Y H:i:s');
		} else {
			$value = ConvertTimeStamp(strtotime($this->getValue()));
		}

		return \CAdminCalendar::CalendarDate($this->getEditInputName(), $value, 10, true);
	}

	/**
	 * Генерирует HTML для поля в списке
	 * @see AdminListHelper::addRowCell();
	 * @param CAdminListRow $row
	 * @param array $data - данные текущей строки
	 * @return mixed
	 */
	public function generateRow(&$row, $data)
	{
		$time = $this->getValue();
		$value = '';
		if($time instanceof Type\DateTime || $time instanceof Type\Date){
			$value = $time->format('d.m.Y H:i:s');
		} else {
			$value = ConvertTimeStamp(strtotime($this->getValue()));
		}
		if (isset($this->settings['EDIT_IN_LIST']) AND $this->settings['EDIT_IN_LIST'])
		{
			$row->AddCalendarField($this->getCode());
		}
		else
		{
			$arDate = ParseDateTime($value);

			if ($arDate['YYYY'] < 10)
			{
				$stDate = '-';
			}
			else
			{
				$stDate = ConvertDateTime($value, "DD.MM.YYYY HH:MI:SS", "ru");
			}

			$row->AddViewField($this->getCode(), $stDate);
		}
	}

	/**
	 * Генерирует HTML для поля фильтрации
	 * @see AdminListHelper::createFilterForm();
	 * @return mixed
	 */
	public function showFilterHtml()
	{
		list($inputNameFrom, $inputNameTo) = $this->getFilterInputName();
		print '<tr>';
		print '<td>' . $this->settings['TITLE'] . '</td>';
		print '<td width="0%" nowrap>' . CalendarPeriod($inputNameFrom, $$inputNameFrom, $inputNameTo, $$inputNameTo, "find_form") . '</td>';
	}

	/**
	 * Сконвертируем дату в формат Mysql
	 * @return boolean
	 */
	public function processEditAction()
	{
		try
		{
			$this->setValue(new \Bitrix\Main\Type\Datetime($this->getValue()));
		} catch (\Exception $e)
		{
		}
		if (!$this->checkRequired())
		{
			$this->addError('REQUIRED_FIELD_ERROR');
		}
	}
}