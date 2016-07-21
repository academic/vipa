find src/* -name "*Test.php" | ./bin/fastest --before="./etc/bin/initORM.php test" --verbose --preserve-order --process=4 "bin/phpunit -c app {};"
