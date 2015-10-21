# github-star-php
The script parses dependencies from `composer.json` and stars their repositories on GitHub.


## Install
```
cd github-star-php/
composer install
```


## Usage
```
php star.php composer.json
# or
GITHUB_TOKEN=xxx php star.php composer.json
```


## Star packages from other package managers
- [npm, bower](https://github.com/mjhasbach/github-star)
