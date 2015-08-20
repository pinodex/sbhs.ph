#!/usr/bin/env php
<?php

/**
 * Compile assets
 *
 * @author       Raphael Marco <pinodex@outlook.ph>
 * @link         http://pinodex.github.io
 * @copyright    Copyright (c) 2015, Raphael Marco.
 */

if (PHP_SAPI !== 'cli') {
	echo "This script must be run in console";
	exit;
}

$cwd = getcwd();
$assets = require 'assets.php';

$supports = array('css', 'js');

function getApiLink($type) {
	return array(
		'js' => 'http://closure-compiler.appspot.com/compile',
		'css' => 'http://cssminifier.com/raw'
	)[$type];
}

function getFields($type, $raw) {
	return array(
		'js' => array(
			'js_code' => $raw,
			'compilation_level' => 'SIMPLE_OPTIMIZATIONS',
			'output_info' => 'compiled_code'
		),
		'css' => array(
			'input' => $raw
		)
	)[$type];
}

$all = array();

if (isset($assets['css'])) {
	if (!$handle = @fopen($assets['css']['output'], 'a')) {
		echo "[ERROR] Cannot create CSS output file.\n";
		exit;
	}

	foreach ($assets['css']['files'] as $file) {
		$file = '../public' . $file;

		if (!file_exists($file)) {
			echo "[WARN] {$file} does not exist. Skipping...\n";
			continue;
		}

		$size = number_format(filesize($pfile) / 1024, 2);

		echo "[INFO] Adding {$file}... ({$size}KB)\n";
		fwrite($handle, file_get_contents($pfile) . "\n");
	}
}

foreach ($manifest as $asset) {
	if (!in_array($asset->type, $supports)) {
		echo "[ERROR] Invalid type {$asset->type}.\n";
		exit;		
	}

	$output = $cwd . '/' . $asset->output;

	if (file_exists($output)) {
		/*
		echo "[ERROR] {$asset->output} already exists. Overwrite? [y/n]: ";

		if (trim(fgets(STDIN)) != 'y') {
			echo "Aborted\n";
			exit;
		}
		*/

		unlink($output);
	}

	if (!$handle = @fopen($output, 'a')) {
		echo "[ERROR] Cannot create output file.\n";
		exit;
	}

	$file_count = 0;

	foreach ($asset->files as $file) {
		$pfile = $cwd . '/' . $asset->directory . '/' . $file;

		if (!file_exists($pfile)) {
			echo "[WARN] {$file} does not exist. Skipping...\n";
			continue;
		}

		$size = number_format(filesize($pfile) / 1024, 2);

		echo "[INFO] Adding {$file}... ({$size}KB)\n";
		fwrite($handle, file_get_contents($pfile) . "\n");

		$file_count++;
	}

	fclose($handle);

	echo "[INFO] {$file_count} file(s) added\n";

	if (property_exists($asset, 'minify') && $asset->minify) {
		echo "[INFO] Preparing files to minify...\n";

		$raw = file_get_contents($output);
		$ch = curl_init();

		curl_setopt_array($ch, array(
			CURLOPT_URL => getApiLink($asset->type),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => http_build_query(
				getFields($asset->type, $raw)
			)
		));

		echo "[INFO] Minifying file...\n";

		if (!$result = curl_exec($ch)) {
			echo "[ERROR] An error occurred minifying file";
			echo "        Skipping file";

			continue;
		}

		echo "[INFO] Writing minified file...\n";

		$handle = fopen($output, 'w');

		fwrite($handle, $result);
		fclose($handle);

		echo "[INFO] File minified\n";
	}

	$prepend  = "/* Compiled on " . date('m/d/y H:i:s') . " */\n";
	$prepend .= file_get_contents($output);

	file_put_contents($output, $prepend);
	
	$total = number_format(filesize($output) / 1024, 2);
	$all[] = $asset->output . " - {$total}KB\n";

	echo "[INFO] Done writing output file ({$total}KB)\n";
}

echo "\n";
echo "Results: \n";

foreach ($all as $stmt) {
	echo $stmt;
}

echo "\n";
echo "Completed!\n";