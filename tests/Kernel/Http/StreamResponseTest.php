<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use EasyWeChat\Kernel\Http\StreamResponse;
use EasyWeChat\Tests\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

class StreamResponseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('testing'));
    }

    public function testSave()
    {
        $response = new StreamResponse(200, [], file_get_contents(STUBS_ROOT.'/files/image.png'));
        $directory = vfsStream::url('testing');

        // default filename
        $filename = $response->save($directory);
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($filename));
        $this->assertStringEndsWith('.png', $filename);

        // custom filename
        $filename = $response->save($directory, 'custom-filename');
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($filename));
        $this->assertSame('custom-filename.png', $filename);
    }

    public function testSaveAs()
    {
        $response = Mockery::mock(StreamResponse::class.'[save]');
        $response->expects()->save('dir', 'filename')->andReturn('filename.png')->once();

        $this->assertSame('filename.png', $response->saveAs('dir', 'filename'));
    }
}
