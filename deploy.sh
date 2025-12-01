#!/bin/bash
# Quick Railway Deployment Setup Script

echo "🚂 Railway Deployment Helper"
echo "=============================="
echo ""

# Check if git is initialized
if [ ! -d .git ]; then
    echo "❌ Git not initialized. Run 'git init' first."
    exit 1
fi

# Check for uncommitted changes
if ! git diff-index --quiet HEAD --; then
    echo "⚠️  Uncommitted changes detected"
    echo "Run: git add . && git commit -m 'your message'"
    exit 1
fi

echo "✅ Git repository ready"
echo ""
echo "📋 Next Steps:"
echo ""
echo "1. Create a GitHub repository (if you haven't already)"
echo "   Visit: https://github.com/new"
echo ""
echo "2. Push your code to GitHub:"
echo "   git remote add origin https://github.com/yourusername/your-repo.git"
echo "   git branch -M main"
echo "   git push -u origin main"
echo ""
echo "3. Deploy on Railway:"
echo "   a. Visit: https://railway.app/new"
echo "   b. Click 'Deploy from GitHub repo'"
echo "   c. Select your repository"
echo "   d. Add MySQL database service"
echo ""
echo "4. Configure environment variables in Railway:"
echo "   - Link MySQL service variables"
echo "   - Set APP_URL to your Railway domain"
echo "   - Set DEBUG_MODE=false"
echo ""
echo "5. Import database schema:"
echo "   Use the Railway MySQL console to run: dev/inventory_schema.sql"
echo ""
echo "📖 For detailed instructions, see: RAILWAY_DEPLOY.md"
echo ""
