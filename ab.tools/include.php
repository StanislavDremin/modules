<?php
spl_autoload_register(function ($className) {
	preg_match('/^(.*?)([\w]+)$/i', $className, $matches);
	if (count($matches) < 3){
		return;
	}

	$filePath = implode(DIRECTORY_SEPARATOR, array(
		__DIR__,
		"lib",
		str_replace('\\', DIRECTORY_SEPARATOR, trim($matches[1], '\\')),
		str_replace('_', DIRECTORY_SEPARATOR, $matches[2]).'.php',
	));
	$filePath = str_replace('AB'.DIRECTORY_SEPARATOR.'Tools'.DIRECTORY_SEPARATOR, '', $filePath);
	$filePath = preg_replace('#AB\/Tools\/#', '', $filePath);
	$filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

	if (is_readable($filePath) && is_file($filePath)){
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});

//$root = \Bitrix\Main\Application::getDocumentRoot();
//$routesFile = $root.'/local/php_interface/ab.tools/routes.php';
//if(file_exists($routesFile) && is_readable($routesFile)){
//	/** @noinspection PhpIncludeInspection */
//	require_once $routesFile;
//}

//$ev = \Bitrix\Main\EventManager::getInstance();
//$ev->registerEventHandlerCompatible('fileman','OnBeforeHTMLEditorScriptRuns', 'ab.tools', '\AB\Tools\EventHandlers', 'onIncludeHTMLEditorScript');
$path = '/local/modules/ab.tools/asset';
$asset = [
	'react' => [
		'js' => [
//			$path.'/js/shim/es6-shim.min.js',
//			$path.'/js/shim/es6-sham.min.js',
			$path.'/js/react/react-with-addons.min.js',
			$path.'/js/react/react-dom.min.js',
		],
		'css' => [
			$path.'/css/preloaders.css',
			$path.'/css/animate.min.css',
		],
	],
	'isjs' => [
		'js' => [$path.'/js/is.min.js'],
	],
	'sweetalert' => [
		'js' => [
			$path.'/js/sweetalert.min.js',
		],
		'css' => [
			$path.'/css/sweetalert.css',
		],
	],
	'mask' => ['js' => $path.'/js/jquery.mask.min.js'],
];
foreach ($asset as $name => $arItem) {
	CJSCore::RegisterExt($name, $arItem);
}

use Symfony\Component\VarDumper\VarDumper;
if (!function_exists('dump')){
	function dump($var, $show = false)
	{
		global $USER;
		if(!is_object($USER))
			$USER = new \CUser();

		if($USER->IsAdmin() || $show){
			$iteArgs = debug_backtrace();
			$bt = $iteArgs[0];
			$dRoot = $_SERVER["DOCUMENT_ROOT"];
			$dRoot = str_replace("/", "\\", $dRoot);
			$bt["file"] = str_replace($dRoot, "", $bt["file"]);
			$dRoot = str_replace("\\", "/", $dRoot);
			$bt["file"] = str_replace($dRoot, "", $bt["file"]);

			?>
			<div style='font-size:9pt; border: 1px solid #999'>
				<div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?=$bt["file"]?> [<?=$bt["line"]?>]</div>
				<? foreach (func_get_args() as $var) {
					VarDumper::dump($var);
				}?>
			</div>
			<?
		}
	}
}