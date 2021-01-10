<?php

namespace MStroink\StecaGrid\Tests\TestCase;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getFixtureFileContents(string $filename): string
    {
        return file_get_contents(dirname(__DIR__) . '/Fixture/' . $filename);
    }
}
