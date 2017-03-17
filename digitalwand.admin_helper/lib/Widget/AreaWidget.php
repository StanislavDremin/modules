<?php
/**
 * Created by PhpStorm.
 * User: stanislav
 * Date: 05.07.16
 * Time: 22:57
 */

namespace DigitalWand\AdminHelper\Widget;


use Bitrix\Main\Application;

class AreaWidget extends HelperWidget
{
	protected $root;

	public function __construct(array $settings)
	{
		parent::__construct($settings);
		$this->root = Application::getDocumentRoot();
	}


	protected function getEditHtml()
	{
		$file = $this->getSettings('FILE');
		global $APPLICATION;

		if(strlen($file)){
			if(file_exists($this->root.$file)){
				ob_start();
				$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					".default",
					Array(
						"AREA_FILE_SHOW"      => "file",     // Показывать включаемую область
						'PATH' => $file
					)
				);
				$out = ob_get_contents();
				ob_end_clean();

				return $out;
			} else {
				echo 'Файл не найден';
			}
		} elseif(strlen($this->getSettings('HTML')) > 0) {
			ob_start();
			echo $this->getSettings('HTML');
			$out = ob_get_contents();
			ob_end_clean();

			return $out;
		}

		return false;
	}

	public function generateRow(&$row, $data)
	{

	}

	public function showFilterHtml()
	{

	}
	
	public function showBasicEditField()
	{
		print '<tr>';
		print '<td colspan="2" width="100%">';
		print '<b style="text-align: center; display: block">'.$this->getSettings('TITLE').'</b>';
		print $this->getEditHtml();
		print '</td>';
		print '</tr>';
	}

}