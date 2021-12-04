import {
  Component,
} from '@angular/core';
import {TranslationService} from '@core/translation/translation.service';

@Component({
  selector: 'app-translation',
  templateUrl: './translation.component.html',
  styleUrls: ['./translation.component.scss'],
})
export class TranslationComponent {
  public language: string;

  public constructor(private translationService: TranslationService) {
    this.translationService.languageChanged.subscribe((language) => {
      this.language = language;
    });

    this.language = translationService.getCurrentLanguage();
  }

  public changeLanguage(language: string): void {
    this.translationService.changeLanguage(language);
  }
}
