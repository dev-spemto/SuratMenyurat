[Setup]
AppName=Spemto-SuratMenyurat
AppVersion=1.0
AppPublisher=SMP Muhammadiyah Tonjong
DefaultDirName={autopf}\Spemto-SuratMenyurat
DefaultGroupName=Spemto-SuratMenyurat

; Memaksa installer berjalan sebagai Administrator
PrivilegesRequired=admin
PrivilegesRequiredOverridesAllowed=commandline

; Penutupan otomatis proses PHP yang sedang berjalan
CloseApplications=yes
CloseApplicationsFilter=*php.exe*
RestartApplications=no

OutputDir=C:\project\suratmenyurat\InstallerOutput
OutputBaseFilename=Setup_Spemto-SuratMenyurat_v1.0
Compression=lzma2/max
SolidCompression=yes
WizardStyle=modern
SetupIconFile=C:\project\suratmenyurat\applogo.ico

[Tasks]
Name: "desktopicon"; Description: "{cm:CreateDesktopIcon}"; GroupDescription: "{cm:AdditionalIcons}"

[Dirs]
; Berikan hak akses WRITE & MODIFY penuh kepada seluruh User Windows pada folder storage, cache, dan database
Name: "{app}\storage"; Permissions: users-full
Name: "{app}\storage\app"; Permissions: users-full
Name: "{app}\storage\app\public"; Permissions: users-full
Name: "{app}\storage\app\public\lampiran"; Permissions: users-full
Name: "{app}\storage\app\public\surat-masuk"; Permissions: users-full
Name: "{app}\storage\framework"; Permissions: users-full
Name: "{app}\storage\framework\cache"; Permissions: users-full
Name: "{app}\storage\framework\sessions"; Permissions: users-full
Name: "{app}\storage\framework\views"; Permissions: users-full
Name: "{app}\storage\logs"; Permissions: users-full
Name: "{app}\bootstrap\cache"; Permissions: users-full
Name: "{app}\database"; Permissions: users-full
Name: "{app}\public\storage"; Permissions: users-full

[Files]
; 1. Salin seluruh file proyek Laravel + php_engine internal dari C:\project\suratmenyurat
Source: "C:\project\suratmenyurat\*"; DestDir: "{app}"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: "\node_modules\*,\dist\*,\InstallerOutput\*,\.git\*,\setup.iss,\storage\logs\*,\storage\framework\cache\*"

; 2. PAKSA SALIN file .env secara khusus
Source: "C:\project\suratmenyurat\.env"; DestDir: "{app}"; Flags: ignoreversion

[Icons]
Name: "{group}\Spemto-SuratMenyurat"; Filename: "wscript.exe"; Parameters: """{app}\run_silent.vbs"" ""{app}"""; IconFilename: "{app}\applogo.ico"
Name: "{autodesktop}\Spemto-SuratMenyurat"; Filename: "wscript.exe"; Parameters: """{app}\run_silent.vbs"" ""{app}"""; IconFilename: "{app}\applogo.ico"; Tasks: desktopicon

[Run]
; 1. Pastikan folder sub-framework dan database.sqlite ada
Filename: "cmd.exe"; Parameters: "/c if not exist ""{app}\database\database.sqlite"" type nul > ""{app}\database\database.sqlite"""; Flags: runhidden
Filename: "cmd.exe"; Parameters: "/c if not exist ""{app}\storage\framework\views"" mkdir ""{app}\storage\framework\views"""; Flags: runhidden
Filename: "cmd.exe"; Parameters: "/c if not exist ""{app}\storage\framework\sessions"" mkdir ""{app}\storage\framework\sessions"""; Flags: runhidden
Filename: "cmd.exe"; Parameters: "/c if not exist ""{app}\storage\framework\cache"" mkdir ""{app}\storage\framework\cache"""; Flags: runhidden
Filename: "cmd.exe"; Parameters: "/c if not exist ""{app}\storage\app\public\lampiran"" mkdir ""{app}\storage\app\public\lampiran"""; Flags: runhidden
Filename: "cmd.exe"; Parameters: "/c if not exist ""{app}\storage\app\public\surat-masuk"" mkdir ""{app}\storage\app\public\surat-masuk"""; Flags: runhidden

