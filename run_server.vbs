Set WshShell = CreateObject("WScript.Shell")
WshShell.Run "cmd /c cd /d """ & WScript.Arguments(0) & """ && " & WScript.Arguments(0) & "\php_engine\php.exe artisan serve --port=8000", 0, False
WScript.Sleep 2000
WshShell.Run "chrome.exe http://127.0.0.1:8000"
