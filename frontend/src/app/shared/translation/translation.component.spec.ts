import {ComponentFixture, TestBed} from '@angular/core/testing';
import {TranslationComponent} from '@shared/translation/translation.component';
import {TranslationService} from '@core/translation/translation.service';

describe('TranslationComponent', () => {
  let translationServiceSpy: jasmine.SpyObj<TranslationService>;
  let component: TranslationComponent;
  let fixture: ComponentFixture<TranslationComponent>;

  beforeEach(() => {
    const spyTranslationService = jasmine.createSpyObj('TranslationService', ['changeLanguage']);

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
    component.changeLanguage('FR');
    expect(translationServiceSpy.changeLanguage.calls.count()).toEqual(1);
  });
});
