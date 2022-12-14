#! /bin/bash

# Get the name of the project
clear
echo "Please enter your project name"
read pname
echo "Please enter your project folder name"
read name
echo "Is that OK? Enter to continue ctrl+c to interupt"
read 
clear
passDB=$( LC_CTYPE=C tr -dc A-Za-z0-9 </dev/urandom | head -c 13 )
passFTP=$( LC_CTYPE=C tr -dc A-Za-z0-9 </dev/urandom | head -c 13 )
passUSER=$( LC_CTYPE=C tr -dc A-Za-z0-9 </dev/urandom | head -c 13 )
dashname=$( echo $pname | tr " " "-" | tr '[:upper:]' '[:lower:]' )

# Create OctoberCMS productKey and save to the file 
env NAME=$name mocha ~/Sites/auto/create-product-key
productKey=$(<product-key.txt)

echo "$productKey"
echo "Is that OK? Enter to create GIT ctrl+c to interupt"
read 

# Create GIT 
postdata='{
    "name": "'${pname}'", 
    "initialize_with_readme": "false"
}'
curl --request POST --header "PRIVATE-TOKEN: [your-gitlab-api-key]" \
    --header "Content-Type: application/json" --data "$postdata" \
    --url 'https://gitlab.com/api/v4/projects/'

# Create DB OK
php ~/Sites/auto/create-db.php $name $passDB

# Create FTP OK
php ~/Sites/auto/create-ftp.php $name $passFTP

# Dowload october via composer OK
composer create-project october/october ~/Sites/$name

# Change terminal directory to project
cd ~/Sites/$name

# Install octoberCMS productKey
php artisan project:set $productKey

# Set up the enviroment
php ~/Sites/auto/set-env.php ~/Sites/${name}/.env http://${name}.test /${name}-admin mariadb103.r1.websupport.sk 3313 prefix_$name prefix_$name $passDB 

# Install octoberCMS
php artisan october:build

# GIT init OK
git init --initial-branch=main
git remote add origin https://gitlab.com/your-gitlab-username/$dashname.git
git add .
git commit -m "Initial commit"
git push -u origin main

# migrate octoberCMS database OK
php artisan october:migrate

# Install October plugins OK 
php artisan plugin:install rainlab.pages
php artisan plugin:install rainlab.builder
php artisan plugin:install janvince.smallgdpr

# Install NPM plugins OK 
npm install 
npm i bulma 
npm i laravel-mix --save-dev
npm i expose-loader --save-dev

# Copy plugins, webpack config and theme OK
rsync -a ~/Sites/auto/copy/ ~/Sites/$name/

# Admin creation
env ADMINURL=http://${name}.test/${name}-admin ADMINPASSWORD=${passUSER} mocha ~/Sites/auto/create-admin

# Second push to GIT
git add .
git commit -m "Install commit"
git push -u origin main

# First init of laravel mix 
npx mix watch

# Print out all information 
clear
echo "$pname"
echo ""
echo "======PROJECT======"
echo "Local: http://${name}.test"
echo "Test: https://${name}.[your-domain]"
echo "Admin URL: /${name}-admin" 
echo ""
echo "======DATABASE====="
echo "Database host: mariadb103.r1.websupport.sk"
echo "Database port: 3313"
echo "Database name: prefix_$name"
echo "Database username: prefix_$name"
echo "DB password: $passDB"
echo ""
echo "=======SFTP========"
echo "SFTP host: [your-domain]"
echo "SFTP user: ${name}.[your-domain]"
echo "SFTP password: $passFTP"
echo ""
echo "=======ADMIN======="
echo "ADMIN username: your-username"
echo "ADMIN email: your-email"
echo "ADMIN password: $passUSER"
echo ""
echo "Dash name: $dashname"

# New Note
echo "Create a new note and copy info:"
echo "Press Enter after that"
read 

# Wait for setup GITFTP OK
echo "Setup the GITFTP DEV:"
echo "=======SFTP========"
echo "SFTP host: [your-domain]"
echo "SFTP user: ${name}.[your-domain]"
echo "SFTP password: $passFTP"
read 

# Upload only gitignored items
sftp ${name}.[your-domain]@[your-domain] <<EOT
mkdir vendor
mkdir modules
mkdir bootstrap
put -r vendor
put -r modules
put bootstrap/compiled.php
put .env
quit
EOT
