import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';
import {CounterComponent} from '@shared/counter/counter.component';
import {TranslationComponent} from '@shared/translation/translation.component';
import {FormsModule} from '@angular/forms';

@NgModule({
  declarations: [
    CounterComponent,
    TranslationComponent,
  ],
  imports: [
    CommonModule,
    FormsModule,
  ],
  exports: [
    CounterComponent,
    TranslationComponent,
  ],
})
export class SharedModule { }
