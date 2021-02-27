import {browser, by, element} from 'protractor';
import {AppPage} from '../framework/app.po';
import {expectNoConsoleLogErrorEntry} from '../framework/assertions';

const generateRandomEmail = () => {
  const random = Math.random().toString(36).substr(2, 10);

  return `${random}@test.com`;
};

const email = generateRandomEmail();

describe('Create Sailor', () => {
  let page: AppPage;

  beforeEach(async () => {
    page = new AppPage();
    await page.navigateTo('/sailor/create');
  });

  it('should contains header component', async () => {
    expect(await page.hasHeader()).toBe(true);
  });

  it('should contains footer component', async () => {
    expect(await page.hasFooter()).toBe(true);
  });

  it('should contains create-sailor component', async () => {
    expect(await element(by.css('.create-sailor')).isPresent()).toBe(true);
  });

  it('should contains a valid creation sailor form', async () => {
    await element(by.id('email')).sendKeys(email);
    await element(by.css('button[type=submit]')).click();
    await browser.waitForAngular();
    expect(await element(by.css('.success')).isPresent()).toBe(true);
  });

  it('should contains no console error', async () => {
    const logs = await page.getLogs();
    expectNoConsoleLogErrorEntry(logs);
  });
});

describe('Delete Sailor', () => {
  let page: AppPage;

  beforeEach(async () => {
    page = new AppPage();
    await page.navigateTo('/sailor/delete');
  });

  it('should contains header component', async () => {
    expect(await page.hasHeader()).toBe(true);
  });

  it('should contains footer component', async () => {
    expect(await page.hasFooter()).toBe(true);
  });

  it('should contains delete-sailor component', async () => {
    expect(await element(by.css('.delete-sailor')).isPresent()).toBe(true);
  });

  it('should contains a valid deletion sailor form', async () => {
    await element(by.id('email')).sendKeys(email);
    await element(by.css('button[type=submit]')).click();
    await browser.waitForAngular();
    expect(await element(by.css('.success')).isPresent()).toBe(true);
  });

  it('should contains no console error', async () => {
    const logs = await page.getLogs();
    expectNoConsoleLogErrorEntry(logs);
  });
});

