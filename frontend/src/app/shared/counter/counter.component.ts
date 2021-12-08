import {
  AfterViewInit,
  Component,
  ElementRef,
  EventEmitter,
  Input,
  OnChanges,
  Output,
  SimpleChanges,
  ViewChild,
} from '@angular/core';

@Component({
  selector: 'app-counter',
  templateUrl: './counter.component.html',
})
export class CounterComponent implements AfterViewInit, OnChanges {
  @Output() public animationFinished = new EventEmitter<void>();
  @Input() public count = 0;
  @ViewChild('counter') private counter!: ElementRef;
  private steps = 10;
  private duration = 2000;

  public ngAfterViewInit() {
    this.animate();
  }

  public ngOnChanges(changes: SimpleChanges) {
    if (typeof this.counter === 'undefined') {
      return;
    }

    if (changes.digit) {
      this.animate();
    }
  }

  private animate() {
    const stepCount = Math.abs(this.duration / this.steps);
    const valueIncrement = (this.count - 0) / stepCount;
    const sinValueIncrement = Math.PI / stepCount;
    let currentValue = 0;
    let currentSinValue = 0;

    const step = () => {
      currentSinValue += sinValueIncrement;
      currentValue += valueIncrement * Math.sin(currentSinValue) ** 2 * 2;
      this.counter.nativeElement.textContent = Math.abs(Math.floor(currentValue));

      if (currentSinValue < Math.PI) {
        window.requestAnimationFrame(step);
      } else {
        this.animationFinished.emit();
      }
    };

    step();
  }
}
