# Robots

[![Join the chat at https://gitter.im/KingdomCompany/glow-robots](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/KingdomCompany/glow-robots?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

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