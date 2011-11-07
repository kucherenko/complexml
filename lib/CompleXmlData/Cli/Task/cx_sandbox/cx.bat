@setlocal

set CX_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

%PHP_COMMAND% "%CX_PATH%cx.php" %*

@endlocal
