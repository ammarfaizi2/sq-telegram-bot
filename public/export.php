<?php

require_once __DIR__."/../bootstrap/web_init.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if (PHP_SAPI === "cli") {

	if (!isset($argv[1])) {
		print "\$argv[1] must be provided!\n";
		exit(1);
	}

	$spreadsheet = new Spreadsheet();
	
	$sheet = $spreadsheet->getActiveSheet();

	$sheet->setCellValue("B2", "No.");
	$sheet->setCellValue("C2", "User ID");
	$sheet->setCellValue("D2", "Name");
	$sheet->setCellValue("E2", "Username");
	$sheet->setCellValue("F2", "Email");
	$sheet->setCellValue("G2", "Wallet");
	$sheet->setCellValue("H2", "Balance");
	$sheet->setCellValue("I2", "Twitter");
	$sheet->setCellValue("J2", "Facebook");
	$sheet->setCellValue("K2", "Medium");
	$sheet->setCellValue("L2", "Joined At");
	$sheet->setCellValue("M2", "Started At");

	$pdo = \Sq\DB::pdo();
	$st = $pdo->prepare("SELECT * FROM `users`;");
	$st->execute();

	$no = 1;
	$pointer = 3;
	while ($r = $st->fetch(PDO::FETCH_ASSOC)) {
		$sheet->setCellValue("B{$pointer}", "{$no}.");
		$sheet->setCellValue("C{$pointer}", $r["id"]);
		$sheet->setCellValue("D{$pointer}", $r["name"]);
		$sheet->setCellValue("E{$pointer}", $r["username"]);
		$sheet->setCellValue("F{$pointer}", $r["email"]);
		$sheet->setCellValue("G{$pointer}", $r["wallet"]);
		$sheet->setCellValue("H{$pointer}", $r["balance"]);
		$sheet->setCellValue("I{$pointer}", $r["twitter_link"]);
		$sheet->setCellValue("J{$pointer}", $r["facebook_link"]);
		$sheet->setCellValue("K{$pointer}", $r["medium_link"]);
		$sheet->setCellValue("L{$pointer}", $r["joined_at"]);
		$sheet->setCellValue("M{$pointer}", $r["started_at"]);
		$pointer++; $no++;
	}

	$writer = new Xlsx($spreadsheet);
	$writer->save($argv[1]);

	exit;
}

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}


if (isset($_GET["action"]) && $_GET["action"] == 1) {
	$file = BASEPATH."/public/xlsx/crytoveno_member_".date("Y_m_d__H_i_s").".xlsx";


	$f = escapeshellarg($file);
	$cmd = shell_exec($std = "nohup /usr/bin/env php ".__FILE__." {$f} >> /dev/null 2>&1 &");
	print json_encode(["f" => $file], JSON_UNESCAPED_SLASHES);
}


if (isset($_GET["check"])) {
	print json_encode(["f" => file_exists($_GET["check"])]);
}