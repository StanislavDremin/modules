<?php

namespace DigitalWand\AdminHelper\Widget;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Р’С‹РїР°РґР°СЋС‰РёР№ СЃРїРёСЃРѕРє.
 *
 * Р”РѕСЃС‚СѓРїРЅС‹Рµ РѕРїС†РёРё:
 * <ul>
 * <li> STYLE - inline-СЃС‚РёР»Рё</li>
 * <li> VARIANTS - РјР°СЃСЃРёРІ СЃ РІР°СЂРёР°РЅС‚Р°РјРё Р·РЅР°С‡РµРЅРёР№ РёР»Рё С„СѓРЅРєС†РёСЏ РґР»СЏ РёС… РїРѕР»СѓС‡РµРЅРёСЏ РІ С„РѕСЂРјР°С‚Рµ РєР»СЋС‡=>Р·Р°РіРѕР»РѕРІРѕРє
 *        РќР°РїСЂРёРјРµСЂ:
 *            [
 *                1=>'РџРµСЂРІС‹Р№ РїСѓРЅРєС‚',
 *                2=>'Р’С‚РѕСЂРѕР№ РїСѓРЅРєС‚'
 *            ]
 * </li>
 * <li> DEFAULT_VARIANT - ID РІР°СЂРёР°РЅС‚Р° РїРѕ-СѓРјРѕР»С‡Р°РЅРёСЋ</li>
 * </ul>
 */
class ComboBoxWidget extends HelperWidget
{
    static protected $defaults = array(
        'EDIT_IN_LIST' => true
    );

    /**
     * @inheritdoc
     *
     * @see AdminEditHelper::showField();
     *
     * @param bool $forFilter
     *
     * @return mixed
     */
    protected function getEditHtml()
    {
        return $this->getComboBox();
    }

    /**
     * @inheritdoc
     */
    protected function getMultipleEditHtml()
    {
        return $this->getComboBox(true);
    }

    /**
     * Р’РѕР·РІСЂР°С‰Р°РµС‚ РҐРўРњР›-РєРѕРґ СЃ РєРѕРјР±РѕР±РѕРєСЃРѕРј.
     *
     * @param bool $multiple РњРЅРѕР¶РµСЃС‚РІРµРЅРЅС‹Р№ СЂРµР¶РёРј.
     * @param bool $forFilter РљРѕРјР±РѕР±РѕРєСЃ Р±СѓРґРµС‚ РІС‹РІРѕРґРёС‚СЊСЃСЏ РІ Р±Р»РѕРєРµ СЃ С„РёР»СЊС‚СЂРѕРј.
     *
     * @return string
     */
    protected function getComboBox($multiple = false, $forFilter = false)
    {
        if ($multiple) {
            $value = $this->getMultipleValue();
        } else {
            $value = $this->getValue();
        }

        $style = $this->getSettings('STYLE');

        $variants = $this->getVariants();

        if (!$multiple)
        {
            array_unshift($variants, array(
                'ID' => null,
                'TITLE' => null
            ));
        }

        if (empty($variants)) {
            $comboBox = Loc::getMessage('DIGITALWAND_AH_MISSING_VARIANTS');
        } else {
            $name = $forFilter ? $this->getFilterInputName() : $this->getEditInputName();
            $comboBox = '<select name="' . $name . ($multiple ? '[]' : null) . '"
                '. ($multiple ? 'multiple="multiple"' : null) . '
                style="' . $style . '">';

            foreach ($variants as $variant) {
                $selected = false;

                if ($variant['ID'] == $value) {
                    $selected = true;
                }

                if ($multiple && in_array($variant['ID'], $value)) {
                    $selected = true;
                } elseif ($variant['ID'] === $value) {
                    $selected = true;
                }

                $comboBox .= "<option value='" . static::prepareToTagAttr($variant['ID']) . "' " . ($selected ? "selected" : "") . ">"
                    . static::prepareToTagAttr($variant['TITLE']) . "</option>";
            }

            $comboBox .= '</select>';
        }

        return $comboBox;
    }

