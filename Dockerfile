# ---- Base Node ----
FROM node:buster AS base
# Create app directory
WORKDIR /app

# ---- Dependencies ----
FROM base AS dependencies
# A wildcard is used to ensure both package.json AND package-lock.json are copied
COPY package*.json /app/
# To ensure github packages will be installed
COPY .npmrc /app/
# install app dependencies including 'devDependencies'
RUN npm ci

# ---- Copy Files/Build ----
FROM dependencies AS build

WORKDIR /app

COPY . .

RUN npm run build

# --- Releases ----
FROM nginx:stable-alpine AS release

COPY --from=build /app/dist /usr/share/nginx/html
COPY .nginx/nginx.conf /etc/nginx/conf.d/default.conf
