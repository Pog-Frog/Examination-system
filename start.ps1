$server = Start-Process cmd -ArgumentList "/c php artisan serve" -PassThru
$npm = Start-Process cmd -ArgumentList "/K npm install & npm run dev" -PassThru
$plagarism_api = Start-Process cmd -ArgumentList "/K cd ../Plagarism_fast_api & venv\Scripts\activate & python src/main.py" -PassThru
[console]::TreatControlCAsInput = $true
while ($true)
{
    if ([console]::KeyAvailable)
    {
        $key = [system.console]::readkey($true)
        if (($key.modifiers -band [consolemodifiers]"control") -and ($key.key -eq "C"))
        {
            taskkill /pid $server.Id /t /f
            taskkill /pid $npm.Id /t /f
            taskkill /pid $plagarism_api.Id /t /f
            break
        }
    }
}

