import {Injectable} from '@angular/core';
import {MatIconRegistry} from '@angular/material/icon';
import {DomSanitizer} from '@angular/platform-browser';

@Injectable({
  providedIn: 'root',
})
export class CustomIconService {
  public constructor(
    private matIconRegistry: MatIconRegistry,
    private domSanitizer: DomSanitizer,
  ) { }
  public init(): void {
    this.matIconRegistry.addSvgIcon('github', this.domSanitizer.bypassSecurityTrustResourceUrl('../../assets/images/github-square-brands.svg'));
    this.matIconRegistry.addSvgIcon('twitter', this.domSanitizer.bypassSecurityTrustResourceUrl('../../assets/images/twitter-square-brands.svg'));
    this.matIconRegistry.addSvgIcon('bottle', this.domSanitizer.bypassSecurityTrustResourceUrl('../../assets/images/bottle.svg'));
    this.matIconRegistry.addSvgIcon('sailor', this.domSanitizer.bypassSecurityTrustResourceUrl('../../assets/images/sailor.svg'));
    this.matIconRegistry.addSvgIcon('check', this.domSanitizer.bypassSecurityTrustResourceUrl('../../assets/images/check-circle.svg'));
  }
}
