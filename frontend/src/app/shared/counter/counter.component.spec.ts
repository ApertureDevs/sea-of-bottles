import {DebugElement} from '@angular/core';
import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';
import {CounterComponent} from '@shared/counter/counter.component';

describe('CounterComponent', () => {
  let component: CounterComponent;
  let fixture: ComponentFixture<CounterComponent>;
  let debugElement: DebugElement;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ CounterComponent ],
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CounterComponent);
    component = fixture.componentInstance;
    debugElement = fixture.debugElement;
    fixture.detectChanges();
  });

  it('should be creatable without input', () => {
    expect(component).toBeTruthy();
    expect(component.count).toBe(0);
    expect(debugElement.nativeElement.innerText).toBe('0');
  });

  it('should be creatable with count', async () => {
    component.count = 100;
    expect(component).toBeTruthy();
    expect(component.count).toBe(100);
    expect(debugElement.nativeElement.innerText).toBe('0');
    await component.animationFinished;
    fixture.detectChanges();
    expect(debugElement.nativeElement.innerText).toBe('100');
  });
});
