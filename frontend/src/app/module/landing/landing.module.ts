import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ThemeModule} from '@core/theme/theme.module';
import {SharedModule} from '@shared/shared.module';
import {LandingComponent} from './pages/landing/landing.component';

const routes: Routes = [
  {
    path: '',
    component: LandingComponent,
  },
];

@NgModule({
  declarations: [LandingComponent],
  imports: [
    CommonModule,
    ThemeModule,
    RouterModule.forChild(routes),
    SharedModule,
  ],
})
export class LandingModule { }
