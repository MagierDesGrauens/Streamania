# Streamania
AWE Projekt - Video Plattform by TV und JL

## Setup git
If you want to disable git-bash login when pushing commits:
- `git config user.name "BENUTZERNAME"`
- `git config user.email "EMAIL"`
- `git config credential.helper store`
- Edit `C:/Program Files/Git/etc/gitconfig` as admin:
    ```
    [core]
	    autocrlf = false
        askpass =

    [credential]
	    # helper = manager
    ```

Deactivate auto converting of line endings to CRLF:
- `git config --global core.autocrlf false`

## Setup the project
Clone this project in your current folder:
  - `git clone https://github.com/MagierDesGrauens/Streamania.git ./`

Clone dependencies:
- `git clone https://github.com/twigphp/Twig.git ./lib/Twig`
- `git clone https://github.com/scssphp/scssphp.git ./lib/Sass`
- `mv ./lib/Twig/src lib/Twig/Twig`

Setup Streamania:
 - Change in `./bin/console.bat` the path of `C:\xampp\` to the location of your xampp
- Excecute: `./bin/console.bat install config`
- Adjust your `./bin/config.ini` file (especially the database connection!)
- Excecute: `./bin/console.bat install database`
- Excecute: `./bin/console.bat install demodata`
- Compile SCSS & JavaScript once: `./bin/console.bat compile`

## Console Commands
- Install: `./bin/console.bat install <action>`
  - `<none>`: If no action is given it will install all of the commands beneath (same order as well)
  - `config`: Creates `./bin/config.ini` and sets up a default configuration
  - `database`: Creates streamania database
  - `demodata`: Adds demo data
- List of installable actions: `./bin/console.bat install --actions`
- Reinstall an action: `./bin/console.bat install <action> --reset`
- Compile SCSS & JavaScript: `-/bin/console.bat compile`
- Compile SCSS: `./bin/console.bat compile scss`
- Compile JavaScript: `./bin/console.bat compile js`

## Watch2Gether - Server
Start the server in: `app\src\Watch2Gether\bin\Release\Watch2Gether.exe`<br>
It connects to the database and listens on the port set in your `./bin/config.ini` file
