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
  public constructor(private translationService: TranslationService) {}

  public changeLanguage(language: string): void {
    this.translationService.changeLanguage(language);
  }
}
