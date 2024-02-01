echo "Set Safe dir..."
git config --system --add safe.directory '*'
echo "Change dir"
cd /var/www
echo "Reset"
git reset --hard
echo "Update"
git pull origin main 2>&1
echo "Run composer"
php composer.phar install