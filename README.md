# Symphony Shell

- Version: 0.4 (alpha build)
- Author: Alistair Kearney (hi@alistairkearney.com)
- Build Date: 10th August 2012
- Requirements: Symphony 2.3.0 or greater


The Symphony Shell extension is a framework that allows commands to run from the command line. Commands 
are scripts provided by this and/or other extensions. See bin/test for an example.

Developers will be able to include commands in their extensions, allowing for operations not suited
to web servers. The command API gives access to Symphony core framework, including Database, Config, Log
etc.

Please be aware that this extension is in its infancy. There might be problems, limitations or major bugs.


## INSTALLATION

1. Upload the 'shell' folder in this archive to your Symphony 'extensions' folder.
2. Enable it by selecting the "Shell", choose Enable from the with-selected menu, then click Apply. _Note: at this point, enabling the extension in Symphony does not do anything_

### Optional Steps
1. Edit `/extensions/shell/bin/symphony`, setting the very first line as the path to the PHP executable. E.G. `#!/usr/bin/php`
2. Run the following command to make `symphony` executable: `chmod +x extensions/shell/bin/symphony`
3. Either add the path to `extensions/shell/bin/symphony` to your `PATH` environment variable or create a symbolic link to it in a location that resides in the `PATH`. This will enable you to run the command `symphony` without preceding path information.


## USAGE

From the shell, you can run the following command

	php -f /path/to/extensions/shell/bin/symphony -- [args]
	
If you followed optional steps 3 and 4, you can forego the `php -f` part. Following optional step 5 will allow you to ignore the path as well.

For usage information, use `--usage`. E.G.

	php -f /path/to/extensions/shell/bin/symphony -- --usage
	
or, depending on your setup, just

	symphony --usage
	

## CHANGE LOG
	
	0.4	-	Added support for Symphony 2.3.x
	
	0.3	-	Fixed bug that ignored the `--usage` flag
		-	Added username (`-u`) option, which can be used instead of `-t`
	
	0.2	-	adding `--usage` after a command will trigger that commands `usage()` function
		-	added `Shell::listCommands([$extension])` function for listing commands in the system
		-	omitting a command will list all the commands available for that extension
		-	omitting both extension and command will list all commands in the system
		-	added "deny from all" `.htaccess` directive to the `bin/` folder
		-	`test` command implements `ShellCommand::usage()`
	
## ISSUES

When using the `-u` flag, the password is read from `STDIN` and echo'd back to the CLI. There are bugs in PHP (<http://bugs.php.net/bug.php?id=34972>) which prevent hiding the input.
	
	
## TODO
	
	- Streamline the installation process
	- Make the README clearer
	- Enabling/Disabling the extension in Symphony admin should have some effect.
	