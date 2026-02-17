# Upload de Arquivos Corrigidos via FTP
# Autor: Thiago Mourão
# Data: 2026-02-17

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Upload de Arquivos Corrigidos - FTP" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$FtpServer = "ftp://186.209.113.101"
$FtpUser = "wind_ftp@faroldeluz.ong.br"
$FtpPass = "Fyj3P7w-Dvh6N"

# Criar credenciais
$SecurePassword = ConvertTo-SecureString $FtpPass -AsPlainText -Force
$Credential = New-Object System.Management.Automation.PSCredential($FtpUser, $SecurePassword)

# Arquivos para upload
$files = @(
    @{Local="controllers\DialogoController.php"; Remote="/controllers/DialogoController.php"},
    @{Local="controllers\MagazineController.php"; Remote="/controllers/MagazineController.php"},
    @{Local="check_files.php"; Remote="/check_files.php"},
    @{Local="clear_cache.php"; Remote="/clear_cache.php"}
)

foreach ($file in $files) {
    $localPath = $file.Local
    $remotePath = $FtpServer + $file.Remote
    
    if (Test-Path $localPath) {
        Write-Host "Enviando: $localPath..." -ForegroundColor Yellow
        
        try {
            $webclient = New-Object System.Net.WebClient
            $webclient.Credentials = $Credential
            $uri = New-Object System.Uri($remotePath)
            $webclient.UploadFile($uri, $localPath)
            
            Write-Host "  OK - Enviado com sucesso!" -ForegroundColor Green
        }
        catch {
            Write-Host "  ERRO: $_" -ForegroundColor Red
        }
    }
    else {
        Write-Host "AVISO: Arquivo nao encontrado: $localPath" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Upload concluido!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Acesse agora:" -ForegroundColor Yellow
Write-Host "1. https://faroldeluz.ong.br/check_files.php"
Write-Host "2. https://faroldeluz.ong.br/clear_cache.php"
Write-Host "3. https://faroldeluz.ong.br/dialogos"
Write-Host "4. https://faroldeluz.ong.br/revista"
Write-Host ""
Read-Host "Pressione ENTER para fechar"
