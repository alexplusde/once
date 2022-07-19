<?php
#
$addon = rex_addon::get('once');

$form = rex_config_form::factory($addon->name);

$field = $form->addTextAreaField('input', null, ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('once_input_label'));

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('once_config'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
