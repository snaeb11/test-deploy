# Railway Deployment Guide

Your Laravel application is now configured for Railway deployment.

## Prerequisites

1. A Railway account (sign up at https://railway.app)
2. Railway CLI installed (optional, for CLI deployment)
3. Your project pushed to a Git repository (GitHub, GitLab, or Bitbucket)

## Deployment Steps

### Option 1: Deploy via Railway Dashboard (Recommended)

1. **Login to Railway**
    - Go to https://railway.app and sign in

2. **Create a Workspace** (if you don't have one)
    - If this is your first project, Railway will create a default workspace automatically
    - Or click on your profile → "Workspaces" → "New Workspace" to create one

3. **Create a New Project**
    - Click "New Project"
    - Select "Deploy from GitHub repo" (or GitLab/Bitbucket)
    - Choose your repository

4. **Configure Environment Variables**
    - In your Railway project, go to the "Variables" tab
    - Add the following required environment variables:

        ```
        APP_NAME=Laravel
        APP_ENV=production
        APP_KEY=base64:YOUR_APP_KEY_HERE
        APP_DEBUG=false
        APP_URL=https://your-app-name.up.railway.app

        DB_CONNECTION=sqlite
        DB_DATABASE=/app/database/database.sqlite

        LOG_CHANNEL=stack
        LOG_LEVEL=error
        ```

5. **Generate APP_KEY**
    - Run locally: `php artisan key:generate --show`
    - Copy the key and add it as `APP_KEY` in Railway variables

6. **Deploy**
    - Railway will automatically detect the Dockerfile and start building
    - The deployment will run automatically on every push to your main branch

### Option 2: Deploy via Railway CLI

**Important**: If you get a "workspaceid" error, you need to create a workspace first in the Railway dashboard, or use Option 1 (Dashboard method) instead.

1. **Install Railway CLI**

    ```bash
    npm i -g @railway/cli
    ```

2. **Login**

    ```bash
    railway login
    ```

3. **Create or Select a Workspace** (if needed)
    - First, go to https://railway.app and create a workspace in the dashboard
    - Or link to an existing workspace:

    ```bash
    railway link
    ```

4. **Initialize Project**

    ```bash
    railway init
    ```

    - This will prompt you to create a new project or link to an existing one
    - If you don't have a workspace, create one in the dashboard first

5. **Set Environment Variables**

    ```bash
    railway variables set APP_KEY=your-app-key-here
    railway variables set APP_ENV=production
    railway variables set APP_DEBUG=false
    ```

6. **Deploy**
    ```bash
    railway up
    ```

## Important Notes

- **Database**: The app is configured to use SQLite. For production, consider using Railway's PostgreSQL service.
- **Storage**: File uploads are stored in the container. For persistent storage, use Railway's volume service.
- **Port**: Railway automatically sets the `PORT` environment variable - the Dockerfile is configured to use it.
- **Migrations**: Migrations run automatically on each deployment via the entrypoint script.

## Switching to PostgreSQL (Optional)

If you want to use PostgreSQL instead of SQLite:

1. Add a PostgreSQL service in Railway
2. Update environment variables:
    ```
    DB_CONNECTION=pgsql
    DB_HOST=${{Postgres.PGHOST}}
    DB_PORT=${{Postgres.PGPORT}}
    DB_DATABASE=${{Postgres.PGDATABASE}}
    DB_USERNAME=${{Postgres.PGUSER}}
    DB_PASSWORD=${{Postgres.PGPASSWORD}}
    ```

## Troubleshooting

- **"You must specify a workspaceid" error**:
    - **Solution 1 (Easiest)**: Use the Railway Dashboard method (Option 1) instead of CLI
    - **Solution 2**: Create a workspace first at https://railway.app → Profile → Workspaces → New Workspace
    - **Solution 3**: If using CLI, run `railway link` to connect to an existing workspace
- **Build fails**: Check Railway logs for specific errors
- **App not starting**: Verify all environment variables are set correctly
- **Database errors**: Ensure migrations ran successfully (check logs)
- **Static assets not loading**: Verify `npm run build` completed successfully

## Monitoring

- View logs in the Railway dashboard
- Check deployment status in the "Deployments" tab
- Monitor resource usage in the "Metrics" tab
