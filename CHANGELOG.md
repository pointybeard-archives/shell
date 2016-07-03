# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0] - 2016-07-03
#### Added
- The hasRequiresAuthenticationTrait trait will now prompt for username and password if none was provided.
- Added more background colours to Message class
- Added 'token' command. Allows enabling, disabling and retrieval of author tokens
- Added `Shell::error()` and `Shell::warning()` convenience methods
- Added `fetch()` method to the command autoloader. Simplifies getting a list of all available commands in the system.
- Removed need for 'extension' or 'e' flag.
- Calling commands authenticate method if it extends `AuthenticatedCommand`.
- Using Message class to display errors.
- Removed `listCommands()` method
- Added additional call to `restore_error_handler`. In version of Symphony prior to 2.7, the Exception and Error handlers are set twice.
- Added exception handling around constructor to avoid problems with sessions already being started (not relevant to shell)
- Added `AuthenticatedCommand` class and 'hasRequiresAuthenticationTrait.php' trait to simplify the process of requiring authentication for a command.
- added new exception `AuthenticationRequiredException` which supports these new classes.

#### Changed
- Renamed 'test' command to 'hello'. No longer requires authentication
- Using `Shell::error()` when trapping a ShellException
- `Message::background()` and `Message::foreground()` now support `NULL`.
- Updated example command to use `AuthenticatedCommand` and `hasRequiresAuthenticationTrait`
- Move `ShellException` into `Exceptions/` folder.
- Moved `Lib/` into `src/`.
- Updated autoloader to look in `workspace/bin/` folder (#3)
- Updated `symphony` command to support loading a command from workspace/bin/ (#3)
- Using PSR4 autoloader instead of classmap

#### Fixed
- Fixed extra new line appearing when requiring input.
- Fixed workspace command autoloader code. Was incorrectly adding .php to the end of everything

## [1.0.4] - 2016-04-08
#### Fixed
- Fixed namespace for Command interface

## [1.0.3] - 2016-04-08
#### Fixed
- Fixed path to the Autoloader. Removed extra : character from password prompt

#### Changed
- Refactoring of Utils::promptForInput(). Supports a default value and a Closure for validating input. Will throw exception if silent flag is true but bash is not available.
- Shell::message() using new Message class

#### Added
- Added Message class which support coloured output

## [1.0.2] - 2015-06-05
#### Fixed
- Fixed autoloader. It was referencing old class name when calling `spl_autoload_register()`
- Fixed the test command
- Type in meta XML caused rendering of Extensions page to fail

#### Changed
- Renaming `lib` -> `Lib` and `Lib/interfaces` -> `Lib/Interfaces`
- Updated to work correctly with 1.0.3 of the `ShellArgs` library.
- Code cleanup with php-cs-fixer

#### Added
- Added `--token` and `--username` to possible arguments
- Added `.gitignore`

## [1.0.1] - 2015-04-03
#### Added
- Added composer autoloader
- Added shell-args package
- Added Utils::promptForInput() which supports silent input capturing (password will no longer echo to the display when using `-u`).

## [1.0.0] - 2014-09-23
#### Added
- Checked compatibility with Symphony 2.4+
- Using namespaces.

#### Changed
- Updated command API.
- Using SPL autoloader for all extension classes and loading commands from other extensions
- Updated argument structure. Requires -c and -e before the command and extension values

## [0.4.0] - 2012-08-10
#### Added
- Added support for Symphony 2.3.x

## [0.3.0] - 2009-08-18
#### Fixed
- Fixed bug that ignored the `--usage` flag

#### Added
- Added username (`-u`) option, which can be used instead of `-t`

## [0.2.0] - 2009-08-14
#### Added
- adding `--usage` after a command will trigger that commands `usage()` function
- added `Shell::listCommands([$extension])` function for listing commands in the system

#### Changed
- omitting a command will list all the commands available for that extension
- omitting both extension and command will list all commands in the system
- added "deny from all" `.htaccess` directive to the `bin/` folder
- `test` command implements `ShellCommand::usage()`

## 0.1.0
#### Added
- Initial release

[Unreleased]: https://github.com/pointybeard/shell/compare/v2.0.0...integration
[2.0.0]: https://github.com/pointybeard/shell/compare/v1.0.4...v2.0.0
[1.0.4]: https://github.com/pointybeard/shell/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/pointybeard/shell/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/pointybeard/shell/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/pointybeard/shell/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/pointybeard/shell/compare/v0.4.0...v1.0.0
[0.4.0]: https://github.com/pointybeard/shell/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/pointybeard/shell/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/pointybeard/shell/compare/v0.1.0...v0.2.0
