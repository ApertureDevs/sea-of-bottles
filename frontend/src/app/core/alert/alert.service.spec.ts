import {TestBed} from '@angular/core/testing';
import {MatSnackBar} from '@angular/material/snack-bar';
import {AlertService} from '@core/alert/alert.service';

describe('AlertService', () => {
  let service: AlertService;
  let matSnackBarSpy: jasmine.SpyObj<MatSnackBar>;

  beforeEach(() => {
    const spy = jasmine.createSpyObj('MatSnackBar', ['open']);

    TestBed.configureTestingModule({
      providers: [
        {provide: MatSnackBar, useValue: spy},
      ],
    })
      .compileComponents();
  });

  beforeEach(() => {
    service = TestBed.inject(AlertService);
    matSnackBarSpy = TestBed.inject(MatSnackBar) as jasmine.SpyObj<MatSnackBar>;
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should create info alert snackbar', () => {
    service.info('This is a test');
    expect(matSnackBarSpy.open).toHaveBeenCalled();
  });

  it('should create error alert snackbar', () => {
    service.error('This is a test');
    expect(matSnackBarSpy.open).toHaveBeenCalled();
  });
});
