#!/bin/bash

echo "Testing Fruits & Vegetables API..."

# Start server in background
php -S localhost:8000 -t public/ &
SERVER_PID=$!

# Wait for server to start
sleep 2

echo "📊 Loading initial data..."
curl -s "http://localhost:8000/load-data"

echo -e "\n\n🍎 Testing fruits collection..."
curl -s "http://localhost:8000/api/collections/fruits" | jq '.count'

echo "🥕 Testing vegetables collection..."
curl -s "http://localhost:8000/api/collections/vegetables" | jq '.count'

echo "🔍 Testing search..."
curl -s "http://localhost:8000/api/collections/fruits/search?q=apple" | jq '.count'

# Kill server
kill $SERVER_PID
echo "API testing completed!"