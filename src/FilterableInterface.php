<?php
namespace SH\Formy;

interface FilterableInterface
{
    public function setForm(Form $form);

    public function filter();
}