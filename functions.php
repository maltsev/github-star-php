<?php

function getDependenciesNames($composerJsonFilePath) {
    $composerJsonRaw = file_get_contents($composerJsonFilePath);
    $composerData = json_decode($composerJsonRaw, true);

    $requirePackages = isset($composerData['require']) ? $composerData['require'] : array();
    $requireDevPackages = isset($composerData['require-dev']) ? $composerData['require-dev'] : array();

    $packages = array_merge($requirePackages, $requireDevPackages);
    $packagesNames = array_keys($packages);
    return array_filter($packagesNames, function ($packageName) {
        return strpos($packageName, '/');
    });
}


function getGithubRepository($packagistClient, $packageName) {
    $package = $packagistClient->get($packageName);
    $repositoryUrl = $package->getRepository();
    if (strpos($repositoryUrl, 'github.com') === false) {
        return;
    }

    $repositoryUrl = str_replace('.git', '', $repositoryUrl);
    $urlParts = explode('/', $repositoryUrl);
    $repositoryName = array_pop($urlParts);
    $repositoryAuthor = array_pop($urlParts);
    return array(
        'author' => $repositoryAuthor,
        'name' => $repositoryName,
        'url' => $repositoryUrl
    );
}
