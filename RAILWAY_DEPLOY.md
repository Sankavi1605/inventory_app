# Railway Deployment Guide

## Prerequisites
1. A Railway account (sign up at https://railway.app)
2. Git installed on your machine
3. This project in a Git repository

## Step 1: Prepare Your Repository

Make sure all files are committed to Git:
```bash
git add .
git commit -m "Prepare for Railway deployment"
```

## Step 2: Create MySQL Database on Railway

1. Go to https://railway.app/new
2. Click "New Project"
3. Click "Provision MySQL"
4. Note down the connection details (they'll be available in the MySQL service variables)

## Step 3: Deploy Application

1. In Railway dashboard, click "New" → "GitHub Repo" (or "Empty Project" if not using GitHub)
2. Select your repository (or connect it first)
3. Railway will automatically detect the Dockerfile and build your app

## Step 4: Configure Environment Variables

In your Railway project, add these environment variables:

### Database Configuration (from MySQL service)
- `DB_HOST` - MySQL host (e.g., `containers-us-west-123.railway.app`)
- `DB_USER` - MySQL username (e.g., `root`)
- `DB_PASSWORD` - MySQL password (copy from MySQL service)
- `DB_NAME` - Database name (e.g., `railway`)
- `MYSQL_PORT` - MySQL port (e.g., `3306`)

### Application Configuration
- `APP_URL` - Your Railway app URL (e.g., `https://your-app.up.railway.app`)
- `DEBUG_MODE` - Set to `false` for production

## Step 5: Import Database Schema

### Option A: Using Railway MySQL Console
1. Click on your MySQL service in Railway
2. Go to "Data" tab → "Query"
3. Copy and paste the contents of `dev/inventory_schema.sql`
4. Run the query

### Option B: Using MySQL Client
1. Get the MySQL connection string from Railway
2. Connect using your local MySQL client:
   ```bash
   mysql -h <DB_HOST> -u <DB_USER> -p<DB_PASSWORD> <DB_NAME> < dev/inventory_schema.sql
   ```

### Option C: Using phpMyAdmin (if available)
1. Connect to your Railway MySQL instance
2. Import `dev/inventory_schema.sql`

## Step 6: Verify Deployment

1. Once deployed, Railway will provide you a URL (e.g., `https://your-app.up.railway.app`)
2. Visit the URL to check if the application is running
3. Try to login with default credentials:
   - Username: `admin`
   - Password: `admin123`

## Step 7: Connect Services (Important!)

In Railway dashboard:
1. Click on your web application service
2. Go to "Variables" tab
3. Click "Reference Variables"
4. Select your MySQL service
5. Add references for:
   - `MYSQLHOST` → `DB_HOST`
   - `MYSQLUSER` → `DB_USER`
   - `MYSQLPASSWORD` → `DB_PASSWORD`
   - `MYSQLDATABASE` → `DB_NAME`

## Troubleshooting

### Database Connection Issues
- Verify all DB_* environment variables are set correctly
- Check that the MySQL service is running
- Ensure the database schema has been imported

### 500 Internal Server Error
- Check the deployment logs in Railway
- Verify `APP_URL` environment variable matches your Railway domain
- Set `DEBUG_MODE=true` temporarily to see detailed errors

### CSS/JS Not Loading
- Ensure `APP_URL` is set correctly with `https://` protocol
- Clear browser cache
- Check that public folder permissions are correct

### Session Issues
- Railway uses ephemeral file systems, so file-based sessions may not persist
- Consider implementing database-based sessions for production

## Railway CLI (Optional)

Install Railway CLI for easier management:
```bash
npm i -g @railway/cli
railway login
railway link
railway logs
```

## Environment Variables Reference

```env
# Database (from MySQL service)
DB_HOST=containers-us-west-xxx.railway.app
DB_USER=root
DB_PASSWORD=<your-mysql-password>
DB_NAME=railway
MYSQL_PORT=3306

# Application
APP_URL=https://your-app.up.railway.app
DEBUG_MODE=false
```

## Automatic Deployments

Railway automatically deploys when you push to your connected Git branch:
```bash
git add .
git commit -m "Update application"
git push origin main
```

## Custom Domain (Optional)

1. Go to your Railway project settings
2. Click "Settings" → "Domains"
3. Click "Custom Domain"
4. Follow instructions to add your domain
5. Update `APP_URL` environment variable to your custom domain

## Cost Estimate

Railway offers:
- $5/month starter plan with 500 hours execution time
- Free $5 credit for new users
- Pay-as-you-go pricing

## Production Checklist

- [ ] Database schema imported successfully
- [ ] Environment variables configured
- [ ] `DEBUG_MODE` set to `false`
- [ ] Default admin password changed
- [ ] Application accessible via Railway URL
- [ ] Login and core features tested
- [ ] SSL certificate active (automatic on Railway)

## Support

For Railway-specific issues:
- Documentation: https://docs.railway.app
- Discord: https://discord.gg/railway
- Status: https://status.railway.app
