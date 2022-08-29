FROM node:lts

# Install dependencies for unit testing
RUN apt-get update \
    && apt-get install -y firefox-esr \
    && rm -rf /var/lib/apt/lists/*
