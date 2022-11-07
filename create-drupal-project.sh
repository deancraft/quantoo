mkdir my-first-drupal9-app \
  && cd my-first-drupal9-app \
  && lando init \
    --source cwd \
    --recipe drupal9 \
    --webroot web \
    --name my-first-drupal9-app
    
# Create latest drupal9 project via composer
lando composer create-project drupal/recommended-project:9.x tmp && cp -r tmp/. . && rm -rf tmp

# Start it up
lando start

# Install a site local drush
lando composer require drush/drush

# Install drupal
lando drush site:install --db-url=mysql://drupal9:drupal9@database/drupal9 -y

# List information about this app
lando info
