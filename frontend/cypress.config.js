const { defineConfig } = require('cypress')

module.exports = defineConfig({
  e2e: {
    specPattern: "e2e/integration",
    supportFile: false,
    videosFolder: "var/test/videos",
    videoUploadOnPasses: false,
    screenshotsFolder: "var/test/screenshots",
    fixturesFolder: "e2e/fixtures",
    baseUrl: "http://frontend-webserver:4200",
    defaultCommandTimeout: 10000
  }
})
