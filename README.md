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

## Console Commands
- Compile SCSS & JS: `bin/console.bat compile`
- Compile SCSS: `bin/console.bat compile scss`
- Compile JS: `bin/console.bat compile js`
- Execute installer: `bin/console.bat install`
