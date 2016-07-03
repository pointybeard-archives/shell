# Shell Extension for Symphony CMS

- Version: v2.0.0
- Date: July 3 2016
- [Release notes](https://github.com/pointybeard/shell/blob/master/CHANGELOG.md)
- [GitHub repository](https://github.com/pointybeard/shell)

The Symphony Shell extension provides access to the Symphony core from the command line.

Developers can include commands in their extensions, allowing for operations not suited
to web servers. The command API gives easy access to the Symphony core framework, including database, config, authentication and logs.

## Installation

This is an extension for [Symphony CMS](http://getsymphony.com). Add it to the `/extensions` folder of your Symphony CMS installation, then enable it though the interface.

### Requirements

This extension requires the **[Shell Arguments](https://github.com/pointybeard/shellargs)** (`symfony/http-foundation`) library to be installed via Composer. Either require it in your main composer.json file, or run `composer install` on the `extension/shell` directory.

```json
"require": {
  "php": ">=5.6.6",
  "pointybeard/shell-args": "~1.0"
}
```

### Optional Setup

To simply accessing Symphony commands on the command line, we recommend you do the follow:

1. Make the `bin/symphony` script executable with `chmod +x extensions/shell/bin/symphony`
2. Add `extensions/shell/bin/symphony` to your `PATH` or create a symbolic link in a location that resides in the `PATH` e.g. `/usr/local/sbin`. This will allow you to call the Symphony command from anywhere.

## Usage

From the shell, you can run the following command

    php -f /path/to/extensions/shell/bin/symphony -- [args]

For usage information, use `--usage`. E.G.

    php -f /path/to/extensions/shell/bin/symphony -- --usage

or, if you followed the "Optional Setup" above, just

    symphony --usage

**This remainder of this document assumes you have set up the extension using the "Optional Setup" steps above.**

### Getting Started

The Shell extension looks for commands in the `bin/` folder of extensions you have installed, and also in `workspace/bin/`. You can see a list of commands by running `symphony` without any arguments. A list like this will be displayed:

    Below is a list of all available commands. (use --usage for details on
    executing individual commands):

       shell/hello
       shell/token

At any time you can use `--usage`, `--help` or `-h` to get help. If you have also specified a command (see below), you will get help for that particular command instead.

Use the `-c` or `--command` argument to run a particular command. The value provided is always `[extension]/[command]` or `[workspace]/[command]`. This extension comes with two commands out of the box: `hello` and `token`.

To run the `hello` command use the following:

    symphony -c shell/hello

You should see output like this:

    Hello! Here are the arguments you passed me.
    0: 'c' => 'shell/hello'

### Authentication

Some commands may require you are authenticated before you use them. To do this, either provide the name of the user you want to authenticate as with `-u <username>` or the auth token of that user with `-t <token>`. When using `-u`, you will be prompted to enter your password.

## Writing a custom command

To write a command, create a class that implements `Symphony\Shell\Lib\Interfaces\Command` and place it into `workspace/bin/`. Alternatively, put it into the `bin/` folder of any Extension. You commands will be prefixed with either `workspace` (if it has been placed in `workspace/bin/`), or by the name of the extension.

Any command you write must have a namespace starting with `Symphony\Shell\Command\` followed by the name of your extension (e.g. `namespace Symphony\Shell\Command\MyExtension`) or `workspace` (i.e. `namespace Symphony\Shell\Command\Workspace`).

When implementing `Symphony\Shell\Lib\Interfaces\Command`, you must include a `usage` and `run` method.

Here is an example of a very basic Command called `test` placed in `workspace/bin/`:

```php
<?php
namespace Symphony\Shell\Command\Workspace;

use Symphony\Shell\Lib;

class Test implements Lib\Interfaces\Command
{

    public function usage()
    {
        return "    usage for 'workspace/test'

    Add usage information for this command." . PHP_EOL;
    }

    public function run()
    {
        Lib\Shell::message("Greetings. This is the test command!");
    }
}
```

From within the `run()` method, you have full access to the Symphony core framework. For example, to get the database object, use `\Symphony::Database()`. Anything you would normally do in an extension, you can do here (e.g. triggering delegates, accessing sections or fields).

### Requiring Authentication

You can secure your commands so that anyone using it must provide Symphony user credentials. To do this, instead of implementing `Symphony\Shell\Lib\Interfaces\Command`, extend `Symphony\Shell\Lib\AuthenticatedCommand`. When you command is run, Shell will notice and force the user to provide a authentication with `-u` or `-t`.

When extending `AuthenticatedCommand`, you must provide an `authenticate()` method in your command. The simplest way is to use the `hasRequiresAuthenticationTrait` trait. It includes a boilerplate `authenticate()` method and generally is more than adequate. It will check if the user is logged in, request username and password if they are not, and throw an `AuthenticationRequiredException` if login ultimately fails.

Here is the same 'test' command from above, but this time it requires authentication:

```php
<?php
namespace Symphony\Shell\Command\Workspace;

use Symphony\Shell\Lib;

class Test extend Lib\AuthenticatedCommand
{
    use Lib\Traits\hasRequiresAuthenticationTrait;

    public function usage()
    {
        return "    usage for 'workspace/test'

    Add usage information for this command." . PHP_EOL;
    }

    public function run()
    {
        Lib\Shell::message("Greetings. This is the test command!");
    }
}
```

## Multiple Symphony installations on the same Host

Note that if you follow the "Optional Steps" above, running `symphony` will always be in the context of that one particular installation.

If you run multiple sites across multiple installations of Symphony, remember that the Shell extension will work with only the installation of Symphony it itself was installed and enabled on.

A workaround to this is to use the symlink method and namespace each install.

For example, you have two sites "Banana" and "Apple". Both are separate installations of Symphony with their own databases. Symlink them using `ln -s /path/to/banana/extension/shell/bin/symphony /usr/local/sbin/symphony-banana` and `ln -s /path/to/banana/extension/shell/bin/symphony /usr/local/sbin/symphony-apple`. Now you can use this shell extension for both and know you are always in the correct context (`> symphony-banana` and `> symphony-apple`).

In the future there might be better support for running a single shell instance across all Symphony installations on a host.

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/shell/issues),
or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/shell/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"Shell Extension for Symphony CMS" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
