import * as fs from 'fs';
import {browser, by, element, logging} from 'protractor';

export class AppPage {
  public async navigateTo(route: string): Promise<void> {
    await browser.get(route);
  }

  public async getLogs(): Promise<logging.Entry[]> {
    const logs = await browser.manage().logs().get(logging.Type.BROWSER);

    return logs;
  }

  public async hasHeader(): Promise<boolean> {
    const isPresent = await element(by.css('.header')).isPresent();

    return isPresent;
  }

  public async hasFooter(): Promise<boolean> {
    const isPresent = await element(by.css('.footer')).isPresent();

    return isPresent;
  }

  public async takeScreenshot() {
    await browser.takeScreenshot().then((png) => {
      const screenshotsDirectory = 'var/screenshots';
      if (!fs.existsSync(screenshotsDirectory)) {
        fs.mkdirSync(screenshotsDirectory);
      }
      let filename = (new Date()).toISOString().replace(/z|t/gi,' ').trim();
      filename += '.png';
      const stream = fs.createWriteStream(`${screenshotsDirectory}/${filename}`);
      stream.write(Buffer.from(png, 'base64'));
      stream.end();
    });
  }
}
