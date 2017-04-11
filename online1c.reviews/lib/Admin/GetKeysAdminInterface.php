<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 01.03.2017
 */

namespace Esd\Main\Admins\Keys;

use Bitrix\Main\Localization\Loc;
use DigitalWand\AdminHelper\Helper\AdminInterface;
use DigitalWand\AdminHelper\Widget;

class GetKeysAdminInterface extends AdminInterface
{

	/**
	 * �������� ���������� �������: ������ ����� � �����. ����� ������ ������� ������ ����:
	 *
	 * ```
	 * array(
	 *    'TAB_1' => array(
	 *        'NAME' => Loc::getMessage('VENDOR_MODULE_ENTITY_TAB_1_NAME'),
	 *        'FIELDS' => array(
	 *            'FIELD_1' => array(
	 *                'WIDGET' => new StringWidget(),
	 *                'TITLE' => Loc::getMessage('VENDOR_MODULE_ENTITY_FIELD_1_TITLE'),
	 *                ...
	 *            ),
	 *            'FIELD_2' => array(
	 *                'WIDGET' => new NumberWidget(),
	 *                'TITLE' => Loc::getMessage('VENDOR_MODULE_ENTITY_FIELD_2_TITLE'),
	 *                ...
	 *            ),
	 *            ...
	 *        )
	 *    ),
	 *    'TAB_2' => array(
	 *        'NAME' => Loc::getMessage('VENDOR_MODULE_ENTITY_TAB_2_NAME'),
	 *        'FIELDS' => array(
	 *            'FIELD_3' => array(
	 *                'WIDGET' => new DateTimeWidget(),
	 *                'TITLE' => Loc::getMessage('VENDOR_MODULE_ENTITY_FIELD_3_TITLE'),
	 *                ...
	 *            ),
	 *            'FIELD_4' => array(
	 *                'WIDGET' => new UserWidget(),
	 *                'TITLE' => Loc::getMessage('VENDOR_MODULE_ENTITY_FIELD_4_TITLE'),
	 *                ...
	 *            ),
	 *            ...
	 *        )
	 *    ),
	 *  ...
	 * )
	 * ```
	 *
	 * ��� TAB_1..2 - ���������� ���� �����, FIELD_1..4 - �������� �������� � ������� ��������. TITLE ��� ���� ��������
	 * �� �����������, � ����� ������ �� ����� ������������� �� ������.
	 *
	 * ����� ��������� ���������� � ������� �������� �������� �������� ��. � ������ HelperWidget.
	 *
	 * @see \DigitalWand\AdminHelper\Widget\HelperWidget
	 *
	 * @return array[]
	 */
	public function fields()
	{
		return array(
			'TAB_1'=>[
				'NAME'=>'������ ������ �� ������� �������',
				'FIELDS'=>array(
					'DATA'=>array(
						'WIDGET' => new Widget\AreaWidget(array(
							'HTML' => '<div id="admin_get_keys"></div>',
							'js' => [
								'/local/src/js/react/react-with-addons.min.js',
								'/local/src/js/react/react-dom.min.js',
								'/local/src/js/sweet_alert/sweetalert.min.js',
								'/local/src/js/is.min.js',
								'/local/modules/esd.main/theme/js/builds/admin.keys.js',
							],
							'css' => [
								'/local/templates/esd_main/css/font-awesome.min.css',
								'/local/src/css/animate.min.css',
								'/local/src/css/preloaders.css',
								'/local/src/js/sweet_alert/sweetalert.css',
								'/local/modules/esd.main/theme/css/esd.main.css',
							],
							'BX_LIBS' => ['jquery','popup']
						)),
					)
				)
			],
			'TAB_2' => [
				'NAME' => '������ ������ �� ������',
				'FIELDS' => array(
					'DATA_2' => [
						'WIDGET' => new Widget\AreaWidget(array(
							'HTML' => '<div id="admin_get_keys_product"></div>',
							'js' => [
								'/local/src/js/react/react-with-addons.min.js',
								'/local/src/js/react/react-dom.min.js',
								'/local/src/js/sweet_alert/sweetalert.min.js',
								'/local/src/js/is.min.js',
								'/local/modules/esd.main/theme/js/builds/admin.keys_for_product.js',
							],
							'css' => [
								'/local/templates/esd_main/css/font-awesome.min.css',
								'/local/src/css/animate.min.css',
								'/local/src/css/preloaders.css',
								'/local/src/js/sweet_alert/sweetalert.css',
								'/local/modules/esd.main/theme/css/esd.main.css',
							],
							'BX_LIBS' => ['jquery','popup']
						)),
					]
				)
			],
		);
	}

	/**
	 * ������ ������� �������� � �����������. ����� ������ ������� ������ ����:
	 *
	 * ```
	 * array(
	 *    '\Vendor\Module\Entity\AdminInterface\EntityListHelper' => array(
	 *        'BUTTONS' => array(
	 *            'RETURN_TO_LIST' => array('TEXT' => Loc::getMessage('VENDOR_MODULE_ENTITY_RETURN_TO_LIST')),
	 *            'ADD_ELEMENT' => array('TEXT' => Loc::getMessage('VENDOR_MODULE_ENTITY_ADD_ELEMENT'),
	 *            ...
	 *        )
	 *    ),
	 *    '\Vendor\Module\Entity\AdminInterface\EntityEditHelper' => array(
	 *        'BUTTONS' => array(
	 *            'LIST_CREATE_NEW' => array('TEXT' => Loc::getMessage('VENDOR_MODULE_ENTITY_LIST_CREATE_NEW')),
	 *            'LIST_CREATE_NEW_SECTION' => array('TEXT' =>
	 * Loc::getMessage('VENDOR_MODULE_ENTITY_LIST_CREATE_NEW_SECTION'),
	 *            ...
	 *        )
	 *    )
	 * )
	 * ```
	 *
	 * ���
	 *
	 * ```
	 * array(
	 *    '\Vendor\Module\Entity\AdminInterface\EntityListHelper',
	 *    '\Vendor\Module\Entity\AdminInterface\EntityEditHelper'
	 * )
	 * ```
	 *
	 * ���:
	 * <ul>
	 * <li> `Vendor\Module\Entity\AdminInterface` - namespace �� ������������� ������� AdminHelper.
	 * <li> `BUTTONS` - ���� ��� ������� � ��������� ��������� ���������� (��������� � ������ getButton()
	 *          ������ AdminBaseHelper).
	 * <li> `LIST_CREATE_NEW`, `LIST_CREATE_NEW_SECTION`, `RETURN_TO_LIST`, `ADD_ELEMENT` - ���������� ��� ���������
	 *          ����������.
	 * <li> `EntityListHelper` � `EntityEditHelper` - ������������� ������ ��������.
	 *
	 * ��� ������� ����� ���������� ���� � ������.
	 *
	 * @see \DigitalWand\AdminHelper\Helper\AdminBaseHelper::getButton()
	 *
	 * @return string[]
	 */
	public function helpers()
	{
		return array(
			'\Esd\Main\Admins\Keys\GetKeysEditHelper'
		);
	}
}