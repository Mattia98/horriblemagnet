# horriblemagnet
A cli tool to extract magnet links from horriblesubs

This tool allows you to extract all magnet links asscoiated to a 'shows' page on horriblesubs and print them to the stdout.
Horriblemagnet currently only supports outputting the magnet links for the 1080p version of the magnets. Support for the other resolutions is planned.

## Requirements
Horriblemagnet is written in PHP and requires the xml module. The script was tested on PHP 7.4 but may possibly run on earlier versions. 
  - PHP >=7.4
  - php-xml

### Ubuntu
The script was tested on Ubuntu 20.04.
You can install the required dependencies like this:

`apt install php-cli php-xml`

### Windows
Windows is completely untested. Try it and let me know!
https://windows.php.net/download/

## Usage
Horriblemagnet does not have any special switches but only one required parameter.

`php horriblemagnet.php <link>`
