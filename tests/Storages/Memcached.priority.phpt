<?php

/**
 * Test: Nette\Caching\Storages\MemcachedStorage priority test.
 */

use Nette\Caching\Storages\MemcachedStorage;
use Nette\Caching\Storages\SQLiteJournal;
use Nette\Caching\Cache;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!MemcachedStorage::isAvailable()) {
	Tester\Environment::skip('Requires PHP extension Memcache.');
}


$storage = new MemcachedStorage('localhost', 11211, '', new SQLiteJournal(TEMP_DIR . '/journal.s3db'));
$cache = new Cache($storage);


// Writing cache...
$cache->save('nette-priority-key1', 'value1', [
	Cache::PRIORITY => 100,
]);

$cache->save('nette-priority-key2', 'value2', [
	Cache::PRIORITY => 200,
]);

$cache->save('nette-priority-key3', 'value3', [
	Cache::PRIORITY => 300,
]);

$cache->save('nette-priority-key4', 'value4');


// Cleaning by priority...
$cache->clean([
	Cache::PRIORITY => '200',
]);

Assert::null($cache->load('nette-priority-key1'));
Assert::null($cache->load('nette-priority-key2'));
Assert::truthy($cache->load('nette-priority-key3'));
Assert::truthy($cache->load('nette-priority-key4'));
