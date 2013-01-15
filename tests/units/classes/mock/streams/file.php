<?php

namespace mageekguy\atoum\tests\units\mock\streams;

use
	mageekguy\atoum,
	mageekguy\atoum\mock\stream,
	mageekguy\atoum\mock\streams\file as testedClass
;

require_once __DIR__ . '/../../../runner.php';

class file extends atoum\test
{
	public function testClass()
	{
		$this->testedClass->extends('mageekguy\atoum\mock\stream');
	}

	public function testGet()
	{
		$this
			->if($file = testedClass::get())
			->then
				->object($file)->isInstanceOf('mageekguy\atoum\mock\stream\controller')
				->castToString($file)->isNotEmpty()
				->string(file_get_contents($file))->isEmpty()
				->variable($fileResource = fopen($file, 'r'))->isNotEqualTo(false)
				->boolean(is_readable($file))->isTrue()
				->boolean(is_writable($file))->isFalse()
				->boolean(rename($file, stream::defaultProtocol . '://' . uniqid()))->isTrue()
				->boolean(fclose($fileResource))->isTrue()
				->boolean(unlink($file))->isTrue()
			->if($file = testedClass::get($path = uniqid()))
			->then
				->object($file)->isInstanceOf('mageekguy\atoum\mock\stream\controller')
				->castToString($file)->isEqualTo(stream::defaultProtocol . '://' . $path)
				->string(file_get_contents($file))->isEmpty()
				->variable($fileResource = fopen($file, 'r'))->isNotEqualTo(false)
				->boolean(is_readable($file))->isTrue()
				->boolean(is_writable($file))->isFalse()
				->boolean(rename($file, stream::defaultProtocol . '://' . uniqid()))->isTrue()
				->boolean(fclose($fileResource))->isTrue()
				->boolean(unlink($file))->isTrue()
		;
	}

	public function testCanNotBeOpened()
	{
		$this
			->if($file = testedClass::get())
			->then
				->object($file->canNotBeOpened())->isIdenticalTo($file)
				->boolean(@fopen($file, 'r'))->isFalse()
		;
	}

	public function testCanBeOpened()
	{
		$this
			->if($file = testedClass::get())
			->and($file->canNotBeOpened())
			->then
				->object($file->canBeOpened())->isIdenticalTo($file)
				->variable(@fopen($file, 'r'))->isNotFalse()
		;
	}
}