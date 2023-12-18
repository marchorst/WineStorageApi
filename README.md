# WineStorageApi
The WineStorageApi is a straightforward PHP application designed for managing a collection of wine bottles. 
Paired with the WineStorageUi, it offers a clean and user-friendly interface for users to interact with their wine inventory. 
This application is intended to be a simpler alternative to platforms like Vivino, offering fewer features but without intrusive advertisements.

## Features:
- Wine Bottle Storage: Easily store and manage information about various wine bottles.
- Simplified Interface: The WineStorageUi provides a user-friendly and visually appealing interface for managing wine collections.
- Future Extensions: The application is planned to undergo further development and enhancements in the future.

## Home Assistant:
The WineStorageApi is designed to be running in HomeAssistant OS, offering the capability to function as a plugin through the WineStorageHomeAssistantRepository.

## Installation
To install the WineStorageApi, follow these steps:

1. Clone the WineStorageHomeAssistantRepository:

```bash
git clone <repository_url>
```
2. Run the following command to install dependencies using Composer:

```bash
composer install
```
3. Clear the database and recreate the database schema:

```bash
php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:create
```

4. Copy the .env file to .env.local
```bash
cp .env .env.local
```
6. Adjust the values in the .env.local file according to your environment and configuration needs.

The latest WineStorageUi is available on the root path.
