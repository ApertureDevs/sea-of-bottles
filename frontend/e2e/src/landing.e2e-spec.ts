import { logging } from 'protractor';
import { AppPage } from './app.po';

describe('Landing', () => {
  let page: AppPage;

  beforeEach(() => {
    page = new AppPage();
  });

  it('should contains no console error', async () => {
    await page.navigateTo('/landing');
    const logs = await page.getLogs();
    expect(logs).not.toContain(jasmine.objectContaining({
      level: logging.Level.SEVERE,
    } as logging.Entry));
  });
});
