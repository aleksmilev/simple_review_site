docker compose up -d --build

if [ ! -d "node_modules" ] && [ -f "package.json" ]; then
    sudo npm install
fi

sudo npm run dev