# ============================================================================
#  start-tunnel.ps1
#  Levanta un Quick Tunnel de Cloudflare hacia el frontend (Vite :5173),
#  captura la URL publica aleatoria y la deja en FRONTEND_URL del backend (.env).
#  Deja la ventana abierta mientras dure la prueba. Ctrl+C apaga el tunel.
# ============================================================================

$ErrorActionPreference = 'Stop'

$BackendDir = $PSScriptRoot                       # carpeta de este script (POSRed)
$EnvFile    = Join-Path $BackendDir '.env'
$VitePort   = 5173
$ApiPort    = 8000

Write-Host "=== Tunel publico (Cloudflare) para la tienda ===" -ForegroundColor Cyan

# --- 1. Localizar cloudflared ---
$cf = (Get-Command cloudflared -ErrorAction SilentlyContinue).Source
if (-not $cf) {
  $cf = Get-ChildItem "$env:LOCALAPPDATA\Microsoft\WinGet\Packages" -Recurse -Filter cloudflared.exe -ErrorAction SilentlyContinue |
        Select-Object -First 1 -ExpandProperty FullName
}
if (-not $cf) {
  Write-Host "X cloudflared no esta instalado." -ForegroundColor Red
  Write-Host "  Instalalo con:  winget install --id Cloudflare.cloudflared" -ForegroundColor Yellow
  Read-Host "Enter para salir"; exit 1
}

# --- 2. Avisar si los servicios locales no estan arriba ---
foreach ($p in @($ApiPort, $VitePort)) {
  $ok = (Test-NetConnection localhost -Port $p -WarningAction SilentlyContinue).TcpTestSucceeded
  if (-not $ok) {
    Write-Host "! El puerto $p no responde." -ForegroundColor Yellow
    if ($p -eq $VitePort) { Write-Host "  -> Levanta el frontend: en POS\Frontend corre 'npm run dev'" -ForegroundColor Yellow }
    if ($p -eq $ApiPort)  { Write-Host "  -> Levanta el backend (Apache en :8000)" -ForegroundColor Yellow }
  }
}

# --- 3. Iniciar el tunel y capturar la URL ---
# Cierra cualquier tunel previo para no acumular varios
Get-Process cloudflared -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue

Write-Host "Iniciando tunel..." -ForegroundColor Cyan
# Log en %TEMP% (sin espacios) y vía --logfile (no -RedirectStandard, para no heredar handles)
$log = Join-Path $env:TEMP ("cf_tunnel_{0}.log" -f ([guid]::NewGuid().ToString()))
if (Test-Path $log) { Remove-Item $log -Force }

$proc = Start-Process -FilePath $cf `
        -ArgumentList 'tunnel', '--url', "http://localhost:$VitePort", '--logfile', $log `
        -PassThru -WindowStyle Hidden

$url = $null
$deadline = (Get-Date).AddSeconds(30)
while ((Get-Date) -lt $deadline -and -not $url) {
  Start-Sleep -Milliseconds 1500
  $content = (Get-Content $log -Raw -ErrorAction SilentlyContinue)
  $m = [regex]::Match([string]$content, 'https://[a-z0-9-]+\.trycloudflare\.com')
  if ($m.Success) { $url = $m.Value }
}

if (-not $url) {
  Write-Host "X No se obtuvo la URL del tunel. Revisa: $log" -ForegroundColor Red
  if (-not $proc.HasExited) { Stop-Process -Id $proc.Id -Force }
  Read-Host "Enter para salir"; exit 1
}

# --- 4. Actualizar FRONTEND_URL en .env (UTF-8 sin BOM) ---
$envContent = Get-Content $EnvFile -Raw
if ($envContent -match '(?m)^FRONTEND_URL=.*$') {
  $envContent = [regex]::Replace($envContent, '(?m)^FRONTEND_URL=.*$', "FRONTEND_URL=$url")
} else {
  $envContent = $envContent.TrimEnd() + "`nFRONTEND_URL=$url`n"
}
[System.IO.File]::WriteAllText($EnvFile, $envContent, (New-Object System.Text.UTF8Encoding $false))

# --- 5. Limpiar config de Laravel ---
Push-Location $BackendDir
try { & php artisan config:clear | Out-Null } catch { Write-Host "! No pude correr 'php artisan config:clear' (hazlo a mano)" -ForegroundColor Yellow }
Pop-Location

# --- 6. Mostrar los links ---
Write-Host ""
Write-Host "==================================================================" -ForegroundColor Green
Write-Host " TUNEL ACTIVO" -ForegroundColor Green
Write-Host "   Tienda:   $url/" -ForegroundColor White
Write-Host "   Referido: $url/<codigo>/register" -ForegroundColor White
Write-Host "==================================================================" -ForegroundColor Green
Write-Host " FRONTEND_URL actualizado en .env y config limpiada." -ForegroundColor Green
Write-Host " Deja ESTA ventana abierta mientras dure la prueba." -ForegroundColor Yellow
Write-Host " Presiona Ctrl+C (o cierra la ventana) para apagar el tunel." -ForegroundColor Yellow
Write-Host ""

# --- 7. Mantener vivo; al salir, matar el tunel ---
try {
  Wait-Process -Id $proc.Id
} finally {
  if (-not $proc.HasExited) { Stop-Process -Id $proc.Id -Force }
  Write-Host "Tunel apagado." -ForegroundColor Cyan
}
