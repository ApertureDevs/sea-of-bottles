import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';
import {RouterModule} from '@angular/router';
import {ThemeModule} from '@core/theme/theme.module';
import {HeaderComponent} from './header.component';

@NgModule({
  declarations: [
    HeaderComponent,
  ],
  imports: [
    CommonModule,
    ThemeModule,
    RouterModule,
  ],
  exports: [
    HeaderComponent,
  ],
})
export class HeaderModule { }
