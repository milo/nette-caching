<?php

/**
 * Test: Nette\Caching\Storages\SQLiteJournal database file permissions.
 */

use Nette\Caching\Storages\SQLiteJournal;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('pdo_sqlite')) {
	Tester\Environment::skip('Requires PHP extension pdo_sqlite.');
} elseif (defined('PHP_WINDOWS_VERSION_BUILD')) {
	Tester\Environment::skip('UNIX test only.');
}


$file = TEMP_DIR . '/' . basename(__FILE__) . '.sqlite';

test(function () use ($file) {
	@unlink($file);
	Assert::false(file_exists($file));

	umask(0);
	new SQLiteJournal($file);

	Assert::same(0666, fileperms($file) & 0777);
});


test(function () use ($file) {
	@unlink($file);
	Assert::false(file_exists($file));

	umask(0777);
	new SQLiteJournal($file);

	Assert::same(0, fileperms($file) & 0777);
});
