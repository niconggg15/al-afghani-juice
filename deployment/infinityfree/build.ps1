$ErrorActionPreference = 'Stop'

$repositoryRoot = (Resolve-Path (Join-Path $PSScriptRoot '..\..')).Path
$timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'
$buildRoot = Join-Path $repositoryRoot "storage\app\infinityfree-build-$timestamp"
$uploadRoot = Join-Path $buildRoot 'upload'
$applicationRoot = Join-Path $uploadRoot 'laravel_app'

New-Item -ItemType Directory -Path $applicationRoot -Force | Out-Null

foreach ($directory in @('app', 'bootstrap', 'config', 'database', 'lang', 'resources', 'routes')) {
    Copy-Item -Path (Join-Path $repositoryRoot $directory) -Destination $applicationRoot -Recurse
}

foreach ($file in @('artisan', 'composer.json', 'composer.lock')) {
    Copy-Item -Path (Join-Path $repositoryRoot $file) -Destination $applicationRoot
}

$storageDirectories = @(
    'app',
    'app\public',
    'framework',
    'framework\cache',
    'framework\cache\data',
    'framework\sessions',
    'framework\testing',
    'framework\views',
    'logs'
)

foreach ($directory in $storageDirectories) {
    New-Item -ItemType Directory -Path (Join-Path $applicationRoot "storage\$directory") -Force | Out-Null
}

New-Item -ItemType Directory -Path (Join-Path $applicationRoot 'bootstrap\cache') -Force | Out-Null
Copy-Item -Path (Join-Path $PSScriptRoot 'app.htaccess') -Destination (Join-Path $applicationRoot '.htaccess')

$publicRoot = Join-Path $repositoryRoot 'public'
foreach ($item in Get-ChildItem -LiteralPath $publicRoot -Force) {
    if ($item.Name -ne 'index.php') {
        Copy-Item -LiteralPath $item.FullName -Destination $uploadRoot -Recurse -Force
    }
}

Copy-Item -Path (Join-Path $PSScriptRoot 'index.php') -Destination (Join-Path $uploadRoot 'index.php')

& composer install --working-dir=$applicationRoot --no-dev --optimize-autoloader --no-interaction --prefer-dist
if ($LASTEXITCODE -ne 0) {
    throw "Composer install failed with exit code $LASTEXITCODE."
}

if (Test-Path (Join-Path $applicationRoot '.env')) {
    throw 'The generated application package unexpectedly contains an .env file.'
}

$archivePath = Join-Path $buildRoot 'infinityfree-upload.zip'
Compress-Archive -Path (Join-Path $uploadRoot '*') -DestinationPath $archivePath -CompressionLevel Optimal

Write-Output "Deployment archive created: $archivePath"
Write-Output "Upload and extract the archive into the hosting htdocs directory."
