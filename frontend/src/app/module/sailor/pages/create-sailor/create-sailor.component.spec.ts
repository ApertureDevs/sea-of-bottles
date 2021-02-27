import {CUSTOM_ELEMENTS_SCHEMA} from '@angular/core';
import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';
import {ReactiveFormsModule} from '@angular/forms';
import {ThemeModule} from '@core/theme/theme.module';
import {NoopAnimationsModule} from '@angular/platform-browser/animations';
import {Observable, of} from 'rxjs';
import {CommandResponse} from '@model/shared/api-response';
import {MessageService} from '@data/service/message.service';
import {CreateSailorComponent} from './create-sailor.component';

describe('CreateSailorComponent', () => {
  let component: CreateSailorComponent;
  let fixture: ComponentFixture<CreateSailorComponent>;
  let messageServiceSpy: jasmine.SpyObj<MessageService>;

  beforeEach(waitForAsync(() => {
    const spy = jasmine.createSpyObj('MessageService', ['createSailor']);

    TestBed.configureTestingModule({
      declarations: [ CreateSailorComponent ],
      imports: [
        ReactiveFormsModule,
        ThemeModule,
        NoopAnimationsModule,
      ],
      schemas: [
        CUSTOM_ELEMENTS_SCHEMA,
      ],
      providers: [
        {provide: MessageService, useValue: spy},
      ],
    })
      .compileComponents();
  }));

  beforeEach(() => {
    messageServiceSpy = TestBed.inject(MessageService) as jasmine.SpyObj<MessageService>;
    fixture = TestBed.createComponent(CreateSailorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should contains working form on valid message', async () => {
    component.form.controls.email.setValue('test@aperturedevs.com');
    expect(component.form.valid).toBeTruthy();
    const serviceResponse: Observable<CommandResponse> = of({id: 'bb53c317-9f9b-4766-875e-c6564730db52'});
    messageServiceSpy.createSailor.and.returnValue(serviceResponse);
    component.commandSubmit();
    component.sailorCreated.subscribe(() => {
      expect(component.wasCreated).toBeTruthy();
    });
  });

  it('should contains invalid form on invalid email format', async () => {
    component.form.controls.email.setValue('test');
    expect(component.form.valid).toBeFalsy();
  });

  it('should contains invalid form on empty data', async () => {
    expect(component.form.valid).toBeFalsy();
  });
});
