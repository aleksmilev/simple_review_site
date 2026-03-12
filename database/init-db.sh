#!/bin/bash
set -e

echo "Waiting for MySQL to be ready..."
until mysql -h mysql -u root -p${MYSQL_ROOT_PASSWORD} -e "SELECT 1" &> /dev/null; do
  echo "MySQL is unavailable - sleeping"
  sleep 1
done

echo "MySQL is ready! Executing SQL files..."

# Execute SQL files in specific order
SQL_FILES=(
  "/database/clean_all.sql"
  "/database/schematic.sql"
  "/database/seed.sql"
)

for sql_file in "${SQL_FILES[@]}"; do
  if [ -f "$sql_file" ]; then
    echo "Executing $(basename $sql_file)..."
    mysql -h mysql -u root -p${MYSQL_ROOT_PASSWORD} ${MYSQL_DATABASE} < "$sql_file"
    echo "Completed $(basename $sql_file)"
  else
    echo "Warning: $sql_file not found, skipping..."
  fi
done

echo "All SQL files executed successfully!"

