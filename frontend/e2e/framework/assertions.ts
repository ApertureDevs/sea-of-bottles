import {logging} from 'protractor';

export const expectNoConsoleLogErrorEntry = (logs: logging.Entry[]) => {
  expect(logs).not.toContain(jasmine.objectContaining({
    level: logging.Level.SEVERE,
  } as logging.Entry));
};
