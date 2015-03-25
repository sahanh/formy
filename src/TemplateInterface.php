<?php
namespace SH\Formy;

interface TemplateInterface
{
    public function setForm(Form $form);

    public function render();
}
