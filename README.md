# Streamania
AWE Projekt - Video Plattform by TV und JL

## Setup
- `git config --global core.autocrlf false`
- `git config user.name "BENUTZERNAME"`
- `git config user.email "EMAIL"`
- `git config credential.helper store`
- C:/Program Files/Git/etc/gitconfig als Admin editieren:
    ```
    [core]
        askpass =

    [credential]
	    # helper = manager
    ```
- `git clone https://github.com/twigphp/Twig.git lib/Twig`
- `git clone https://github.com/scssphp/scssphp.git lib/Sass`
- `mv lib/Twig/src lib/Twig/Twig`
- Change in `./bin/compile-scss.bat` the path of `C:\xampp\` to the location of your xampp

## Console Commands
- Compile SCSS: `bin/compile-scss.bat`
