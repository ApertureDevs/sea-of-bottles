import {TestBed} from '@angular/core/testing';
import {ThemeModule} from '@core/theme/theme.module';
import {TranslationService} from '@core/translation/translation.service';
import {TranslateService} from '@ngx-translate/core';
import {of} from 'rxjs';

describe('TranslationService', () => {
  let translationService: TranslationService;
  let translateServiceSpy: jasmine.SpyObj<TranslateService>;

  beforeEach(() => {
    const spyTranslateService = jasmine.createSpyObj('TranslateService', ['setDefaultLang', 'use', 'get']);

    TestBed.configureTestingModule({
      imports: [
        ThemeModule,
      ],
      providers: [
        TranslationService,
        {provide: TranslateService, useValue: spyTranslateService},
      ],
    });

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
  });

  it('should translate key', () => {
    translateServiceSpy.get.and.returnValue(of('translated-key'));
    translationService.translateKey('test').subscribe((translated) => {
      expect(translated).toEqual('translated-key');
    });
  });
});
