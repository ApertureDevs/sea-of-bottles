import {TestBed} from '@angular/core/testing';
import {TranslationService} from '@core/translation/translation.service';
import {TranslateService} from '@ngx-translate/core';
import {of} from 'rxjs';
import {StorageService} from '@data/service/storage.service';

describe('TranslationService', () => {
  let translationService: TranslationService;
  let translateServiceSpy: jasmine.SpyObj<TranslateService>;
  let storageServiceSpy: jasmine.SpyObj<StorageService>;

  beforeEach(() => {
    const spyTranslateService = jasmine.createSpyObj('TranslateService', ['setDefaultLang', 'use', 'get']);
    const spyStorageService = jasmine.createSpyObj('StorageService', {getItem: of(null), setItem: of()});

    TestBed.configureTestingModule({
      providers: [
        TranslationService,
        {provide: StorageService, useValue: spyStorageService},
        {provide: TranslateService, useValue: spyTranslateService},
      ],
    });

    storageServiceSpy = TestBed.inject(StorageService) as jasmine.SpyObj<StorageService>;
    translationService = TestBed.inject(TranslationService);
    translateServiceSpy = TestBed.inject(TranslateService) as jasmine.SpyObj<TranslateService>;
  });

  it('should be created', () => {
    expect(translationService).toBeTruthy();
    expect(translateServiceSpy.setDefaultLang).toHaveBeenCalledOnceWith('en');
  });

  it('should change language', () => {
    translationService.changeLanguage('fr');
    expect(translateServiceSpy.use).toHaveBeenCalledOnceWith('fr');
    expect(storageServiceSpy.setItem).toHaveBeenCalledOnceWith('language', 'fr');
  });

  it('should translate key', () => {
    translateServiceSpy.get.and.returnValue(of('translated-key'));
    translationService.translateKey('test').subscribe((translated) => {
      expect(translated).toEqual('translated-key');
    });
  });
});
