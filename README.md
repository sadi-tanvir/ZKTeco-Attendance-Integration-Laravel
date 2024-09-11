# ZKTeco-Attendance-Integration-PHP

A PHP-based integration solution for ZKTeco devices, focusing on attendance management. This project enables seamless communication with ZKTeco devices, retrieves attendance records, and manages attendance data in a Laravel application.

## Features

- **Device Connection**: Establishes a connection with ZKTeco attendance devices.
- **Attendance Retrieval**: Fetches attendance records from the device.
- **Database Management**: Stores attendance data in the local database using Eloquent models.
- **Automated Sync**: Automatically syncs attendance data with a remote CMS through API requests.
- **Error Handling**: Logs connection attempts, errors, and duplicate records.

## Installation

1. **Clone the repository**:

    ```bash
    git clone https://github.com/sadi-tanvir/ZKTeco-Attendance-Integration-Laravel.git
    ```

2. **Navigate to the project directory**:

    ```bash
    cd ZKTeco-Attendance-Integration-Laravel
    ```

3. **Install dependencies**:

    ```bash
    composer install
    ```

4. **Configure your `.env` file**:

    Copy the `.env.example` to `.env` and update the database and other configurations according to your environment.

    ```bash
    cp .env.example .env
    ```

    ### Configure API URL
    
    To send attendance information to your database, you need to specify your API URL in the `.env` file. Follow these steps:
    
    1. **Open your `.env` file**.
    
    2. **Add the following line** to specify your API endpoint. Replace `https://example.com/api/create-attendance` with your actual API URL:

    ```env
    CMS_API_URL=https://example.com/api/create-attendance
    ```

This configuration allows your application to send attendance data to the specified API endpoint.


5. **Generate application key**:

    ```bash
    php artisan key:generate
    ```

## Usage

### Laravel Development Script

To streamline your development workflow, a batch script (`larvel.bat`) is provided to start the Laravel server and run the scheduler worker simultaneously.

#### Script: `larvel.bat`

```batch
@echo off
cd /d "D:\laragon\www\teco_v1"
start "" "D:\laragon\bin\php\php-8.2\php.exe" artisan serve
start "" "D:\laragon\bin\php\php-8.2\php.exe" artisan schedule:work
pause
