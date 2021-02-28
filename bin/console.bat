@echo off
SETLOCAL EnableDelayedExpansion
set phpPath="C:\xampp\php\php.exe"

IF "%1"=="compile" (
    IF "%2"=="scss" (
        %phpPath% %~dp0\compile-scss.php
    ) ELSE (
        IF "%2"=="js" (
            %phpPath% %~dp0\compile-js.php
        ) ELSE (
            %phpPath% %~dp0\compile-scss.php
            %phpPath% %~dp0\compile-js.php
        )
    )
)

IF "%1"=="install" (
    IF "%2"=="--scratch" (
        echo If your Database already exists, you will loose ALL data!
        SET /p installScratch= Install from scratch [y/N]?:

        IF /i "!installScratch!"=="y" CALL :installScratchFunc
    ) ELSE (
        %phpPath% %~dp0\install.php "%2" "%3"
    )
)

GOTO :end

rem install from scratch
:installScratchFunc
%phpPath% %~dp0\install.php --scratch
EXIT /B %ERRORLEVEL%

:end
