Composer Helper
===============
Composer helper for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist adipriyantobpn/yii2-composer-helper "*"
```

or add

```
"adipriyantobpn/yii2-composer-helper": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, new `composer` console application can be ready to use in your command prompt :

```bash
$ ./yii composer -h

DESCRIPTION

SUB-COMMANDS

- composer/batch-export                   Batch export for all supported data,
- composer/export-all-available-packages  Export all available Composer packages, which are registered in packagist.org
- composer/export-installed-packages      Export all installed packages
- composer/export-root-packages           Export all packages which specified in the composer.json
- composer/require-packages               Install packages via Composer, export the packages list, and commit them using
                                          git
```

Documentation
-------------

Each CLI sub-command of `yii2-composer-helper` can be seen with -h, just like shown below.

### Export Root Packages
```bash
$ ./yii composer/export-root-packages -h
```
```bash
DESCRIPTION

Export all packages which specified in the composer.json


USAGE

yii composer/export-root-packages [format] [file] [...options...]

- format: string (defaults to 'json')
  Format of the output: text or json

- file: string|null (defaults to '@common/runtime/composer-packages-root.json')
  File path for saving exported data, also support Yii2 path alias


OPTIONS

--appconfig: string
  custom application configuration file path.
  If not set, default application configuration is used.

--color: boolean, 0 or 1
  whether to enable ANSI color in the output.
  If not set, ANSI color will only be enabled for terminals that support it.

--debug, -d: boolean, 0 or 1 (defaults to 1)
  debug the system shell command executed by this console application

--help, -h: boolean, 0 or 1
  whether to display help information about current command.

--interactive: boolean, 0 or 1 (defaults to 1)
  whether to run the command interactively.

--verbose, -v: boolean, 0 or 1 (defaults to 1)
  increase the verbosity of messages, as in composer --verbose
```

### Export Installed Packages
```bash
$ ./yii composer/export-installed-packages -h
```
```bash
DESCRIPTION

Export all installed packages


USAGE

yii composer/export-installed-packages [asTree] [file] [format] [...options...]

- asTree: boolean, 0 or 1 (defaults to 0)
  List the dependencies as a tree

- file: string (defaults to '@common/runtime/composer-packages.txt')
  File path for saving exported data, also support Yii2 path alias

- format: string (defaults to 'text')
  Format of the output: text or json


OPTIONS

--appconfig: string
  custom application configuration file path.
  If not set, default application configuration is used.

--color: boolean, 0 or 1
  whether to enable ANSI color in the output.
  If not set, ANSI color will only be enabled for terminals that support it.

--debug, -d: boolean, 0 or 1 (defaults to 1)
  debug the system shell command executed by this console application

--help, -h: boolean, 0 or 1
  whether to display help information about current command.

--interactive: boolean, 0 or 1 (defaults to 1)
  whether to run the command interactively.

--verbose, -v: boolean, 0 or 1 (defaults to 1)
  increase the verbosity of messages, as in composer --verbose
```

### Export All Available Packages
```bash
$ ./yii composer/export-all-available-packages -h
```
```bash
DESCRIPTION

Export all available Composer packages, which are registered in packagist.org


USAGE

yii composer/export-all-available-packages [file] [...options...]

- file: string (defaults to '@common/runtime/composer-packages-all-available.txt')
  File path for saving exported data, also support Yii2 path alias


OPTIONS

--appconfig: string
  custom application configuration file path.
  If not set, default application configuration is used.

--color: boolean, 0 or 1
  whether to enable ANSI color in the output.
  If not set, ANSI color will only be enabled for terminals that support it.

--debug, -d: boolean, 0 or 1 (defaults to 1)
  debug the system shell command executed by this console application

--help, -h: boolean, 0 or 1
  whether to display help information about current command.

--interactive: boolean, 0 or 1 (defaults to 1)
  whether to run the command interactively.

--verbose, -v: boolean, 0 or 1 (defaults to 1)
  increase the verbosity of messages, as in composer --verbose
```

### Batch Export
```bash
$ ./yii composer/batch-export -h
```
```bash
DESCRIPTION

Batch export for all supported data,
including installed packages (as text & as tree) specified in the composer.json,
and all available packages from packagist.org


USAGE

yii composer/batch-export [installedPackageFilePath] [installedPackageAsTreeFilePath] [allAvailablePackageFilePath] [...options...]

- installedPackageFilePath: string (defaults to '@common/runtime/composer-packages.txt')
  $installedPackageFilePath

- installedPackageAsTreeFilePath: string (defaults to '@common/runtime/composer-packages-tree.txt')
  $installedPackageAsTreeFilePath

- allAvailablePackageFilePath: string (defaults to '@common/runtime/composer-packages-all-available.txt')
  $allAvailablePackageFilePath


OPTIONS

--appconfig: string
  custom application configuration file path.
  If not set, default application configuration is used.

--color: boolean, 0 or 1
  whether to enable ANSI color in the output.
  If not set, ANSI color will only be enabled for terminals that support it.

--debug, -d: boolean, 0 or 1 (defaults to 1)
  debug the system shell command executed by this console application

--help, -h: boolean, 0 or 1
  whether to display help information about current command.

--interactive: boolean, 0 or 1 (defaults to 1)
  whether to run the command interactively.

--verbose, -v: boolean, 0 or 1 (defaults to 1)
  increase the verbosity of messages, as in composer --verbose
```

### Install new package
```bash
$ ./yii composer/require-packages -h
```
```bash
DESCRIPTION

Install packages via Composer, export the packages list, and (optionally) commit them into version control using git


USAGE

yii composer/require-packages <packageName> [preferSource] [commit] [...options...]

- packageName (required): string
  Package name

- preferSource: boolean, 0 or 1 (defaults to 1)
  Whether installing the package sources, or just install the package distribution

- commit: boolean, 0 or 1 (defaults to 0)
  Whether commit the package list using git


OPTIONS

--appconfig: string
  custom application configuration file path.
  If not set, default application configuration is used.

--color: boolean, 0 or 1
  whether to enable ANSI color in the output.
  If not set, ANSI color will only be enabled for terminals that support it.

--debug, -d: boolean, 0 or 1 (defaults to 1)
  debug the system shell command executed by this console application

--help, -h: boolean, 0 or 1
  whether to display help information about current command.

--interactive: boolean, 0 or 1 (defaults to 1)
  whether to run the command interactively.

--verbose, -v: boolean, 0 or 1 (defaults to 1)
  increase the verbosity of messages, as in composer --verbose
```