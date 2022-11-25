gnome-terminal --tab --title="server" -e 'sh -c "php artisan serve"' --tab --title="npm" -e 'sh -c "npm install ; npm run dev"' --tab --title="Plagarism API" -e 'sh -c "cd ../Plagarism_fast_api && source venv/bin/activate && python src/main.py"';

while true; do
    read -p "exit?" t
    case $t in
        "1" ) pkill "gnome-terminal"; gnome-terminal --tab --title="server" -e 'sh -c "php artisan serve"' --tab --title="npm" -e 'sh -c "npm install ; npm run dev"' --tab --title="Plagarism API" -e 'sh -c "cd ../Plagarism_fast_api && source venv/bin/activate && python src/main.py"';;
        "2" ) pkill "gnome-terminal"; gnome-terminal --tab --title="server" -e 'sh -c "composer update; php artisan serve"' --tab --title="npm" -e 'sh -c "npm install ; npm run dev"';;
        * ) pkill "gnome-terminal"; exit;;
    esac
done
