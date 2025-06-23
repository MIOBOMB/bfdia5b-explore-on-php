# bfdia5b-explore-on-php
BFDIA 5b explore core written on php

# Setup
1. Upload the files on a webserver
2. Import database.sql into a MySQL/MariaDB database
3. Modify "server/api.php" by adding database and MySQL user

# Setup captcha
1. open https://docs.hcaptcha.com/ and register
2. create sitekey
3. enter secretkey and sitekey in variables in "server/api.php"
4. modify $registercaptcha or $logincaptcha at your discretion
