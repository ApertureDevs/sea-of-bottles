import {ComponentFixture, TestBed} from '@angular/core/testing';
import {TranslationComponent} from '@shared/translation/translation.component';
import {TranslationService} from '@core/translation/translation.service';
import {EventEmitter} from '@angular/core';

describe('TranslationComponent', () => {
  let translationServiceSpy: jasmine.SpyObj<TranslationService>;
  let component: TranslationComponent;
  let fixture: ComponentFixture<TranslationComponent>;

  beforeEach(() => {
    const spyTranslationService = jasmine.createSpyObj(
      'TranslationService',
      ['changeLanguage', 'getCurrentLanguage'],
      {languageChanged: new EventEmitter()},
    );

    TestBed.configureTestingModule({
      declarations: [
        TranslationComponent,
      ],
      providers: [
        {provide: TranslationService, useValue: spyTranslationService},
      ],
    })
      .compileComponents();

    translationServiceSpy = TestBed.inject(TranslationService) as jasmine.SpyObj<TranslationService>;
    fixture = TestBed.createComponent(TranslationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be creatable', () => {
    expect(component).toBeTruthy();
  });

  it('should change language', () => {
    component.changeLanguage('fr');
    expect(translationServiceSpy.changeLanguage).toHaveBeenCalledOnceWith('fr');
  });

  it('should change language', () => {
    component.changeLanguage('fr');
    expect(translationServiceSpy.changeLanguage).toHaveBeenCalledOnceWith('fr');
  });
});
