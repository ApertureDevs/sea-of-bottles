import {StorageService} from '@data/service/storage.service';

describe('StorageService', () => {
  let storageService: StorageService;

  beforeEach(() => {
    storageService = new StorageService();
  });

  it('should be created', () => {
    expect(storageService).toBeTruthy();
  });

  it('should store item', async () => {
    await storageService.setItem('test-key', 'test-value');
    const storedValue = await storageService.getItem<string>('test-key').toPromise();
    expect(storedValue).toBe('test-value');
  });

  it('should remove item', async () => {
    await storageService.setItem('test-key', 'test-value').toPromise();
    let storedValue = await storageService.getItem<string>('test-key').toPromise();
    expect(storedValue).toBe('test-value');
    await storageService.removeItem('test-key').toPromise();
    storedValue = await storageService.getItem<string>('test-key').toPromise();
    expect(storedValue).toBeNull();
  });
});
