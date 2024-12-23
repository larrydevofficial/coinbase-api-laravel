# Coinbase API Laravel

This Laravel application integrates with the Coinbase API to retrieve cryptocurrency data, store it in your database, and provide real-time feedback on the fetching process.

## Features

-   **Coinbase API Integration:** Makes calls to the Coinbase API to fetch crypto data.
-   **Automatic Data Storage:** Uses background jobs to store retrieved data in your database asynchronously.
-   **Real-Time Feedback:** Provides updates on the status of data fetching, including initiation, progress, and completion.

## Requirements

-   **Laravel:** Ensure you have Laravel installed on your development machine. Refer to the official documentation for installation instructions: [https://laravel.com/docs/](https://laravel.com/docs/)
-   **Docker:** Docker and Docker Compose are required for building and running the application. Detailed installation guides are available at [https://www.docker.com/](https://www.docker.com/) and [https://docs.docker.com/compose/](https://docs.docker.com/compose/)
-   **Coinbase API Keys:** You'll need API keys from your Coinbase developer account. To create API keys:
    1.  Log in to your Coinbase account and navigate to the Developer Dashboard.
    2.  Click on "API Keys" and create a new API key.
    3.  Copy and paste the API key and secret into your `.env` file, following the format provided.

## Installation

1.  **Clone the repository:**

    ```bash
    git clone https://github.com/larrydevofficial/coinbase-api-laravel.git
    ```

2.  **Install dependencies:**

    ```bash
    cd coinbase-api-laravel
    composer install
    ```

3.  **Configure environment variables:**

    Create a file named `.env` in the project root directory. Paste the following lines into the `.env` file, replacing with your actual Coinbase API keys:

    ```
    COINBASE_KEY_NAME="organizations/{org_id}/apiKeys/{key_id}"
    COINBASE_KEY_SECRET="-----BEGIN EC PRIVATE KEY-----\nYOUR PRIVATE KEY\n-----END EC PRIVATE KEY-----\n"
    ```

    **Important:**

    -   Replace `{org_id}` with your organization ID.
    -   Replace `YOUR PRIVATE KEY` with your actual Coinbase API key secret in the correct format.

4.  **Generate application key:**

    ```bash
    php artisan key:generate
    ```

5.  **Run database migrations:**

    ```bash
    php artisan migrate
    ```

6.  **Run NPM with HMR (optional):** (For development with Hot Module Replacement)

    ```bash
    npm run dev
    ```

## Usage

1.  **Build and start containers:**

    ```bash
    docker-compose build
    docker-compose up
    ```

2.  **Access the application:**

    Open http://localhost in your web browser to access the application.

    **Note:** The actual application endpoint might differ depending on your Laravel project structure. If you've customized the port used by the containers, adjust the URL accordingly (e.g., http://localhost:8000 if port 8000 is mapped).

## Development

1.  **Make changes:**

    Edit the relevant Laravel code files as needed.

2.  **Rebuild and restart containers:**

    ```bash
    docker-compose build
    docker-compose up -d --build
    ```

    The `-d` flag instructs Docker Compose to run the containers in detached mode, allowing them to run in the background. The `--build` flag ensures that containers are rebuilt if any dependencies have changed.

3.  **Exec into container:**

    ```bash
    docker compose exec -it app sh
    ```

    If you do not have Node or Composer installed on the host machine, you will have to `docker exec` into the `app` container to run commands like `php artisan` or `npm run dev`.

## Testing

1.  **Run tests:**

    ```bash
    php artisan test
    ```
    When the application is functioning correctly, all tests should pass.

## Queues

1.  **Run queues:**

    ```bash
    php artisan queue:work
    ```
    This application uses queues to process inserting models into the database as well as other tasks.


## Additional Notes

-   This README provides a general guide. Refer to the Laravel and Coinbase API documentation for more details on specific functionalities.
-   Consider incorporating security best practices (e.g., sanitization, validation) for user input and API interactions.
-   Implement logging for debugging purposes.
-   Consider implementing unit and integration tests for comprehensive application testing.