import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';
import {TranslationModule} from '@core/translation/translation.module';
import {ThemeModule} from './theme/theme.module';

@NgModule({
  declarations: [],
  imports: [
    CommonModule,
  ],
  exports: [
    ThemeModule,
    TranslationModule,
  ],
})
export class CoreModule {
}
