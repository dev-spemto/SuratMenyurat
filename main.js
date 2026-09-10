const { app, BrowserWindow } = require('electron');
const { spawn } = require('child_process');
const path = require('path');

let phpProcess;

app.whenReady().then(() => {
    const basePath = app.isPackaged ? process.resourcesPath : __dirname;
    const phpPath = path.join(basePath, 'php_engine', 'php.exe');
    const appPath = app.isPackaged ? path.join(process.resourcesPath, 'app') : __dirname;

    // Jalankan server PHP artisan di latar belakang
    phpProcess = spawn(phpPath, ['artisan', 'serve', '--port=8000'], {
        cwd: appPath,
        windowsHide: true // Menyembunyikan jendela konsol CMD di Windows
    });

    const mainWindow = new BrowserWindow({
        width: 1280,
        height: 800,
        title: 'Spemto - Surat Menyurat', // Judul window disesuaikan
        icon: path.join(__dirname, 'applogo.ico'),
        autoHideMenuBar: true
    });

    // Beri jeda 3 detik agar PHP server siap sebelum memuat URL
    setTimeout(() => {
        mainWindow.loadURL('http://127.0.0.1:8000');
    }, 3000);
});

app.on('window-all-closed', () => {
    if (phpProcess) phpProcess.kill();
    app.quit();
});