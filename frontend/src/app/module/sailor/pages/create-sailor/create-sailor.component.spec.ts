import {CUSTOM_ELEMENTS_SCHEMA} from '@angular/core';
import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';
import {HttpClientModule} from '@angular/common/http';
import {ReactiveFormsModule} from '@angular/forms';
import {ThemeModule} from '@core/theme/theme.module';
import {NoopAnimationsModule} from '@angular/platform-browser/animations';
import {CreateSailorComponent} from './create-sailor.component';

describe('CreateSailorComponent', () => {
  let component: CreateSailorComponent;
  let fixture: ComponentFixture<CreateSailorComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateSailorComponent ],
      imports: [
        HttpClientModule,
        ReactiveFormsModule,
        ThemeModule,
        NoopAnimationsModule,
      ],
      schemas: [
        CUSTOM_ELEMENTS_SCHEMA,
      ],
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateSailorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
