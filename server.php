<?php

session_start();

function get_numbs($number) {
	$numbers = array('count' => 0, 'value' => []);
	for ($sum = 0; $sum < $number; ++$numbers['count'])
		$sum += $numbers['value'][] = rand(1, $number - $sum);
	if ($numbers['count'] < 10) {
		$count = 10 - $numbers['count'];
		for ($i = 0; $i < $count; ++$i, ++$numbers['count'])
			$numbers['value'][] = rand(1, $number);
	}
	return $numbers;
}

function get_pos($count) {
	$posis = [];
	$npos = null;
	$unique = null;
	for ($i = 0; $i < $count; ++$i) { 
		do {
			$unique = true;
			$npos = array('x' => rand(20, 400), 'y' => rand(20, 150));	
			foreach ($posis as &$pos) { 
				$length = sqrt(pow($npos['x'] - $pos['x'], 2) + pow($npos['y'] - $pos['y'], 2));
				if ($length <= 40) {
					$unique = false;
					break;
				}
			}
		} while (!$unique);
		$posis[] = $npos;
	}
	return $posis;
}

function update() {
	$number = rand(10, 99);
	$numbers = get_numbs($number);
	$posis = get_pos($numbers['count']);
	$balls = [];
	for ($i = 0; $i < $numbers['count']; ++$i)
		$balls[] = array('x' => $posis[$i]['x'], 'y' => $posis[$i]['y'], 'number' => $numbers['value'][$i]);
	$_SESSION['balls'] = $balls;
	$_SESSION['time'] = time();
	$_SESSION['contain'] = array('pos' => array('x' => 30, 'y' => 200), 'number' => $number);
}

if (isset($_POST['start'])) {
	update();
	$_SESSION['stime'] = 60;
	$data = array('balls' => $_SESSION['balls'], 'contain' => $_SESSION['contain']);
	echo json_encode($data);
	exit();
}

if (isset($_POST['mousedown'])) {
	$pos = &$_POST['mousedown'];
	$balls = &$_SESSION['balls'];
	$_SESSION['selected'] = -1;
	for ($i = 0; $i < count($balls); ++$i) {
		$ball = &$balls[$i];
		$length = sqrt(pow($ball['x'] - $pos['x'], 2) + pow($ball['y'] - $pos['y'], 2));
		if ($length <= 20) {
			$_SESSION['bias'] = array('x' => $ball['x'] - $pos['x'], 'y' => $ball['y'] - $pos['y']);
			$_SESSION['selected'] = $i;
		}
	}
	echo json_encode(array('focus' => $_SESSION['selected'] != -1 ? true : false ));
	exit();
}

if (isset($_POST['mousemove'])) {
	$ball = &$_SESSION['balls'][$_SESSION['selected']];
	$pos = &$_POST['mousemove'];
	$bias = &$_SESSION['bias'];
	$ball['x'] = $pos['x'] + $bias['x'];
	$ball['y'] = $pos['y'] + $bias['y'];
	$buff = &$ball;
	array_splice($_SESSION['balls'], $_SESSION['selected'], 1);
	$_SESSION['balls'][] = $buff;
	$_SESSION['selected'] = count($_SESSION['balls']) - 1;
	echo json_encode(array('balls' => $_SESSION['balls'], 'contain' => $_SESSION['contain']));
	exit();
}

if (isset($_POST['check'])) {
	$count = 0;
	$contain_pos = &$_SESSION['contain']['pos'];
	foreach ($_SESSION['balls'] as &$ball)
		if ($ball['x'] > $contain_pos['x'] + 70 - 20 &&
			$ball['x'] < $contain_pos['x'] + 70 + 250 + 20 &&
			$ball['y'] > $contain_pos['y'] - 20 &&
			$ball['y'] < $contain_pos['y'] + 120 + 20)
		$count += $ball['number'];
	$old_number = $_SESSION['contain']['number'];
	update();
	$res = $count == $old_number;
	if ($res) {
		if ($_SESSION['stime'] > 15) $_SESSION['stime'] -= 5;
	} else $_SESSION['stime'] = 60;
	echo json_encode(array('data' => array('balls' => $_SESSION['balls'], 'contain' => $_SESSION['contain']), 'res' => $res));
	exit();
}

if (isset($_POST['time'])) {
	$time = $_SESSION['stime'] - (time() - $_SESSION['time']);
	echo json_encode(array('time' => $time));
}
