<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TunnelController extends Controller
{
    private int $vitePort = 5173;
    private string $urlLocal = 'http://localhost:5173';

    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $estado = $this->estado();
        return view('tunnel.index', $estado);
    }

    /**
     * Activa el túnel de forma NO bloqueante: lanza cloudflared y responde al
     * instante. La URL se captura luego por polling vía status() (así el panel
     * no se congela durante la activación).
     */
    public function start()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        if (!function_exists('popen') || !function_exists('shell_exec')) {
            return back()->with('error', 'El servidor PHP no permite ejecutar procesos (popen/shell_exec deshabilitados en php.ini). Mientras tanto puedes usar el archivo tunel.bat.');
        }

        $cf = $this->cloudflaredPath();
        if (!$cf) {
            return back()->with('error', 'No se encontró cloudflared. Instálalo con: winget install --id Cloudflare.cloudflared (o define CLOUDFLARED_PATH en .env).');
        }

        // Cerrar cualquier túnel previo (no dejar nada abierto)
        $this->matarCloudflared();

        $logFile = $this->logFilePath();
        @unlink($logFile);

        // Lanzar cloudflared detached con PowerShell Start-Process SIN -RedirectStandard.
        // Clave: -RedirectStandard fuerza la herencia de handles y cloudflared se quedaría
        // con el socket del request de Apache → ERR_CONNECTION_RESET. Sin redirect no hereda,
        // y para capturar la URL usamos la opción --logfile de cloudflared (él escribe su log).
        $psPath = storage_path('app/launch_tunnel.ps1');
        $script = "\$ErrorActionPreference='SilentlyContinue'\r\n"
            . "Start-Process -FilePath '{$cf}' "
            . "-ArgumentList 'tunnel','--url','http://localhost:{$this->vitePort}','--logfile','{$logFile}' "
            . "-WindowStyle Hidden\r\n";
        file_put_contents($psPath, $script);

        $cmd = 'powershell -NoProfile -ExecutionPolicy Bypass -File "' . $psPath . '"';
        $h = @popen($cmd, 'r');
        if ($h !== false) {
            pclose($h);
        }

        Storage::put('tunnel.json', json_encode(['state' => 'starting', 'url' => null, 'started_at' => now()->toDateTimeString()]));

        return back()->with('info', 'Generando el link… aparecerá en unos segundos.');
    }

    /**
     * Estado del túnel (JSON, liviano) para el polling de la vista.
     * Cuando detecta la URL por primera vez, la fija en FRONTEND_URL.
     */
    public function status()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        if (!$this->cloudflaredCorriendo()) {
            return response()->json(['state' => 'off', 'url' => null]);
        }

        $data = Storage::exists('tunnel.json') ? json_decode(Storage::get('tunnel.json'), true) : null;
        if ($data && !empty($data['url'])) {
            return response()->json(['state' => 'online', 'url' => $data['url']]);
        }

        // Proceso vivo pero sin URL aún: buscarla en el log de cloudflared
        $contenido = @file_get_contents($this->logFilePath()) ?: '';

        if (preg_match('#https://[a-z0-9-]+\.trycloudflare\.com#', $contenido, $m)) {
            $url = $m[0];
            $this->setFrontendUrl($url);   // config no está cacheada: el próximo request lee el nuevo valor
            Storage::put('tunnel.json', json_encode(['state' => 'online', 'url' => $url, 'started_at' => $data['started_at'] ?? now()->toDateTimeString()]));
            return response()->json(['state' => 'online', 'url' => $url]);
        }

        return response()->json(['state' => 'starting', 'url' => null]);
    }

    /** Apaga el túnel y deja todo cerrado/seguro. */
    public function stop()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $this->matarCloudflared();
        $this->setFrontendUrl($this->urlLocal);
        Storage::delete('tunnel.json');

        return back()->with('success', 'Túnel apagado. No quedó nada abierto.');
    }

    // ── Helpers ──────────────────────────────────────────────

    private function estado(): array
    {
        $corriendo = $this->cloudflaredCorriendo();
        $data = Storage::exists('tunnel.json') ? json_decode(Storage::get('tunnel.json'), true) : null;
        $url  = $corriendo ? ($data['url'] ?? null) : null;

        return [
            'corriendo' => $corriendo && $url,   // en línea con link listo
            'iniciando' => $corriendo && !$url,  // proceso vivo, link aún generándose
            'url'       => $url,
            'desde'     => $corriendo ? ($data['started_at'] ?? null) : null,
            'tieneCloudflared' => (bool) $this->cloudflaredPath(),
        ];
    }

    private function cloudflaredCorriendo(): bool
    {
        $out = (string) @shell_exec('tasklist /FI "IMAGENAME eq cloudflared.exe" 2>NUL');
        return str_contains(strtolower($out), 'cloudflared.exe');
    }

    private function matarCloudflared(): void
    {
        @shell_exec('taskkill /F /IM cloudflared.exe 2>NUL');
    }

    /**
     * Ruta del log de cloudflared. Usa el temp del sistema (sin espacios):
     * Start-Process -ArgumentList parte los argumentos en los espacios, y la
     * ruta del proyecto tiene "My Web Sites", lo que rompería --logfile.
     */
    private function logFilePath(): string
    {
        return rtrim(sys_get_temp_dir(), '\\/') . DIRECTORY_SEPARATOR . 'pos_tunnel.log';
    }

    private function cloudflaredPath(): ?string
    {
        // 1) configurado por env
        $cfg = config('app.cloudflared_path');
        if ($cfg && is_file($cfg)) return $cfg;

        // 2) en el PATH
        $which = trim((string) @shell_exec('where cloudflared 2>NUL'));
        if ($which) {
            $first = strtok($which, "\r\n");
            if ($first && is_file($first)) return $first;
        }

        // 3) instalación winget del usuario
        $base = getenv('LOCALAPPDATA');
        if ($base) {
            $g = glob($base . '\\Microsoft\\WinGet\\Packages\\Cloudflare.cloudflared*\\cloudflared.exe');
            if ($g) return $g[0];
        }

        // 4) ruta conocida (fallback)
        $known = 'C:\\Users\\Usuario\\AppData\\Local\\Microsoft\\WinGet\\Packages\\Cloudflare.cloudflared_Microsoft.Winget.Source_8wekyb3d8bbwe\\cloudflared.exe';
        return is_file($known) ? $known : null;
    }

    private function setFrontendUrl(string $url): void
    {
        $path = base_path('.env');
        if (!is_file($path)) return;
        $content = file_get_contents($path);

        if (preg_match('/^FRONTEND_URL=.*$/m', $content)) {
            $content = preg_replace('/^FRONTEND_URL=.*$/m', 'FRONTEND_URL=' . $url, $content);
        } else {
            $content = rtrim($content) . "\nFRONTEND_URL=" . $url . "\n";
        }
        file_put_contents($path, $content);
    }
}
