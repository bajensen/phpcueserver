<?php
$config = require 'config.php';

$action = $argv[1];
$switch = $argv[2];

if (in_array($action, ['on', 'off'])) {
    $switchConfig = $config['switches'][$switch];
    $cmd = $switchConfig[$action];

    if (! array_key_exists('use_general', $switchConfig) || $switchConfig['use_general'] == true) {
        $generalCmd = $config['general'][$action];
        $cmd = $generalCmd . $cmd;
    }

    if (array_key_exists('status_method', $switchConfig)  && $switchConfig['status_method'] == 'cache') {
        setSwitchState($switch, $action);
    }

    execCommand($cmd);

    echo $action;
}

if (in_array($action, ['status'])) {
    $switchSettings = $config['switches'][$switch];

    $method = array_key_exists('status_method', $switchSettings) ? $switchSettings['status_method'] : 'button';

    $button = array_key_exists($action, $switchSettings) ? $switchSettings[$action] : null;

    if ($method == 'none') {
        echo 0;
    }
    elseif ($method == 'always') {
        echo 1;
    }
    elseif ($method == 'button') {
        echo getButtonLevel($button);
    }
    elseif ($method == 'playback_run_mode') {
        echo (int) (getPlaybackField('runMode') == '0');
    }
    elseif ($method == 'cache') {
        echo (int) (getSwitchState($switch));
    }
}

function getSwitchState ($switch) {
    return @file_get_contents(__DIR__ . '/.tmp_switch_' . $switch) == 'on';
}

function setSwitchState ($switch, $state) {
    file_put_contents(__DIR__ . '/.tmp_switch_' . $switch, $state);
}

function getPlaybackField ($field = 'runMode', $playbackId = 1) {
    global $config;

    $params = ['req' => 'PI', 'id' => $playbackId];

    $url = $config['server'] . '/get.cgi?' . http_build_query($params);

    $result = file_get_contents($url);

    if ($result !== false) {
        $decoded = unpack('Cplayback/CrunMode/CoutputLevel/CcombineMode/SfadeTimer/SfolllowTimer/LstreamTimer/ScurrentCue/SnextCue/LfadeTimes/SfollowTime/SlinkCue/a8reserved/a32currentName/a32nextName', $result);

        if (! array_key_exists($field, $decoded)) {
            throw new Exception('Result did not contain key for: ' . $field);
        }

        return $decoded[$field];
    }
    else {
        throw new Exception('file_get_contents failed on URL: ' . $url);
    }

}

function getSystemInfo ($field = 'serial') {
    global $config;

    $params = ['req' => 'SI'];

    $url = $config['server'] . '/get.cgi?' . http_build_query($params);

    $result = file_get_contents($url);

    echo 'len:' . strlen($result) . PHP_EOL;

    if ($result !== false) {
        $decoded = unpack('a16serial/a24deviceName/a12firmwareVersion/a24timeStr/Cmodel/ChasPassword', $result);

        print_r($decoded);

        if (! array_key_exists($field, $decoded)) {
            throw new Exception('Result did not contain key for: ' . $field);
        }

        return $decoded[$field];
    }
    else {
        throw new Exception('file_get_contents failed on URL: ' . $url);
    }

}

function getButtonLevel ($button) {
    global $config;

    $params = ['req' => 'BV'];

    $url = $config['server'] . '/get.cgi?' . http_build_query($params);

    $result = file_get_contents($url);

    if ($result !== false) {
        $decoded = unpack('C8button', $result);

        if (! array_key_exists($button, $decoded)) {
            throw new Exception('Result did not contain key for: ' . $button);
        }

        return (int) ($decoded[$button] > 0);
    }
    else {
        throw new Exception('file_get_contents failed on URL: ' . $url);
    }
}

function execCommand ($command) {
    global $config;

    $params = ['cmd' => $command];

    $url = $config['server'] . '/exe.cgi?' . http_build_query($params);

    $result = file_get_contents($url);

    if ($result === false) {
        throw new Exception('file_get_contents failed on URL: ' . $url);
    }
}

