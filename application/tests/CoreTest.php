<?php

/**
 * Tests Kohana Core
 *
 * @TODO Use a virtual filesystem (see phpunit doc on mocking fs) for find_file etc.
 *
 * @group application
 * @group application.core
 
 *
 * @package    Kohana
 * @category   Tests
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) Kohana Team
 * @license    https://koseven.ga/LICENSE.md
 * 
 * while(true) do clear; ./vendor/bin/phpunit --bootstrap=modules/unittest/bootstrap.php  application/tests/CoreTest.php; sleep 8; done;
 
 */
class CoreTest extends Unittest_TestCase
{

    public function test_find_file_use_file_cache()
    {
        // initeate koaha with caching
        
        // now enable cache module
        $modules = Kohana::modules();
        $modules['cache'] = MODPATH . 'cache';
        Kohana::modules($modules);
        
        kohana::$caching = TRUE;
        
        // setup kohana file cache config.
        file_put_contents(APPPATH . 'config/cache.php', "<?php\nreturn array( 'prefix'=>'cache1_', 'file'=> array('driver' => 'file', 'cache_dir' => APPPATH.'cache/','default_expire' => 3600));");
        
        // utilise file cache so kohana_cache class is loaded else its not loaded and file_cache function is used in Kohana_Core.hp
        Cache::instance('file');
        
        // we muust file Koahna_Cache file now
        $this->assertTrue(class_exists('Kohana_Cache'));
        
        // shutdown kohana so we can write cache file
        Kohana::shutdown_handler();
        
        // kohana::deinit();
        
        // read cache generated suing Koahan_Core::file_cache
        $this->assertInternalType('array', Kohana::file_cache('Kohana::find_file()'));
    }
}