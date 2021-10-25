import {CUSTOM_ELEMENTS_SCHEMA} from '@angular/core';
import {ComponentFixture, TestBed} from '@angular/core/testing';
import {ReactiveFormsModule} from '@angular/forms';
import {ThemeModule} from '@core/theme/theme.module';
import {NoopAnimationsModule} from '@angular/platform-browser/animations';
import {MessageService} from '@data/service/message.service';
import {CommandResponse} from '@model/shared/api-response';
import {Observable, of} from 'rxjs';
import {TranslateModule} from '@ngx-translate/core';
import {TranslationService} from '@core/translation/translation.service';
import {AlertService} from '@core/alert/alert.service';
import {DeleteSailorComponent} from './delete-sailor.component';

describe('DeleteSailorComponent', () => {
  let component: DeleteSailorComponent;
  let fixture: ComponentFixture<DeleteSailorComponent>;
  let messageServiceSpy: jasmine.SpyObj<MessageService>;
  let translationServiceSpy: jasmine.SpyObj<TranslationService>;
  let alertServiceSpy: jasmine.SpyObj<AlertService>;

  beforeEach(() => {
    const spyMessageService = jasmine.createSpyObj('MessageService', ['deleteSailor']);
    const spyTranslationService = jasmine.createSpyObj('TranslationService', ['translateKey']);
    const spyAlertService = jasmine.createSpyObj('AlertService', ['info']);

    TestBed.configureTestingModule({
      declarations: [ DeleteSailorComponent ],
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
    fixture = TestBed.createComponent(DeleteSailorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should contains working form on valid message', () => {
    translationServiceSpy.translateKey.and.returnValue(of('translated-message'));
    component.form.controls.email.setValue('test@aperturedevs.com');
    expect(component.form.valid).toBeTruthy();
    const serviceResponse: Observable<CommandResponse> = of({id: 'bb53c317-9f9b-4766-875e-c6564730db52'});
    messageServiceSpy.deleteSailor.and.returnValue(serviceResponse);
    component.commandSubmit();
    component.sailorDeleted.subscribe(() => {
      expect(component.sailorDeleted).toBeTruthy();
      expect(translationServiceSpy.translateKey.calls.count()).toBe(1);
      expect(alertServiceSpy.info.calls.count()).toBe(1);
    });
  });

  it('should contains invalid form on invalid email format', () => {
    component.form.controls.email.setValue('test');
    expect(component.form.valid).toBeFalsy();
  });

  it('should contains invalid form on empty data', () => {
    expect(component.form.valid).toBeFalsy();
  });
});
