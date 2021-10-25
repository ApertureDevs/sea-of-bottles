import {fakeAsync, TestBed, tick} from '@angular/core/testing';
import {CreateBottleCommand} from '@model/domain/message/command/create-bottle-command';
import {HttpClientTestingModule, HttpTestingController} from '@angular/common/http/testing';
import {CreateSailorCommand} from '@model/domain/message/command/create-sailor-command';
import {DeleteSailorCommand} from '@model/domain/message/command/delete-sailor-command';
import {ThemeModule} from '@core/theme/theme.module';
import {NoopAnimationsModule} from '@angular/platform-browser/animations';
import {TranslationService} from '@core/translation/translation.service';
import {AlertService} from '@core/alert/alert.service';
import {of} from 'rxjs';
import {MessageService} from './message.service';

describe('MessageService', () => {
  let messageService: MessageService;
  let httpMock: HttpTestingController;
  let alertServiceSpy: jasmine.SpyObj<AlertService>;
  let translationServiceSpy: jasmine.SpyObj<TranslationService>;

  beforeEach(() => {
    const spyAlertService = jasmine.createSpyObj('AlertService', ['error']);
    const spyTranslationService = jasmine.createSpyObj('TranslationService', ['translateKey']);

    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule,
        NoopAnimationsModule,
        ThemeModule,
      ],
      providers: [
        MessageService,
        {provide: AlertService, useValue: spyAlertService},
        {provide: TranslationService, useValue: spyTranslationService},
      ],
    });
    messageService = TestBed.inject(MessageService);
    httpMock = TestBed.inject(HttpTestingController);
    alertServiceSpy = TestBed.inject(AlertService) as jasmine.SpyObj<AlertService>;
    translationServiceSpy = TestBed.inject(TranslationService) as jasmine.SpyObj<TranslationService>;
  });

  it('should be created', () => {
    expect(messageService).toBeTruthy();
  });

  it('should return sea information', () => {
    messageService.getSea().subscribe((result) => {
      expect(result).toBeDefined();
      expect(result.bottles_recovered).toEqual(100);
      expect(result.bottles_remaining).toEqual(150);
      expect(result.bottles_total).toEqual(250);
      expect(result.sailors_total).toEqual(50);
    });

    const request = httpMock.expectOne('http://local.api.seaofbottles.aperturedevs.com/api/sea');
    expect(request.request.method).toEqual('GET');
    request.flush({
      bottles_recovered: 100,
      bottles_remaining: 150,
      bottles_total: 250,
      sailors_total: 50,
    });
    httpMock.verify();
  });

  it('should create a bottle without error', () => {
    const command: CreateBottleCommand = {
      message: 'This is a test message.',
    };
    messageService.createBottle(command).subscribe((result) => {
      expect(result.id).toBeDefined();
      expect(result.id).toEqual('88a177e1-d838-48f6-89c8-0ca54a2c4008');
    });

    const request = httpMock.expectOne('http://local.api.seaofbottles.aperturedevs.com/api/bottle');
    expect(request.request.method).toEqual('POST');
    expect(request.request.body.message).toEqual('This is a test message.');
    request.flush({id: '88a177e1-d838-48f6-89c8-0ca54a2c4008'});
    httpMock.verify();
  });

  it('should handle invalid bottle creation', fakeAsync(() => {
    translationServiceSpy.translateKey.and.returnValue(of('translated-error'));
    const command: CreateBottleCommand = {
      message: 'a'.repeat(501),
    };
    messageService.createBottle(command).subscribe({
      error: (error) => {
        expect(error.type).toBeDefined();
        expect(error.type).toEqual('Invalid Request');
        expect(error.description).toBeDefined();
        expect(error.description).toEqual('message : This value is too long. It should have 500 characters or less.');
        expect(error.status).toBeDefined();
        expect(error.status).toEqual(400);
        tick(100);
        expect(alertServiceSpy.error.calls.count()).toBe(1);
      },
    });

    const request = httpMock.expectOne('http://local.api.seaofbottles.aperturedevs.com/api/bottle');
    expect(request.request.method).toEqual('POST');
    expect(request.request.body.message).toEqual('a'.repeat(501));
    request.flush({type: 'Invalid Request', description: 'message : This value is too long. It should have 500 characters or less.', status: 400}, {status: 400, statusText: 'ERROR'});
    httpMock.verify();
  }));

  it('should create a sailor without error', () => {
    const command: CreateSailorCommand = {
      email: 'test@aperturedevs.com',
    };
    messageService.createSailor(command).subscribe((result) => {
      expect(result.id).toBeDefined();
      expect(result.id).toEqual('88a177e1-d838-48f6-89c8-0ca54a2c4008');
    });

    const request = httpMock.expectOne('http://local.api.seaofbottles.aperturedevs.com/api/sailor');
    expect(request.request.method).toEqual('POST');
    expect(request.request.body.email).toEqual('test@aperturedevs.com');
    request.flush({id: '88a177e1-d838-48f6-89c8-0ca54a2c4008'});
    httpMock.verify();
  });

  it('should delete a sailor without error', () => {
    const command: DeleteSailorCommand = {
      email: 'test@aperturedevs.com',
    };
    messageService.deleteSailor(command).subscribe((result) => {
      expect(result.id).toBeDefined();
      expect(result.id).toEqual('88a177e1-d838-48f6-89c8-0ca54a2c4008');
    });

    const request = httpMock.expectOne('http://local.api.seaofbottles.aperturedevs.com/api/sailor');
    expect(request.request.method).toEqual('DELETE');
    expect(request.request.body.email).toEqual('test@aperturedevs.com');
    request.flush({id: '88a177e1-d838-48f6-89c8-0ca54a2c4008'});
    httpMock.verify();
  });
});
