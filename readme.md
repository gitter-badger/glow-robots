# Robots

A toolset for parsing, validating, and generating a robots.txt file.

## Installing

The recommended way to install Glow\Robots is to use [composer](http://www.getcomposer.com)

```
composer require glow/robots
```

## Usage

```
$p = new Glow\Robots\Parser();
$p->setSource(file_get_contents('http://cnn.com/robots.txt'));
$p->parse();
```