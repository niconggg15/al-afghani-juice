# Deploying to InfinityFree

Set the domain directory to `htdocs` and upload the Laravel project so that
`app`, `bootstrap`, `vendor`, and `public` are directly inside `htdocs`.
The root `index.php` and `.htaccess` files forward requests to Laravel's
`public` directory and serve public assets from there.

Pushing changes to GitHub does not automatically upload them to InfinityFree.
After pushing, upload the changed files to `htdocs` with the InfinityFree File
Manager or FTP. Keep `.env` private and configure the production app key,
database connection, and `APP_URL` on the hosting account.
