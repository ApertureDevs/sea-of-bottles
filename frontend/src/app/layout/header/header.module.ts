import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';
import {RouterModule} from '@angular/router';
import {ThemeModule} from '@core/theme/theme.module';
import {SharedModule} from '@shared/shared.module';
import {HeaderComponent} from './header.component';

@NgModule({
  declarations: [
    HeaderComponent,
  ],
  imports: [
    CommonModule,
    ThemeModule,
    RouterModule,
    SharedModule,
  ],
  exports: [
    HeaderComponent,
  ],
})
export class HeaderModule { }
