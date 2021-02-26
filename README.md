# Streamania
AWE Projekt - Video Plattform by TV und JL

## Setup
- `git config user.name "BENUTZERNAME"`
- `git config user.email "EMAIL"`
- `git config credential.helper store`
- C:/Program Files/Git/etc/gitconfig als Admin editieren:
    ```
    [core]
	    autocrlf = false
        askpass =

    [credential]
	    # helper = manager
    ```
- `git config --global core.autocrlf false`
- `git clone https://github.com/twigphp/Twig.git lib/Twig`
- `git clone https://github.com/scssphp/scssphp.git lib/Sass`
- `mv lib/Twig/src lib/Twig/Twig`
- Change in `./bin/console.bat` the path of `C:\xampp\` to the location of your xampp
- Excecute the install command: `bin/console.bat install`
- Adjust your `bin/config.ini` file

## Console Commands
- Install: `bin/console.bat install <action>`
  - installs `bin/config.ini`, Streamania database, demo data
- Compile SCSS & JS: `bin/console.bat compile`
- Compile SCSS: `bin/console.bat compile scss`
- Compile JS: `bin/console.bat compile js`
- Execute installer: `bin/console.bat install`

## Watch2Gether - Server
Server is in: `app\src\Watch2Gether\bin\Release\Watch2Gether.exe`<br>
It connects to the database (host, db, user, pass) and listens on the port stored in `bin/console.ini`
