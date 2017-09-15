#!/bin/bash

###
# Generate required salt configurations for WordPress
###

echo AUTH_KEY="$(openssl rand -base64 32)" SECURE_AUTH_KEY="$(openssl rand -base64 32)" LOGGED_IN_KEY="$(openssl rand -base64 32)" NONCE_KEY="$(openssl rand -base64 32)" AUTH_SALT="$(openssl rand -base64 32)" SECURE_AUTH_SALT="$(openssl rand -base64 32)" LOGGED_IN_SALT="$(openssl rand -base64 32)" NONCE_SALT="$(openssl rand -base64 32)" WP_CACHE_KEY_SALT="$(openssl rand -base64 32)"