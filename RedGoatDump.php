<?php

class RedGoatDump
{

	private $cli;
	private $c = "\x20\x20";
	private $invokeFromArray;

	public function __construct()
	{
		$this->cli = php_sapi_name() === 'cli';
	}

	public function dump($args)
	{
		$this->invokeFromArray = false;

		echo (!$this->cli ? "<pre>" : "");
		$a = is_array($args) ? true : false;
		// echo $a ? "array(size=" . count($args) . ") {\n" : "";
		echo $this->__dump($args);
		// echo $a ? "}\n" : "";
		echo "\n";

		echo (!$this->cli ? "</pre>" : "");
	}

	private function __dump($v, $in=-1, $k=0, $continue = true)
	{

		$r = "";
		$bigSpace = ($in > -1 ? str_repeat($this->c , $in) : "" );
		$t = gettype($v);

		//### key
		$key = $bigSpace
		. $this->c
		. "["
		. ($this->isString($k) ? "\"" : "")
		. $k
		. ($this->isString($k) ? "\"" : "")
		. "] => ";

		if(in_array($t, array('object','array'))) {

			$this->invokeFromArray = true;

			//### Array case
			if($t === "array") {

				$r .= ($in > -1 ? $key : "") . "array(size=" . count($v) . ") \n";

				if(count($v) == 0 ) {
					$r .= $key . "{empty}";
				}

				$in++;
			}

			//### Object case
			if($t === "object") {
				$reflect = new ReflectionClass($v);

				$className = $reflect->getName();

				$r .= $key;
				$r .=  $t . "(class::" . $className . ")\x20(" . count($v) .") \n";

				// Properties
				$properties = $reflect->getProperties();

				foreach($properties as $property) {

					$attr = $this->checkAttribute($property, false);

					$property->setAccessible(true);
					$propertyVal = $property->getValue($v);
					$propertyType = gettype($propertyVal);

					$r .= $bigSpace . $this->c . $this->c
					. "\""
					. $property->getName()
					."\":$attr";
					if(!is_null($propertyVal)) {
						$this->invokeFromArray = false;
						$r .= " => "
						. $this->getPrettyTypeAndCount($propertyVal, $propertyType, $k, $in, false);
					}
					$r .= "\n";

					$property->setAccessible(false);
				}

				// Methods
				$methods  = $reflect->getMethods();

				foreach($methods as $method) {
					$attr = $this->checkAttribute($method);
					$r .= $bigSpace . $this->c . $this->c
					. "@" . $className . "::" . $method->getName() . "()\x20$attr\n";
				}

				$in++;
				$continue = ($className === "stdClass" ? true : false);
			}

			//reiterate
			foreach($v as $sk=>$vl) {
				$r .= $this->__dump($vl, $in, $sk, $continue);
			}

		} else {
			if($continue) {
				$r .= $this->getPrettyTypeAndCount($v, $t, $k, $in, true);
			}
		}

		return $r;
	}


	private function getPrettyTypeAndCount($v, $t, $k=null, $in=-1, $ret=false) :string
	{
		$bigSpace = ($in > -1 ? str_repeat($this->c , $in) : "" ) . $this->c;

		$r = "";

		if($this->invokeFromArray) {
			$r	.=
			$bigSpace
			. "["
			. ($this->isString($k) ? "\"" : "")
			. $k
			. ($this->isString($k) ? "\"" : "")
			."] => ";
		}

		if($t === "NULL") {
			$r .= "NULL";
		} else if($t === "integer") {
			$r .= "int($v)";
		} else if($t === "string") {
			$r .= "\"$v\"\x20str(" . strlen($v) . ")" ;
		} else if($t === "boolean") {
			$r .= "bool(" . ($v ? 'true' : 'false') . ")";
		}

		$r .= $ret ? "\n" : "";

		return $r;
	}

	private function isString($k) :bool
	{
		if(gettype($k) === "string") {
			return true;
		}
		return false;

	}

	private function checkAttribute($cont, $isMethod=true) :string
	{
		$attr = $this->cli ? "<" : "&lt;";

		if (!$isMethod && $cont->isDefault()) {
			$attr .= "default";
		}
		if ($cont->isPrivate()) {
			$attr .= (strlen($attr) > 4 ? "\x20" : "") . "private";
		}
		if ($cont->isProtected()) {
			$attr .= (strlen($attr) > 4 ? "\x20" : "") . "protected";
		}
		if ($cont->isPublic()) {
			$attr .= (strlen($attr) > 4 ? "\x20" : "") . "public";
		}
		if ($cont->isStatic()) {
			$attr .= (strlen($attr) > 4 ? "\x20" : "") . "static";
		}

		$attr .= $this->cli ? ">" : "&gt;";

		return $attr;
	}
}