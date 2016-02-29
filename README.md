# PHPSpec File Matcher [![Build Status](https://travis-ci.org/EcomDev/phpspec-file-matcher.svg)](https://travis-ci.org/EcomDev/phpspec-file-matcher) [![Coverage Status](https://coveralls.io/repos/github/EcomDev/phpspec-file-matcher/badge.svg?branch=develop)](https://coveralls.io/github/EcomDev/phpspec-file-matcher?branch=develop)

Allows to match directory/file existance and basic file content match.


## Installation

1. Add composer dependency

    ```bash
    composer require --dev "ecomdev/phpspec-file-matcher"
    ```

2. Add extension to your PHPSpec configuration

    ```yaml
    extensions:
      - EcomDev\PHPSpec\FileMatcher\Extension
    ```
    
## Matchers

* Directory existance: 
    * `shouldCreateDirectory($path)`
    * `shouldBeDirectory($path)`
    * `shouldHaveDirectory($path)`

* File existance: 
    * `shouldCreateFile($filePath)`
    * `shouldBeFile($filePath)`
    * `shouldHaveFile($filePath)`
    
* File content: 
    * `shouldCreateFileContent($filePath, $content)`
    * `shouldHaveFile($filePath, $content)`


## Example 


```php
<?php

namespace spec\Example;

use PhpSpec\ObjectBehavior;

class FileSpec extends ObjectBehavior
{
     function it_creates_a_file_on_save()
     {
         $this->save('file.txt', 'some_nice_content')
            ->shouldCreateFile('file.txt')
            ->shouldHaveFileContent('file.txt', 'some_nice_content');
     }
}
```



