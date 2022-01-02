FROM alpine:3 AS cloner
ARG COMMIT_HASH
RUN apk add --no-cache bash git
RUN git clone https://github.com/ApertureDevs/sea-of-bottles.git /app \
    && cd /app \
    && git checkout $COMMIT_HASH

FROM node:lts AS builder
ARG APP_ENV
COPY --from=cloner /app/frontend /app
RUN cd /app \
    && npm ci \
    && npx ng build --configuration=$APP_ENV

FROM nginx:1
COPY --from=builder /app/dist /app
WORKDIR /app
