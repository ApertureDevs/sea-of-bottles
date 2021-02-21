import {CUSTOM_ELEMENTS_SCHEMA} from '@angular/core';
import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';
import {HttpClientModule} from '@angular/common/http';
import {ReactiveFormsModule} from '@angular/forms';
import {ThemeModule} from '@core/theme/theme.module';
import {NoopAnimationsModule} from '@angular/platform-browser/animations';
import {CreateBottleComponent} from './create-bottle.component';

describe('CreateBottleComponent', () => {
  let component: CreateBottleComponent;
  let fixture: ComponentFixture<CreateBottleComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateBottleComponent ],
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
    fixture = TestBed.createComponent(CreateBottleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
