import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';
import {CounterComponent} from '@shared/counter/counter.component';
import {TranslationComponent} from '@shared/translation/translation.component';

@NgModule({
  declarations: [
    CounterComponent,
    TranslationComponent,
  ],
  imports: [
    CommonModule,
  ],
  exports: [
    CounterComponent,
    TranslationComponent,
  ],
})
export class SharedModule { }
