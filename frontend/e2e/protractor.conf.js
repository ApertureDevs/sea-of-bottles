const { SpecReporter, StacktraceOption } = require('jasmine-spec-reporter');
const fs = require('fs');

/**
 * @type { import("protractor").Config }
 */
exports.config = {
  allScriptsTimeout: 11000,
  specs: [
    './tests/**/*.e2e-spec.ts'
  ],
  capabilities: {
    browserName: 'chrome',
    chromeOptions: {
      args: [
        '--headless',
        '--no-sandbox',
      ]
    }
  },
  directConnect: true,
  SELENIUM_PROMISE_MANAGER: false,
  baseUrl: 'http://localhost:4200/',
  framework: 'jasmine',
  jasmineNodeOpts: {
    showColors: true,
    defaultTimeoutInterval: 30000,
    print: function() {}
  },
  onPrepare() {
    require('ts-node').register({
      project: require('path').join(__dirname, './tsconfig.json')
    });
    jasmine.getEnv().addReporter(new SpecReporter({
      spec: {
        displayStacktrace: StacktraceOption.PRETTY
      }
    }));
    jasmine.getEnv().addReporter({
      specDone: function(result) {
        const screenshotsDirectory = 'var/screenshots';
        if (!fs.existsSync(screenshotsDirectory)) {
          fs.mkdirSync(screenshotsDirectory);
        }
        if(result.failedExpectations.length > 0) {
          browser.takeScreenshot().then(function (screenshot) {
            let filename = (new Date()).toISOString().replace(/z|t/gi, ' ').trim();
            filename += '.png';
            fs.writeFile(`${screenshotsDirectory}/${filename}`, screenshot, 'base64', function (err) {
              if (err) throw err;
              console.info(`"${filename}" screenshot created.`);
            });
          });
        }
      }})
  }
};
