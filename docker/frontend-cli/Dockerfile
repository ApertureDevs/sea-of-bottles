FROM node:lts

# Install dependencies for unit testing
RUN apt-get update \
    && apt-get install -y firefox-esr \
    && rm -rf /var/lib/apt/lists/*
# Install dependencies for e2e testing
RUN apt-get update \
    && apt-get install -y ibgtk2.0-0 libgtk-3-0 libgbm-dev libnotify-dev libgconf-2-4 libnss3 libxss1 libasound2 libxtst6 xauth xvfb\
    && rm -rf /var/lib/apt/lists/*
