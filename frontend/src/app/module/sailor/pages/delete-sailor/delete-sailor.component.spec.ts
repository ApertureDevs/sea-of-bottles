import {CUSTOM_ELEMENTS_SCHEMA} from '@angular/core';
import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';
import {HttpClientModule} from '@angular/common/http';
import {ReactiveFormsModule} from '@angular/forms';
import {ThemeModule} from '@core/theme/theme.module';
import {NoopAnimationsModule} from '@angular/platform-browser/animations';
import {DeleteSailorComponent} from './delete-sailor.component';

describe('DeleteSailorComponent', () => {
  let component: DeleteSailorComponent;
  let fixture: ComponentFixture<DeleteSailorComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ DeleteSailorComponent ],
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
    fixture = TestBed.createComponent(DeleteSailorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
