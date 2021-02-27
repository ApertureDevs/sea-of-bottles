import {CUSTOM_ELEMENTS_SCHEMA} from '@angular/core';
import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';
import {ReactiveFormsModule} from '@angular/forms';
import {ThemeModule} from '@core/theme/theme.module';
import {NoopAnimationsModule} from '@angular/platform-browser/animations';
import {MessageService} from '@data/service/message.service';
import {Observable, of} from 'rxjs';
import {CommandResponse} from '@model/shared/api-response';
import {CreateBottleComponent} from './create-bottle.component';

describe('CreateBottleComponent', () => {
  let component: CreateBottleComponent;
  let fixture: ComponentFixture<CreateBottleComponent>;
  let messageServiceSpy: jasmine.SpyObj<MessageService>;

  beforeEach(waitForAsync(() => {
    const spy = jasmine.createSpyObj('MessageService', ['createBottle']);

    TestBed.configureTestingModule({
      declarations: [ CreateBottleComponent ],
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
    fixture = TestBed.createComponent(CreateBottleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should contains working form on valid message', async () => {
    component.form.controls.message.setValue('This is a test message!');
    expect(component.form.valid).toBeTruthy();
    const serviceResponse: Observable<CommandResponse> = of({id: 'bb53c317-9f9b-4766-875e-c6564730db52'});
    messageServiceSpy.createBottle.and.returnValue(serviceResponse);
    component.commandSubmit();
    component.bottleCreated.subscribe(() => {
      expect(component.wasCreated).toBeTruthy();
    });
  });

  it('should contains invalid form on too short message', async () => {
    component.form.controls.message.setValue('hi');
    expect(component.form.valid).toBeFalsy();
  });

  it('should contains invalid form on too long message', async () => {
    component.form.controls.message.setValue('a'.repeat(501));
    expect(component.form.valid).toBeFalsy();
  });

  it('should contains invalid form on empty data', async () => {
    expect(component.form.valid).toBeFalsy();
  });
});
