ARG NODE_VERSION=13
ARG NGINX_VERSION=1.17

# develop stage
FROM node:${NODE_VERSION}-alpine as develop-stage
WORKDIR /app

RUN yarn global add @quasar/cli

COPY package.json yarn.lock ./
RUN set -eux; \
	yarn install

COPY . ./

VOLUME /app/node_modules

CMD ["quasar", "dev"]

# build stage
FROM develop-stage as build-stage
RUN set -eux; \
	quasar build

# production stage
FROM nginx:${NGINX_VERSION}-alpine as production-stage
COPY --from=build-stage /app/dist/spa /usr/share/nginx/html
