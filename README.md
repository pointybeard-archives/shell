# Symphony Shell

- Version: 1.0.1
- Author: Alistair Kearney (hi@alistairkearney.com)
- Build Date: 3rd April 2015
- Requirements: Symphony 2.6 or greater


The Symphony Shell extension is a framework that allows commands to run from the command line. Commands
are scripts provided by this and/or other extensions. See bin/test for an example.

Developers can include commands in their extensions, allowing for operations not suited
to web servers. The command API gives access to Symphony core framework, including Database, Config, Log
etc.

## INSTALLATION

1. Upload the 'shell' folder in this archive to your Symphony 'extensions' folder.
2. Enable it by selecting the "Shell", choose Enable from the with-selected menu, then click Apply.

### Optional Steps
1. Run the following to make the `symphony` script executable: `chmod +x extensions/shell/bin/symphony`
2. Add the `extensions/shell/bin/symphony` path to your `PATH` environment variable or create
    a symbolic link to it in a location that resides in the `PATH` e.g. /usr/local/sbin. This will
    enable you to run the command `symphony` without preceding path information.


## USAGE

From the shell, you can run the following command

    php -f /path/to/extensions/shell/bin/symphony -- [args]

For usage information, use `--usage`. E.G.

    php -f /path/to/extensions/shell/bin/symphony -- --usage

or, if you followed the optional steps above, just

    symphony --usage


## CHANGE LOG

    1.0.1 - Added composer autoloader
          - Added shell-args package
          - Added Utils::promptForInput() which supports silent input capturing. Password will no
            longer echo to the display when using -u

    1.0 -   Checked compatiblity with Symphony 2.4+
        -   Using namespaces.
        -   Updated command API.
        -   Using SPL autoloader for all extension classes and loading commands from other extensions
        -   Updated argument structure. Requires -c and -e before the command and extension values

    0.4 -   Added support for Symphony 2.3.x

    0.3 -   Fixed bug that ignored the `--usage` flag
        -   Added username (`-u`) option, which can be used instead of `-t`

    0.2 -   adding `--usage` after a command will trigger that commands `usage()` function
        -   added `Shell::listCommands([$extension])` function for listing commands in the system
        -   omitting a command will list all the commands available for that extension
        -   omitting both extension and command will list all commands in the system
        -   added "deny from all" `.htaccess` directive to the `bin/` folder
        -   `test` command implements `ShellCommand::usage()`

## TODO

    - Streamline the installation process
    - Make the README clearer
    - Enabling/Disabling the extension in Symphony admin should have some effect.
