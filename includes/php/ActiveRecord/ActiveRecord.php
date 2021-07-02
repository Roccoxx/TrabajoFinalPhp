<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ValueObject/ValueObject.php';

abstract class ActiveRecord
{
	abstract public function get();
	abstract public function set(ValueObject $value);
	abstract public function fetch($id);
	abstract public function insert();
	abstract public function update();
	abstract public function delete($id);
}