import {Injectable} from '@angular/core';
import {TranslateService} from '@ngx-translate/core';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class TranslationService {
  public constructor(private translateService: TranslateService) {
    translateService.setDefaultLang('en');
  }

  public changeLanguage(language: string): void {
    this.translateService.use(language);
  }

  public translateKey(key: string): Observable<string> {
    return this.translateService.get(key);
  }
}
