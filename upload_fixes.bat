@echo off
echo ========================================
echo  Upload de Arquivos Corrigidos - FTP
echo ========================================
echo.

set FTP_HOST=186.209.113.101
set FTP_USER=wind_ftp@faroldeluz.ong.br
set FTP_PASS=Fyj3P7w-Dvh6N

echo Criando script FTP...
(
echo open %FTP_HOST%
echo %FTP_USER%
echo %FTP_PASS%
echo binary
echo cd /
echo put controllers\DialogoController.php controllers/DialogoController.php
echo put controllers\MagazineController.php controllers/MagazineController.php
echo put check_files.php check_files.php
echo put clear_cache.php clear_cache.php
echo bye
) > ftp_upload.txt

echo.
echo Fazendo upload dos arquivos...
ftp -s:ftp_upload.txt

echo.
echo Limpando arquivo temporario...
del ftp_upload.txt

echo.
echo ========================================
echo  Upload concluido!
echo ========================================
echo.
echo Acesse agora:
echo 1. https://faroldeluz.ong.br/check_files.php
echo 2. https://faroldeluz.ong.br/clear_cache.php
echo 3. https://faroldeluz.ong.br/dialogos
echo 4. https://faroldeluz.ong.br/revista
echo.
pause
