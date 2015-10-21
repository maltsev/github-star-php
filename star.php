#!/usr/bin/php
<?php
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else if (file_exists(__DIR__ . '/../../autoload.php')) {
    require_once __DIR__ . '/../../autoload.php';
} else {
    echo "Composer's autoload.php is not found\n";
    exit(1);
}

require_once __DIR__ . '/functions.php';

$composerJsonRelativePath = isset($argv[1]) ? $argv[1] : '';
if (! $composerJsonRelativePath) {
    echo "composer.json path is not set\n";
    exit(1);
}

$composerJsonPath = realpath($composerJsonRelativePath);
if (! $composerJsonPath) {
    echo "composer.json is not found\n";
    exit(1);
}

if (! is_readable($composerJsonPath)) {
    echo "composer.json access error\n";
    exit(1);
}


$githubToken = getenv('GITHUB_TOKEN');
if (! $githubToken) {
    $githubToken = trim(readline('Github token: '));
}

if (! $githubToken) {
    echo "Github token is not set\n";
    exit(1);
}


$dependenciesNames = getDependenciesNames($composerJsonPath);

$packagistClient = new Packagist\Api\Client();
$githubRepositories = array();
foreach ($dependenciesNames as $packageName) {
    try {
        $githubRepository = getGithubRepository($packagistClient, $packageName);
    } catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
        if (strpos($e->getMessage(), '[reason phrase] Not Found') === false) {
            throw $e;
        }
    }

    if (! $githubRepository) {
        echo "Github repository for package $packageName is not found\n";
        continue;
    }

    $githubRepositories[] = $githubRepository;
}

if (empty($githubRepositories)) {
    exit;
}

$client = new \Github\Client();
$client->authenticate($githubToken, '', Github\Client::AUTH_URL_TOKEN);

foreach ($githubRepositories as $githubRepository) {
    $client->api('current_user')->starring()->star($githubRepository['author'], $githubRepository['name']);
    echo $githubRepository['url'] . "\n";
}
