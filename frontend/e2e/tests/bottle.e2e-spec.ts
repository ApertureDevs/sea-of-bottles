import {browser, by, element} from 'protractor';
import {AppPage} from '../framework/app.po';
import {expectNoConsoleLogErrorEntry} from '../framework/assertions';

describe('Create Bottle', () => {
  let page: AppPage;

  beforeEach(async () => {
    page = new AppPage();
    await page.navigateTo('/bottle/create');
  });

  it('should contains header component', async () => {
    expect(await page.hasHeader()).toBe(true);
  });

  it('should contains footer component', async () => {
    expect(await page.hasFooter()).toBe(true);
  });

  it('should contains create-bottle component', async () => {
    expect(await element(by.css('.create-bottle')).isPresent()).toBe(true);
  });

  it('should contains a valid creation bottle form', async () => {
    await element(by.id('message')).sendKeys('This is a test message!');
    await element(by.css('button[type=submit]')).click();
    await browser.waitForAngular();
    expect(await element(by.css('.success')).isPresent()).toBe(true);
  });

  it('should contains no console error', async () => {
    const logs = await page.getLogs();
    expectNoConsoleLogErrorEntry(logs);
  });
});
