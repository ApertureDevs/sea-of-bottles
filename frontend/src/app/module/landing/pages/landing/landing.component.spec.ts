import {CUSTOM_ELEMENTS_SCHEMA, DebugElement} from '@angular/core';
import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';
import {By} from '@angular/platform-browser';
import {MessageService} from '@data/service/message.service';
import {Observable, of} from 'rxjs';
import {Sea} from '@model/domain/message/projection/sea';
import {LandingComponent} from './landing.component';

describe('LandingComponent', () => {
  let component: LandingComponent;
  let fixture: ComponentFixture<LandingComponent>;
  let debugElement: DebugElement;
  let messageServiceSpy: jasmine.SpyObj<MessageService>;

  beforeEach(waitForAsync(() => {
    const spy = jasmine.createSpyObj('MessageService', ['getSea']);

    TestBed.configureTestingModule({
      declarations: [ LandingComponent ],
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
    fixture = TestBed.createComponent(LandingComponent);
    component = fixture.componentInstance;
    const serviceResponse: Observable<Sea> = of({
      bottles_remaining: 5,
      bottles_recovered: 15,
      bottles_total: 20,
      sailors_total: 10,
    });
    messageServiceSpy.getSea.and.returnValue(serviceResponse);
    debugElement = fixture.debugElement;
    fixture.detectChanges();
  });

  it('should be creatable', () => {
    expect(component).toBeTruthy();
  });

  it('should contains counters', async () => {
    await fixture.whenStable();
    expect(component.sea).toBeDefined();
    expect(component.sea?.bottles_recovered).toBeGreaterThanOrEqual(15);
    expect(component.sea?.bottles_remaining).toBeGreaterThanOrEqual(5);
    expect(component.sea?.bottles_total).toBeGreaterThanOrEqual(20);
    expect(component.sea?.sailors_total).toBeGreaterThanOrEqual(10);
  });

  it('should contains logo', () => {
    const logo = debugElement.query(By.css('.jumbotron__image'));
    expect(logo).toBeTruthy();
  });

  it('should contains title', () => {
    const title = debugElement.query(By.css('.jumbotron__main-title')).nativeElement.innerText;
    expect(title).toEqual('SEA OF BOTTLES');
  });
});
