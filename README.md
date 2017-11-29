# Asset-Builder

PHP library to define bundles based on dynamic lists of JavaScript and CSS files via glob. Additional features are JavaScript and CSS minification. Also supports LESS and SASS to CSS transformation. 

## Usage

```php
use Phaldan\AssetBuilder\AssetBuilder; 

$app = new AssetBuilder(); 

print $app->createProduction(__DIR__.DIRECTORY_SEPARATOR, 'assets/css')
  ->addGroups([
    "bundle.css" => $app->getGlobFileList([
      "assets/css/bootstrap.min.css", 
      "modules/*/css/*.css",
      "modules/*/css/*.scss", 
      "modules/*/css/*.less", 
    ]),
    "bundle.js" => $app->getGlobFileList([
      "assets/js/jquery.min.js", 
      "assets/js/bootstrap.min.js", 
      "modules/*/js/*.js",
    ])
  ])
  ->setCachePath(__DIR__.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR) 
  ->execute($_GET["bundle"]);
```
