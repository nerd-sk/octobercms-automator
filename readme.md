# OctoberCMS Automator 
Mac Script for automate creation of OctoberCMS project **(sorry its a really shitcode - I'm not the shell scripter - and I dont have time to make it pretty)**. You have to set it up to your style (hosting, git provider etc...), but some code its easy to reuse for your needs.

## How to run it
```
npm install 
sh new.sh
```

## Story
After installing about 30. site with OctoberCMS I've just hate to setup everything. This script handle every step for me, which saves me a lot of time.  
User just name the project and the folder name on local enviroment.

## What does it do 
- Generate passwords for SFTP,DB and admin user
- Get the OctoberCMS licence key via sellenium script
- Create a GIT repository on GITLAB via API
- Create public database on Websupport (Slovak hosting provider) - I am use it also in my local enviroment, becouse my local and public test site is like mirror :)
- Create SFTP account on Websupport (Slovak hosting provider)
- Download octoberCMS via composer to the specified folder (for my case its ```/Sites/[name]``` becouse im using laravel valet)
- Set up the OctoberCMS product key  
- Set up the .env file with the generated database, url, backend-uri, theme, and SMTP email account for testing
- Install octoberCMS (with everything setted up)
- Init git with setup the remote origin and push the initial commit to the gitlab
- migrate the project
- install my core octoberCMS plugins 
- install npm with my core libraries
- copy my theme and other files (copy dir) with r-sync
- admin user for backend creation with the sellenium script 
- git commit and push 
- laravel mix watch becouse of installing another libraries
- print out all information and start coping gitignored files to the sftp account 
- other files its automaticaly uploaded by the gitFTP-Deploy app which I used

## Do you like it? Searching for maintainer, upgrader, uber macho man 
This project its build for my very specific needs (GitLab, Laravel Valet, Websupport - SK hosting), not experienced shellscripter, selleniumer, phper and really with hurry. But if you find it usefull and want to upgrade it it will be awesome for other users to save time. 

So if you wanna be the maintainer dont hesitate to contact me  
 ```be [at] nerd.sk```