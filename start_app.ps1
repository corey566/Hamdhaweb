Write-Host "Waiting for composer to finish..."
while ($true) {
    if (Test-Path "vendor/autoload.php") {
        $files = Get-ChildItem "vendor" -Recurse | Measure-Object
        Start-Sleep -Seconds 5
        $files2 = Get-ChildItem "vendor" -Recurse | Measure-Object
        if ($files.Count -eq $files2.Count) {
             break
        }
    }
    Start-Sleep -Seconds 3
}

Write-Host "Composer finished! Setting up environment..."

if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
}
php artisan key:generate

(Get-Content .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=sqlite' | Set-Content .env
(Get-Content .env) -replace 'DB_DATABASE=.*', '#DB_DATABASE=' | Set-Content .env

if (-not (Test-Path "database/database.sqlite")) {
    New-Item -ItemType File -Force -Path "database/database.sqlite"
}

Write-Host "Running migrations and seeders..."
php artisan migrate:fresh --seed

Write-Host "Building frontend assets..."
npm run build

Write-Host "Starting servers..."
Start-Process -FilePath "php" -ArgumentList "artisan serve" -NoNewWindow
Start-Process -FilePath "npm" -ArgumentList "run dev" -NoNewWindow
