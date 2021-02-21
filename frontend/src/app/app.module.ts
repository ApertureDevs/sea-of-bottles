import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';

import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {CoreModule} from '@core/core.module';
import {HttpClientModule} from '@angular/common/http';
import {ThemeModule} from '@core/theme/theme.module';
import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {HeaderModule} from './layout/header/header.module';
import {FooterModule} from './layout/footer/footer.module';

@NgModule({
  declarations: [
    AppComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HeaderModule,
    FooterModule,
    CoreModule,
    HttpClientModule,
    BrowserAnimationsModule,
    ThemeModule,
  ],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule { }
