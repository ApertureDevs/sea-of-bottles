import {browser, logging} from 'protractor';

export class AppPage {
  public async navigateTo(route: string): Promise<void> {
    await browser.get(route);
  }

  public async getLogs(): Promise<logging.Entry[]> {
    let logs = await browser.manage().logs().get(logging.Type.BROWSER);

    return logs;
  }
}