    /**
     * @inheritdoc
     */
    protected function getValueReadonly()
    {
        $variants = $this->getVariants();
        $value = $variants[$this->getValue()]['TITLE'];

        return static::prepareToOutput($value);
    }

    /**
     * @inheritdoc
     */
    protected function getMultipleValueReadonly()
    {
        $variants = $this->getVariants();
        $values = $this->getMultipleValue();
        $result = '';

        if (empty($variants)) {
            $result = 'РќРµ СѓРґР°Р»РѕСЃСЊ РїРѕР»СѓС‡РёС‚СЊ РґР°РЅРЅС‹Рµ РґР»СЏ РІС‹Р±РѕСЂР°';
        } else {
            foreach ($variants as $id => $data) {
                $name = strlen($data["TITLE"]) > 0 ? $data["TITLE"] : "";

                if (in_array($id, $values)) {
                    $result .= static::prepareToOutput($name) . '<br/>';
                }
            }
        }

        return $result;
    }

    /**
     * Р’РѕР·РІСЂР°С‰Р°РµС‚ РјР°СЃСЃРёРІ РІ СЃР»РµРґСѓСЋС‰РµРј С„РѕСЂРјР°С‚Рµ:
     * <code>
     * array(
     *      '123' => array('ID' => 123, 'TITLE' => 'ololo'),
     *      '456' => array('ID' => 456, 'TITLE' => 'blablabla'),
     *      '789' => array('ID' => 789, 'TITLE' => 'pish-pish'),
     * )
     * </code>
     * 
     * Р РµР·СѓР»СЊС‚Р°С‚ Р±СѓРґРµС‚ РІС‹РІРѕРґРёС‚СЊСЃСЏ РІ РєРѕРјР±РѕР±РѕРєСЃРµ.
     * @return array
     */
    protected function getVariants()
    {
        $variants = $this->getSettings('VARIANTS');

        if (is_array($variants) AND !empty($variants)) {
            return $this->formatVariants($variants);
        } elseif (is_callable($variants)) {
            $var = $variants();

            if (is_array($var)) {
                return $this->formatVariants($var);
            }
        }

        return array();
    }

    /**
     * РџСЂРёРІРѕРґРёС‚ РІР°СЂРёР°РЅС‚С‹ Рє РЅСѓР¶РЅРѕРјСѓ С„РѕСЂРјР°С‚Сѓ, РµСЃР»Рё РѕРЅРё Р·Р°РґР°РЅС‹ РІ РІРёРґРµ РѕРґРЅРѕРјРµСЂРЅРѕРіРѕ РјР°СЃСЃРёРІР°.
     *
     * @param $variants
     *
     * @return array
     */
    protected function formatVariants($variants)
    {
        $formatted = array();

        foreach ($variants as $id => $data) {
            if (!is_array($data)) {
                $formatted[$id] = array(
                    'ID' => $id,
                    'TITLE' => $data
                );
            }
        }

        return $formatted;
    }

    /**
     * @inheritdoc
     */
    public function generateRow(&$row, $data)
    {
        if ($this->settings['EDIT_IN_LIST'] AND !$this->settings['READONLY']) {
            $row->AddInputField($this->getCode(), array('style' => 'width:90%'));
        } else {
            $row->AddViewField($this->getCode(), $this->getValueReadonly());
        }
    }

    /**
     * @inheritdoc
     */
    public function showFilterHtml()
    {
        print '<tr>';
        print '<td>' . $this->getSettings('TITLE') . '</td>';
        print '<td>' . $this->getComboBox(false, true) . '</td>';
        print '</tr>';
    }

    /**
     * @inheritdoc
     */
    public function processEditAction()
    {
        if ($this->getSettings('MULTIPLE')) {
            $sphere = $this->data[$this->getCode()];
            unset($this->data[$this->getCode()]);

            foreach ($sphere as $sphereKey) {
                $this->data[$this->getCode()][] = array('VALUE' => $sphereKey);
            }
        }

        parent::processEditAction();
    }
}
