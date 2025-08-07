#!/bin/bash
# This script runs before the new application files are copied over.
# It cleans the web server's root directory.

# Navigate to the web root directory
cd /var/www/html

# Remove all existing files to ensure a clean slate
# The 'shopt' command ensures that hidden files (like .htaccess) are also removed.
shopt -s dotglob
rm -rf *
