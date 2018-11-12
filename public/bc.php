<?php
declare(ticks=1)
require_once __DIR__."/../bootstrap/web_init.php";

if (isset($argv[1])) {
	pcntl_signal(SIGCHLD, SIG_IGN);

	$st = Sq\DB::pdo()->prepare("SELECT `id` FROM `users`;");
	$st->execute();
	$i = 0;
	while ($r = $st->fetch(PDO::FETCH_NUM)) {
		$i++;
		$pid = pcntl_fork();
		if ($pid == 0) {
			print "{$r[0]}: ".
			\Sq\Exe::sendMessage(
				[
					"chat_id" => $r[0],
					"text"  => $argv[1],
				]
			)["out"];
			exit(0);
		}

		if ($i == 10) {
			$status = null;
			print "\n\nWait...\n";
			pcntl_waitpid($pid, $status, WUNTRACED);
			$i = 0;
			print "\n\nOK!\n";
		}
	}
}
