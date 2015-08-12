@setlocal

set YII_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=C:\xampp\php\php.exe

%PHP_COMMAND% "%YII_PATH%yiic" %*

@endlocal