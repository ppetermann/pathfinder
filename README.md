# PathFinder (Version: 0.2.2)
[![Version](http://img.shields.io/packagist/v/devedge/pathfinder.svg)](https://packagist.org/packages/devedge/pathfinder)
[![License](http://img.shields.io/packagist/l/devedge/pathfinder.svg)](https://github.com/ppetermann/pathfinder)
[![Build Status](https://scrutinizer-ci.com/g/ppetermann/pathfinder/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ppetermann/pathfinder/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ppetermann/pathfinder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ppetermann/pathfinder/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ppetermann/pathfinder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ppetermann/pathfinder/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6a3a8401-9124-4139-a080-b06f9901fe16/mini.png)](https://insight.sensiolabs.com/projects/6a3a8401-9124-4139-a080-b06f9901fe16)

## ABOUT
basically this is a this is a simple a* implementation, which can be used in PHP projects,
it was created for https://cmdr.club/routes/, but is build generic enough to be used
in other cases.

## USAGE
TODO: add more documentation
basically what you have to do is create a Node implementation derived from PathFinder\Node,
implementing its abstract methods (and if you need overwrite the others).

see tests/PathFinder/AStarTest.php for an example

more docs to come

## LINKS
https://devedge.eu
https://github.com/ppetermann/pathfinder
https://cmdr.club/routes/
