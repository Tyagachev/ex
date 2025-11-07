FROM node:22-alpine

WORKDIR /var/www

# Установка зависимостей
COPY package*.json ./
RUN npm install

# Копирование исходного кода
COPY . .

# Права доступа
RUN chown -R node:node /var/www

EXPOSE 3000
CMD ["npm", "run", "dev"]
