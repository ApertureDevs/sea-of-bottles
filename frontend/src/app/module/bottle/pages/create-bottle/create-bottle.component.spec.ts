import {CUSTOM_ELEMENTS_SCHEMA} from '@angular/core';
import {ComponentFixture, TestBed} from '@angular/core/testing';
import {ReactiveFormsModule} from '@angular/forms';
import {ThemeModule} from '@core/theme/theme.module';
import {NoopAnimationsModule} from '@angular/platform-browser/animations';
import {MessageService} from '@data/service/message.service';
import {Observable, of} from 'rxjs';
import {CommandResponse} from '@model/shared/api-response';
import {TranslateModule} from '@ngx-translate/core';
import {TranslationService} from '@core/translation/translation.service';
import {AlertService} from '@core/alert/alert.service';
import {CreateBottleComponent} from './create-bottle.component';

describe('CreateBottleComponent', () => {
  let component: CreateBottleComponent;
  let fixture: ComponentFixture<CreateBottleComponent>;
  let messageServiceSpy: jasmine.SpyObj<MessageService>;
  let translationServiceSpy: jasmine.SpyObj<TranslationService>;
  let alertServiceSpy: jasmine.SpyObj<AlertService>;

  beforeEach(() => {
    const spyMessageService = jasmine.createSpyObj('MessageService', ['createBottle']);
    const spyTranslationService = jasmine.createSpyObj('TranslationService', ['translateKey']);
    const spyAlertService = jasmine.createSpyObj('AlertService', ['info']);

    TestBed.configureTestingModule({
      declarations: [
        CreateBottleComponent,
      ],
      imports: [
        ReactiveFormsModule,
        ThemeModule,
        NoopAnimationsModule,
        TranslateModule.forRoot(),
      ],
      schemas: [
        CUSTOM_ELEMENTS_SCHEMA,
      ],
      providers: [
        {provide: MessageService, useValue: spyMessageService},
        {provide: TranslationService, useValue: spyTranslationService},
        {provide: AlertService, useValue: spyAlertService},
      ],
    })
      .compileComponents();

    messageServiceSpy = TestBed.inject(MessageService) as jasmine.SpyObj<MessageService>;
    translationServiceSpy = TestBed.inject(TranslationService) as jasmine.SpyObj<TranslationService>;
    alertServiceSpy = TestBed.inject(AlertService) as jasmine.SpyObj<AlertService>;
    fixture = TestBed.createComponent(CreateBottleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should contains working form on valid message', () => {
    translationServiceSpy.translateKey.and.returnValue(of('translated-message'));
    component.form.controls.message.setValue('This is a test message!');
    expect(component.form.valid).toBeTruthy();
    const serviceResponse: Observable<CommandResponse> = of({id: 'bb53c317-9f9b-4766-875e-c6564730db52'});
    messageServiceSpy.createBottle.and.returnValue(serviceResponse);
    component.commandSubmit();
    component.bottleCreated.subscribe(() => {
      expect(component.wasCreated).toBeTruthy();
      expect(translationServiceSpy.translateKey.calls.count()).toBe(1);
      expect(alertServiceSpy.info.calls.count()).toBe(1);
    });
  });

  it('should contains invalid form on too short message', () => {
    component.form.controls.message.setValue('hi');
    expect(component.form.valid).toBeFalsy();
  });

  it('should contains invalid form on too long message', () => {
    component.form.controls.message.setValue('a'.repeat(501));
    expect(component.form.valid).toBeFalsy();
  });

  it('should contains invalid form on empty data', () => {
    expect(component.form.valid).toBeFalsy();
  });
});
