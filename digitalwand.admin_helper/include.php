<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('digitalwand.admin_helper',
    array(
        'DigitalWand\AdminHelper\EventHandlers' => 'lib/EventHandlers.php',

        'DigitalWand\AdminHelper\Helper\Exception' => 'lib/helper/Exception.php',

        'DigitalWand\AdminHelper\Helper\AdminInterface' => 'lib/helper/AdminInterface.php',
        'DigitalWand\AdminHelper\Helper\AdminBaseHelper' => 'lib/helper/AdminBaseHelper.php',
        'DigitalWand\AdminHelper\Helper\AdminListHelper' => 'lib/helper/AdminListHelper.php',
        'DigitalWand\AdminHelper\Helper\AdminSectionListHelper' => 'lib/helper/AdminSectionListHelper.php',
        'DigitalWand\AdminHelper\Helper\AdminEditHelper' => 'lib/helper/AdminEditHelper.php',
        'DigitalWand\AdminHelper\Helper\AdminSectionEditHelper' => 'lib/helper/AdminSectionEditHelper.php',

        'DigitalWand\AdminHelper\EntityManager' => 'lib/EntityManager.php',

        'DigitalWand\AdminHelper\Widget\HelperWidget' => 'lib/widget/HelperWidget.php',
        'DigitalWand\AdminHelper\Widget\CheckboxWidget' => 'lib/widget/CheckboxWidget.php',
        'DigitalWand\AdminHelper\Widget\ComboBoxWidget' => 'lib/widget/ComboBoxWidget.php',
        'DigitalWand\AdminHelper\Widget\StringWidget' => 'lib/widget/StringWidget.php',
        'DigitalWand\AdminHelper\Widget\NumberWidget' => 'lib/widget/NumberWidget.php',
        'DigitalWand\AdminHelper\Widget\FileWidget' => 'lib/widget/FileWidget.php',
        'DigitalWand\AdminHelper\Widget\TextAreaWidget' => 'lib/widget/TextAreaWidget.php',
        'DigitalWand\AdminHelper\Widget\HLIBlockFieldWidget' => 'lib/widget/HLIBlockFieldWidget.php',
        'DigitalWand\AdminHelper\Widget\DateTimeWidget' => 'lib/widget/DateTimeWidget.php',
        'DigitalWand\AdminHelper\Widget\IblockElementWidget' => 'lib/widget/IblockElementWidget.php',
        'DigitalWand\AdminHelper\Widget\UrlWidget' => 'lib/widget/UrlWidget.php',
        'DigitalWand\AdminHelper\Widget\VisualEditorWidget' => 'lib/widget/VisualEditorWidget.php',
        'DigitalWand\AdminHelper\Widget\UserWidget' => 'lib/widget/UserWidget.php',
        'DigitalWand\AdminHelper\Widget\OrmElementWidget' => 'lib/widget/OrmElementWidget.php',
        'DigitalWand\AdminHelper\Widget\AreaWidget' => 'lib/widget/AreaWidget.php',
        'DigitalWand\AdminHelper\Widget\MultiShopWidget' => 'lib/widget/MultiShopWidget.php',
        'DigitalWand\AdminHelper\Widget\SectionWidget' => 'lib/widget/SectionWidget.php',
    )
);

spl_autoload_register(function ($className) {
	preg_match('/^(.*?)([\w]+)$/i', $className, $matches);
	if (count($matches) < 3) {
		return;
	}
	$filePath = implode(DIRECTORY_SEPARATOR, array(
		__DIR__,
		"lib",
		str_replace('\\', DIRECTORY_SEPARATOR, trim($matches[1], '\\')),
		str_replace('_', DIRECTORY_SEPARATOR, $matches[2]) . '.php'
	));
	$filePath = str_replace('DigitalWand'.DIRECTORY_SEPARATOR .'AdminHelper'.DIRECTORY_SEPARATOR,'',$filePath);
	$filePath = preg_replace('#DigitalWand\/AdminHelper\/#','',$filePath);
	$filePath = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

	if (is_readable($filePath) && is_file($filePath)) {
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});
