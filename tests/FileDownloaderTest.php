<?php

use Hups\Util\FileDownloader;

class FileDownloaderTest extends PHPUnit_Framework_TestCase
{
	public function testCorrentMimeDetection()
	{
		$mime = FileDownloader::getFileMime(__DIR__ . '/test_files/test.txt');
		$this->assertEquals($mime, 'text/plain');
		
		$mime = FileDownloader::getFileMime(__DIR__ . '/test_files/test.zip');
		$this->assertEquals($mime, 'application/zip');

		$mime = FileDownloader::getFileMime(__DIR__ . '/test_files/test.html');
		$this->assertEquals($mime, 'text/html');
	}
}

