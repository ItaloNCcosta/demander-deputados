#!/bin/sh
echo "🚀 Iniciando o container..."

if [ ! -f /var/www/vendor/autoload.php ]; then
    echo "📦 Instalando dependências com o Composer..."
    composer install --no-interaction --prefer-dist
fi

if [ ! -f /var/www/.env ]; then
    echo "🔧 Copiando .env..."
    cp /var/www/.env.example /var/www/.env

    echo "🔑 Gerando chave da aplicação..."
    php artisan key:generate
fi

echo "🔒 Ajustando permissões..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

if [ ! -L /var/www/public/storage ]; then
    echo "🔗 Criando o link simbólico para o storage..."
    php artisan storage:link
fi

echo "📂 Rodando migrations..."
php artisan migrate --force

echo "🌱 Rodando seeders..."
php artisan db:seed --force

if [ ! -d "node_modules" ]; then
    echo "📦 node_modules não encontrado. Instalando dependências..."
    npm install
else
    echo "✅ node_modules já existe. Pulando npm install."
fi

echo "⚡ Rodando npm run dev em segundo plano..."
npm run dev &

echo "🖥️ Iniciando o PHP-FPM..."
exec php-fpm
