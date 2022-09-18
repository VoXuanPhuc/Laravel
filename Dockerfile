# ---- Base Node ----
FROM node:latest AS base
# Create app directory
WORKDIR /app

# ---- Dependencies ----
FROM base AS dependencies
# A wildcard is used to ensure both package.json AND package-lock.json are copied
COPY package*.json /app/

# install app dependencies including 'devDependencies'
RUN yarn install

# ---- Copy Files/Build ----
FROM dependencies AS build

WORKDIR /app

COPY . .

RUN yarn build

# --- Releases ----
FROM nginx:stable-alpine AS release

COPY --from=build /app/dist /usr/share/nginx/html
COPY .nginx/nginx.conf /etc/nginx/conf.d/default.conf
