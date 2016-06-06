<?php namespace Fuzzybaird\DatabaseSync\Tests\Models;

use Fuzzybaird\DatabaseSync\Post;
use PluginTestCase;

class DatabaseToolsTest extends PluginTestCase
{
    public function testCreateFirstPost()
    {
        $post = Post::create(['title' => 'Hi!']);
        $this->assertEquals(1, $post->id);
    }
}