; 2. Clear cache Laravel
Filename: "{app}\php_engine\php.exe"; Parameters: "artisan config:clear"; WorkingDir: "{app}"; Flags: runhidden
Filename: "{app}\php_engine\php.exe"; Parameters: "artisan cache:clear"; WorkingDir: "{app}"; Flags: runhidden
Filename: "{app}\php_engine\php.exe"; Parameters: "artisan route:clear"; WorkingDir: "{app}"; Flags: runhidden
Filename: "{app}\php_engine\php.exe"; Parameters: "artisan view:clear"; WorkingDir: "{app}"; Flags: runhidden

; 3. Generate APP_KEY jika belum ada
Filename: "{app}\php_engine\php.exe"; Parameters: "artisan key:generate --force"; WorkingDir: "{app}"; Flags: runhidden

; 4. Buat Storage Link agar file lampiran & surat masuk bisa diakses publik
Filename: "{app}\php_engine\php.exe"; Parameters: "artisan storage:link"; WorkingDir: "{app}"; Flags: runhidden

; 5. Migrate & Seed Database (Amankan data jika database sudah ada)
Filename: "{app}\php_engine\php.exe"; Parameters: "artisan migrate --force"; WorkingDir: "{app}"; Flags: runhidden
Filename: "{app}\php_engine\php.exe"; Parameters: "artisan db:seed --force"; WorkingDir: "{app}"; Flags: runhidden

; 6. Jalankan aplikasi via wscript
Filename: "wscript.exe"; Parameters: """{app}\run_silent.vbs"" ""{app}"""; Flags: nowait postinstall skipifsilent

[UninstallDelete]
Type: filesandordirs; Name: "{app}\storage"
Type: filesandordirs; Name: "{app}\database"
Type: filesandordirs; Name: "{app}\bootstrap\cache"
Type: filesandordirs; Name: "{app}\php_engine"
Type: filesandordirs; Name: "{app}\public\storage"
Type: files; Name: "{app}\.env"

[Code]
// Prosedur untuk mengubah flag byte pada shortcut (.lnk) menjadi Run as Administrator (Compatible dengan Inno Setup 6+)
procedure SetShortcutRunAsAdmin(const ShortcutPath: string);
var
  Stream: TFileStream;
  Buffer: AnsiChar;
  ByteVal: Byte;
begin
  if FileExists(ShortcutPath) then
  begin
    try
      Stream := TFileStream.Create(ShortcutPath, fmOpenReadWrite or fmShareExclusive);
      try
        Stream.Seek(21, soFromBeginning);
        if Stream.Read(Buffer, 1) = 1 then
        begin
          ByteVal := Ord(Buffer) or $20; // Set bit SLDF_RUNAS_USER
          Buffer := Chr(ByteVal);
          Stream.Seek(21, soFromBeginning);
          Stream.Write(Buffer, 1);
        end;
      finally
        Stream.Free;
      end;
    except
      // Lewati jika shortcut sedang terkunci
    end;
  end;
end;

procedure CurStepChanged(CurStep: TSetupStep);
var
  DesktopShortcut, StartMenuShortcut: string;
begin
  if CurStep = ssPostInstall then
  begin
    // Otomatis ubah shortcut di Start Menu menjadi Run as Administrator
    StartMenuShortcut := ExpandConstant('{group}\Spemto-SuratMenyurat.lnk');
    SetShortcutRunAsAdmin(StartMenuShortcut);

    // Otomatis ubah shortcut di Desktop menjadi Run as Administrator
    DesktopShortcut := ExpandConstant('{autodesktop}\Spemto-SuratMenyurat.lnk');
    SetShortcutRunAsAdmin(DesktopShortcut);
  end;
end;

procedure CurUninstallStepChanged(CurUninstallStep: TUninstallStep);
var
  ResultCode: Integer;
begin
  if CurUninstallStep = usUninstall then
  begin
    // Matikan proses background PHP terlebih dahulu saat uninstall dijalankan
    Exec('taskkill.exe', '/f /im php.exe', '', SW_HIDE, ewWaitUntilTerminated, ResultCode);
  end;
end;

procedure DeinitializeUninstall();
begin
  DelTree(ExpandConstant('{app}'), True, True, True);
end;