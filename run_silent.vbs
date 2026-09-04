Set WshShell = CreateObject("WScript.Shell")
Set FSO = CreateObject("Scripting.FileSystemObject")
AppDir = WScript.Arguments(0)

' 1. Jalankan server PHP internal secara tersembunyi (background)
WshShell.Run """" & AppDir & "\php_engine\php.exe"" -S 127.0.0.1:8000 -t """ & AppDir & "\public""", 0, False

' 2. Beri jeda 2 detik agar PHP server siap menerima koneksi
WScript.Sleep 2000

' 3. Cari jalur instalasi Google Chrome
ChromePath = ""
Path1 = WshShell.ExpandEnvironmentStrings("%ProgramFiles%") & "\Google\Chrome\Application\chrome.exe"
Path2 = WshShell.ExpandEnvironmentStrings("%ProgramFiles(x86)%") & "\Google\Chrome\Application\chrome.exe"
Path3 = WshShell.ExpandEnvironmentStrings("%LocalAppData%") & "\Google\Chrome\Application\chrome.exe"

If FSO.FileExists(Path1) Then
    ChromePath = Path1
ElseIf FSO.FileExists(Path2) Then
    ChromePath = Path2
ElseIf FSO.FileExists(Path3) Then
    ChromePath = Path3
End If

' 4. Eksekusi: Utamakan Chrome mode WebApp (tanpa address bar)
'    Jika Chrome tidak ditemukan, gunakan 'cmd /c start' yang PASTI mengikuti Default Browser Windows
If ChromePath <> "" Then
    WshShell.Run """" & ChromePath & """ --app=http://127.0.0.1:8000", 1, False
Else
    WshShell.Run "cmd /c start http://127.0.0.1:8000", 0, False
End If