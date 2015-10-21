# github-star-php
The script parses dependencies from `composer.json` and stars their repositories on GitHub.


## Install
```
composer require maltsev/github-star-php
```


## Usage
```
php star.php composer.json
# or
GITHUB_TOKEN=xxx php star.php composer.json
```


## Star packages from other package managers
- [npm, bower](https://github.com/mjhasbach/github-star)
