import {TestBed} from '@angular/core/testing';
import {CreateBottleCommand} from '@model/domain/message/command/create-bottle-command';
import {HttpClientTestingModule, HttpTestingController} from '@angular/common/http/testing';
import {CreateSailorCommand} from '@model/domain/message/command/create-sailor-command';
import {DeleteSailorCommand} from '@model/domain/message/command/delete-sailor-command';
import {ThemeModule} from '@core/theme/theme.module';
import {NoopAnimationsModule} from '@angular/platform-browser/animations';
import {MessageService} from './message.service';

describe('MessageService', () => {
  let service: MessageService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule,
        NoopAnimationsModule,
        ThemeModule,
      ],
    });
    service = TestBed.inject(MessageService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should return sea information', () => {
    service.getSea().subscribe((result) => {
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
    service.createBottle(command).subscribe((result) => {
      expect(result.id).toBeDefined();
      expect(result.id).toEqual('88a177e1-d838-48f6-89c8-0ca54a2c4008');
    });

    const request = httpMock.expectOne('http://local.api.seaofbottles.aperturedevs.com/api/bottle');
    expect(request.request.method).toEqual('POST');
    expect(request.request.body.message).toEqual('This is a test message.');
    request.flush({id: '88a177e1-d838-48f6-89c8-0ca54a2c4008'});
    httpMock.verify();
  });

  it('should handle invalid bottle creation', () => {
    const command: CreateBottleCommand = {
      message: 'a'.repeat(501),
    };
    service.createBottle(command).subscribe({
      error: (error) => {
        expect(error.type).toBeDefined();
        expect(error.type).toEqual('Invalid Request');
        expect(error.description).toBeDefined();
        expect(error.description).toEqual('message : This value is too long. It should have 500 characters or less.');
        expect(error.status).toBeDefined();
        expect(error.status).toEqual(400);
      },
    });

    const request = httpMock.expectOne('http://local.api.seaofbottles.aperturedevs.com/api/bottle');
    expect(request.request.method).toEqual('POST');
    expect(request.request.body.message).toEqual('a'.repeat(501));
    request.flush({type: 'Invalid Request', description: 'message : This value is too long. It should have 500 characters or less.', status: 400}, {status: 400, statusText: 'ERROR'});
    httpMock.verify();
  });

  it('should create a sailor without error', () => {
    const command: CreateSailorCommand = {
      email: 'test@aperturedevs.com',
    };
    service.createSailor(command).subscribe((result) => {
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
    service.deleteSailor(command).subscribe((result) => {
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
