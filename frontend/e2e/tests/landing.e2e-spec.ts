import {browser, by, element} from 'protractor';
import {AppPage} from '../framework/app.po';
import {expectNoConsoleLogErrorEntry} from '../framework/assertions';

describe('Landing', () => {
  let page: AppPage;

  beforeEach(async () => {
    page = new AppPage();
    await page.navigateTo('/landing');
  });

  it('should contains header component', async () => {
    expect(await page.hasHeader()).toBe(true);
  });

  it('should contains footer component', async () => {
    expect(await page.hasFooter()).toBe(true);
  });

  it('should contains landing component', async () => {
    expect(await element(by.css('.landing')).isPresent()).toBe(true);
  });

  it('should contains sea counters', async () => {
    await browser.waitForAngular();
    expect(await element(by.css('.counter-section')).isPresent()).toBe(true);
  });

  it('should contains no console error', async () => {
    const logs = await page.getLogs();
    expectNoConsoleLogErrorEntry(logs);
  });
});
