import {EventEmitter, Injectable} from '@angular/core';
import {TranslateService} from '@ngx-translate/core';
import {Observable} from 'rxjs';
import {StorageService} from '@data/service/storage.service';

@Injectable({
  providedIn: 'root',
})
export class TranslationService {
  public languageChanged = new EventEmitter<string>();
  private storageKey = 'language';
  private defaultLanguage = 'en';

  public constructor(
    private translateService: TranslateService,
    private storageService: StorageService,
  ) {
    translateService.setDefaultLang(this.defaultLanguage);

    this.storageService.getItem<string>(this.storageKey).subscribe((language) => {
      if (language === null) {
        return;
      }

      this.translateService.use(language);
      this.languageChanged.emit(language);
    });
  }

  public changeLanguage(language: string): void {
    this.translateService.use(language);
    this.storageService.setItem<string>(this.storageKey, language);
    this.languageChanged.emit(language);
  }

  public translateKey(key: string): Observable<string> {
    return this.translateService.get(key);
  }

  public getCurrentLanguage(): string {
    const language = this.translateService.currentLang;

    if (typeof language === 'undefined') {
      return this.defaultLanguage;
    }

    return language;
  }
}
