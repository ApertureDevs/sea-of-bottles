import {NgModule} from '@angular/core';
import {TranslateLoader, TranslateModule} from '@ngx-translate/core';
import {HttpClient, HttpClientModule} from '@angular/common/http';
import {TranslateHttpLoader} from '@ngx-translate/http-loader';

@NgModule({
  declarations: [],
  imports: [
    HttpClientModule,
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: (http: HttpClient): TranslateHttpLoader => new TranslateHttpLoader(http),
        deps: [HttpClient],
      },
    }),
  ],
  exports: [],
})
export class TranslationModule {}
