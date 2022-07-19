<?php

$addon = rex_addon::get('once');
echo rex_view::title($addon->i18n('once_title'));

$form = rex_config_form::factory($addon->name);

$field = $form->addTextAreaField('input', null, ["class" => "form-control codemirror", "data-codemirror-theme" =>"themename", "data-codemirror-mode" => "php/htmlmixed"]);
$field->setLabel(rex_i18n::msg('once_input_label'));

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('once_input'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');

function once_execute()
{
    if ($input = rex_config::get("once", "input")) {
        $code = preg_replace('/^\<\?(?:php)?/', '', $input);
        $is = ini_set('display_errors', '1');
        ob_start();
        $return = false;

        try {
            $return = eval($code);
            echo $return;
        } catch (Throwable $exception) {
            echo get_class($exception).': '.$exception->getMessage();
        }

        $output = ob_get_clean();
        ini_set('display_errors', $is);
        if ($output) {
            $output = str_replace(["\r\n\r\n", "\n\n"], "\n", trim(strip_tags($output)));
            $output = preg_replace('@in ' . preg_quote(__FILE__, '@') . "\([0-9]*\) : eval\(\)'d code @", '', $output);
        }
        return $output;
    }
    return "";
}

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('once_result'), false);
$fragment->setVar('body', once_execute(), false);
echo $fragment->parse('core/page/section.php');

rex_config::set("once", "input", "");
